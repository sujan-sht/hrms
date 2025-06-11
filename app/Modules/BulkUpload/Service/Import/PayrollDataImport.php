<?php

namespace App\Modules\BulkUpload\Service\Import;

use App\Modules\Payroll\Entities\PayrollEmployee;

class PayrollDataImport
{
    public static function import($array)
    {
        try {
            foreach ($array as $key => $data) {
                $year = $data['1'];
                $month = $data['2'];
                $inputData = [
                    'overtime_pay' => $data[5] ?? 0,
                    'fine_penalty' => $data[6] ?? 0,
                    'festival_bonus' => $data[7] ?? 0,
                ];

                $payrollEmployee = PayrollEmployee::whereHas('payroll', function ($query) use ($year,$month) {
                    $query->where('year', $year)->where('month', $month);
                })->where('employee_id', $data[3])->first();
               
                if ($payrollEmployee) {
                    $payrollEmployee->update($inputData);
                }
            }
            toastr()->success('Employee Payroll Data Uploaded succesfully');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            toastr('Data Format For Excel Upload Is Invalid ', 'error');
        }
    }
}
