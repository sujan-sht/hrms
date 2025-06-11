<?php

namespace App\Modules\Asset\Repositories;

use App\Modules\Asset\Entities\Asset;
use App\Modules\Asset\Entities\AssetAllocate;
use App\Modules\Asset\Entities\AssetQuantity;

class AssetRepository implements AssetInterface
{
    public function getList()
    {
        return Asset::pluck('title', 'id');
    }

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = Asset::when(array_keys($filter, true), function ($query) use ($filter) {

            if (isset($filter['title']) && !empty($filter['title'])) {
                $query->where('title', 'like', '%' . $filter['title'] . '%');
            }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));

        return $result;
    }

    public function find($id)
    {
        return Asset::find($id);
    }

    public function save($data)
    {
        return Asset::create($data);
    }

    public function update($id, $data)
    {
        return Asset::find($id)->update($data);
    }

    public function delete($id)
    {
        return Asset::find($id)->delete();
    }

    public function deleteAssetQuantity($asset_id)
    {
        return AssetQuantity::where('asset_id', $asset_id)->delete();
    }

    public function deleteAssetAllocate($asset_id)
    {
        return AssetAllocate::where('asset_id', $asset_id)->delete();
    }
}
