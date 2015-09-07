<?php
/**
 * Created by PhpStorm.
 * User: seyfer
 * Date: 07.09.15
 * Time: 23:21
 */

namespace ZendBaseModel\Service\Factory;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ZendDbAdapterFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $dbParams = $serviceLocator->get('Application')->getConfig()['dbParams'];
        $modules  = $serviceLocator->get('ModuleManager')->getLoadedModules();

        if (isset($modules['BjyProfiler'])) {
            $adapter = new \BjyProfiler\Db\Adapter\ProfilingAdapter([
                                                                        'driver'   => $dbParams['driver'],
                                                                        'dsn'      => 'mysql:dbname=' . $dbParams['database'] .
                                                                                      ';host=' . $dbParams['hostname'],
                                                                        'database' => $dbParams['database'],
                                                                        'username' => $dbParams['username'],
                                                                        'password' => $dbParams['password'],
                                                                        'hostname' => $dbParams['hostname'],
                                                                    ]);

            if (php_sapi_name() == 'cli') {
                $logger = new \Zend\Log\Logger();
                // write queries profiling info to stdout in CLI mode
                $writer = new \Zend\Log\Writer\Stream('php://output');
                $logger->addWriter($writer, \Zend\Log\Logger::DEBUG);
                $adapter->setProfiler(new \BjyProfiler\Db\Profiler\LoggingProfiler($logger));
            } else {
                $adapter->setProfiler(new \BjyProfiler\Db\Profiler\Profiler());
            }
            if (isset($dbParams['options']) && is_array($dbParams['options'])) {
                $options = $dbParams['options'];
            } else {
                $options = [];
            }

            $adapter->injectProfilingStatementPrototype($options);
        } else {
            $adapter = new \Zend\Db\Adapter\Adapter($dbParams);
        }

        return $adapter;
    }
}