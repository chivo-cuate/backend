<?php

namespace app\utilities;

class DateHelper {
    
    private static $spanishIntervals = ['años', 'meses', 'días', 'horas', 'minutos', 'segundos'];

    private static function _validateInput(&$time1, &$time2) {
        if (!is_int($time1)) {
            $time1 = strtotime($time1);
        }
        if (!is_int($time2)) {
            $time2 = strtotime($time2);
        }
        if ($time1 > $time2) {
            $ttime = $time1;
            $time1 = $time2;
            $time2 = $ttime;
        }
    }
    
    private static function _extractIntervals(&$time1, &$time2, &$intervals, &$diffs) {
        foreach ($intervals as $interval) {
            $ttime = strtotime('+1 ' . $interval, $time1);
            $add = 1;
            $looped = 0;
            while ($time2 >= $ttime) {
                $add++;
                $ttime = strtotime("+" . $add . " " . $interval, $time1);
                $looped++;
            }
            $time1 = strtotime("+" . $looped . " " . $interval, $time1);
            $diffs[$interval] = $looped;
        }
    }
    
    private static function _generateDiffString($diffs, $precision) {
        $count = 0;
        $times = [];
        $i = 0;
        foreach ($diffs as $value) {
            if ($count >= $precision) {
                break;
            }
            if ($value > 0) {
                $times[] = $value . " " . self::$spanishIntervals[$i];
                $count++;
            }
            $i++;
        }
        array_splice($times, $precision);
        return implode(", ", $times);
    }

    public static function dateDiff($time1, $time2, $precision = 2) {
        self::_validateInput($time1, $time2);
        $intervals = ['year', 'month', 'day', 'hour', 'minute', 'second'];
        $diffs = [];
        self::_extractIntervals($time1, $time2, $intervals, $diffs);
        self::_generateDiffString($diffs, $precision);
    }

}
