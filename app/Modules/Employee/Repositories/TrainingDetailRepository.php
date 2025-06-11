<?php

namespace App\Modules\Employee\Repositories;

use App\Modules\Training\Entities\TrainingAttendance;

class TrainingDetailRepository implements TrainingDetailInterface
{
    protected $model;

    public function __construct(TrainingAttendance $trainingAttendance)
    {
        $this->model = $trainingAttendance;
    }

    public function findAll($empId)
    {
        return $this->model->where('employee_id', $empId)->where('status',11)->latest()->get();
    }

    public function findOne($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function update($id, $data)
    {
        $trainingAttendance = $this->findOne($id);
        $trainingAttendance->fill($data);
        $trainingAttendance->update();

        return $trainingAttendance;
    }
}
