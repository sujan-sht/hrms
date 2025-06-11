<?php

namespace App\Modules\BulkUpload\Service\Import;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\MedicalDetail;

class MedicalDetailImport
{
    public static function import($array)
    {
        // dd($array);
        try {
            foreach ($array as $data) {

                if (!is_null($data[0])) {
                    $inputData = [
                        'emp_code' => $data[1] ?? null,
                        'org_id' => $data[2] ?? null,
                        'medical_problem' =>  $data[3] ?? null,
                        'details' => $data[4] ?? null,
                        'insurance_company_name' => $data[5] ?? null,
                        'medical_insurance_details' => $data[6] ?? null,
                    ];

                    if (Employee::where('employee_code', $data[1])->exists()) {
                        $employee = Employee::where('employee_code', $data[1])->where('organization_id',$data[2])->first();
                        $inputData['employee_id'] = $employee->id;
                    } else {
                        $inputData['employee_id'] = null;
                    }
                    
                    $previousMedicalDetail = MedicalDetail::where('employee_id',$inputData['employee_id'])->first();
                    if($previousMedicalDetail){
                        $previousMedicalDetail->update($inputData);
                    }
                    else{
                        $medicalDetail = MedicalDetail::create($inputData);
                    }
                
                }
                
            }
            toastr()->success('Bulk Upload succesfully');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            toastr('Data Format For Excel Upload Is Invalid ', 'error');
        }
    }
}
