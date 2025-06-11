<?php

namespace App\Modules\Employee\Repositories;

use App\Modules\Employee\Entities\MedicalDetail;


class MedicalDetailRepository implements MedicalDetailInterface
{
    protected $model;

    public function __construct(MedicalDetail $medicalDetail)
    {
        $this->model = $medicalDetail;
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
        $medicalDetail = $this->findOne($id);
        $medicalDetail->fill($data);
        $medicalDetail->update();

        return $medicalDetail;
    }

    public function delete($id)
    {
        $this->findOne($id)->delete();
        return true;
    }
}
