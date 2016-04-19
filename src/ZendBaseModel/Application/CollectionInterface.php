<?php
/**
 * Created by PhpStorm.
 * User: seyfer
 * Date: 4/19/16
 */
namespace ZendBaseModel\Application;


/**
 * Description of Collection
 *
 * @author seyfer
 */
interface CollectionInterface
{
    /**
     * get raw data
     *
     * @return array
     */
    public function getInitialContainer();

    /**
     * @param $itemPrototype
     * @return $this
     */
    public function setItemPrototype($itemPrototype);

    /**
     * @return array
     */
    public function getArrayCopy();

    /**
     * @return int
     */
    public function count();

    /**
     * @return mixed
     */
    public function current();

    /**
     * @return mixed
     */
    public function key();

    /**
     * @return mixed
     */
    public function next();

    /**
     * @return mixed
     */
    public function rewind();

    /**
     * @return bool
     */
    public function valid();

    /**
     * @param mixed $offset
     * @return bool
     *
     */
    public function offsetExists($offset);

    /**
     * @param mixed $offset
     * @return null
     */
    public function offsetGet($offset);

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value);

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset);

    /**
     * @return string
     */
    public function serialize();

    /**
     * @param string $serialized
     */
    public function unserialize($serialized);

    /**
     * @param callable|\Closure $block
     */
    public function each(\Closure $block);

    /**
     * @return \ArrayIterator
     */
    public function getIterator();

    /**
     *
     * @param integer $id
     * @return mixed
     */
    public function findById($id);

    /**
     * @return $this
     */
    public function toArray();

    /**
     *
     * @return $this
     * @throws \Exception
     */
    public function toPrototype();
}