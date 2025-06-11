<?php

namespace App\Modules\BulkUpload\Service\Import;

use App\Modules\Attendance\Entities\AttendanceLog;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Repositories\EmployeeRepository;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\ProductVin;
use Illuminate\Support\Collection;
// use Illuminate\Support\Facades\Date;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class EmpBiometricImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as  $row) {
            $employee = Employee::where('employee_code', $row['emp_code'])->first();
            if ($employee) {
                $checkEmpModel = Employee::where('biometric_id', '=', $row['biometric_id'])->first();
                if (!$checkEmpModel) {
                    $employee->biometric_id = $row['biometric_id'];
                    $employee->save();
                }
            }
        }
    }
}
