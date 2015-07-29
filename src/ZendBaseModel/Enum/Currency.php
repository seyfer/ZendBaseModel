<?php

namespace ZendBaseModel\Enum;

/**
 * Description of Currency
 *
 * @author seyfer
 */
class Currency extends BaseEnum
{

    const USD = 'USD';
    const EUR = 'EUR';
    const RUB = 'RUB';
    const KGS = 'KGS';

    public static $available = [self::USD, self::EUR, self::RUB, self::KGS];

    /**
     * map
     *
     * @var array
     */
    protected static $currencyMap
        = [
            "РУБ" => "RUB",
            "КГС" => "KGS",
        ];

    /**
     *
     * @param string $in
     * @return string|NULL
     */
    public static function getByMap($in)
    {
        $out = self::$currencyMap[$in] ?: $in;

        return $out;
    }

}
