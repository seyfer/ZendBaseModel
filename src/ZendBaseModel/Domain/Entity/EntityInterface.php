<?php

namespace ZendBaseModel\Domain\Entity;

/**
 * @author seyfer
 */
interface EntityInterface
{

    public function getId();

    public function getArrayCopy();

    public function exchangeArray(array $array);
}
