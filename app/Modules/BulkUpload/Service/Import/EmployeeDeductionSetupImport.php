<?php

namespace App\Modules\BulkUpload\Service\Import;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Payroll\Entities\DeductionSetup;
use App\Modules\Payroll\Entities\EmployeeSetup;
use App\Modules\Payroll\Entities\GrossSalarySetup;
use App\Modules\Payroll\Repositories\DeductionSetupRepository;
use App\Modules\Payroll\Repositories\IncomeSetupRepository;

class EmployeeDeductionSetupImport
{
    public static function import($array)
    {
        $deductionObj = new DeductionSetupRepository();
        try {
            foreach ($array as $key => $data) {
                $incNum = 5;
                if ($key != 0) {
                    $deductions = $deductionObj->findAll(null, ['organizationId' => $data[4]]);
                    foreach ($deductions as $deduction) {
                        $inputData = [
                            'employee_id' => $data[1] ?? null,
                            'organization_id' => $data[4] ?? null,
                            'reference' => 'deduction',
                            'reference_id' => $array[0][$incNum],
                            'amount' => $data[$incNum] ?? 0,
                            'status' =>$data[$incNum] > 0 ? 11 : 10,
                        ];
                        $deductionModel = DeductionSetup::where('id',$array[0][$incNum])->where('organization_id',$data[4])->first();
                        // dd($deductionModel);
                        if($deductionModel){
                            $previousGrossSalaryDetail = EmployeeSetup::where('employee_id', $inputData['employee_id'])->where('organization_id', $inputData['organization_id'])->where('reference', 'deduction')->where('reference_id', $inputData['reference_id'])->first();
                            if ($previousGrossSalaryDetail) {
                                $previousGrossSalaryDetail->update($inputData);
                            } else {
                                $grossSalary = EmployeeSetup::create($inputData);
                            }
                        }

                        $incNum++;
                    }
                }
            }
            toastr()->success('Employee Deduction Setup Uploaded succesfully');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            toastr('Data Format For Excel Upload Is Invalid ', 'error');
        }
    }
}
