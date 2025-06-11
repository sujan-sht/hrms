<?php

namespace App\Modules\Employee\Repositories;

use App\Modules\Employee\Entities\EmergencyDetail;
use App\Modules\Employee\Entities\FamilyDetail;

class EmergencyDetailRepository implements EmergencyDetailInterface
{
    protected $model;

    public function __construct(EmergencyDetail $emergencyDetail)
    {
        $this->model = $emergencyDetail;
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
        $emergencyDetail = $this->findOne($id);
        $emergencyDetail->fill($data);
        $emergencyDetail->update();

        return $emergencyDetail;
    }

    public function delete($id)
    {
        $this->findOne($id)->delete();
        return true;
    }
}
