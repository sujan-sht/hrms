<?php

namespace App\Modules\Onboarding\Repositories;

interface EvaluationInterface
{
    public function getList();

    public function getListWithTitle();

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function findOne($id);

    public function create($data);

    public function update($id, $data);

    public function delete($id);

    public function upload($file);

    public function checkData($params);
}
