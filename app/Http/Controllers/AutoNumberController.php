<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\AutoNumberHelper;

class AutoNumberController extends Controller
{
    public function get(Request $request)
    {
        $params = $request->all();

        $res = AutoNumberHelper::initGenerateNumber($params['prefix']);
        
        return response()->json($res);
    }
}
