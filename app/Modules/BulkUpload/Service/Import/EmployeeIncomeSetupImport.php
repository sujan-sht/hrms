<?php

namespace App\Modules\BulkUpload\Service\Import;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Payroll\Entities\EmployeeSetup;
use App\Modules\Payroll\Entities\GrossSalarySetup;
use App\Modules\Payroll\Entities\IncomeSetup;
use App\Modules\Payroll\Repositories\IncomeSetupRepository;

class EmployeeIncomeSetupImport
{
    public static function import($array)
    {
        $incomeObj = new IncomeSetupRepository();
        try {
            foreach ($array as $key => $data) {
                $incNum = 5;
                if ($key != 0) {
                    $incomes = $incomeObj->findAll(null, ['organizationId' => $data[4]]);
                    foreach ($incomes as $income) {
                        $inputData = [
                            'employee_id' => $data[1] ?? null,
                            'organization_id' => $data[4] ?? null,
                            'reference' => 'income',
                            'reference_id' => $array[0][$incNum],
                            'amount' => $data[$incNum] ?? 0,
                            'status' => 11,
                        ];
                        $incomeModel = IncomeSetup::where('id',$array[0][$incNum])->where('organization_id',$data[4])->first();
                        if($incomeModel){
                            $previousGrossSalaryDetail = EmployeeSetup::where('employee_id', $inputData['employee_id'])->where('organization_id', $inputData['organization_id'])->where('reference', 'income')->where('reference_id', $inputData['reference_id'])->first();
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
            toastr()->success('Employee Income Setup Uploaded succesfully');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            toastr('Data Format For Excel Upload Is Invalid ', 'error');
        }
    }
}
