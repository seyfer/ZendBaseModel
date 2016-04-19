<?php
namespace ZendBaseModel\Application;

/**
 * Class Response
 * @package ZendBaseModel\Service
 */
class Response implements ResponseInterface
{

    protected $success = false;
    protected $result;
    protected $message;
    protected $additionalData;

    /**
     * Response constructor.
     * @param $success
     * @param null $result
     * @param null $message
     * @param null $additionalData
     */
    public function __construct($success, $result = null, $message = null, $additionalData = null)
    {
        $this->success        = $success;
        $this->result         = $result;
        $this->message        = $message;
        $this->additionalData = $additionalData;
    }

    /**
     * @param bool $success
     * @param null $result
     * @param null $message
     * @param mixed $additionalData
     * @return ResponseInterface
     */
    public static function create($success, $result = null, $message = null, $additionalData = null)
    {
        return new static($success, $result, $message, $additionalData);
    }

    public function getSuccess()
    {
        return $this->success;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getAdditionalData()
    {
        return $this->additionalData;
    }

    public function toArray()
    {
        return [
            'success'        => $this->success,
            'result'         => $this->result,
            'message'        => $this->message,
            'additionalData' => $this->additionalData
        ];
    }

}