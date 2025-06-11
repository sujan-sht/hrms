<?php

namespace App\Modules\Api\Service\Employee;

use App\Modules\Employee\Entities\Employee;
use Illuminate\Support\Facades\Auth;

class EmployeeService
{
    public function getList()
    {
        $data = [];
        $filter = [];

        if (auth()->user()->user_type == 'supervisor') {
            $filter['ids'] = Employee::getSubordinates(auth()->user()->id);
        }

        $employeeModels = Employee::where('status', 1)->when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['ids'])) {
                $query->whereIn('id', $filter['ids']);
            }
        })->get();

        if ($employeeModels->count() > 0) {
            foreach ($employeeModels as $employeeModel) {
                $data[] = [
                    'id' => $employeeModel->id,
                    'name' => $employeeModel->full_name
                ];
                // $data[$employeeModel->id] = $employeeModel->full_name;
            }
        }
        return $data;
    }

    public function getOtherEmployeeList()
    {
        $emp_id = Auth::user()->userEmployer;
        $emp_id = optional(Auth::user()->userEmployer)->id;

        $employeeModels = Employee::select('id', 'first_name', 'middle_name', 'last_name')->where('status', 1)->where('id', '!=', $emp_id)->get();

        $data = [];
        if ($employeeModels->count() > 0) {
            foreach ($employeeModels as $employeeModel) {
                $data[] = [
                    'id' => $employeeModel->id,
                    'name' => $employeeModel->full_name
                ];
            }
        }
        return $data;

    }

    public static function findAlternativeEmployees($params)
    {
        $data = [];
        if (isset($params['employee_id'])) {
            $employeeId = $params['employee_id'];
            $mainEmployeeModel = Employee::find($employeeId);
            if ($mainEmployeeModel) {
                
                $models = Employee::where([
                    'department_id' => $mainEmployeeModel->department_id,
                    'organization_id' => $mainEmployeeModel->organization_id,
                    'status' => 1
                ])->get();
                if ($models) {
                    foreach ($models as $model) {
                        if ($model->employee_id == $employeeId) {
                            // no nothing
                        } else {
                            $data[] = [
                                'id' => $model->id,
                                'name' => $model->full_name
                            ];
                            
                        }
                    }
                }
            }
        }

        return $data;
    }
}
