<?php

namespace App\Modules\BulkUpload\Service\Import;

use App\Modules\Labour\Entities\Labour;
use App\Modules\Labour\Entities\SkillSetup;

class LabourImport
{
    public static function import($array)
    {

        $filteredArray = array_filter($array, function($subArray) {
            return !empty(array_filter($subArray, function($value) {
                return !is_null($value);
            }));
        });
        try {
            foreach ($filteredArray as $data) {

                $inputData = [
                    'first_name' => $data[1] ?? null,
                    'middle_name' => $data[2] ?? null,
                    'last_name' =>  $data[3] ?? null,
                    'organization' =>  $data[5] ?? null,
                    'pan_no' => $data[7] ?? null,
                    'description' => $data[8] ?? null,

                ];

                if(!is_null($data[4])){
                    $inputData['join_date'] = date_converter()->nep_to_eng_convert($data[4]);
                }
               
                
                if(!is_null($data[6])){
                    $skillType=SkillSetup::where('category',$data[6])->first();
                    if(!is_null($skillType)){
                        $inputData['skill_type'] = $skillType->id;
                    }
                }

                Labour::create($inputData);

            }
            toastr()->success('Labour Upload succesfully');
        } catch (\Exception $e) {
            toastr('Data Format For Excel Upload Is Invalid ', 'error');
        }

        
    }
    
}
