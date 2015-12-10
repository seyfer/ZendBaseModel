<?php
namespace ZendBaseModel\Security\Crypt;

/**
 * Class Simple
 * @package ZendBaseModel\Security\Crypt
 */
class Simple implements ICryptInterface
{

    const ENCRYPT_KEY  = 'pass123';
    const ENCRYPT_SALT = 'bGuxLwQtGweGHJMV4';

    /**
     * @var string
     */
    protected $encryptKey;

    /**
     * @var string
     */
    protected $salt;

    function __construct($options)
    {
        $this->encryptKey = isset($options['encryptKey']) ? $options['encryptKey'] : self::ENCRYPT_KEY;
        $this->salt       = isset($options['salt']) ? $options['salt'] : self::ENCRYPT_SALT;
    }

    /**
     * Encode data
     * @param array|object $source
     * @return int
     */
    public function encode($source)
    {

        $sourceString = serialize($source);

        $encrypted = $this->doCrypt($sourceString, $this->encryptKey, $this->salt);

        return base64_encode($encrypted);
    }

    /**
     * @param $string
     * @param $sequence
     * @param $salt
     * @return int
     */
    protected function doCrypt($string, $sequence, $salt)
    {
        $strLen = strlen($string);
        $gamma  = '';
        while (strlen($gamma) < $strLen) {
            $sequence = pack("H*", sha1($gamma . $sequence . $salt));
            $gamma .= substr($sequence, 0, 8);
        }

        return $string ^ $gamma;

    }

    /**
     * Decode encoded before data
     * @param $encodedSource
     * @return mixed
     */
    public function decode($encodedSource)
    {
        if (empty($encodedSource)) {
            return null;
        }

        $decoded = base64_decode($encodedSource);

        if (empty($decoded)) {
            return null;
        }

        $decrypted = $this->doCrypt($decoded, $this->encryptKey, $this->salt);

        if (!$this->isSerialized($decrypted)) {
            return null;
        }

        return unserialize($decrypted);
    }

    /**
     * Check is string serialized
     * @param $str
     * @return bool
     */
    public function isSerialized($str)
    {
        return ($str == serialize(false) || @unserialize($str) !== false);
    }

}