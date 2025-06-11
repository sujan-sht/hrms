<?php

namespace App\Modules\Payroll\Repositories;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Payroll\Entities\ArrearAdjustment;
use App\Modules\Payroll\Entities\ArrearAdjustmentDetail;
use App\Modules\Payroll\Entities\GrossSalarySetup;
use Illuminate\Support\Facades\DB;

class ArrearAdjustmentRepository implements ArrearAdjustmentInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1])
    {
        $result = ArrearAdjustment::query();
        if (isset($filter['organization_id'])) {
            $result->where('organization_id', $filter['organization_id']);
        }
        if (isset($filter['emp_id'])) {
            $result->where('emp_id', $filter['emp_id']);
        }

        if (isset($filter['eng_year']) && $filter['eng_year']) {
            $result->where('eng_year', $filter['eng_year']);
        }
        if (isset($filter['year']) && $filter['year']) {
            $result->where('year', $filter['year']);
        }

        if (isset($filter['month']) && $filter['month']) {
            $result->where('month', $filter['month']);
        }
        if (isset($filter['eng_month']) && $filter['eng_month']) {
            $result->where('eng_month', $filter['eng_month']);
        }


        $result = $result->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 10));
        return $result;
    }

    public function find($id)
    {
        return ArrearAdjustment::find($id);
    }

    public function save($data)
    {
        return ArrearAdjustment::create($data);
    }
    public function saveDetail($data)
    {
        return ArrearAdjustmentDetail::create($data);
    }

    public function findOne($filter)
    {
        $result = ArrearAdjustment::where($filter)->first();
        return $result;
    }
    public function update($id, $data)
    {
        $result = ArrearAdjustment::find($id);
        return $result->update($data);
    }

    public function delete($id)
    {
        $result = ArrearAdjustment::destroy($id);
        if($result) {
            $this->deleteChildTable($id);
        }

        return $result;
    }

    public function deleteChildTable($id)
    {
        $result = ArrearAdjustmentDetail::where('arrear_adjustment_id', $id)->delete();

        return $result;
    }


    public function getDetailByArrearId($id)
    {
         $arrear= ArrearAdjustment::find($id)->arrearAdjustmentDetail;
         return $arrear;
    }

}
