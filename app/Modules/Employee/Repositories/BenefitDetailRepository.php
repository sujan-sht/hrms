<?php

namespace App\Modules\Employee\Repositories;

use App\Modules\Employee\Entities\BenefitDetail;


class BenefitDetailRepository implements BenefitDetailInterface
{
    protected $model;

    public function __construct(BenefitDetail $benefitDetail)
    {
        $this->model = $benefitDetail;
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
        $benefitDetail = $this->findOne($id);
        $benefitDetail->fill($data);
        $benefitDetail->update();

        return $benefitDetail;
    }

    public function delete($id)
    {
        $this->findOne($id)->delete();
        return true;
    }
}
