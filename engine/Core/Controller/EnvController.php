<?php
/**
 * Created by PhpStorm.
 * User: seyfer
 * Date: 9/23/15
 * Time: 11:00 PM
 */

namespace Core\Controller;

use Core\Tool\Environment;
use Zend\Mvc\Controller\AbstractActionController;
use ZendMover\Copier;

/**
 * Class EnvController
 * @package Core\Controller
 */
class EnvController extends AbstractActionController
{
    /**
     * @return string
     */
    public function installAction()
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