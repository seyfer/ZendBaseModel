<?php

namespace ZendBaseModel\Application;

/**
 * Description of Collection
 *
 * @author seyfer
 */
class Collection implements
    \ArrayAccess, \Serializable, \Iterator, \Countable, CollectionInterface
{

    /**
     * main container
     *
     * @var array
     */
    protected $container;

    /**
     * class for collection item
     *
     * @var \stdClass
     */
    protected $itemPrototype;

    /**
     * container as it is
     *
     * @var array
     */
    protected $initialContainer;

    /**
     *
     * @param CollectionInterface|array $initial
     */
    public function __construct($initial = [])
    {
        if (is_array($initial)) {
            $this->container        = $initial;
            $this->initialContainer = $initial;
        } else if ($initial instanceof CollectionInterface) {
            $this->container        = $initial->getArrayCopy();
            $this->initialContainer = $initial->getArrayCopy();
        }
    }

    /**
     * get raw data
     *
     * @return array
     */
    public function getInitialContainer()
    {
        return $this->initialContainer;
    }

    /**
     * @param $itemPrototype
     * @return $this
     */
    public function setItemPrototype($itemPrototype)
    {
        $this->itemPrototype = $itemPrototype;

        return $this;
    }

    /**
     * @return array
     */
    public function getArrayCopy()
    {
        return $this->container;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->container);
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return current($this->container);
    }

    /**
     * @return mixed
     */
    public function key()
    {
        return key($this->container);
    }

    /**
     * @return mixed
     */
    public function next()
    {
        return next($this->container);
    }

    /**
     * @return mixed
     */
    public function rewind()
    {
        return reset($this->container);
    }

    /**
     * @return bool
     */
    public function valid()
    {
        $key = key($this->container);

        return ($key !== NULL && $key !== FALSE);
    }

    /**
     * @param mixed $offset
     * @return bool
     *
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * @param mixed $offset
     * @return null
     */
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize($this->container);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $this->container = unserialize($serialized);
    }

    /**
     * @param callable|\Closure $block
     */
    public function each(\Closure $block)
    {
        foreach ($this->container as $el) {
            $block($el);
        }
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->container);
    }

    /**
     *
     * @param integer $id
     * @return mixed
     */
    public function findById($id)
    {
        if (!$this->container || !$id) {
            return null;
        }

        foreach ($this->container as $each) {

            if (is_object($each)) {
                if ($each->getId() == $id) {
                    return $each;
                }
            }

            if (is_array($each)) {
                if ($each['id'] == $id) {
                    return $each;
                }
            }
        }

        return null;
    }

    /**
     * @return $this
     */
    public function toArray()
    {
        foreach ($this as $id => $value) {

            if (is_object($value)) {
                $valueArr  = $value->getArrayCopy();
                $this[$id] = $valueArr;
            }
        }

        return $this;
    }

    /**
     *
     * @return $this
     * @throws \Exception
     */
    public function toPrototype()
    {
    }

    //how to implement toPrototype example
//    public function toPrototype()
//    {
//        if (!$this->itemPrototype) {
//            throw new \Exception(__METHOD__ . " set prototype class first");
//        }
//
//        foreach ($this as $key => $value) {
//
//            if (!class_exists($this->itemPrototype)) {
//                throw new \RuntimeException(__METHOD__ . " class " .
//                                            $this->itemPrototype . "doesn't exist");
//            }
//
//            $contractor = new $this->itemPrototype();
//
//            if (is_string($value)) {
//                $contractor->setId($key);
//                $contractor->setName($value);
//            } else if (is_array($value) && isset($value['id'])) {
//                $contractor->setId($value['id']);
//                $contractor->setName($value['name']);
//            } else {
//                throw new \Exception(__METHOD__ . " wrong format");
//            }
//
//            $this[$key] = $contractor;
//        }
//
//        return $this;
//    }

}
