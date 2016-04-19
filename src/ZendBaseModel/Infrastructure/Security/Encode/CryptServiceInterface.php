<?php
/**
 * Created by PhpStorm.
 * User: seyfer
 * Date: 4/19/16
 */
namespace ZendBaseModel\Infrastructure\Security\Encode;


/**
 * Class CryptService
 * @package ZendBaseModel\Security\Encode
 */
interface CryptServiceInterface
{
    /**
     * @param $sourceString
     * @return string
     */
    public function encode($sourceString);

    /**
     * Decode encoded before data
     * @param $encodedSource
     * @return mixed
     */
    public function decode($encodedSource);
}