<?php

namespace App\Modules\Attendance\Repositories;

interface AttendanceRequestLinkInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function find($id);

    public function save($data);

    public function update($id, $data);

    public function delete($id);
}
