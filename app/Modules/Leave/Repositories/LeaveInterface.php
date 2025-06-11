<?php

namespace App\Modules\Leave\Repositories;

interface LeaveInterface
{
    public function getList();

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function findOne($id);

    public function create($data);

    public function update($id, $data);

    public function delete($id);

    public function upload($file);

    public function checkData($params);

    public function getEmployeeLeaves($employee_id = null, $limit = '');

    public function getEmployeeApprovalFlow($employee_id);

    public function findTeamleaves($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function getApprovedTodayLeave();

    public function sendMailNotification($model);

    public function countEmployeeLeaveStatus();

    public function pluckEmployeeApproval();

    public function PreProcessData($request);

    public function postProcessData($request);

    public function uploadAttachment($id, $file);

    public function checkLeave($params);

    public function employeeRemainingLeaveDetails($filter, $limit = '');

    public function empRemainingLeaveDetailsLeaveTypewise($emp_id);

    public function leaveEncashmentLogs($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function leaveEncashmentLogsActivity($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);
}
