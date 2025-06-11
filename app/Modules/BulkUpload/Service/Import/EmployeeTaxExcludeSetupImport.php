<?php

namespace App\Modules\BulkUpload\Service\Import;

use App\Modules\Payroll\Entities\EmployeeTaxExcludeSetup;
use App\Modules\Payroll\Repositories\TaxExcludeSetupRepository;

class EmployeeTaxExcludeSetupImport
{
    public static function import($array)
    {
        $taxExcludeObj = new TaxExcludeSetupRepository();
        // dd($array);
        try {
            foreach ($array as $key => $data) {
                $incNum = 4;
                if ($key != 0) {
                    $taxExcludes = $taxExcludeObj->findAll(null, ['organizationId' => $data[3]]);
                    foreach ($taxExcludes as $taxExclude) {
                        $inputData = [
                            'employee_id' => $data[1] ?? null,
                            'organization_id' => $data[3] ?? null,
                            'tax_exclude_setup_id' => $array[0][$incNum],
                            'amount' => $data[$incNum] ?? 0,
                        ];

                        $previousEmployeeTaxExcludeSetupDetail = EmployeeTaxExcludeSetup::where('employee_id', $inputData['employee_id'])->where('organization_id', $inputData['organization_id'])->where('tax_exclude_setup_id', $inputData['tax_exclude_setup_id'])->first();
                        if ($previousEmployeeTaxExcludeSetupDetail) {
                            $previousEmployeeTaxExcludeSetupDetail->update($inputData);
                        } else {
                            EmployeeTaxExcludeSetup::create($inputData);
                        }

                        $incNum++;
                    }
                }
            }
            toastr()->success('Employee TaxExclude Setup Uploaded succesfully');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            toastr('Data Format For Excel Upload Is Invalid ', 'error');
        }
    }
}
