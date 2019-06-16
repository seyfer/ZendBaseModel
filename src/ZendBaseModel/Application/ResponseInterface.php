<?php
/**
 * Created by PhpStorm.
 * User: seyfer
 * Date: 4/19/16
 */

namespace ZendBaseModel\Application;


/**
 * Class Response
 * @package ZendBaseModel\Service
 */
interface ResponseInterface
{
    /**
     * @param bool $success
     * @param null $result
     * @param null $message
     * @param mixed $additionalData
     * @return ResponseInterface
     */
    public static function create($success, $result = null, $message = null, $additionalData = null);

    public function getSuccess();

    public function getResult();

    public function getMessage();

    public function getAdditionalData();

    public function toArray();
}
