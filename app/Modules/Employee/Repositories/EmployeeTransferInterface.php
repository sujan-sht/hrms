<?php

namespace App\Modules\Employee\Repositories;

interface EmployeeTransferInterface
{
    public function findAll($limit=null, $filter=[], $sort = ['by' => 'id', 'sort' => 'desc']);

    public function findCarrierMobilityList($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'desc']);

    public function findOne($id);

    public function create($data);

    public function update($id, $data);

    public function delete($id);
}
