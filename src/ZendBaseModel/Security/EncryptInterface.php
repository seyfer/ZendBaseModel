<?php
/**
 * Created by PhpStorm.
 * User: seyfer
 * Date: 17.04.15
 * Time: 12:53
 */

namespace Account\Core\Infrastructure\Security;

/**
 * Interface EncryptInterface
 * @package Account\Core\Infrastructure\Security
 */
interface EncryptInterface
{
    public function makeSign($configKey);

    public function decryptSign($configKey, $sign);

    public function checkSign($configKey, $sign);
}