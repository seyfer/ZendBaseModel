<?php
/**
 * Created by PhpStorm.
 * User: seyfer
 * Date: 8/18/16
 */

namespace ZendBaseModel\PortAdapter\Doctrine;

/**
 * Class EntityManagerReconnectTrait
 * @package ZendBaseModel\PortAdapter\Doctrine
 */
trait EntityManagerReconnectTrait
{
    use EntityManagerAwareTrait;

    public function close()
    {
        $this->getEm()->clear();
        $this->disconnect();
    }

    public function disconnect()
    {
        $this->getEm()->getConnection()->close();
    }

    public function connect()
    {
        $this->getEm()->getConnection()->connect();
    }

    /**
     * MySQL Server has gone away
     */
    public function reconnect()
    {
        $connection = $this->getEm()->getConnection();
        if (!$connection->ping()) {

            $this->disconnect();
            $this->connect();

            $this->checkEMConnection($connection);
        }
    }

    /**
     * method checks connection and reconnect if needed
     * MySQL Server has gone away
     *
     * @param $connection
     * @throws \Doctrine\ORM\ORMException
     */
    protected function checkEMConnection($connection)
    {
        if (!$this->getEm()->isOpen()) {
            $config = $this->getEm()->getConfiguration();

            $this->em = $this->getEm()->create(
                $connection, $config
            );
        }
    }

}