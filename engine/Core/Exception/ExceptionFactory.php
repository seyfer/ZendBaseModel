<?php
namespace Core\Exception;

use Zend\Log\Logger;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ExceptionFactory
 * @package Core\Exception
 */
class ExceptionFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocatorInterface
     * @return ExceptionListener
     */
    public function createService(ServiceLocatorInterface $serviceLocatorInterface)
    {
        $config      = $serviceLocatorInterface->get('Config');
        $routeParams = $config['exceptionHandler']['routeMatch'];

        /** @var Logger $logger */
        $logger = $serviceLocatorInterface->get('logger.exceptions');

        return new ExceptionListener($routeParams, $logger);
    }

}