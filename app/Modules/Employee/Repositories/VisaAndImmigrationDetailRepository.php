<?php

namespace App\Modules\Employee\Repositories;

use App\Modules\Employee\Entities\VisaAndImmigrationDetail;


class VisaAndImmigrationDetailRepository implements VisaAndImmigrationDetailInterface
{
    protected $model;

    public function __construct(VisaAndImmigrationDetail $visaAndImmigration)
    {
        $this->model = $visaAndImmigration;
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
        $visaAndImmigration = $this->findOne($id);
        $visaAndImmigration->fill($data);
        $visaAndImmigration->update();

        return $visaAndImmigration;
    }

    public function delete($id)
    {
        $this->findOne($id)->delete();
        return true;
    }
}
