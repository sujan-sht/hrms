<?php

namespace App\Modules\Training\Repositories;

interface TrainingTrainerInterface
{
    public function getList();

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function findOne($id);

    public function create($data);

    public function updateOrCreate($data);

    public function update($id, $data);

    public function delete($id);

}
