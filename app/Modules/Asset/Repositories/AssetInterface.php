<?php

namespace App\Modules\Asset\Repositories;

interface AssetInterface
{
    public function getList();

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function find($id);

    public function save($data);

    public function update($id, $data);

    public function delete($id);

    public function deleteAssetQuantity($asset_id);

    public function deleteAssetAllocate($asset_id);
}
