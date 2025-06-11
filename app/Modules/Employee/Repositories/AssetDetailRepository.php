<?php

namespace App\Modules\Employee\Repositories;

use App\Modules\Employee\Entities\AssetDetail;


class AssetDetailRepository implements AssetDetailInterface
{
    protected $model;

    public function __construct(AssetDetail $assetDetail)
    {
        $this->model = $assetDetail;
    }

    public function findAll($empId)
    {
        return $this->model->where('employee_id',$empId)->latest()->get();
    }

    public function findOne($id)
    {
        return $this->model->where('id',$id)->first();
    }

    public function save($data)
    {
        return $this->model->create($data);
    }

    public function update($id, $data)
    {
        $assetDetail = $this->findOne($id);
        $assetDetail->fill($data);
        $assetDetail->update();

        return $assetDetail;
    }

    public function delete($id)
    {
        $this->findOne($id)->delete();
        return true;
    }
}
