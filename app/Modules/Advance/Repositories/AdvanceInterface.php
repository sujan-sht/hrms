<?php

namespace App\Modules\Advance\Repositories;

interface AdvanceInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function findOne($id);

    public function create($data);

    public function update($id, $data);

    public function updateStatus($id, $data);

    public function updateAdvanceStatus($id, $data);

    public function delete($id);
    
    public function sendMailNotification($model);
}
