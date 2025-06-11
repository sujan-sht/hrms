<?php

namespace App\Helpers;

use App\Modules\Employee\Entities\Employee;
use App\Modules\User\Entities\User;

class HREmployeeHelper
{

    public static function getEmployeeIds()
    {
        if (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'super_admin' || auth()->user()->user_type == 'hr') {
            return Employee::all()->pluck('id')->toArray();
        } else {
            $user = User::where('id', auth()->user()->id)->first();
            return Employee::where('id', $user->emp_id)->pluck('id')->toArray();
        }
    }


    // For user IDs
    public static function getParentUserList($type, $orgFlag = true)
    {
        $empModel = optional(auth()->user())->userEmployer;

        $divisionHR = User::whereIn('user_type', $type)->whereHas('userEmployer', function ($query) use ($empModel, $orgFlag) {
            if ($orgFlag) {
                $query->where('organization_id', $empModel->organization_id);
            }
        })->pluck('username', 'id')->toArray();
        return $divisionHR;
    }


    // For employee IDs (REquired Params)
    // [
    //     'model'=>user,
    //     'user_type'=>['supervisor','hr],
    //     'organization_id'=>1
    // ]
    public static function getUserListsByType($filter = ['model' => 'user'])
    {
        if ($filter['model'] == 'user') {
            $modelArray = User::where('active', 1)->when(true, function ($query) use ($filter) {
                $query->whereIn('user_type', $filter['user_type']);
            })
                ->whereHas('userEmployer', function ($query) use ($filter) {
                    if (isset($filter['organization_id']) && !empty($filter['organization_id'])) {
                        $query->where('organization_id', $filter['organization_id']);
                    }

                    if (isset($filter['department_id']) && !empty($filter['department_id'])) {
                        $query->where('department_id', $filter['department_id']);
                    }
                })->get();
        } elseif ($filter['model'] == 'employee') {
            $modelArray = Employee::where('status', 1)->when(true, function ($query) use ($filter) {
                if (isset($filter['organization_id']) && !empty($filter['organization_id'])) {
                    $query->where('organization_id', $filter['organization_id']);
                }

                if (isset($filter['department_id']) && !empty($filter['department_id'])) {
                    $query->where('department_id', $filter['department_id']);
                }
            })->whereHas('getUser', function ($query) use ($filter) {
                $query->whereIn('user_type', $filter['user_type']);
            })->get();
        }


        $employee_data = array();
        foreach ($modelArray as $employee) {
            //user list
            if($filter['model'] == 'user'){
                if (!empty(optional($employee->userEmployer)->middle_name)) {
                    $full_name = optional($employee->userEmployer)->first_name . ' ' . optional($employee->userEmployer)->middle_name . ' ' . optional($employee->userEmployer)->last_name;
                } else {
                    $full_name = optional($employee->userEmployer)->first_name . ' ' . optional($employee->userEmployer)->last_name;
                }

            //employee list
            }else{
                if (!empty($employee->middle_name)) {
                    $full_name = $employee->first_name . ' ' . $employee->middle_name . ' ' . $employee->last_name;
                } else {
                    $full_name = $employee->first_name . ' ' . $employee->last_name;
                }
            }
            $employee_data += array(
                $employee->id => $full_name
            );
        }
        return $employee_data;
    }
}
