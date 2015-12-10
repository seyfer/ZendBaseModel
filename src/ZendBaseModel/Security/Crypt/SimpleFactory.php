<?php
namespace ZendBaseModel\Security\Crypt;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class SimpleFactory
 * @package ZendBaseModel\Security\Crypt
 */
class SimpleFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return Simple|mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config  = $serviceLocator->get('Config');
        $options = isset($config['crypt']) && isset($config['crypt']['simple']) ? $config['crypt']['simple'] : [];

        return new Simple($options);
    }
}