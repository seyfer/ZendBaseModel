<?php
/**
 * Created by PhpStorm.
 * User: seyfer
 * Date: 4/19/16
 */
namespace ZendBaseModel\Application;


/**
 * Old code ported
 *
 * @author seyfer
 */
interface DateInterface
{
    /**
     * @param        $time
     * @param string $format
     * @return string
     */
    public static function deltadate($time, $format = '');

    /**
     * @param \DateTimeInterface $a
     * @param \DateTimeInterface $b
     * @param bool $absolute Should the interval be forced to be positive?
     * @param string $cap The greatest time unit to allow (H, M)
     *
     * @return \DateInterval The difference as a time only interval
     */
    public static function timeDiff(\DateTimeInterface $a, \DateTimeInterface $b, $absolute = false, $cap = 'H');

    /**
     * @param string $format
     * @param int $time
     * @return bool|mixed|string
     */
    public static function dateRu($format = 'd.m.Y', $time = 0);

    /**
     * @param      $date
     * @param bool $timestamp
     * @param bool $ymd
     * @return int|string
     */
    public static function dateconvert($date, $timestamp = false, $ymd = true);
}