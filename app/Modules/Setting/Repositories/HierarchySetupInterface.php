<?php

namespace App\Modules\Setting\Repositories;

interface HierarchySetupInterface
{
    public function getList();

    public function departmentList();

    public function levelList();

    public function designationList();

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function find($id);

    public function save($data);

    public function update($id, $data);

    public function delete($id);
}
