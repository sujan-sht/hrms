<?php
namespace App\Modules\Setting\Repositories;

use App\Modules\Setting\Entities\Level;
use App\Modules\Setting\Entities\LevelDesignation;
use App\Modules\Setting\Entities\LevelOrganization;

class LevelRepository implements LevelInterface
{

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $levelModel  = Level::query();
        $levelModel->when(true, function ($query) use ($filter) {
            // if (isset($filter['organization_id']) && $filter['organization_id'] != '') {
            //     $query->whereHas('employee', function ($q) use ($filter) {
            //         $q->where('organization_id', $filter['organization_id']);
            //     });
            // }

            // if (isset($filter['employee_id']) && $filter['employee_id'] != '') {
            //     $query = $query->where('employee_id', $filter['employee_id']);
            // }
        });
        $result = $levelModel->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : config('attendance.export-length'));
        return $result;
    }
    public function find($id){
        return Level::find($id);
    }

    public function save($data){
        $level = Level::create($data);
        if(isset($level)){
            $this->saveLevelOrganization($level->id, $data);
            $this->saveLevelDesignation($level->id, $data);
        }
        return $level;
    }

    public function update($id,$data){
        $result = $this->find($id);
        $flag = $result->update($data);
        if($flag){
            $this->deleteLevelOrganization($id);
            $this->deleteLevelDesignation($id);

            $this->saveLevelOrganization($id, $data);
            $this->saveLevelDesignation($id, $data);
        }
        return $flag;
    }

    public function delete($id){
        $flag = Level::destroy($id);
        if($flag){
            $this->deleteLevelOrganization($id);
            $this->deleteLevelDesignation($id);
        }
        return $flag;
    }

    public function saveLevelOrganization($levelId, $data)
    {
        if(isset($data['organization_ids']) && !empty($data['organization_ids'])){
            foreach ($data['organization_ids'] as $organization_id) {
                $levelOrg['organization_id'] = $organization_id;
                $levelOrg['level_id'] = $levelId;
                LevelOrganization::create($levelOrg);
            }
        }
        return true;
    }

    public function saveLevelDesignation($levelId, $data)
    {
        if(isset($data['designation_ids']) && !empty($data['designation_ids'])){
            foreach ($data['designation_ids'] as $designation_id) {
                $levelDesignation['designation_id'] = $designation_id;
                $levelDesignation['level_id'] = $levelId;
                LevelDesignation::create($levelDesignation);
            }
        }
        return true;
    }

    public function deleteLevelOrganization($levelId){
        return LevelOrganization::where('level_id', $levelId)->delete();
    }

    public function deleteLevelDesignation($levelId){
        return LevelDesignation::where('level_id', $levelId)->delete();
    }

    public function getList(){
        return Level::pluck('title', 'id');
    }
}
