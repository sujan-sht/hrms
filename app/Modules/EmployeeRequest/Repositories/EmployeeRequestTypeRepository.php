<?php

namespace App\Modules\EmployeeRequest\Repositories;

use App\Modules\EmployeeRequest\Entities\EmployeeRequestType;

/**
 * EmployeeRequestRepository
 */
class EmployeeRequestTypeRepository implements EmployeeRequestTypeInterface
{
    public function findAll($limit = null, $filter, $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1])
    {
        $result = EmployeeRequestType::orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }

    public function find($id)
    {
        return EmployeeRequestType::find($id);
    }

    public function update($id, $data)
    {
        return EmployeeRequestType::find($id)->update($data);
    }

    public function save($data)
    {
        return EmployeeRequestType::create($data);
    }

    public function delete($id)
    {
        $request_type = EmployeeRequestType::find($id);
        $request_type->employeeRequest()->delete();
        return $request_type->delete();
    }

    public function getalllist()
    {
        return EmployeeRequestType::where('status', 1)->get();

    }

    public function getList()
    {
        return EmployeeRequestType::where('status', 1)->pluck('title', 'id');
    }

    public function getRequestTypes()
    {
        return EmployeeRequestType::all();
    }

    public function getRequestTypesList()
    {
        return EmployeeRequestType::select('id', 'title')->get();
    }

}
