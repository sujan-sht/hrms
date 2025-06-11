<?php

namespace App\Modules\Shift\Repositories;

use App\Modules\Shift\Entities\EmployeeShift;

/**
 * EmployeeShiftRepository
 */
class EmployeeShiftRepository implements EmployeeShiftInterface
{
    public function findAll()
    {
        return EmployeeShift::all();
    }

    public function save($data)
    {
        if ($employeeShift = EmployeeShift::whereEmployeeId($data['employee_id'])->whereShiftId($data['shift_id'])->where('days', '=', $data['days'])->where('group_id', '=', $data['group_id'])->first()) {
            return $employeeShift->update([
                'shift_id' => $data['shift_id'],
                'employee_id' => $data['employee_id'],
                'days' => $data['days'],
                'group_id' => $data['group_id'],
                'updated_by' => auth()->user()->id,
            ]);
        }
        return EmployeeShift::create($data);
    }

    public function delete($employee_id, $shift_id, $days, $group_id)
    {
        EmployeeShift::whereEmployeeId($employee_id)->whereShiftId($shift_id)->where('days', '=', $days)->delete();
    }

    public function getDays()
    {
        return EmployeeShift::DAYS;
    }

    public function findOne($filter)
    {
        $result = EmployeeShift::where($filter)->orderBy('id', 'DESC')->first();
        return $result;
    }

    public function getAll($filter = [], $limit = null, $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1])
    {
        $result = EmployeeShift::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['employee_id'])) {
                $query->where('employee_id', '=', $filter['employee_id']);
            }
            if (isset($filter['shift_id'])) {
                $query->where('shift_id', '=', $filter['shift_id']);
            }
            if (isset($filter['group_id'])) {
                $query->where('group_id', '=', $filter['group_id']);
            }
            if (isset($filter['days'])) {
                $query->where('days', '=', $filter['days']);
            }

        })->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }

    public function getEmployeeShift($emp_id, $shift_id, $day)
    {
        return EmployeeShift::where('employee_id', $emp_id)
            ->where('shift_id', $shift_id)
            ->where('days', $day)->orderBy('id', 'DESC')->first();
    }

    public static function employeeShift($emp_id, $day)
    {
        return EmployeeShift::where('employee_id', $emp_id)->where('days', $day)->orderBy('id', 'DESC')->first();
    }

    public function deleteByGroup($group_id)
    {
        EmployeeShift::whereGroupId($group_id)->delete();
    }

}
