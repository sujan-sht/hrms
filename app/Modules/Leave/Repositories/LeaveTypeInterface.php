<?php

namespace App\Modules\Leave\Repositories;

interface LeaveTypeInterface
{
    public function getList($filter=[]);

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function findOne($id);

    public function findFromCode($code,$org);

    public function create($data);

    public function update($id, $data);

    public function delete($id);

    public function upload($file);

    public function getLeaveTypeName($leave_type_id);

    public function getLeaveTypes($organization_id, $leave_year_id, $gender, $marital_status, $params = null);

    public function getAllLeaveTypes($organization_id, $leave_year_id);

    public function getLeaveTypesFromOrganization($organization_id, $leave_year_id, $params = null);

    public function getEmpListFromLeaveType($id);

    public function deleteEmployeeLeave($leaveTypeId);
}
