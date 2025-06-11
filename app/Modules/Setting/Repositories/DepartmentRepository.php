<?php
namespace App\Modules\Setting\Repositories;

use App\Modules\Setting\Entities\Department;
use App\Modules\Setting\Entities\DepartmentOrganization;

class DepartmentRepository implements DepartmentInterface
{

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $departmentModel  = Department::query();
        $departmentModel->when(true, function ($query) use ($filter) {
            // if (isset($filter['organization_id']) && $filter['organization_id'] != '') {
            //     $query->whereHas('employee', function ($q) use ($filter) {
            //         $q->where('organization_id', $filter['organization_id']);
            //     });
            // }

            // if (isset($filter['employee_id']) && $filter['employee_id'] != '') {
            //     $query = $query->where('employee_id', $filter['employee_id']);
            // }
        });
        $result = $departmentModel->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : config('attendance.export-length'));
        return $result;
    }
    public function find($id){
        return Department::find($id);
    }

    public function save($data){
        $department =  Department::create($data);
        if(isset($department)){
            $this->saveDepartmentOrganization($department->id, $data);
        }
        return $department;
    }

    public function update($id,$data){
        $result = $this->find($id);
        $flag = $result->update($data);
        if($flag){
            $this->deleteDepartmentOrganization($id);
            $this->saveDepartmentOrganization($id, $data);
        }
        return $flag;
    }

    public function delete($id){
        $flag = Department::destroy($id);
        if($flag){
            $this->deleteDepartmentOrganization($id);
        }
        return $flag;
    }

    public function saveDepartmentOrganization($departmentId, $data)
    {
        if(isset($data['organization_ids']) && !empty($data['organization_ids'])){
            foreach ($data['organization_ids'] as $organization_id) {
                $departmentOrg['organization_id'] = $organization_id;
                $departmentOrg['department_id'] = $departmentId;
                DepartmentOrganization::create($departmentOrg);
            }
        }
        return true;
    }

    public function deleteDepartmentOrganization($departmentId){
        return DepartmentOrganization::where('department_id', $departmentId)->delete();

    }

    public function getList(){
        return Department::pluck('title', 'id');
    }
}
