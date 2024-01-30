<?php

namespace App\Helpers;

use Validator;

class ExceptionHelper
{
    private static $messages = [
        'unique' => ':attribute :input telah digunakan!',
        'required' => ':attribute wajib diisi!'
    ];

    public static function validate($params, $validate)
    {
        $responseArr['status'] = 'success';
        $responseArr['message'] = '';

        $validator = Validator::make($params, $validate, self::$messages);

        if ($validator->fails()) {
            $responseArr['status'] = 'error';
            $responseArr['message'] = $validator->errors();
            return $responseArr;
        }

        return $responseArr;
    }
}