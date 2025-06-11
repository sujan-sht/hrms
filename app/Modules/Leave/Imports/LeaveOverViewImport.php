<?php

namespace App\Modules\Leave\Imports;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\EmployeeLeave;
use App\Modules\Leave\Entities\LeaveOverview;
use App\Modules\Leave\Entities\LeaveType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LeaveOverViewImport implements ToModel, WithHeadingRow
{

    // public function collection(Collection $rows)
    // {
    //     // dd($rows);
    //     foreach ($rows as $row)
    //     {
    //         dd($row['code']);
    //     }
    // }

    public function model(array $row)
    {
            if (Employee::where('employee_code', $row['code'])->exists()) {
                $employee = Employee::where('employee_code', $row['code'])->first();
                $inputData['employee_id'] = $employee->id;
            }
            $LeaveArraySlices = (array_slice($row, 3));
            foreach ($LeaveArraySlices as $LeaveArrayKey => $leaveArrayValue) {
                $leaveType = LeaveType::where([
                    'code' => $LeaveArrayKey,
                    'organization_id' => $employee->organization_id,
                    'leave_year_id' => getCurrentLeaveYearId(),
                ])->first();
                if ($leaveType) {

                    $inputData['leave_type_id'] = $leaveType->id;
                    $inputData['previous_remaining_leave'] = $leaveArrayValue;

                    LeaveOverview::updateOrCreate([
                        'employee_id' => $inputData['employee_id'],
                        'leave_type_id' => $inputData['leave_type_id'],
                    ], $inputData);

                    $empLeave = EmployeeLeave::where([
                        'employee_id' => $inputData['employee_id'],
                        'leave_type_id' => $inputData['leave_type_id'],
                        'leave_year_id' => getCurrentLeaveYearId(),
                        'is_valid' => 11
                    ])->first();

                    if ($empLeave) {
                        $empLeave->leave_remaining += $inputData['previous_remaining_leave'];
                        $empLeave->save();
                    }
                }
            }
    }
}
