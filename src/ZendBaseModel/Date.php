<?php

namespace ZendBaseModel;

/**
 * Old code ported
 *
 * @author seyfer
 */
class Date extends \DateTime
{

    const STANDARD = "Y-m-d H:i:s";

    /**
     * @param        $time
     * @param string $format
     * @return string
     */
    public static function deltadate($time, $format = '')
    {
        $hours = floor(($time) / 3600);
        $time -= $hours * 3600;
        $minuts = sprintf('%1$02d', floor($time / 60));

        $res = $hours . ':' . $minuts . '';

        return $res;
    }

    /**
     * @param string $format
     * @param int    $time
     * @return bool|mixed|string
     */
    public static function dateRu($format = 'd.m.Y', $time = 0)
    {
        $month = ['January'   => 'Январь', 'February' => 'Февраль',
                  'March'     => 'Март', 'April' => 'Апрель',
                  'May'       => 'Май', 'June' => 'Июнь',
                  'July'      => 'Июль', 'August' => 'Август',
                  'September' => 'Сентябрь', 'October' => 'Октябрь',
                  'November'  => 'Ноябрь', 'December' => 'Декабрь'];

        $res = date($format, $time);

        foreach ($month as $key => $val) {
            $res = str_replace($key, $val, $res);
        }

        return $res;
    }

    /**
     * @param      $date
     * @param bool $timestamp
     * @param bool $ymd
     * @return int|string
     */
    public static function dateconvert($date, $timestamp = false, $ymd = true)
    {
        // already a timestamp
        if (is_numeric($date)) {
            return $timestamp ? $date : date('Y-m-d', $date);
        }

        // mktime(hh, mm, ss, MM, DD, YYYY);
        $conv = ['ЯНВ' => '01', 'ФЕВ' => '02', 'МАР' => '03',
                 'АПР' => '04', 'МАЙ' => '05', 'ИЮН' => '06',
                 'ИЮЛ' => '07', 'АВГ' => '08', 'СЕН' => '09',
                 'ОКТ' => '10', 'НОЯ' => '11', 'ДЕК' => '12',
                 'JAN' => '01', 'FEB' => '02', 'MAR' => '03',
                 'APR' => '04', 'MAY' => '05', 'JUN' => '06',
                 'JUL' => '07', 'AUG' => '08', 'SEP' => '09',
                 'OCT' => '10', 'NOV' => '11', 'DEC' => '12'];

        // YYMMDD
        // 110328
        //  BUT
        // DDMMYY 280311 ???
        if (preg_match('/^(\d{2})(\d{2})(\d{2})$/', $date, $arr)) {

            if ($ymd) {
                $year = $arr[1] <= date('y') ? $arr[1] + 2000 : $arr[1] + 1900;

                return $timestamp ? mktime(0, 0, 0, $arr[2], $arr[3], $year) :
                    $year . '-' . $arr[2] . '-' . $arr[3];
            } else {
                $year = $arr[3] <= date('y') ? $arr[3] + 2000 : $arr[3] + 1900;

                return $timestamp ? mktime(0, 0, 0, $arr[2], $arr[1], $year) :
                    $year . '-' . $arr[2] . '-' . $arr[1];
            }
        }

        //формат осмп
        // (hh:mm )dd.mm.yy(yy)( hh:mm)
        if (preg_match('/^((\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2}))?$/', $date, $match)) {
            $_timestamp = mktime((int)$match[5], (int)$match[6], (int)$match[7], ((int)$match[3]) - 1, (int)$match[4], (int)$match[2]);

            return $timestamp ?
                $_timestamp : date('Y-m-d H:i:s', $_timestamp);
        }

        // (hh:mm )dd.mm.yy(yy)( hh:mm)
        if (preg_match('/^((\d{2}):(\d{2})(\.000)? )?(\d{2})[\.\/](\d{2})[\.\/](\d{2,4})( (\d{2}):(\d{2})(\.000)?)?$/', $date, $match)) {
            $minutes = $match[3] ? $match[3] : $match[10];
            $hours   = $match[2] ? $match[2] : $match[9];
            $day     = $match[5];
            $month   = $match[6];
            $year    = $match[7] + ($match[7] < 100 ?
                    ($match[7] > 25 ? 1900 : 2000) : 0);

            return mktime((int)$hours, (int)$minutes, 0, $month, $day, $year);
        }

        // (hh:mm )yy(yy)-mm-dd( hh:mm:ss)
        if (preg_match('/^((\d{2}):(\d{2}(\.000)?) )?(\d{2,4})\-(\d{2})\-(\d{2})( (\d{2}):(\d{2}):(\d{2})(\.000)?)?$/', $date, $match)) {
            $minutes = $match[3] ? $match[3] : $match[10];
            $hours   = $match[2] ? $match[2] : $match[9];
            $day     = $match[7];
            $month   = $match[6];
            $year    = $match[5] + ($match[5] < 100 ?
                    ($match[5] > 25 ? 1900 : 2000) : 0);

            return mktime((int)$hours, (int)$minutes, 0, $month, $day, $year);
        }

        // hh:mm
        if (preg_match('/^(\d{1,2})\:(\d{2})$/', $date, $match)) {
            return ($match[1] * 60 + $match[2]) * 60;
        }

        // all ok, 1912-12-31 or 1912-1-1 or 2011-02-2
        if (preg_match('/(\d{4})-(\d{1,2})-(\d{1,2})/', $date, $arr)) {

            return $timestamp ?
                mktime(0, 0, 0, $arr[2], $arr[3], $arr[1]) : $date;
        }

        // 18.03.2005
        if (preg_match('/^(\d{2})\.(\d{2})\.(\d{2,4})$/', $date, $arr)) {

            $year = $arr[3] > 1900 ? $arr[3] :
                ($arr[3] <= date('y') ? $arr[3] + 2000 : $arr[3] + 1900);

            return $timestamp ?
                mktime(0, 0, 0, $arr[2], $arr[1], $year) :
                $year . '-' . $arr[2] . '-' . $arr[1];
        }

        // 09:06 3/01/11
        // 3/01/11
        if (preg_match('/((\d{2}):(\d{2}) ){0,1}(\d{1,2})[\.\/](\d{2})[\.\/](\d{2})/', $date, $arr)) {

            $year = $arr[6] <= date('y') ? $arr[6] + 2000 : $arr[6] + 1900;

            return $timestamp ?
                mktime((float)$arr[2], (float)$arr[3], 0, $arr[5], $arr[4], $year) :
                $year . '-' . $arr[5] . '-' . ($arr[4] < 10 ? '0' . (string)$arr[4] : $arr[4]);
        }

        //        // 3/01/11 09:06 не сделано
        //        // 3/01/11
        //        if (preg_match('/(\d{1,2})[\.\/](\d{2})[\.\/](\d{2})( (\d{2}):(\d{2})){0,1}/', $date, $arr))
        //        {
        //
        //            $year = $arr[3] <= date('y') ? $arr[3] + 2000 : $arr[3] + 1900;
        //
        //            return $timestamp ? mktime((float) $arr[4], (float) $arr[5], 0, $arr[5], $arr[4], $year) :
        //                    $year . '-' . $arr[5] . '-' . ($arr[4] < 10 ? '0' . (string) $arr[4] : $arr[4]);
        //        }

        // 10FEB2355, 31OCT87
        if (preg_match('/(\d{2})(\w{3})(\d{2})(\d{2})/', $date, $arr)) {

            $year = date('Y');

            return $timestamp ? mktime($arr[3], $arr[4], 0, $conv[$arr[2]], $arr[1], $year) :
                $year . '-' . $conv[$arr[2]] . '-' . $arr[1] . " {$arr[3]}:{$arr[4]}:00";
        }

        // T2255-D01122011 ...
        if (preg_match('/^(\d{2})(\d{2})-(\d{2})(\d{2})(\d{4})/', $date, $arr)) {
            return $arr[5] . '-' . $arr[4] . '-' . $arr[3] . " {$arr[1]}:{$arr[2]}:00";
        }

        //310313 1620
        if (preg_match('/^(\d{2})(\d{2})(\d{2}) (\d{2})(\d{2})/', $date, $arr)) {
            return $timestamp ? mktime($arr[4], $arr[5], 0, $arr[2], $arr[1], '20' . $arr[3]) :
                '20' . $arr[3] . '-' . $arr[2] . '-' . $arr[1] . " {$arr[4]}:{$arr[5]}:00";
        }

        // 2355 09APR, ...
        if (preg_match('/(\d{2})(\d{2})(.*)(\d{2})(.{3})/', $date, $arr)) {
            $year = date('Y');

            return $timestamp ? mktime($arr[1], $arr[2], 0, $conv[$arr[5]], $arr[4], $year) :
                $year . '-' . $conv[$arr[5]] . '-' . $arr[4] . " {$arr[1]}:{$arr[2]}:00";
        }

        // 01JAN90, 10FEB85, ...
        if (preg_match('/(\d{2})(.{3})(\d{2})/', $date, $arr)) {

            $year = $arr[3] <= date('y') ? $arr[3] + 2000 : $arr[3] + 1900;

            return $timestamp ? mktime(0, 0, 0, $conv[$arr[2]], $arr[1], $year) :
                $year . '-' . $conv[$arr[2]] . '-' . $arr[1];
        }

        // 01/90, 10$85, ... // recode to cp1251 from utf8
        if (preg_match('/(\d{2})(.*)(\d{2})/', $date, $arr)) {

            $arr[2] = iconv('utf8', 'cp1251', $arr[2]);
            $year   = $arr[3] <= date('y') ? $arr[3] + 2000 : $arr[3] + 1900;

            return $timestamp ? mktime(0, 0, 0, $conv[$arr[2]], $arr[1], $year) :
                $year . '-' . $conv[$arr[2]] . '-' . $arr[1];
        }

        // 11FEB ...
        if (preg_match('/(\d{2})(\w{3})/', $date, $arr)) {

            $year = date('Y');
            $res  = mktime(0, 0, 0, $conv[$arr[2]], $arr[1], $year);
            //                if ( $res < time() )
            //                    $res = mktime(0,0,0,$conv[$arr[2]],$arr[1],$year+1);
            return $timestamp ? $res : $year . '-' . $conv[$arr[2]] . '-' . $arr[1];
        }

        return $timestamp ? 0 : '';
    }

    /**
     * @param \DateTimeZone $sourceTimeZone
     * @param \DateTimeZone $needleTimeZone
     * @param               $time
     * @return mixed
     */
    public static function chanageTimeZone(\DateTimeZone $sourceTimeZone, \DateTimeZone $needleTimeZone, $time)
    {

        if (!is_numeric($time)) {
            $time = date("Y-m-d H:i:s", self::dateconvert($time));
        } else {
            $time = date("Y-m-d H:i:s", $time);
        }

        $timeLimitDate = new \DateTime($time, $sourceTimeZone);
        $timeLimitDate->setTimezone($needleTimeZone);

        return $timeLimitDate->format("Y-m-d H:i:s");
    }

}
