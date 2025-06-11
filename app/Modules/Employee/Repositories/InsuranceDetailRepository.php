<?php

namespace App\Modules\Employee\Repositories;

use App\Modules\Employee\Entities\EmployeeInsuranceDetail;


class InsuranceDetailRepository implements InsuranceDetailInterface
{
    protected $model;

    public function __construct(EmployeeInsuranceDetail $insuranceDetail)
    {
        $this->model = $insuranceDetail;
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
        $insuranceDetail = $this->findOne($id);
        $insuranceDetail->fill($data);
        $insuranceDetail->update();

        return $insuranceDetail;
    }

    public function delete($id)
    {
        $this->findOne($id)->delete();
        return true;
    }
}
