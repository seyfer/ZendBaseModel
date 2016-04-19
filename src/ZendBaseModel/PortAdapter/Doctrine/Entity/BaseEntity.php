<?php

namespace ZendBaseModel\PortAdapter\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;
use ZendBaseModel\Domain\Entity\EntityInterface;

/**
 * Description of Entry
 *
 * @author seyfer
 * @ORM\MappedSuperclass
 */
abstract class BaseEntity implements EntityInterface
{

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * @param array $array
     */
    public function exchangeArray(array $array)
    {
        foreach ($array as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * @param $key
     * @param $value
     */
    private function set($key, $value)
    {
        $workKey = $key;

        //fix underscore
        if (strpos($workKey, "_") !== FALSE) {

            $workKey = preg_replace_callback('/_(.?)/', function ($a) {
                return strtoupper($a[1]);
            }, $key);
        }

        if (property_exists($this, $workKey)) {
            $setter = "set" . ucfirst($workKey);

            if (method_exists($this, $setter) && $value !== NULL) {
                $this->$setter($value);
            }
        }
    }

}
