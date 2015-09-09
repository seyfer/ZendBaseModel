<?php

namespace Account\Core\Infrastructure\Security;

use Zend\Crypt\Exception\InvalidArgumentException;
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
     * @param string $salt
     * @return void|Mcrypt
     */
    public function setSalt($salt)
    {
        return parent::setSalt($salt);
    }

    /**
     * @param string $data
     * @return string
     */
    public function encrypt($data)
    {
        if (empty($data)) {
            throw new InvalidArgumentException('The data to encrypt cannot be empty');
        }
        if (null === $this->getKey()) {
            throw new InvalidArgumentException('No key specified for the encryption');
        }
        if (null === $this->getSalt()) {
            throw new InvalidArgumentException('The salt (IV) cannot be empty');
        }
        if (null === $this->getPadding()) {
            throw new InvalidArgumentException('You have to specify a padding method');
        }

        //в чем соль
        $iv = $this->getSalt();

        // encryption
        $result = mcrypt_encrypt(
            $this->supportedAlgos[$this->algo],
            $this->getKey(),
            $data,
            $this->supportedModes[$this->mode],
            $iv
        );

        return base64_encode($iv . $result);
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
