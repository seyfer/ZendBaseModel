<?php
namespace ZendBaseModel\PortAdapter\Dispatch\Zend\ServiceLocator\Exception;

use Zend\Log\Logger;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZendBaseModel\PortAdapter\Exception\ExceptionListener;

/**
 * Class ExceptionFactory
 * @package ZendBaseModel\Exception
 */
class ExceptionFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocatorInterface
     * @return ExceptionListener
     */
    public function createService(ServiceLocatorInterface $serviceLocatorInterface)
    {
        $config          = $serviceLocatorInterface->get('Config');
        $routeParams     = $config['exceptionHandler']['routeMatch'];
        $stopPropagation = $config['exceptionHandler']['stopPropagation'];

        /** @var Logger $logger */
        $logger = $serviceLocatorInterface->get('logger.exceptions');

        return new ExceptionListener($routeParams, $logger, $stopPropagation);
    }

}