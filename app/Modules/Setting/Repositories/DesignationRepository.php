<?php
namespace App\Modules\Setting\Repositories;

use App\Modules\Setting\Entities\Designation;
use App\Modules\Setting\Entities\DesignationOrganization;

class DesignationRepository implements DesignationInterface
{

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $designationModel  = Designation::query();
        $designationModel->when(true, function ($query) use ($filter) {
            // if (isset($filter['organization_id']) && $filter['organization_id'] != '') {
            //     $query->whereHas('employee', function ($q) use ($filter) {
            //         $q->where('organization_id', $filter['organization_id']);
            //     });
            // }

            // if (isset($filter['employee_id']) && $filter['employee_id'] != '') {
            //     $query = $query->where('employee_id', $filter['employee_id']);
            // }
        });
        $result = $designationModel->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : config('attendance.export-length'));
        return $result;
    }

    public function find($id){
        return Designation::find($id);
    }

    public function save($data){
        $designation =  Designation::create($data);
        if(isset($designation)){
            $this->saveDesignationOrganization($designation->id, $data);
        }
        return $designation;
    }

    public function update($id,$data){
        $result = $this->find($id);
        $flag = $result->update($data);
        if($flag){
            $this->deleteDesignationOrganization($id);
            $this->saveDesignationOrganization($id, $data);
        }
        return $flag;
    }

    public function delete($id){
        $flag = Designation::destroy($id);
        if($flag){
            $this->deleteDesignationOrganization($id);
        }
        return $flag;
    }

    public function saveDesignationOrganization($designationId, $data)
    {
        if(isset($data['organization_ids']) && !empty($data['organization_ids'])){
            foreach ($data['organization_ids'] as $organization_id) {
                $designationOrg['organization_id'] = $organization_id;
                $designationOrg['designation_id'] = $designationId;
                DesignationOrganization::create($designationOrg);
            }
        }
        return true;
    }

    public function deleteDesignationOrganization($designationId){
        return DesignationOrganization::where('designation_id', $designationId)->delete();
    }

    public function getList(){
        return Designation::pluck('title', 'id');
    }
}
