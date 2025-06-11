<?php

namespace App\Modules\BulkUpload\Service\Import;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\EmployeeLeave;
use App\Modules\Employee\Entities\LeaveDetail;
use App\Modules\LeaveYearSetup\Entities\LeaveYearSetup;
use App\Modules\LeaveYearSetup\Repositories\LeaveYearSetupRepository;
use App\Modules\Leave\Entities\EmployeeLeaveOpening;
use App\Modules\Leave\Repositories\LeaveTypeRepository;

class LeaveDetailImport
{
    public static function import($array)
    {
        try {
            foreach ($array as $data) {

                if (!is_null($data[1])) {
                    $inputData = [
                        'emp_code' => $data[1] ?? null,
                        'organization_id' => $data[2] ?? null,
                        'leave_code' =>  $data[3] ?? null,
                        'opening_leave' => $data[4] ?? null,
                        // 'leave_remaining' => $data[5] ?? null,
                    ];
                    if (Employee::where('employee_code', $data[1])->exists()) {
                        $employee = Employee::where('employee_code', $data[1])->where('organization_id',$data[2])->first();
                        $inputData['employee_id'] = $employee->id;
                    } else {
                        $inputData['employee_id'] = null;
                    }
                    if(!is_null($data[3])){
                        $leaveTypeObj = new LeaveTypeRepository();
                        $leaveType = $leaveTypeObj->findFromCode($data[3],$data[2]);
                        $inputData['leave_type_id'] = $leaveType->id;
                    }
                    $leaveYear = LeaveYearSetup::currentLeaveYear();
                    $inputData['leave_year_id'] = $leaveYear->id;
                    $previousEmployeeLeave = EmployeeLeave::where('employee_id',$inputData['employee_id'])->where('leave_year_id',$leaveYear->id)->where('leave_type_id',$leaveType->id)->first();
                    $previousEmployeeLeaveOpening = EmployeeLeaveOpening::where('employee_id',$inputData['employee_id'])->where('leave_year_id',$leaveYear->id)->where('organization_id',$data[2])->where('leave_type_id',$leaveType->id)->first();
                    if($previousEmployeeLeave){
                        $previousEmployeeLeave->update($inputData);
                    }
                    else{
                        $employeeLeave = EmployeeLeave::create($inputData);
                    }
                    if($previousEmployeeLeaveOpening){
                        $previousEmployeeLeaveOpening->update($inputData);
                    }
                    else{
                        $employeeLeaveOpening = EmployeeLeaveOpening::create($inputData);
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
