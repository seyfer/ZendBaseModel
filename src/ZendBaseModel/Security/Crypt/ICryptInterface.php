<?php
namespace ZendBaseModel\Security\Crypt;

/**
 * Class CryptInterface
 * @package ZendBaseModel\Security\Crypt
 */
interface ICryptInterface
{
    /**
     * Encode method
     * @param $source
     * @return mixed
     */
    public function encode($source);

    /**
     * Decode encoded before data
     * @param $encodedSource
     * @return mixed
     */
    public function decode($encodedSource);

}