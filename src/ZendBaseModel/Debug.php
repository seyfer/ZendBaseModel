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

    public static $stripTags = FALSE;

    public static $off = FALSE;

    /**
     * @return boolean
     */
    public static function isOff()
    {
        return self::$off;
    }

    /**
     * @param boolean $off
     * @return $this
     */
    public static function setOff($off)
    {
        self::$off = $off;
    }

    /**
     * @return boolean
     */
    public static function isStripTags()
    {
        return self::$stripTags;
    }

    /**
     * @param boolean $stripTags
     * @return $this
     */
    public static function setStripTags($stripTags)
    {
        self::$stripTags = $stripTags;
    }

    /**
     * wrapper for smarter debug
     *
     * @param      $var
     * @param int $maxDepth
     * @param bool $stripTags
     */
    public static function dump($var, $maxDepth = 3, $stripTags = null)
    {
        if (!self::isDevMode()) {
            return;
        }

        if ($stripTags === null) {
            $stripTags = self::isStripTags();
        }

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
        if (!self::isDevMode()) {
            return;
        }

        $vars = func_get_args();

        foreach ($vars as $var) {
            self::dump($var);
        }

    }

    /**
     * Constant for safety use in production
     *
     * @return bool
     */
    private static function isDevMode()
    {
        if (defined('DEVELOPMENT_MODE') && DEVELOPMENT_MODE == TRUE) {
            if (self::isOff()) {
                return FALSE;
            }

            return TRUE;
        }

        return FALSE;
    }

}
