<?php

namespace App\Modules\Payroll\Repositories;

use App\Modules\Payroll\Entities\BonusSetup;
use App\Modules\Payroll\Entities\BonusSetupReferenceSalaryType;

class BonusSetupRepository implements BonusSetupInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'order', 'sort' => 'ASC'], $status = [0, 1])
    {
        $authUser = auth()->user();
        if($authUser->user_type == 'division_hr') {
            $filter['organizationId'] = optional($authUser->userEmployer)->organization_id;
        }
        $result = BonusSetup::query();
        if(isset($filter['organizationId'])) {
            $result->where('organization_id', $filter['organizationId']);
        }
        // $result->where('status',11);
        // if (auth()->user()->user_type != 'admin' && auth()->user()->user_type != 'super_admin') {
        //     $result->where('created_by', auth()->user()->id);
        // }

        $result = $result->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));

        return $result;
    }

    public function findAllActive($limit = null, $filter = [], $sort = ['by' => 'order', 'sort' => 'ASC'], $status = [0, 1])
    {
        $authUser = auth()->user();
        if($authUser->user_type == 'division_hr') {
            $filter['organizationId'] = optional($authUser->userEmployer)->organization_id;
        }
        $result = BonusSetup::query();
        if(isset($filter['organizationId'])) {
            $result->where('organization_id', $filter['organizationId']);
        }
        $result->where('status',11);
        // if (auth()->user()->user_type != 'admin' && auth()->user()->user_type != 'super_admin') {
        //     $result->where('created_by', auth()->user()->id);
        // }

        $result = $result->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));

        return $result;
    }

    public function find($id)
    {
        return BonusSetup::find($id);
    }

    public function getList($params=[])
    {
        if(isset($params['organizationId'])) {
            $result = BonusSetup::where('organization_id', $params['organizationId'])->where('status',11)->orderBy('order','asc')->pluck('title', 'id');
        } else {
            $result = BonusSetup::where('status',11)->orderBy('order','asc')->pluck('title', 'id');
        }

        return $result;
    }

    public function save($data)
    {
        return BonusSetup::create($data);
    }

    public function saveDetail($data)
    {
        return BonusSetupReferenceSalaryType::create($data);
    }

    public function update($id, $data)
    {
        $result = BonusSetup::find($id);

        if(isset($data['daily_basis_status'])) {
            $data['daily_basis_status'] = 11;
        } else {
            $data['daily_basis_status'] = 10;
        }

        return $result->update($data);
    }

    public function delete($id)
    {
        return BonusSetup::destroy($id);
    }
    public function deleteChild($id)
    {
        $result = BonusSetupReferenceSalaryType::where('bonus_setup_id', $id)->delete();
        return $result;
    }
}
