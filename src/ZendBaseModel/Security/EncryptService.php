<?php

namespace ZendBaseModel\Security;

use Zend\Crypt\Symmetric\Mcrypt;

/**
 * Class Encrypt
 * @package Account\Core\Infrastructure\Security
 */
class EncryptService extends Mcrypt implements EncryptInterface
{

    /**
     * @var array
     */
    private $keys = [];

    /**
     * @var string
     */
    protected $salt;

    /**
     * @param array $options
     * @param array $keys
     */
    public function __construct(array $options = [], array $keys = [])
    {
        if (!$options) {
            //our protocol configured in this way
            $options = [
                'algo' => MCRYPT_RIJNDAEL_128,
                'mode' => MCRYPT_MODE_NOFB,
            ];
        }

        parent::__construct($options);
        $this->keys = $keys;
    }

    /**
     * our algorithm for sign creation
     *
     * @param $configKey
     * @return string
     */
    public function makeSign($configKey)
    {
        $this->setCalculatedSalt();

        $key = $this->setKeyFromConfigKey($configKey);

        $data = [
            //32 size is wrong!
            //            'key'  => $this->getKey(),
            'key'  => $key,
            'date' => time()
        ];

        $hash = $this->encrypt(serialize($data));

        return $hash;
    }

    /**
     * @param $configKey
     * @param $sign
     * @return mixed
     */
    public function decryptSign($configKey, $sign)
    {
        $this->setKeyFromConfigKey($configKey);

        $decrypted    = $this->decrypt($sign);
        $unserialized = unserialize($decrypted);

        return $unserialized;
    }

    /**
     * @param $configKey
     */
    public function setKeyFromConfigKey($configKey)
    {
        $key = $this->getKeyConfig($configKey)['key'];
        $this->setKey($key);

        return $key;
    }

    /**
     * @param $configKey
     * @param $sign
     * @return bool
     * @throws \Exception
     */
    public function checkSign($configKey, $sign)
    {
        if (is_string($configKey)) {
            $decrypted = $this->decryptSign($configKey, $sign);

            return $decrypted ? TRUE : FALSE;
        }

        if (is_array($configKey)) {
            return $this->checkGroupSign($configKey, $sign);
        }

        throw new \RuntimeException('Empty config key');
    }

    /**
     * @param array $configKeys
     * @param       $sign
     * @return bool
     */
    public function checkGroupSign(array $configKeys = [], $sign)
    {
        $checked = FALSE;
        foreach ($configKeys as $configKey) {
            $decrypted = $this->decryptSign($configKey, $sign);

            $checked = $decrypted ? TRUE : FALSE;

            if ($checked === TRUE) {
                break;
            }
        }

        return $checked;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getKeyConfig($key)
    {
        return $this->keys[$key];
    }

    /**
     *
     * @return string
     */
    public function calcSalt()
    {
        $this->salt = mcrypt_create_iv($this->getSaltSize(), MCRYPT_RAND);

        return $this->salt;
    }

    /**
     * соль
     */
    public function setCalculatedSalt()
    {
        $this->setSalt($this->calcSalt());
    }

    /**
     * @param string $data
     * @return string
     */
    public function encrypt($data)
    {
        $result = parent::encrypt($data);

        return base64_encode($result);
    }

    /**
     * @param string $data
     * @return string
     */
    public function decrypt($data)
    {
        $dataDec = base64_decode($data);

        return parent::decrypt($dataDec);
    }

}
