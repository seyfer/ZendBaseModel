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
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module implements
    ConsoleUsageProviderInterface, ServiceProviderInterface, ViewHelperProviderInterface
{

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

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

    public function getConsoleUsage(Console $console)
    {
        return [
        ];
    }

    public function getServiceConfig()
    {
        return [
            'shared' => [
                'doctrine.entitymanager.orm_default' => false
            ],
        ];
    }

    public function getViewHelperConfig()
    {
        return [
            'invokables' => [
                '\Zend\Form\View\Helper\FormElement' => \ZendBaseModel\Form\View\Helper\FormElement::class,
                'FormDateTimePicker'                 => \ZendBaseModel\Form\View\Helper\FormDateTimePicker::class,
            ],
        ];
    }

}
