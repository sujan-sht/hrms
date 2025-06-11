<?php

namespace App\Modules\BulkUpload\Service\Import;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\PreviousJobDetail;

class PreviousJobDetailImport
{
    public static function import($array)
    {
        // dd($array);
        try {
            foreach ($array as $data) {

                if (!is_null($data[0])) {
                    $inputData = [
                        'emp_code' => $data[1] ?? null,
                        'company_name' => $data[3] ?? null,
                        'address' =>  $data[4] ?? null,
                        'from_date' => $data[5] ?? null,
                        'to_date' => $data[6] ?? null,
                        'job_title' => $data[7] ?? null,
                        'designation_on_joining' => $data[8] ?? null,
                        'designation_on_leaving' => $data[9] ?? null,
                        'industry_type' => $data[10] ?? null,
                        'break_in_career' => $data[11] ?? null,
                        'reason_for_leaving' => $data[12] ?? null,
                        'role_key' => $data[13] ?? null,
                        
                    ];
                    // dd($inputData);

                    if (Employee::where('employee_code', $data[1])->exists()) {
                        $employee = Employee::where('employee_code', $data[1])->where('organization_id',$data[2])->first();
                        $inputData['employee_id'] = $employee->id;
                    } else {
                        $inputData['employee_id'] = null;
                    }
                    
                    // dd($inputData);
                    $previousPreviousJobDetailDetail = PreviousJobDetail::where('employee_id',$inputData['employee_id'])->first();
                    if($previousPreviousJobDetailDetail){
                        $previousPreviousJobDetailDetail->update($inputData);
                    }
                    else{
                        $PreviousJobDetailDetail = PreviousJobDetail::create($inputData);
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
