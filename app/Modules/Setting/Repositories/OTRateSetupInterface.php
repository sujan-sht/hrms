<?php

namespace App\Modules\Setting\Repositories;

interface OTRateSetupInterface
{
    
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function findOne($data);

    public function findOtRateByOrganization($data);

    public function create($data);

    public function createOtDetail($data);

    public function update($id, $data);

    public function delete($id);

    public function deleteChild($id);
}
