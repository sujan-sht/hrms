<?php

namespace App\Modules\Offboarding\Repositories;

interface OffboardClearanceInterface
{
    public function getList();

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function findOne($id);

    public function create($data);

    public function update($id, $data);

    public function delete($id);

    public function sendMailNotification($model,$resignationModel);

    public function createEmployeeClearance($data);
}
