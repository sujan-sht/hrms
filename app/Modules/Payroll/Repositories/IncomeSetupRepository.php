<?php

namespace App\Modules\Payroll\Repositories;

use App\Modules\Payroll\Entities\IncomeSetup;
use App\Modules\Payroll\Entities\IncomeSetupReferenceSalaryType;

class IncomeSetupRepository implements IncomeSetupInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'order', 'sort' => 'ASC'], $status = [0, 1])
    {
        $authUser = auth()->user();
        if($authUser->user_type == 'division_hr') {
            $filter['organizationId'] = optional($authUser->userEmployer)->organization_id;
        }
        $result = IncomeSetup::query();
        if(isset($filter['organizationId'])) {
            $result->where('organization_id', $filter['organizationId']);
        }
        // if (auth()->user()->user_type != 'admin' && auth()->user()->user_type != 'super_admin') {
        //     $result->where('created_by', auth()->user()->id);
        // }

        $result = $result->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }

    public function find($id)
    {
        return IncomeSetup::find($id);
    }

    public function getList($params=[])
    {
        if(isset($params['organizationId'])) {
            $result = IncomeSetup::where('organization_id', $params['organizationId'])->where('status',11)->orderBy('order','asc')->pluck('title', 'id');
        } else {
            $result = IncomeSetup::where('status',11)->orderBy('order','asc')->pluck('title', 'id');
        }

        return $result;
    }

    public function getFixedList($params=[])
    {
        if(isset($params['organizationId'])) {
            $result = IncomeSetup::where('organization_id', $params['organizationId'])->where('status',11)->where('method','=',1)->orderBy('order','asc')->pluck('title', 'id');
        } else {
            $result = IncomeSetup::where('status',11)->orderBy('order','asc')->pluck('title', 'id');
        }

        return $result;
    }

    public function save($data)
    {
        if(isset($data['daily_basis_status'])) {
            $data['daily_basis_status'] = 11;
        } else {
            $data['daily_basis_status'] = 10;
        }

        return IncomeSetup::create($data);
    }
    public function saveDetail($data){
        return IncomeSetupReferenceSalaryType::create($data);
    }

    public function update($id, $data)
    {
        $result = IncomeSetup::find($id);

        if(isset($data['daily_basis_status'])) {
            $data['daily_basis_status'] = 11;
        } else {
            $data['daily_basis_status'] = 10;
        }

        return $result->update($data);
    }

    public function delete($id)
    {
        return IncomeSetup::destroy($id);
    }
    public function deleteChild($id)
    {
        // dd($id);
        $result = IncomeSetupReferenceSalaryType::where('income_setup_id', $id)->delete();
        return $result;
    }
}
