<?php

namespace App\Modules\Payroll\Repositories;

interface TaxExcludeSetupInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1]);

    public function find($id);

    public function getList($params=[]);

    public function save($data);

    public function update($id, $data);

    public function delete($id);

}
