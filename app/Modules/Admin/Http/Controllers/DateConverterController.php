<?php

namespace App\Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class DateConverterController extends Controller
{
    public function eng_to_nep(Request $request)
    {
        $date = $request->date;
        $nepaliDate = date_converter()->eng_to_nep_convert($date);
        return response()->json($nepaliDate, 200);
    }
}
