<?php

namespace App\Modules\Employee\Repositories;

use App\Modules\Employee\Entities\FamilyDetail;

class FamilyDetailRepository implements FamilyDetailInterface
{
    protected $model;

    public function __construct(FamilyDetail $familyDetail)
    {
        $this->model = $familyDetail;
    }

    public function findAll($empId)
    {
        return $this->model->where('employee_id', $empId)->where('status', 'approved')->latest()->get();
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
        $familyDetail = $this->findOne($id);
        $familyDetail->fill($data);
        $familyDetail->update();

        return $familyDetail;
    }

    public function delete($id)
    {
        $this->findOne($id)->delete();
        return true;
    }
}
