<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Lahan;
use App\Helpers\GlobalHelper;
use App\Models\User;

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
                        "class" => User::class,
                        "field" => "reseller_id",
                        "prefix" => $prefix,
                        "initial" => 'A',
                        "start_from" => User::first()->reseller_id,
                    ];
                    break;
                default:
                    echo "Perlu jasa pembuatan aplikasi web/android ? call me 085290724894";
            }
        }

        return self::generateNumber($data, $date);
    }

    private static function generateNumber($params, $date)
    {
        $prefix = $params['prefix'];
        $initial = $params['initial'];
        $start_from = '01'; //$params['start_from'];

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
        $number = $initial;

        $data = $params['class']::where($params['field'], 'like', '%' . $initial . '%')->orderBy('id', 'DESC')->first();
        if ($data == null) {
            $number .= sprintf('%02d', $start_from);
        } else {
            $repeat = true;
            $last = substr($data[$params['field']],1);
            // $last = explode('/', $data[$params['field']])[1];

            $new = sprintf('%02d', ++$last);
            while ($repeat) {
                $data = $params['class']::where($params['field'], $number . $new)->first();
                if ($data == null) {
                    $repeat = false;
                    $number .= sprintf('%02d', $new);
                } else {
                    $new++;
                }
            }
        }
        return $number;
    }
}
