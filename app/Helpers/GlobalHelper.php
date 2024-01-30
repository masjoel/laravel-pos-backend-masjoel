<?php

namespace App\Helpers;

class GlobalHelper
{
    public static function findString($needle,$haystack,$i,$word)
    {   // $i should be "" or "i" for case insensitive
        if (strtoupper($word)=="W") {   // if $word is "W" then word search instead of string in string search.
            if (preg_match("/\b{$needle}\b/{$i}", $haystack)) {
                return true;
            }
        } else {
            if(preg_match("/{$needle}/{$i}", $haystack)) {
                return true;
            }
        }
        return false;
        // Put quotes around true and false above to return them as strings instead of as bools/ints.
    }

    public static function dayEngToInd($english) {
        if ($english == 'Monday') {
            $day = 'Senin';
        } else if ($english == 'Tuesday') {
            $day = 'Selasa';
        } else if ($english == 'Wednesday') {
            $day = 'Rabu';
        } else if ($english == 'Thursday') {
            $day = 'Kamis';
        } else if ($english == 'Friday') {
            $day = 'Jum\'at';
        } else if ($english == 'Saturday') {
            $day = 'Sabtu';
        } else if ($english == 'Sunday') {
            $day = 'Minggu';
        } else {
            $day = 'Unknown';
        }

        return $day;
    }

    public static function ToMonthIndo($number) {
        if ($number == '01') {
            $month = 'Jan';
        } else if ($number == '02') {
            $month = 'Feb';
        } else if ($number == '03') {
            $month = 'Mar';
        } else if ($number == '04') {
            $month = 'Apr';
        } else if ($number == '05') {
            $month = 'Mei';
        } else if ($number == '06') {
            $month = 'Jun';
        } else if ($number == '07') {
            $month = 'Jul';
        } else if ($number == '08') {
            $month = 'Agt';
        } else if ($number == '09') {
            $month = 'Sep';
        } else if ($number == '10') {
            $month = 'Okt';
        } else if ($number == '11') {
            $month = 'Nov';
        } else if ($number == '12') {
            $month = 'Des';
        }

        return $month;
    }
    public static function numberToMonthIndo($number) {
        if ($number == '01') {
            $month = 'Januari';
        } else if ($number == '02') {
            $month = 'Februari';
        } else if ($number == '03') {
            $month = 'Maret';
        } else if ($number == '04') {
            $month = 'April';
        } else if ($number == '05') {
            $month = 'Mei';
        } else if ($number == '06') {
            $month = 'Juni';
        } else if ($number == '07') {
            $month = 'Juli';
        } else if ($number == '08') {
            $month = 'Agustus';
        } else if ($number == '09') {
            $month = 'September';
        } else if ($number == '10') {
            $month = 'Oktober';
        } else if ($number == '11') {
            $month = 'November';
        } else if ($number == '12') {
            $month = 'Desember';
        }

        return $month;
    }

    public static function minutes($time)
    {
        $time = explode(':', $time);
        return ($time[0]*60) + ($time[1]) + ($time[2]/60);
    }

    public static function minuteToHourMinute($minutes) 
    {
        $hours = floor($minutes / 60);
        $min = $minutes - ($hours * 60);

        return $hours." jam, ".$min." menit";
    }

    public static function convertSeparator($number)
    {
        $number = str_replace('.', '', $number);
        $number = str_replace(',', '', $number);

        if ($number > 0) {
            return $number;
        }

        return 0;
    }

    public static function periodDateTime($date, $dateTo = null)
    {
        if ($dateTo) {
            $ages_interval = date_diff(date_create($dateTo), date_create($date));
        } else {
            $ages_interval = date_diff(date_create(), date_create($date));
        }
        $age = $ages_interval->format("%Y thn, %M bln, %d hr");

        return $age;
    }

    public static function dateIndo($date) 
    {
        if ($date) {
            $expl_time = explode(' ', $date);

            $fullDate = explode('-', $expl_time[0]);

            $date = $fullDate[2];
            $month = $fullDate[1];
            $year = $fullDate[0];

            return $date.' '.self::numberToMonthIndo($month).' '.$year.' '.(isset($expl_time[1]) ? $expl_time[1] : '');
        }

        return '-';
    }

    public static function findArrayByValue($params, $key, $value)
    {
        $res = false;

        if ($params) {
            foreach ($params as $param) {
                if ($param[$key] == $value) {
                    $res = true;
                    continue;
                }
            }
        }

        return $res;
    }

    public static function camelToSnake($camel)
    {
        $snake = preg_replace('/[A-Z]/', '_$0', $camel);
        $snake = strtolower($snake);
        $snake = ltrim($snake, '_');
        return $snake;
    }

    public static function numberToRomanRepresentation($number) 
    {
        $map = ['M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1];
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }

    public static function spellNumberInIndonesian($number)
    {
        $result = "";
        $number = strval($number);
        if (!preg_match("/^[0-9]{1,15}$/", $number)) return false;

        $ones           = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan"];
        $majorUnits     = ["", "ribu", "juta", "milyar", "trilyun"];
        $minorUnits     = ["", "puluh", "ratus"];
        $length         = strlen($number);
        $isAnyMajorUnit = false;
        
        for ($i = 0, $pos = $length - 1; $i < $length; $i++, $pos--) {
            if ($number[$i] != '0') {
                if ($number[$i] != '1') {
                    $result .= $ones[$number[$i]].' '.$minorUnits[$pos % 3].' ';
                } else if ($pos % 3 == 1 && $number[$i + 1] != '0') {
                    if ($number[$i + 1] == '1')
                        $result .= "sebelas ";
                    else
                        $result .= $ones[$number[$i + 1]]." belas ";
                    $i++; $pos--;
                } else if ($pos % 3 != 0) {
                    $result .= "se".$minorUnits[$pos % 3].' ';
                } else if ($pos == 3 && !$isAnyMajorUnit) {
                    $result .= "se";
                } else {
                    $result .= "satu ";
                }
                $isAnyMajorUnit = true;
            }

            if ($pos % 3 == 0 && $isAnyMajorUnit) {
                $result         .= $majorUnits[$pos / 3].' ';
                $isAnyMajorUnit = false;
            }
        }
        $result = trim($result);
        if ($result == "") $result = "nol";

        return ucwords($result);
    }
}