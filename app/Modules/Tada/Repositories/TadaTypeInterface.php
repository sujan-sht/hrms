<?php

namespace App\Modules\Tada\Repositories;

interface TadaTypeInterface
{
    public function findAll($limit = null, $filter, $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1]);

    public function find($id);

    public function update($id, $data);

    public function save($data);

    public function delete($id);

    public function getList($type = null);
    public function saveSubType($data);
    public function deleteSubType($id);
    public function subTypeLists();
}
