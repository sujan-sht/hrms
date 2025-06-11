<?php

namespace App\Modules\Grievance\Repositories;

interface GrievanceInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1]);

    public function find($id);

    public function getList();

    public function save($data);

    public function update($id, $data);

    public function delete($id);

    public function sendMailNotification($model);

    public function upload($file);

}
