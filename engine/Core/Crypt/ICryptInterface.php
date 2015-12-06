<?php
namespace Core\Crypt;

/**
 * Class CryptInterface
 * @package Core\Crypt
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