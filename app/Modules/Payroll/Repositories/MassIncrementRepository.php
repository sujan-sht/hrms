<?php

namespace App\Modules\Payroll\Repositories;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Payroll\Entities\MassIncrement;
use App\Modules\Payroll\Entities\GrossSalarySetup;
use Illuminate\Support\Facades\DB;

class MassIncrementRepository implements MassIncrementInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1])
    {
        $result = MassIncrement::query();
        if (auth()->user()->user_type != 'admin' && auth()->user()->user_type != 'super_admin') {
            $result->where('created_by', auth()->user()->id);
        }

        $result = $result->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }

    public function find($id)
    {
        return MassIncrement::find($id);
    }

    public function save($data)
    {
        return MassIncrement::create($data);
    }

    public function findOne($filter)
    {
        $result = MassIncrement::where($filter)->first();
        return $result;
    }
    public function update($id, $data)
    {
        $result = MassIncrement::find($id);
        return $result->update($data);
    }



    public function delete($id)
    {
        return MassIncrement::destroy($id);

    }
    public function getTodayMassIncrement(){
        $result = MassIncrement::where('effective_date',date('Y-m-d'))->get();
        return $result;
    }


}
