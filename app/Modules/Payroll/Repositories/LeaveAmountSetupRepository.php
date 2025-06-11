<?php

namespace App\Modules\Payroll\Repositories;

use App\Modules\Payroll\Entities\LeaveAmountSetup;
use App\Modules\Payroll\Entities\LeaveAmountSetupDetail;

class LeaveAmountSetupRepository implements LeaveAmountSetupInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1])
    {
        $authUser = auth()->user();
        if($authUser->user_type == 'division_hr') {
            $filter['organizationId'] = optional($authUser->userEmployer)->organization_id;
        }
        $result = LeaveAmountSetup::query();
        if(isset($filter['organizationId'])) {
            $result->where('organization_id', $filter['organizationId']);
        }
        
        $result = $result->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));

        return $result;
    }

    public function find($id)
    {
        return LeaveAmountSetup::find($id);
    }

    public function getList($params=[])
    {
        if(isset($params['organizationId'])) {
            $result = LeaveAmountSetup::where('organization_id', $params['organizationId'])->where('status',11)->orderBy('order','asc')->pluck('title', 'id');
        } else {
            $result = LeaveAmountSetup::where('status',11)->orderBy('order','asc')->pluck('title', 'id');
        }

        return $result;
    }

    public function save($data)
    {
        return LeaveAmountSetup::create($data);
    }
    public function saveDetail($data){
        return LeaveAmountSetupDetail::create($data);
    }

    public function update($id, $data)
    {
        $result = LeaveAmountSetup::find($id);
        
        return $result->update($data);
    }

    public function delete($id)
    {
        return LeaveAmountSetup::destroy($id);
    }
    public function deleteChild($id)
    {
        $result = LeaveAmountSetupDetail::where('leave_amount_setup_id', $id)->delete();
        return $result;
    }
}
