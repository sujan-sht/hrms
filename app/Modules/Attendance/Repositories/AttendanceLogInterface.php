<?php

namespace App\Modules\Attendance\Repositories;

interface AttendanceLogInterface
{
    public function findAll($limit, $filter, $sort, $status);

    public function find($id);

    public function save($data);

    public function update($id, $data);

    public function delete($id);

    public function findOne($filter);

    public function findMinMaxTime($filter);

    public function findMinTime($filter);

    public function findMaxTime($filter);

    public function getEmpAttLogByOrg($date, $yesterday_date);

    public function findLastAttendance($date, $emp_id);

    public function getTodayCheckInOut();

    function allocationList();

    public function checkAllocationExists($data);

    public function webAtdAllocation($data);
    public function findAllocation($id);
    public function updateAllocation($id,$data);
    function destroyAllocation($id);
}
