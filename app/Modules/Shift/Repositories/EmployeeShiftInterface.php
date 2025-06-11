<?php

namespace App\Modules\Shift\Repositories;

interface EmployeeShiftInterface
{
    public function findAll();

    public function save($data);

    public function delete($employee_id, $shift_id, $days, $group_id);

    public function getDays();

    public function findOne($filter);

    public function getAll($filter = [], $limit = null, $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1]);

    public function getEmployeeShift($emp_id,$shift_id,$day);

    public static function employeeShift($emp_id, $day);

    public function deleteByGroup($group_id) ;
}