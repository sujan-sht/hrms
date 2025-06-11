<?php

namespace App\Modules\Payroll\Repositories;

interface BonusInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function findOne($id);

    public function create($data);

    public function delete($id);

}
