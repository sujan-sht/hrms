<?php
namespace App\Modules\Setting\Repositories;

use App\Modules\Setting\Entities\ForceLeaveSetting;

class ForceLeaveSetupRepository implements ForceLeaveSetupInterface
{

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $forceLeaveSetupModel  = ForceLeaveSetting::query();
        $forceLeaveSetupModel->when(true, function ($query) use ($filter) {
        });
        $result = $forceLeaveSetupModel->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : config('attendance.export-length'));
        return $result;
    }
    public function find($id){
        return ForceLeaveSetting::find($id);
    }

    public function save($data){
        return ForceLeaveSetting::create($data);
    }

    public function update($id,$data){
        $result = $this->find($id);
        return $result->update($data);
    }

    public function delete($id){
        return ForceLeaveSetting::destroy($id);
    }
}
