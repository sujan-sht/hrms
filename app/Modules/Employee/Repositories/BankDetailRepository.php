<?php

namespace App\Modules\Employee\Repositories;

use App\Modules\Employee\Entities\BankDetail;


class BankDetailRepository implements BankDetailInterface
{
    protected $model;

    public function __construct(BankDetail $bankDetail)
    {
        $this->model = $bankDetail;
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
        $bankDetail = $this->findOne($id);
        $bankDetail->fill($data);
        $bankDetail->update();

        return $bankDetail;
    }

    public function delete($id)
    {
        $this->findOne($id)->delete();
        return true;
    }
}
