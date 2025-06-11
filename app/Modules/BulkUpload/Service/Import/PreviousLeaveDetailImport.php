<?php

namespace App\Modules\BulkUpload\Service\Import;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\EmployeeLeave;
use App\Modules\Leave\Entities\LeaveOverview;
use App\Modules\Leave\Entities\LeaveType;
use App\Modules\Leave\Repositories\LeaveTypeRepository;

class PreviousLeaveDetailImport
{
    public static function import($array)
    {
        try {
            foreach ($array as $data) {
                $inputData = [
                    'previous_remaining_leave' => $data[3] ?? null,
                ];

                if (Employee::where('employee_code', $data[0])->exists()) {
                    $employee = Employee::where('employee_code', $data[0])->where('organization_id', $data[1])->first();
                    $inputData['employee_id'] = $employee->id;
                } else {
                    $inputData['employee_id'] = null;
                }
                if (!is_null($data[2])) {
                    // $leaveTypeObj = new LeaveTypeRepository();
                    // $leaveType = $leaveTypeObj->findFromCode($data[2], $data[1]);
                    $leaveType = LeaveType::where([
                        'code' => $data[2],
                        'organization_id'=>$data[1],
                        'leave_year_id'=>getCurrentLeaveYearId(),

                    ])->first();
                    $inputData['leave_type_id'] = $leaveType->id;
                }

                LeaveOverview::updateOrCreate([
                    'employee_id' => $inputData['employee_id'],
                    'leave_type_id' => $inputData['leave_type_id'],
                ], $inputData);

                $empLeave = EmployeeLeave::where([
                    'employee_id' => $inputData['employee_id'],
                    'leave_type_id' => $inputData['leave_type_id'],
                    'leave_year_id' => getCurrentLeaveYearId(),

                ])->first();

                if ($empLeave) {
                    $empLeave->leave_remaining += $inputData['previous_remaining_leave'];
                    $empLeave->save();
                }
            }
            toastr()->success('Leave Data Uploaded succesfully');
        } catch (\Exception $e) {
            toastr('Data Format For Excel Upload Is Invalid ', 'error');
        }
    }
}
