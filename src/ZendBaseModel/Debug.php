<?php

namespace ZendBaseModel;

use Doctrine\Common\Util\Debug as DoctrineDebug;

/**
 * Description of Debug
 *
 * @author seyfer
 */
class Debug
{

    /**
     * wrapper for smarter debug
     *
     * @param      $var
     * @param int  $maxDepth
     * @param bool $stripTags
     */
    public static function dump($var, $maxDepth = 2, $stripTags = true)
    {
        ob_start();
        DoctrineDebug::dump($var, $maxDepth, $stripTags);
        $dump = ob_get_clean();

        if (php_sapi_name() === 'cli') {
            echo $dump;
        } else {
            echo '<pre>';
            echo $dump;
            echo '</pre>';
        }
    }

    /**
     * many
     */
    public static function vars()
    {
        $vars = func_get_args();

        foreach ($vars as $var) {
            self::dump($var);
        }

    }

}
