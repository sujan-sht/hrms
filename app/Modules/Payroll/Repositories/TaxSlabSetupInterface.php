<?php

namespace App\Modules\Payroll\Repositories;

interface TaxSlabSetupInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1]);

    public function find($id);

    public function getList();

    public function save($data);

    public function update($id, $data);

    public function delete($id);

    public function getTaxSlabFromOrganization();

    public function updateOrCreate($data);

}
