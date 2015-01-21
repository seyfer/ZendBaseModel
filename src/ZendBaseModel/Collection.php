<?php

namespace ZendBaseModel;

/**
 * Description of Collection
 *
 * @author seyfer
 */
class Collection implements
    \ArrayAccess, \Serializable, \Iterator, \Countable
{

    /**
     * main container
     *
     * @var type
     */
    protected $container;

    /**
     * class for collection item
     *
     * @var type
     */
    protected $itemPrototype;

    /**
     * container as it is
     *
     * @var type
     */
    protected $initialContainer;

    /**
     *
     * @param \ZendBaseModel\Collection|array $initial
     */
    public function __construct($initial = [])
    {
        if (is_array($initial)) {
            $this->container        = $initial;
            $this->initialContainer = $initial;
        } else if ($initial instanceof Collection) {
            $this->container        = $initial->getArrayCopy();
            $this->initialContainer = $initial->getArrayCopy();
        }
    }

    /**
     * get raw data
     *
     * @return type
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
     * @return type
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
     * @param callable $block
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
     * @param type $id
     * @return Contractor
     */
    public function findById($id)
    {
        if (!$this->container || !$id) {
            return;
        }

        foreach ($this->container as $each) {

            if ($each->getId() == $id) {
                return $each;
            }
        }
    }

    /**
     * обратно в массив
     *
     * @return \SED\Model\Collection\Contractors
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
     * как заполнить прототип
     *
     * @return $this
     * @throws \Exception
     */
    public function toPrototype()
    {
        if (!$this->itemPrototype) {
            throw new \Exception(__METHOD__ . " set prototype class first");
        }

        foreach ($this as $key => $value) {

            if (!class_exists($this->itemPrototype)) {
                throw new \RuntimeException(__METHOD__ . " class " .
                                            $this->itemPrototype . "doesn't exist");
            }

            $contractor = new $this->itemPrototype();

            if (is_string($value)) {
                $contractor->setId($key);
                $contractor->setName($value);
            } else if (is_array($value) && isset($value['id'])) {
                $contractor->setId($value['id']);
                $contractor->setName($value['name']);
            } else {
                throw new \Exception(__METHOD__ . " wrong format");
            }

            $this[$key] = $contractor;
        }

        return $this;
    }

}
