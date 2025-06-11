<?php
namespace App\Modules\GeoFence\Repositories;

use App\Modules\GeoFence\Entities\GeoFence;
use App\Modules\GeoFence\Entities\GeofenceAllocation;

class GeoFenceRepository implements GeoFenceInterface
{
    public function findAll($limit = 10, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result =GeoFence::when(array_keys($filter, true), function ($query) use ($filter) {


        })->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;

    }

    public function find($id)
    {
        return GeoFence::find($id);
    }

    public function save($data)
    {
        return GeoFence::create($data);
    }


    public function update($id,$data)
    {
        $result = GeoFence::find($id);
        return $result->update($data);
    }

    public function delete($id)
    {
        $model = GeoFence::destroy($id);
        if($model){
            GeofenceAllocation::where('geofence_id', $id)->delete();
        }
        return $model;
    }

    function allocationList($geofence_id){
        $qry = GeofenceAllocation::query();
        $qry->where('geofence_id', $geofence_id);
        if(auth()->user()->user_type == 'division_hr'){
          $qry->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);  
        }
        $result = $qry->get();
        return $result;
    }
    public function checkAllocationExists($data)
    {
        $qry = GeofenceAllocation::query();
        $qry = $qry->where('geofence_id', $data['geofence_id'])->where('organization_id', $data['organization_id'])->where('department_id', $data['department_id']);
        if(isset($data['id'])){
            $qry->where('id', '!=', $data['id']);
        }
        $result = $qry->exists();
        return $result;
    }

    public function geofenceAllocation($data) {
        $inputData['geofence_id'] = $data['geofence_id'];
        $inputData['organization_id'] = $data['organization_id'];
        $inputData['branch_id'] = $data['branch_id'];

        if(!empty($data['allocation_details'])){
            foreach ($data['allocation_details'] as $allocation) {
                if (isset($allocation['department_id']) && isset($allocation['employee_ids'])) {
                    $inputData['department_id'] = $allocation['department_id'];
                    $inputData['employee_id'] = json_encode($allocation['employee_ids']);
                    GeofenceAllocation::create($inputData);
                }
            }
        }
        return true;
    }

    public function findAllocation($id)
    {
        return GeofenceAllocation::find($id);
    }

    public function updateAllocation($id,$data)
    {
        $result = GeofenceAllocation::find($id);
        if(isset($data['employee_ids']) && !empty($data['employee_ids'])){
            $data['employee_id'] = json_encode($data['employee_ids']);
        }else{
            $data['employee_id'] = null;
        }
        return $result->update($data);
    }

    function destroyAllocation($id) {
        return GeofenceAllocation::destroy($id);
    }

}
