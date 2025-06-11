<?php

namespace App\Modules\OvertimeRequest\Repositories;

interface OvertimeRequestInterface
{
    public function findAll($limit, $filter, $sort);

    public function find($id);

    public function save($data);

    public function update($id, $data);

    public function delete($id);

    public function findTeamOvertimeRequests($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function sendMailNotification($model);

    public function getEmployeeOvertimeRequests($employeeId = null, $limit = null);

}
