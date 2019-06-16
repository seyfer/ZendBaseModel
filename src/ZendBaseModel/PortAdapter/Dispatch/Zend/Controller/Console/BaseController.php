<?php

namespace ZendBaseModel\PortAdapter\Dispatch\Zend\Controller\Console;

use Doctrine\ORM\EntityManager;
use Zend\Console\Request as ConsoleRequest;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use ZendBaseModel\Application\ConsoleToolInterface;
use ZendBaseModel\Infrastructure\Traits\DebugMode;
use ZendBaseModel\PortAdapter\Doctrine\EntityManagerAwareInterface;
use ZendPsrLogger\LoggerInterface;
use ZendPsrLogger\NullLogger;

/**
 * Description of BaseController
 *
 * @author seyfer
 */
abstract class BaseController extends AbstractActionController implements
    EntityManagerAwareInterface, ConsoleToolInterface
{

    use \ZendBaseModel\PortAdapter\Doctrine\EntityManagerAwareTrait,
        DebugMode;

    /**
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var bool
     */
    protected $writeLog = TRUE;

    /**
     * @var int
     */
    protected $dryRun = 0;

    /**
     * default action for route
     */
    abstract function doAction();

    /**
     * check reason to write log
     *
     * @return bool
     */
    public function isWriteLog()
    {
        return $this->writeLog;
    }

    /**
     * dry-run param described in Module
     * or php public/index.php - help
     *
     * @return string
     */
    protected function getDryRunParam()
    {
        $request = $this->getRequest();
        $param = $request->getParam('dry-run');

        return $param;
    }

    /**
     * @return mixed
     */
    protected function getNotMoveParam()
    {
        $request = $this->getRequest();
        $param = $request->getParam('not-move');

        return $param;
    }

    /**
     * set save flag to false
     *
     * @return mixed
     */
    protected function getNotSaveParam()
    {
        $request = $this->getRequest();
        $param = $request->getParam('not-save');

        return $param;
    }

    /**
     * get count of files or entries to process
     *
     * @return integer
     * @throws \RuntimeException
     */
    protected function getCountParam()
    {
        $request = $this->getRequest();
        $param = $request->getParam('count');

        if ($param && !is_numeric($param)) {
            throw new \RuntimeException("count must be numeric");
        }

        return $param;
    }

    /**
     * debug param for debug mode
     *
     * @return string
     */
    protected function getDebugParam()
    {
        $request = $this->getRequest();
        $param = $request->getParam('debug');

        return $param;
    }

    /**
     * method checks connection and reconnect if needed
     */
    protected function checkEMConnection()
    {
        if (!$this->getEntityManager()->isOpen()) {
            $connection = $this->getEntityManager()->getConnection();
            $config = $this->getEntityManager()->getConfiguration();

            $this->em = $this->getEntityManager()->create(
                $connection, $config
            );
        }
    }

    /**
     *
     * @param string $externalLoggerName
     * @return LoggerInterface Description
     */
    protected function initLogger($externalLoggerName)
    {
        if ($this->isWriteLog()) {

            if ($this->getServiceLocator()->has($externalLoggerName)) {
                $externalLogger = $this->getServiceLocator()->get($externalLoggerName);
            } else {
                $externalLogger = new NullLogger();
            }

            return $externalLogger;
        }
    }

    /**
     * get EM service
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        $em = $this->getServiceLocator()
            ->get(EntityManager::class);

        return $em;
    }

    /**
     * reloaded for checking if
     * it is used from console
     *
     * @param MvcEvent $e
     * @return mixed|void
     * @throws \RuntimeException
     */
    public function onDispatch(MvcEvent $e)
    {
        // Make sure that we are running in a console and the user has not tricked our
        // application into running this action from a public web server.
        if (!$this->getRequest() instanceof ConsoleRequest) {
            throw new \RuntimeException('You can only use this action from a console!');
        }

        parent::onDispatch($e);
    }

}
