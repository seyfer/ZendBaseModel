<?php
/**
 * Created by PhpStorm.
 * User: seyfer
 * Date: 09.09.15
 * Time: 18:10
 */

namespace Account\Core\Infrastructure\Security;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EncryptServiceFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        if (!is_array($config) || !array_key_exists('security', $config)) {
            throw new \RuntimeException('Security keys not set');
        }

        return new EncryptService([], $config['security']['keys']);
    }
}