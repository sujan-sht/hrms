<?php

namespace App\Modules\Asset\Repositories;

interface AssetQuantityInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function find($id);

    public function save($data);

    public function update($id, $data);

    public function delete($id);

    public function updateRemainingQuantity($inputData, $operation);

    public function checkAssetExits($asset_id);
}
