<?php

namespace App\Modules\Api\Http\Controllers;


use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class ModuleController extends ApiController
{
    public function index()
    {
        try {
            $data['modules']=DB::table('modules')->select('id','name','app_status')->get();

            return  $this->respond([
                'status' => true,
                'data' => $data
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    
}
