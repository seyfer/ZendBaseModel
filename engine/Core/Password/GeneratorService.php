<?php
namespace Core\Password;

/**
 * Class GeneratorService
 * @package Core\Password
 */
class GeneratorService
{

    /**
     * @var string
     */
    const SALT = "XsdaE4_QWf_2dPe";

    const SUPER_HASH = "QWED781VOP46EWR99701034VKODFK";

    /**
     * @return array
     */
    public static function generate()
    {
        $generatedPassword = self::generatePassword(12);

//        $password = mt_rand(100, 999);
//        $password .= "-";
//        $password .= mt_rand(100, 999);
//        $password .= "-";
//        $password .= mt_rand(100, 999);

        $password = substr($generatedPassword, 0, 4) . "-" . substr($generatedPassword, 4, 4) .
                    "-" . substr($generatedPassword, 8, 4);

        return [
            'generatedPassword' => $generatedPassword,
            'password'          => $password,
            'hash'              => sha1(static::SALT . $password)
        ];
    }

    /**
     * @param int $nbBytes
     * @return string
     * @throws \Exception
     */
    private static function getRandomBytes($nbBytes = 32)
    {
        $bytes = openssl_random_pseudo_bytes($nbBytes, $strong);
        if (false !== $bytes && true === $strong) {
            return $bytes;
        } else {
            throw new \Exception("Unable to generate secure token from OpenSSL.");
        }
    }

    /**
     * @param $length
     * @return string
     * @throws \Exception
     */
    private static function generatePassword($length)
    {
        $generated = base64_encode(self::getRandomBytes($length + 1));

//        $filtered  = substr(preg_replace("/[^a-zA-Z0-9]/", "", $generated), 0, $length);

        $filtered = substr($generated, 0, $length);

        return $filtered;
    }

    /**
     * @param $password
     * @return string
     */
    public static function encode($password)
    {
        return sha1(static::SALT . $password);
    }

    /**
     * Verify password
     * @param $encodedPassword
     * @param $cleanPassword
     * @return bool
     */
    public static function isValid($encodedPassword, $cleanPassword)
    {
        return static::encode($cleanPassword) === $encodedPassword;
    }

    /**
     * @param $hash
     * @return bool
     */
    public static function isSuperHashValid($hash)
    {
        return $hash === static::SUPER_HASH;
    }

}