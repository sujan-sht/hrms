<?php

namespace App\Modules\BulkUpload\Service\Import;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Payroll\Entities\EmployeeBonusSetup;
use App\Modules\Payroll\Entities\EmployeeSetup;
use App\Modules\Payroll\Entities\GrossSalarySetup;
use App\Modules\Payroll\Repositories\BonusSetupRepository;

class EmployeeBonusSetupImport
{
    public static function import($array)
    {
        $bonusObj = new BonusSetupRepository();
        try {
            foreach ($array as $key => $data) {
                $incNum = 4;
                if ($key != 0) {
                    $bonuses = $bonusObj->findAll(null, ['organizationId' => $data[3]]);
                    foreach ($bonuses as $bonus) {
                        $inputData = [
                            'employee_id' => $data[1] ?? null,
                            'organization_id' => $data[3] ?? null,
                            'bonus_setup_id' => $array[0][$incNum],
                            'amount' => $data[$incNum] ?? 0,
                            'status' => 11,
                        ];

                        $previousBonusDetail = EmployeeBonusSetup::where('employee_id', $inputData['employee_id'])->where('organization_id', $inputData['organization_id'])->where('bonus_setup_id', $inputData['bonus_setup_id'])->first();
                        if ($previousBonusDetail) {
                            $previousBonusDetail->update($inputData);
                        } else {
                            $employeeBonusSetup = EmployeeBonusSetup::create($inputData);
                        }

                        $incNum++;
                    }
                }
            }
            toastr()->success('Employee Bonus Setup Uploaded succesfully');
        } catch (\Exception $e) {
            toastr('Data Format For Excel Upload Is Invalid ', 'error');
        }
    }
}
