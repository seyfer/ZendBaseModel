<?php
/**
 * Created by PhpStorm.
 * User: seyfer
 * Date: 08.06.15
 * Time: 22:26
 */

namespace ZendBaseModel\PortAdapter\Event\EntityManager;

use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;

/**
 * Class StaticEventManager
 * @package ZendBaseModel\Event\EntityManager
 */
class StaticEventManager extends EventManager
{
    /**
     * @var EventManagerInterface
     */
    protected static $instance;

    /**
     * Singleton
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Retrieve instance
     *
     * @return StaticEventManager
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::setInstance(new static());
        }

        return static::$instance;
    }

    /**
     * Set the singleton to a specific SharedEventManagerInterface instance
     *
     * @param EventManagerInterface $instance
     * @return void
     */
    public static function setInstance(EventManagerInterface $instance)
    {
        static::$instance = $instance;
    }

    /**
     * Is a singleton instance defined?
     *
     * @return bool
     */
    public static function hasInstance()
    {
        return (static::$instance instanceof EventManagerInterface);
    }

    /**
     * Reset the singleton instance
     *
     * @return void
     */
    public static function resetInstance()
    {
        static::$instance = null;
    }

}