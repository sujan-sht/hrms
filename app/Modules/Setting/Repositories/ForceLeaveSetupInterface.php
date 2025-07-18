<?php
namespace App\Modules\Setting\Repositories;

interface ForceLeaveSetupInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function find($id);

    public function save($data);

    public function update($id,$data);

    public function delete($id);
}
