<?php

namespace App\Modules\Employee\Repositories;

use App\Modules\Employee\Entities\EmployeeCarrierMobility;
use App\Modules\Employee\Entities\EmployeeTransfer;
use App\Modules\Employee\Entities\PerformanceDetail;
use App\Modules\Employee\Repositories\EmployeeTransferInterface;

class EmployeeTransferRepository implements EmployeeTransferInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'desc'])
    {
        if(auth()->user()->user_type == 'employee') {
            $filter['employee_id'] = auth()->user()->emp_id;
        }

        $result = PerformanceDetail::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['employee_id'])) {
                $query->where('employee_id', $filter['employee_id']);
            }
            if (isset($filter['category'])) {
                $query->where('category', $filter['category']);
            }
            if (isset($filter['type_id'])) {
                $query->where('type_id', $filter['type_id']);
            }
        })
        ->orderBy($sort['by'], $sort['sort'])
        ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));

        return $result;
    }
    public function findCarrierMobilityList($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'desc'])
    {
        if(auth()->user()->user_type == 'employee') {
            $filter['employee_id'] = auth()->user()->emp_id;
        }

        $result = EmployeeCarrierMobility::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['employee_id'])) {
                $query->where('employee_id', $filter['employee_id']);
            }
        })
        ->orderBy($sort['by'], $sort['sort'])
        ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));

        return $result;
    }

    public function findOne($id)
    {
        return EmployeeTransfer::find($id);
    }

    public function create($data)
    {
        return EmployeeTransfer::create($data);
    }

    public function update($id, $data)
    {
        $model = $this->findOne($id);
        $model->update($data);

        return $model;
    }

    public function delete($id)
    {
        $model = $this->findOne($id);
        
        return $model->delete();
    }
}
