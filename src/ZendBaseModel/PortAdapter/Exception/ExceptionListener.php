<?php
namespace ZendBaseModel\PortAdapter\Exception;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Log\Logger;
use Zend\Mvc\Application as ZendApplication;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\View\Model\ModelInterface;
use ZendBaseModel\Application\ValidationException;

/**
 * Class ExceptionListener
 * @package Exception
 */
class ExceptionListener extends AbstractListenerAggregate
{
    /**
     * @var array
     */
    protected $routeParams;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var boolean
     */
    protected $stopPropagation;

    /**
     * @param array $routeParams
     * @param Logger $logger
     * @param bool $stopPropagation
     */
    public function __construct(array $routeParams, Logger $logger, $stopPropagation = true)
    {
        $this->routeParams     = $routeParams;
        $this->logger          = $logger;
        $this->stopPropagation = $stopPropagation;

    }

    /**
     * @inheritdoc
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH_ERROR, [$this, 'handleException']);
    }

    /**
     * @param MvcEvent $e
     * @return bool|mixed
     */
    public function handleException(MvcEvent $e)
    {
        $error = $e->getError();
        if (empty($error)) {
            return true;
        }

        // Do nothing if the result is a response object
        $result = $e->getResult();
        if ($result instanceof Response) {
            return true;
        }

        switch ($error) {
            case ZendApplication::ERROR_CONTROLLER_NOT_FOUND:
            case ZendApplication::ERROR_CONTROLLER_INVALID:
            case ZendApplication::ERROR_ROUTER_NO_MATCH:
                // Specifically not handling next cases
                return true;
            case ZendApplication::ERROR_EXCEPTION:
            default:
                $application = $e->getApplication();
                $routeMatch  = new RouteMatch($this->routeParams);

                /** @var \Exception $exception */
                $exception = $e->getParam('exception');
                if (!$exception instanceof ValidationException) {
                    $this->logger->err($exception->getMessage(), $exception->getTrace());
                    $message = 'Internal server error';
                } else {
                    $message = $exception->getMessage();
                }

                //New MvcEvent assembling
                $event = new MvcEvent(MvcEvent::EVENT_DISPATCH);
                $event->setApplication($application)
                      ->setRequest($e->getRequest())
                      ->setResponse($e->getResponse())
                      ->setRouter($e->getRouter())
                      ->setError($e->getError())
                      ->setRouteMatch($routeMatch)
                      ->setParam('message', $message)
                      ->setParam('exception', $e->getParam('exception'));


                // Trigger new dispatch MvcEvent till we get some Model from our ExceptionController
                $responses = $application->getEventManager()->trigger($event, function ($response) {
                    return $response instanceof ModelInterface;
                });

                if (!$responses->stopped()) {
                    // There is no Model returned. Something went wrong...
                    //TODO: there is unexpected situation i think, should throw exception
                    return true;
                }

                $e->stopPropagation($this->stopPropagation);
                $e->setViewModel($responses->last());

                return $responses->last();
        }
    }
}