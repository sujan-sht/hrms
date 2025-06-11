<?php

namespace App\Modules\Employee\Repositories;

use App\Modules\Employee\Entities\EducationDetail;


class EducationDetailRepository implements EducationDetailInterface
{
    protected $model;

    public function __construct(EducationDetail $educationDetail)
    {
        $this->model = $educationDetail;
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
        $educationDetail = $this->findOne($id);
        $data = array_filter($data, function ($value) {
            return !(is_array($value) && empty($value));
        });
        $educationDetail->update($data);
        return $educationDetail;
    }

    public function delete($id)
    {
        $this->findOne($id)->delete();
        return true;
    }
}
