<?php

namespace App\Modules\Worklog\Repositories;

interface WorklogInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function find($id);

    public function getList();

    public function save($data);

    public function getStatus();

    public function update($id, $data);

    public function delete($id);
}
