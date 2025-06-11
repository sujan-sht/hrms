<?php
namespace App\Modules\Setting\Repositories;
use App\Modules\Leave\Entities\LeaveEncashmentSetup;

class LeaveEncashmentSetupRepository implements LeaveEncashmentSetupInterface
{

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $leaveEncashmentSetupModel  = LeaveEncashmentSetup::query();
        $leaveEncashmentSetupModel->when(true, function ($query) use ($filter) {
            // if (isset($filter['organization_id']) && $filter['organization_id'] != '') {
            //     $query->whereHas('employee', function ($q) use ($filter) {
            //         $q->where('organization_id', $filter['organization_id']);
            //     });
            // }

            // if (isset($filter['employee_id']) && $filter['employee_id'] != '') {
            //     $query = $query->where('employee_id', $filter['employee_id']);
            // }
        });
        $result = $leaveEncashmentSetupModel->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : config('attendance.export-length'));
        return $result;
    }
    public function find($id){
        return LeaveEncashmentSetup::find($id);
    }

    public function save($data){
        return LeaveEncashmentSetup::create($data);
    }

    public function update($id,$data){
        $result = $this->find($id);
        return $result->update($data);
    }

    public function delete($id){
        return LeaveEncashmentSetup::destroy($id);
    }

    // public function getList(){
    //     return LeaveEncashmentSetup::pluck('title', 'id');
    // }
}
