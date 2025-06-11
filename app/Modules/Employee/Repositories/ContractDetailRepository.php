<?php

namespace App\Modules\Employee\Repositories;

use App\Modules\Employee\Entities\ContractDetail;


class ContractDetailRepository implements ContractDetailInterface
{
    protected $model;

    public function __construct(ContractDetail $contractDetail)
    {
        $this->model = $contractDetail;
    }

    public function findAll($empId)
    {
        return $this->model->where('employee_id', $empId)->latest()->get();
    }

    public function findOne($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function save($data)
    {
        return $this->model->create($data);
    }

    public function update($id, $data)
    {
        $contractDetail = $this->findOne($id);
        $contractDetail->fill($data);
        $contractDetail->update();

        return $contractDetail;
    }

    public function delete($id)
    {
        $this->findOne($id)->delete();
        return true;
    }
}
