<?php

namespace App\Service\Import;

use App\Modules\Client\Entities\Client;
use App\Modules\Dropdown\Entities\Dropdown;
use App\Modules\Employee\Entities\Employee;
use Illuminate\Support\Facades\DB;
use Yoeunes\Toastr\Facades\Toastr;

class ClientImport implements ImportInterface{

    public function import($array)
    {


        DB::beginTransaction();
        try{
            foreach($array as $data){

                if(!is_null($data[0])){
                    Client::create([

                        'name'=>$data[0] ?? null,
                        'contact_person'=>$data[1] ?? null,
                        'contact_no'=>$data[2] ?? null,
                        'contact_designation'=>$data[3] ?? null,
                        'pan_no'=>$data[4] ?? null,
                        'vat_no'=>$data[5] ?? null,
                        'contact_mail'=>$data[6] ?? null,
                        'company_address'=>$data[7] ?? null,
                        'city'=>$data[8] ?? null,
                        'company_phone_no'=> $data[9] ?? null,
                        'location'=> $data[10] ?? null,
                    ]);
                }


            }
        }catch(\Exception $e){

            toastr()->error('Something went wrong!!');

        }

        DB::commit();

    }
}
