<?php

namespace App\Modules\Attendance\Repositories;

interface AttendanceRequestInterface
{
    public function findAll($limit, $filter, $sort);

    public function find($id);

    public function approvedAtdRequestExist($date, $emp_id, $type);


    public function save($data);

    public function getStatus();

    public function update($id, $data);

    public function delete($id);

    public function getTypes();

    public function getKinds();

    public function sendMailNotification($model);

    public function findTeamAttendance($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function getEmployeeAttendanceRequest($limit = '');

    public function checkRequestExists($data);

    public function returnBackDeductedLeave($data);
}
