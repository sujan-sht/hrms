<?php

namespace App\Modules\Organization\Repositories;

interface OrganizationInterface
{
    public function getList($filter = []);

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function findOne($id);

    public function create($data);

    public function update($id, $data);

    public function delete($id);

    public function upload($file);

    public function getAll();

    public function findFirstOrganizationId();
}
