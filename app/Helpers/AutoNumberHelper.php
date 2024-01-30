<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Lahan;
use App\Helpers\GlobalHelper;

class AutoNumberHelper
{
    public static function initGenerateNumber($prefix, $date = '')
    {
        $data = [];

        if ($prefix == null || $prefix == "") {
            return response()->json([
                "status" => "error",
                "data" => "",
                "message" => "Prefix should exist!",
            ]);
        } else {
            switch ($prefix) {
                case "NOMOR":
                    $data = [
                        "class" => Lahan::class,
                        "field" => "nomor",
                        "prefix" => $prefix,
                    ];
                    break;
                default:
                    echo "Your favorite color is neither red, blue, nor green!";
            }
        }

        return self::generateNumber($data, $date);
    }

    private static function generateNumber($params, $date)
    {
        $prefix = $params['prefix'];
        $initial = 'DMD';

        $now = Carbon::now();

        $month_param = $now->month;
        $year_param = $now->year;

        if ($date != '') {
            $expl_date = explode('-', $date);
            if (count($expl_date) > 1) {
                $month_param = $expl_date[1];
                $year_param = $expl_date[0];
            }
        }

        $year = $year_param;
        $month = GlobalHelper::numberToRomanRepresentation($month_param);

        $number = '';
        // $number = '#' . date('ymd');
        // $number = $prefix . '/' . $initial . '/' . $year . '/' . $month . '/';

        $data = $params['class']::where($params['field'], 'like', $number . '%')->orderBy('id', 'DESC')->first();

        if ($data == null) {
            $number .= sprintf('%03d', 1);
            // $number .= sprintf('%04d', 1);
        } else {
            $repeat = true;
            $last = substr($data[$params['field']], -3);
            $new = sprintf('%03d', ++$last);
            while ($repeat) {
                $data = $params['class']::where($params['field'], $number . $new)->first();
                if ($data == null) {
                    $repeat = false;
                    $number .= sprintf('%03d', $new);
                } else {
                    $new++;
                }
            }
        }
        return $number;
    }

}
