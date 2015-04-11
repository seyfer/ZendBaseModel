<?php

namespace ZendBaseModel\Controller;

use Zend\Form\FormInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\View\Model\ViewModel;
use ZendBaseModel\Repository\BaseRepository;

/**
 * Description of BaseEntityController
 *
 * @author seyfer
 */
abstract class BaseEntityController extends BaseController
{

    const DEFAULT_PAGE_COUNT = 10;

    protected $controllerRepository  = "";
    protected $controllerRoute       = 'home/default';
    protected $controllerRouteParams = [];

    /**
     * @var BaseRepository
     */
    protected $currentRepository;
    protected $page;
    protected $template;

    /**
     * add and edit form and filter
     *
     * @var
     */
    protected $defaultFormClassName;
    /**
     * @var FormInterface
     */
    protected $defaultForm;
    protected $defaultFilterClassName;
    /**
     * @var InputFilterInterface
     */
    protected $defaultFilter;

    /**
     * index search form
     *
     * @var
     */
    protected $defaultSearchFormClassName = \ZendBaseModel\Form\SearchForm::class;
    protected $defaultSearchForm;

    public function getControllerRepository()
    {
        return $this->controllerRepository;
    }

    /**
     * @param \Zend\Mvc\MvcEvent $e
     */
    public function onDispatch(\Zend\Mvc\MvcEvent $e)
    {
        if (!$this->getControllerRepository()) {
            throw new \Exception("property controllerRepository must be set");
        }

        //init repository
        $this->currentRepository = $this->getEntityManager()
                                        ->getRepository($this->getControllerRepository());

        $this->initForm();
        $this->initSearchForm();

        parent::onDispatch($e);
    }

    /**
     * init default add and edit form
     *
     * @return null
     */
    protected function initForm()
    {
        if ($this->defaultFormClassName) {

            if (!class_exists($this->defaultFormClassName)) {
                return null;
            }

            $this->defaultForm = new $this->defaultFormClassName();

            if ($this->defaultFilterClassName) {

                if (!class_exists($this->defaultFilterClassName)) {
                    return null;
                }

                $this->defaultFilter = new $this->defaultFilterClassName();
                $this->defaultForm->setInputFilter($this->defaultFilter);
            }
        }
    }

    /**
     * init default search form
     *
     * @return null
     */
    protected function initSearchForm()
    {
        if ($this->defaultSearchFormClassName) {

            if (!class_exists($this->defaultSearchFormClassName)) {
                return null;
            }

            $this->defaultSearchForm = new $this->defaultSearchFormClassName();
        }
    }

    /**
     * список с пагинацией
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        $request    = $this->getRequest();
        $this->page = (int)$this->params()->fromQuery('page');

        if ($request->isPost()) {
            $post = $request->getPost();

            $pageNum = $post['pagenum']
                ?: static::DEFAULT_PAGE_COUNT;
            $this->defaultSearchForm->get("pagenum")->setValue($pageNum);
            $this->sessionContainer->pageNum = $pageNum;

            $this->sessionContainer->post = $post;

            $entries = $this->currentRepository
                ->findWithPaginator($pageNum, $this->page, $this->sessionContainer->post, ['id' => 'DESC']);
        } else {
            $pageNum = $this->sessionContainer->pageNum ?: static::DEFAULT_PAGE_COUNT;
            $this->defaultSearchForm->get("pagenum")->setValue($pageNum);

            $entries = $this->currentRepository
                ->findWithPaginator($pageNum, $this->page, $this->sessionContainer->post, ['id' => 'DESC']);
        }

        $view = new ViewModel([
                                  'entries'     => $entries,
                                  'session'     => $this->sessionContainer->post,
                                  'form'        => $this->defaultSearchForm,
                                  'route'       => $this->controllerRoute,
                                  'routeParams' => $this->controllerRouteParams,
                              ]);

        if ($this->template) {
            $view->setTemplate($this->template);
        }

        return $view;
    }

    /**
     * base add action
     *
     * @return array|\Zend\Http\Response
     * @throws \Exception
     */
    public function addAction()
    {
        if ($this->request->isPost()) {
            $post = $this->request->getPost();

            $this->defaultForm->setData($post);

            if ($this->defaultForm->isValid()) {
                $className = $this->getControllerRepository();
                $entity    = new $className();

                $validData = $this->defaultForm->getData();

                $entity->exchangeArray($validData);

                $this->getEntityManager()->persist($entity);
                $this->getEntityManager()->flush();

                // Redirect to list of albums
                return $this->redirect()->toRoute($this->controllerRoute, $this->controllerRouteParams);
            } else {
                \Zend\Debug\Debug::dump($this->defaultForm->getMessages());
            }
        }

        if (!$this->defaultForm) {
            throw new \Exception(__METHOD__ . " no form");
        }

        return [
            "form"        => $this->defaultForm,
            'route'       => $this->controllerRoute,
            'routeParams' => $this->controllerRouteParams,
        ];
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function editAction()
    {
        $id = (int)$this->params()->fromRoute("id");

        if (!$id) {
            return $this->redirect()->toRoute($this->controllerRoute, $this->controllerRouteParams);
        }

        $entity = $this->currentRepository->find($id);

        $this->defaultForm->get("submit")->setValue("Обновить");
        $this->defaultForm->bind($entity);

        if ($this->request->isPost()) {
            $post = $this->request->getPost();

            $this->defaultForm->setData($post);

            if ($this->defaultForm->isValid()) {
                $this->defaultForm->bindValues();

                $this->getEntityManager()->flush();

                $this->redirect()->toRoute($this->controllerRoute, $this->controllerRouteParams);
            }
        }

        return [
            "id"          => $id,
            "form"        => $this->defaultForm,
            "entity"      => $entity,
            'route'       => $this->controllerRoute,
            'routeParams' => $this->controllerRouteParams,
        ];
    }

    /**
     * basic delete action
     *
     * @return ViewModel
     */
    public function deleteAction()
    {
        $id = (int)$this->params()->fromRoute("id");

        if (!$id) {
            return $this->redirect()->toRoute($this->controllerRoute, $this->controllerRouteParams);
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost()->get('del', 'No');

            if ($del == 'Yes') {
                $id = (int)$request->getPost('id');

                //                \Zend\Debug\Debug::dump($this->currentRepository, $id);
                //                exit();

                $entity = $this->currentRepository->find($id);

                if ($entity) {
                    $this->getEntityManager()->remove($entity);
                    $this->getEntityManager()->flush();

                    $this->flashMessenger()->addInfoMessage("Deleted id-" . $id . " entry");
                }
            }

            // Redirect to list
            return $this->redirect()->toRoute($this->controllerRoute, $this->controllerRouteParams);
        }

        $view = new ViewModel([
                                  'id'          => $id,
                                  'entity'      => $this->currentRepository->find($id),
                                  'route'       => $this->controllerRoute,
                                  'routeParams' => $this->controllerRouteParams,
                              ]);

        $view->setTemplate("partial/delete.phtml");

        return $view;
    }

}
