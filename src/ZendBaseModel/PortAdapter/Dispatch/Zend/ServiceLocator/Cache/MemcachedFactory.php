<?php

namespace ZendBaseModel\PortAdapter\Dispatch\Zend\ServiceLocator\Cache;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class MemcachedFactory
 * @package ZendBaseModel\Cache
 */
class MemcachedFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return \Memcached
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $memcachedOptions = $config['memcached'];

        $memcached = new \Memcached();
        $memcached->addServer($memcachedOptions['host'], $memcachedOptions['port']);

        return $memcached;
    }
}
