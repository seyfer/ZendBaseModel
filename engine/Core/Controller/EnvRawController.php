<?php
/**
 * Created by PhpStorm.
 * User: seyfer
 * Date: 9/24/15
 * Time: 3:25 PM
 */

namespace Core\Controller;


use Core\Tool\Environment;
use Zend\Mvc\Controller\AbstractController;
use Zend\Mvc\MvcEvent;
use ZendMover\Copier;

class EnvRawController extends AbstractController
{

    /**
     * Execute the request
     *
     * @param  MvcEvent $e
     * @return mixed
     */
    public function onDispatch(MvcEvent $e)
    {
        $env = $this->getRequest()->getParam('env');
        $dbu = $this->getRequest()->getParam('dbu');
        $dbp = $this->getRequest()->getParam('dbp');
        $dbn = $this->getRequest()->getParam('dbn');

        try {
            $environment = new Environment($env, new Copier());
            $result      = $environment->installEnv($env, $dbu, $dbp, $dbn);

            return $result ? "Success\n" : '';
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";
        }
    }
}