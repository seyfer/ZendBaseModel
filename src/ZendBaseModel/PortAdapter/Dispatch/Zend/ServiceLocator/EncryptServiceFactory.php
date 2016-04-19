<?php
/**
 * Created by PhpStorm.
 * User: seyfer
 * Date: 09.09.15
 * Time: 18:10
 */

namespace ZendBaseModel\PortAdapter\Dispatch\Zend\ServiceLocator;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZendBaseModel\PortAdapter\Security\EncryptService;

/**
 * Class EncryptServiceFactory
 * @package ZendBaseModel\PortAdapter\Dispatch\Zend\ServiceLocator
 */
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