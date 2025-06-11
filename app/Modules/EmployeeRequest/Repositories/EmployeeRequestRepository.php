<?php

namespace App\Modules\EmployeeRequest\Repositories;

use App\Modules\Dropdown\Entities\Dropdown;
use App\Modules\EmployeeRequest\Entities\EmployeeRequest;
use Carbon\Carbon;
use DB;

/**
 * EmployeeRequestRepository
 */
class EmployeeRequestRepository implements EmployeeRequestInterface
{
    public function findAll($limit = null, $filter, $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1])
    {
        $result = EmployeeRequest::orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        if ($filter) {
            $result = EmployeeRequest::where('title', 'like', '%' . $filter . '%')
                ->orWhereHas('employee', function ($q) use ($filter) {
                    $q->where('employments.first_name', 'like', '%' . $filter . '%');
                    $q->orWhere('employments.last_name', 'like', '%' . $filter . '%');
                })
                ->orWhereHas('dropdown', function ($a) use ($filter) {
                    $a->where('dropvalue', 'like', '%' . $filter . '%');
                })
                ->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        }
        return $result;
    }

    public function findUserRequests($limit = null, $user_id, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $page_number = 1)
    { 
        $result = EmployeeRequest::whereEmployeeId($user_id)->when(array_keys($filter), function ($query) use ($filter) {

            if (isset($filter['from_date']) && isset($filter['to_date']) && !is_null($filter['from_date']) && !is_null($filter['to_date'])) {
                $query->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$filter['from_date'], $filter['to_date']]);
            } elseif (isset($filter['from_date']) && !is_null($filter['from_date'])) {
                $query->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '>=', $filter['from_date']);
            } elseif (isset($filter['to_date']) && !is_null($filter['to_date'])) {
                $query->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $filter['to_date']);
            }

            if(isset($filter['status'])) {
                $query->where('status', $filter['status']);
            }

            if (isset($filter['search_value']) && !is_null($filter['search_value'])) {
                $query->where('title', 'like', '%' . $filter . '%')
                ->orWhereHas('employee', function ($q) use ($filter) {
                    $q->where('employments.first_name', 'like', '%' . $filter . '%');
                    $q->orWhere('employments.last_name', 'like', '%' . $filter . '%');
                });
            }

        })->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999), ['*'], 'page', $page_number);

        return $result;
    }

    public function find($id)
    {
        return EmployeeRequest::find($id);
    }

    public function getEmployeerequest()
    {
        return EmployeeRequest::where('status', 1)->get();
    }

    public function update($id, $data)
    {
        return EmployeeRequest::find($id)->update($data);
    }

    public function save($data)
    {
        return EmployeeRequest::create($data);
    }

    public function delete($id)
    {
        return EmployeeRequest::find($id)->delete();
    }

    public function benefit()
    {
        $result = Dropdown::where('fid', '=', '11')->pluck('dropvalue', 'id');
        return $result;
    }

    public function upload($file)
    {
        $imageName = $file->getClientOriginalName();
        $fileName = date('Y-m-d-h-i-s') . '-' . preg_replace('[ ]', '-', $imageName);
        $file->move(public_path() . EmployeeRequest::FILE_PATH, $fileName);
        return $fileName;
    }

    public function getTotal($status)
    {
        $total = EmployeeRequest::where('status', '=', $status)->count();
        return $total;
    }

    public function getTotalRequest()
    {
        $total = EmployeeRequest::where('created_at', 'like', Carbon::now()->toDateString() . '%')->count();
        return $total;
    }
    public function getTotalRequestlist()
    {
        $totalrequestlist = EmployeeRequest::where('created_at', 'like', Carbon::now()->toDateString() . '%')->get();
        return $totalrequestlist;
    }

    public function getTotalRequestlistbytype($type_id)
    {
        $totalrequestlist = EmployeeRequest::where('created_at', 'like', Carbon::now()->toDateString() . '%')
            ->where('type_id', $type_id)->get();
        return $totalrequestlist;
    }

    public function getByRequestType($type, $empid)
    {
        $total = EmployeeRequest::where('type_id', '=', $type)
            ->where('employee_id', '=', $empid)
            ->get();
        return $total;
    }

    public function findAllTodayList($empid)
    {
        $total = EmployeeRequest::where('employee_id', '=', $empid)
            ->take(5)->orderBy('created_at', 'DESC')->get();
        return $total;
    }

    public function findRequestByType($empid, $requesttype)
    {
        if ($requesttype == 0) {
            $total = EmployeeRequest::where('employee_id', '=', $empid)
                ->take(5)->orderBy('created_at', 'DESC')->get();
        } else {
            $total = EmployeeRequest::where('employee_id', '=', $empid)
                ->where('type_id', '=', $requesttype)
                ->take(5)->orderBy('created_at', 'DESC')->get();
        }

        return $total;
    }

    public function advanceSearch($limit = 10, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    { 
        $result = EmployeeRequest::when(array_keys($filter), function ($query) use ($filter) {

            if (isset($filter['status'])) {
                $query->where('status', $filter['status']);
            }

            if (isset($filter['approved_by']) && !empty($filter['approved_by'])) {
                $query->where('approved_by', $filter['approved_by']);
            }

            if (isset($filter['first_approval_id']) && !is_null($filter['first_approval_id']) && isset($filter['second_approval_id']) && !is_null($filter['second_approval_id'])) {
                $query->where(function ($q) use ($filter) {
                    $q->where('first_approval_id', $filter['first_approval_id']);
                    $q->orWhere('forwarded_to', $filter['second_approval_id']);
                });
            } elseif (isset($filter['first_approval_id']) && !is_null($filter['first_approval_id'])) {
                $query->where('first_approval_id', $filter['first_approval_id']);
            } elseif (isset($filter['second_approval_id']) && !is_null($filter['second_approval_id'])) {
                $query->where('forwarded_to', $filter['second_approval_id']);
            }

          
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999)); 
        return $result;
    }


}
