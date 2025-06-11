<?php

namespace App\Modules\OrganizationalStructure\Repositories;

interface OrganizationalStructureInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1]);

    public function find($id);

    public function save($data);

    public function update($id, $data);

    public function delete($id);

    public function getOrgStructureDetails($orgStructureId);

    public function deleteOrgStructureDetails($orgStructureId);
}
