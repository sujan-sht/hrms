<?php

namespace App\Modules\BusinessTrip\Repositories;

use App\Modules\BusinessTrip\Entities\TravelRequestType;

class TravelRequestTypeRepository implements TravelRequestTypeInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $typeModel  = TravelRequestType::query();
        $typeModel->when(true, function ($query) use ($filter) {
            if (isset($filter['status']) && $filter['status'] != '') {
                $query = $query->where('status', $filter['status']);
            }
            
        });
        $result = $typeModel->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : config('attendance.export-length'));
        return $result;
    }

    public function getList()
    {
        return TravelRequestType::where('status',11)->pluck('title', 'id')->toArray();
    }
    public function find($id)
    {
        return TravelRequestType::find($id);
    }

    public function save($data)
    {
        $model = TravelRequestType::create($data);
        return $model;
    }

    public function update($id, $data)
    {
        return TravelRequestType::find($id)->update($data);
    }

    public function delete($id)
    {
        return TravelRequestType::find($id)->delete();
    }
   
}
