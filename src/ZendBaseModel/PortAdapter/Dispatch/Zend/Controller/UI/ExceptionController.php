<?php

namespace ZendBaseModel\PortAdapter\Dispatch\Zend\Controller\UI;

use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;

/**
 * Class ExceptionController
 * @package ZendBaseModel\Controller
 */
class ExceptionController extends AbstractActionController
{
    /**
     * @return null|JsonModel|ViewModel
     * @throws \Exception
     */
    public function indexAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();

        if ($request instanceof \Zend\Console\Request) {
            $event = $this->getEvent();
            echo $event->getParam('message');

            return null;
        }

        if ($request->isXmlHttpRequest()) {
            $event = $this->getEvent();
            $message = $event->getParam('message');
            $response = [
                'success' => false,
                'result' => null,
                'error' => $message,
            ];

            return new JsonModel($response);
        }

        $event = $this->getEvent();

        /** @var PhpRenderer $viewRender */
        $viewRender = $this->getServiceLocator()->get('ViewRenderer');
        $model = new ViewModel([
            'message' => $event->getParam('message'),
            'exception' => $event->getParam('exception'),
        ]);
        $model->setTemplate("error/error");

        $layoutModel = new ViewModel(['content' => $viewRender->render($model)]);
        $layoutModel->setTemplate("layout/empty");

        return $layoutModel;
    }
}
