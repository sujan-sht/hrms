<?php

namespace App\Modules\Warning\Repositories;

interface WarningInterface
{
    public function getList($filter = []);

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function findOne($id);

    public function create($data);

    public function update($id, $data);

    public function delete($id);


}
