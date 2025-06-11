<?php

namespace App\Modules\GeoFence\Repositories;

interface GeoFenceInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function find($id);

    public function save($data);

    public function update($id, $data);

    public function delete($id);
    public function allocationList($geofence_id);
    public function checkAllocationExists($data);

    public function geofenceAllocation($data);
    public function findAllocation($id);
    public function updateAllocation($id, $data);
    function destroyAllocation($id);
}
