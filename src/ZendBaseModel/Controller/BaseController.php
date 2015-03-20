<?php

namespace ZendBaseModel\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\Session\SessionManager;

/**
 * Description of BaseController
 *
 * @author seyfer
 */
abstract class BaseController extends AbstractActionController
{

    /**
     * @var EntityRepository
     */
    protected $currentRepository;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var SessionManager
     */
    protected $sessionManager;

    /**
     * @var Container
     */
    protected $sessionContainer;

    /**
     * @var Form
     */
    protected $defaultForm;

    /**
     * @var string
     */
    protected $defaultFormClassName;

    /**
     * @var string
     */
    protected $defaultInputFilterClassName;

    /**
     * @param \Zend\Mvc\MvcEvent $e
     */
    public function onDispatch(\Zend\Mvc\MvcEvent $e)
    {
        //        $this->sessionManager = $this->getServiceLocator()
        //                ->get('Zend\Session\SessionManager');

        $this->sessionContainer = new Container('appSession');

        $this->initDefaultForm();

        if (!$this->defaultForm) {
            $this->initForm();
        }

        parent::onDispatch($e);
    }

    protected function initForm()
    {
        return;
    }

    protected function initDefaultForm()
    {
        if ($this->defaultFormClassName) {
            $class = $this->defaultFormClassName;

            if (class_exists($class)) {
                $this->defaultForm = new $class();
            }

            if ($this->defaultForm && $this->defaultInputFilterClassName) {
                $class = $this->defaultInputFilterClassName;
                $this->defaultForm->setInputFilter(new $class());
            }
        }
    }

    /**
     * @param EntityManager $em
     */
    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     *
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()
                             ->get('Doctrine\ORM\EntityManager');
        }

        return $this->em;
    }

    /**
     * @param $helperName
     * @return mixed
     */
    protected function getViewHelper($helperName)
    {
        return $this->getServiceLocator()
                    ->get('viewhelpermanager')->get($helperName);
    }

}
