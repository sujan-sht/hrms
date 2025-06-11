<?php

namespace App\Modules\Poll\Repositories;

interface PollOptionInterface
{

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function find($id);

    public function save($data);

    public function update($id, $data);

    public function delete($id);

    public function checkAndUpdate($pollOptionData, $poll_id);
}
