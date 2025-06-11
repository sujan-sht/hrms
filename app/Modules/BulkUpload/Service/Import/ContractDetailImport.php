<?php

namespace App\Modules\BulkUpload\Service\Import;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\ContractDetail;

class ContractDetailImport
{
    public static function import($array)
    {
        try {
            foreach ($array as $data) {

                if (!is_null($data[0])) {
                    $inputData = [
                        'emp_code' => $data[1] ?? null,
                        'org_id' => $data[2] ?? null,
                        'title' =>  $data[3] ?? null,
                        'start_from' => $data[4] ?? null,
                        'end_to' => $data[5] ?? null,
                    ];

                    if (Employee::where('employee_code', $data[1])->exists()) {
                        $employee = Employee::where('employee_code', $data[1])->where('organization_id',$data[2])->first();
                        $inputData['employee_id'] = $employee->id;
                    } else {
                        $inputData['employee_id'] = null;
                    }
                    
                    $previousContractDetail = ContractDetail::where('employee_id',$inputData['employee_id'])->first();
                    if($previousContractDetail){
                        $previousContractDetail->update($inputData);
                    }
                    else{
                        $ContractDetail = ContractDetail::create($inputData);
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
