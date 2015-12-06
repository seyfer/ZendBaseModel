<?php
namespace Core\Encode;

/**
 * Class CryptService
 * @package Tour\Infrastructure\Encode
 */
class CryptService
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
     * @param $sourceString
     * @return string
     */
    public function encode($sourceString)
    {
        $encrypted = $this->doCrypt($sourceString, $this->encryptKey, $this->salt);

        return base64_encode($encrypted);
    }

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

        return $this->doCrypt($decoded, $this->encryptKey, $this->salt);
    }
}