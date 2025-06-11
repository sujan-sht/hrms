<?php

namespace App\Modules\Employee\Repositories;

use App\Modules\Employee\Entities\PreviousJobDetail;


class PreviousJobDetailRepository implements PreviousJobDetailInterface
{
    protected $model;

    public function __construct(PreviousJobDetail $previousJobDetail)
    {
        $this->model = $previousJobDetail;
    }

    public function findAll($empId)
    {
        $query = $this->model;
        if(isset($empId) && !is_null($empId)) {
            $query = $query->where('employee_id', $empId);
        }
        if (auth()->user()->user_type == 'hr' ||
            auth()->user()->user_type == 'division_hr' ||
            auth()->user()->user_type == 'admin' ||
            auth()->user()->user_type == 'super_admin') {
            $query = $query->whereIn('approved_by_hr', [0, 1]);
        } else {
            $query = $query->where('approved_by_hr', 1);
        }
        return $query->latest()->get();
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
        $previousJobDetail = $this->findOne($id);
        $previousJobDetail->fill($data);
        $previousJobDetail->update();

        return $previousJobDetail;
    }

    public function delete($id)
    {
        $this->findOne($id)->delete();
        return true;
    }
}
