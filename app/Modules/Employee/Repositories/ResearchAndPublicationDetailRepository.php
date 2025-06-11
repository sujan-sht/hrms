<?php

namespace App\Modules\Employee\Repositories;

use App\Modules\Employee\Entities\ResearchAndPublicationDetail;


class ResearchAndPublicationDetailRepository implements ResearchAndPublicationDetailInterface
{
    protected $model;

    public function __construct(ResearchAndPublicationDetail $researchAndPublicationDetail)
    {
        $this->model = $researchAndPublicationDetail;
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
        $researchAndPublicationDetail = $this->findOne($id);
        $researchAndPublicationDetail->fill($data);
        $researchAndPublicationDetail->update();

        return $researchAndPublicationDetail;
    }

    public function delete($id)
    {
        $this->findOne($id)->delete();
        return true;
    }
}
