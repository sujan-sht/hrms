<?php

namespace App\Modules\Branch\Repositories;

interface BranchInterface
{
    public function getList();

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function findOne($id);

    public function create($data);

    public function update($id, $data);

    public function delete($id);

    public function upload($file);

    public function branchListOrganizationwise($organizationId);

    public function branchListMultipleOrganizationwise($organizationId);

    public function branchesData();
}
