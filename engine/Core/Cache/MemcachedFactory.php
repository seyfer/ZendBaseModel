<?php
namespace Core\Cache;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class MemcachedFactory
 * @package Core\Cache
 */
class MemcachedFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config           = $serviceLocator->get('Config');
        $memcachedOptions = $config['memcached'];

        $memcached = new \Memcached();
        $memcached->addServer($memcachedOptions['host'], $memcachedOptions['port']);

        return $memcached;
    }
}