<?php

namespace App\Modules\Payroll\Repositories;

interface IncomeSetupInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1]);

    public function find($id);

    public function getList($params=[]);
    public function getFixedList($params=[]);
    
    public function save($data);

    public function saveDetail($data);

    public function update($id, $data);

    public function delete($id);

    public function deleteChild($id);

}
