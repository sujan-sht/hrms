<?php

namespace App\Modules\BulkUpload\Service\Import;

use App\Modules\Employee\Entities\EmergencyDetail;
use App\Modules\Employee\Entities\Employee;

class EmergencyDetailImport
{
    public static function import($array)
    {
        try {
            foreach ($array as $data) {
                // dd($data);
                if (!is_null($data[0])) {
                    $inputData = [
                        'emp_code' => $data[1] ?? null,
                        'org_id' => $data[2] ?? null,
                        'name' => $data[3] ?? null,
                        'phone1' => $data[4] ?? null,
                        'phone2' =>  $data[5] ?? null,
                        'address' => $data[6] ?? null,
                        'relation' => $data[7] ?? null,
                        'note' => $data[8] ?? null,
                    ];
                    // dd($inputData);

                    if (Employee::where('employee_code', $data[1])->exists()) {
                        $employee = Employee::where('organization_id',$data[2])->where('employee_code', $data[1])->where('organization_id',$data[2])->first();
                        $inputData['employee_id'] = $employee->id;
                    } else {
                        $inputData['employee_id'] = null;
                    }
                    if(!is_null($data[7])){
                        $inputData['relation'] =EmergencyDetail::getRelationTypeId($data[7]);
                    }
                    // dd( $inputData['relation']);
                    $previousEmergencyDetail = EmergencyDetail::where('employee_id',$inputData['employee_id'])->where('relation',$inputData['relation'])->first();
                    if($previousEmergencyDetail){
                        $previousEmergencyDetail->update($inputData);
                    }
                    else{
                        $EmegencyDetail = EmergencyDetail::create($inputData);
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
