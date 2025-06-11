<?php

namespace App\Modules\FuelConsumption\Repositories;

interface FuelConsumptionInterface
{
    public function findAllByEmployee($emp_id, $limit=null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = ['pending', 'verified', 'approved']);
    public function findAll($limit=null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = ['pending', 'verified', 'approved']);

    public function find($id);

    public function save($data);

    public function update($id,$data);

    public function delete($id);
    
}