<?php

namespace App\Modules\Asset\Repositories;

use App\Modules\Asset\Entities\AssetQuantity;


class AssetQuantityRepository implements AssetQuantityInterface
{

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = AssetQuantity::when(array_keys($filter, true), function ($query) use ($filter) {

            if (isset($filter['asset_id']) && !empty($filter['asset_id'])) {
                $query->where('asset_id', $filter['asset_id']);
            }
            
            if (isset($filter['expiry_date']) && !empty($filter['expiry_date'])) {
                if (setting('calendar_type') == 'BS') {
                    $query->where('expiry_date', date_converter()->nep_to_eng_convert($filter['expiry_date']));
                } else {
                    $query->where('expiry_date', $filter['expiry_date']);
                }
            }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));

        return $result;
    }

    public function find($id)
    {
        return AssetQuantity::find($id);
    }

    public function save($data)
    {
        return AssetQuantity::create($data);
    }

    public function update($id, $data)
    {
        return AssetQuantity::find($id)->update($data);
    }

    public function delete($id)
    {
        return AssetQuantity::find($id)->delete();
    }

    public function updateRemainingQuantity($inputData, $operation)
    {
        $assetQuantity = $this->checkAssetExits($inputData['asset_id']);
        if ($assetQuantity) {
            if ($operation == 'Add') {
                $data['remaining_quantity'] = $assetQuantity['remaining_quantity'] + $inputData['quantity'];
            } elseif ($operation == 'Sub') {
                $data['remaining_quantity'] = $assetQuantity['remaining_quantity'] - $inputData['quantity'];
            }
            $this->update($assetQuantity['id'], $data);
        }
    }

    public function checkAssetExits($asset_id)
    {
        return AssetQuantity::where('asset_id', $asset_id)->first();
    }
}
