<?php
namespace Core\Crypt;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class SimpleFactory
 * @package Core\Crypt
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