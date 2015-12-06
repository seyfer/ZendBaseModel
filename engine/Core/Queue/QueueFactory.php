<?php
namespace Core\Queue;

use SimpleQueue\Adapter\PdoSQL\MySQLDriver;
use SimpleQueue\Adapter\PdoSQLAdapter;
use SimpleQueue\QueueService as SimpleQueueService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZendQueue\Adapter\Db as AdapterDb;

/**
 * Class QueueFactory
 * @package Core\Queue
 */
class QueueFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return QueueService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config            = $serviceLocator->get('Config');
        $connectionDefault = $config['doctrine']['connection']['orm_default']['params'];

        $connection = [
            'host'     => $connectionDefault['host'],
            'port'     => $connectionDefault['port'],
            'username' => $connectionDefault['user'],
            'password' => $connectionDefault['password'],
            'dbname'   => $connectionDefault['dbname'],
            'type'     => 'pdo_mysql'
        ];

        $driver = new MySQLDriver(
            $connectionDefault['host'], $connectionDefault['user'], $connectionDefault['password'],
            $connectionDefault['dbname']
        );

        $adapter = new PdoSQLAdapter($driver);
        $queue   = new SimpleQueueService($adapter);

        $service = new QueueService($queue);

        return $service;
    }
}