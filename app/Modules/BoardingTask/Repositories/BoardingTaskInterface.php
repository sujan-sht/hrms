<?php

namespace App\Modules\BoardingTask\Repositories;

interface BoardingTaskInterface
{
    public function getList($params = null);

    public function getListWithData($params, $mrfId, $applicantId);

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function findOne($id);

    public function create($data);

    public function update($id, $data);

    public function delete($id);
}
