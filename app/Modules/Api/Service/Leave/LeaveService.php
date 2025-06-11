<?php

namespace App\Modules\Api\Service\Leave;

use App\Modules\Employee\Entities\EmployeeLeave;
use App\Modules\LeaveYearSetup\Entities\LeaveYearSetup;
use App\Modules\Leave\Entities\EmployeeLeaveOpening;
use App\Modules\Leave\Entities\Leave;
use App\Modules\Leave\Entities\LeaveType;

class LeaveService
{

    public function getRemainingLeave()
    {
        $leaveType = LeaveType::get();
        $current_leave_year_data = LeaveYearSetup::currentLeaveYear();
        $leave_year_id = $current_leave_year_data['id'];

        $emp = auth()->user()->userEmployer;
        $data = [];
        foreach ($leaveType as $key => $lType) {
            $data[$key]['id'] = $lType->id;
            $data[$key]['name'] = $lType->name;
            $data[$key]['remain'] = optional(EmployeeLeave::getLeaveRemaining($leave_year_id, $emp->id, $lType->id))->leave_remaining ?? 0;
            $data[$key]['open'] = EmployeeLeaveOpening::getLeaveOpening($leave_year_id, $emp->organization_id, $emp->id, $lType->id) ?? 0;
        }
        return $data;
    }

    public function statusList()
    {
        return collect(Leave::statusList())->map(function ($value, $key) {
            return [
                'id' => $key,
                'name' => $value
            ];
        });
    }

    public function leaveTypeList()
    {
        return LeaveType::select('id', 'name')->get();
    }

    public function leaveTypeListWithFilter($organization_id,$leave_year_id){
        return LeaveType::where('status', 11)->where('organization_id', $organization_id)->where('leave_year_id', $leave_year_id)->select('id','name')->get();
    }

    public function leaveCategories()
    {
        $categories = Leave::leaveKindList();
        foreach ($categories as $key => $value) {
            $data[] = [
                'id' => $key,
                'name' =>  $value,
            ];
        }

        return $data;
    }

    public function halfLeaveTypes()
    {
        $halfLeaveTypes = Leave::halfTypeList();

        foreach ($halfLeaveTypes as $key => $value) {
            $data[] = [
                'id' => $key,
                'name' => $value
            ];
        }
        return $data;
    }
}
