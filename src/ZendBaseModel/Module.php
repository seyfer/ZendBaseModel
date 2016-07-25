<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendBaseModel;

use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use ZendBaseModel\PortAdapter\Dispatch\Zend\Form\View\Helper\FormDateTimePicker;
use ZendBaseModel\PortAdapter\Dispatch\Zend\Form\View\Helper\FormElement;

/**
 * Class Module
 * @package ZendBaseModel
 */
class Module implements
    ConfigProviderInterface,
    AutoloaderProviderInterface,
    ConsoleUsageProviderInterface,
    ServiceProviderInterface,
    ViewHelperProviderInterface
{

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__,
                ],
            ],
        ];
    }

    /**
     * @param Console $console
     * @return array
     */
    public function getConsoleUsage(Console $console)
    {
        return [
        ];
    }

    /**
     * @return array
     */
    public function getServiceConfig()
    {
        return [
        ];
    }

    /**
     * @return array
     */
    public function getViewHelperConfig()
    {
        return [
            'invokables' => [
                '\Zend\Form\View\Helper\FormElement' => FormElement::class,
                'FormDateTimePicker'                 => FormDateTimePicker::class,
            ],
        ];
    }

}
