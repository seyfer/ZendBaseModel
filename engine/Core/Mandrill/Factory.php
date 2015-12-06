<?php
namespace Core\Mandrill;

use Zend\Log\Logger;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Factory
 * @package Core\Mandrill
 */
class Factory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocatorInterface
     * @return \Mandrill
     */
    public function createService(ServiceLocatorInterface $serviceLocatorInterface)
    {
        $config      = $serviceLocatorInterface->get('Config');
        $apiKey = $config['mandrill']['apiKey'];
        $host = $config['host'];

        $replyTo = $config['mandrill']['replyTo'];
        $from = $config['mandrill']['from'];

        return new MandrillSenderService(new \Mandrill($apiKey), $host, $replyTo, $from);
    }

}