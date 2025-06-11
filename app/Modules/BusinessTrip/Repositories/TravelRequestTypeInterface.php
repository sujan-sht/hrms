<?php

namespace App\Modules\BusinessTrip\Repositories;

interface TravelRequestTypeInterface
{
    public function findAll($limit, $filter, $sort);
    public function getList();
    public function find($id);

    public function save($data);

    public function update($id, $data);

    public function delete($id);
}
