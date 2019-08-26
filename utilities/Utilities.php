<?php

namespace app\utilities;

use app\models\Menu;

class Utilities {

    public static function getModelErrorsString($model) {
        $errors = [];
        foreach ($model->getErrors() as $error) {
            if (!self::arrayContains($errors, $error[0])) {
                $errors[] = $error[0];
            }
        }
        return implode('. ', $errors);
    }

    public static function arrayContains($array, $string) {
        foreach ($array as $value) {
            if ($value === $string) {
                return true;
            }
        }
        return false;
    }

    public static function getArrayIndexByAttrForInsertion($array, $attrib, $value) {
        $i = 0;
        foreach ($array as $value) {
            if ($array[$attrib] === $value) {
                return $i;
            }
            $i++;
        }
        return count($array);
    }

    public static function getCurrentMenu($branchId) {
        return Menu::find()->where(['branch_id' => $branchId])->orderBy(['date' => SORT_DESC])->one();
    }

    public static function dateDiff($time1, $time2, $precision = 6) {
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

        // Set up intervals and diffs arrays
        $intervals = ['year', 'month', 'day', 'hour', 'minute', 'second'];
        $diffs = [];

        // Loop thru all intervals
        foreach ($intervals as $interval) {
            // Create temp time from time1 and interval
            $ttime = strtotime('+1 ' . $interval, $time1);
            // Set initial values
            $add = 1;
            $looped = 0;
            // Loop until temp time is smaller than time2
            while ($time2 >= $ttime) {
                // Create new temp time from time1 and interval
                $add++;
                $ttime = strtotime("+" . $add . " " . $interval, $time1);
                $looped++;
            }

            $time1 = strtotime("+" . $looped . " " . $interval, $time1);
            $diffs[$interval] = $looped;
        }

        $count = 0;
        $times = [];
        $spanishIntervals = ['años', 'meses', 'días', 'horas', 'minutos', 'segundos'];
        // Loop thru all diffs
        $i = 0;
        foreach ($diffs as $interval => $value) {
            // Break if we have needed precission
            if ($count >= $precision) {
                break;
            }
            // Add value and interval 
            // if value is bigger than 0
            if ($value > 0) {
                
                // Add value and interval to times array
                $times[] = $value . " " . $spanishIntervals[$i];
                $count++;
            }
            $i++;
        }
        array_splice($times, 1);
        // Return string with times
        return implode(", ", $times);
    }

}
