<?php

namespace App\Modules\Payroll\Repositories;

interface MassIncrementInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1]);

    public function find($id);

    public function save($data);

    public function findOne($filter);

    public function update($id, $data);

    public function delete($id);

    public function getTodayMassIncrement();


}
