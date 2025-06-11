<?php

namespace App\Modules\Template\Repositories;

interface TemplateInterface
{
    public function getList();

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function findOne($id);

    public function findByTemplateType($id);

    public function create($data);

    public function update($id, $data);

    public function delete($id);
}
