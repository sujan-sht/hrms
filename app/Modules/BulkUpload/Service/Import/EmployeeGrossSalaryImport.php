<?php

namespace App\Modules\BulkUpload\Service\Import;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Payroll\Entities\GrossSalarySetup;

class EmployeeGrossSalaryImport
{
    public static function import($array)
    {
        try {
            // dd($array);
            foreach ($array as $data) {
                if (!is_null($data[0])) {
                    $inputData = [
                        'employee_id' => $data[1] ?? null,
                        'organization_id' => $data[4] ?? null,
                        'gross_salary' => $data[5] ?? null,
                    ];
                    $previousGrossSalaryDetail = GrossSalarySetup::where('employee_id',$inputData['employee_id'])->where('organization_id',$inputData['organization_id'])->first();
                    if($previousGrossSalaryDetail){
                        $previousGrossSalaryDetail->update($inputData);
                    }
                    else{
                        $grossSalary = GrossSalarySetup::create($inputData);
                    }
                }
                
            }
            toastr()->success('Gross Salary Uploaded succesfully');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            toastr('Data Format For Excel Upload Is Invalid ', 'error');
        }
    }
}
