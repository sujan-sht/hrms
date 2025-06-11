<?php

namespace App\Modules\NewShift\Repositories;

use App\Modules\NewShift\Entities\NewShift;
use App\Modules\NewShift\Entities\NewShiftRequest;
use App\Modules\User\Entities\User;

/**
 * ShiftTypeRepository
 */
class ShiftRepository implements ShiftInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = NewShift::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['title']) && !is_null($filter['title'])) {
                $query->where('title', 'like', '%' . $filter['title'] . '%');
            }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));

        return $result;
    }

    public function save($data)
    {
        return NewShift::create($data);
    }

    public function getIdByTitle($title)
    {
        return NewShift::whereTitle($title)->first()->id;
    }

    public function find($id)
    {
        return NewShift::find($id);
    }

    public function update($id, $data)
    {
        return NewShift::find($id)->update($data);
    }

    public function delete($id)
    {
        return NewShift::find($id)->delete();
    }


    public function getNewShiftByOrganization($org_id)
    {
        return NewShift::where('org_id', $org_id)->get();
    }

    public function getList()
    {
        // $shifts = Shift::pluck('title', 'id');
        // return $shifts;

        // ranjan
        $shifts = NewShift::all();
        $returnArray = [];
        foreach ($shifts as $key => $value) {
            $returnArray[$value->id] = $value->title . ' (' . $value->start_time . '-' . $value->end_time . ')';

            if ($value->title == 'Custom') {
                $returnArray[$value->id] = $value->title . ': ' . $value->custom_title . ' (' . $value->start_time . '-' . $value->end_time . ')';
            }
        }
        return $returnArray;
    }

    //Requests
    public function findAllRequests($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $newShiftRequestModel  = NewShiftRequest::query();
        $newShiftRequestModel->when(true, function ($query) use ($filter) {
            // if (isset($filter['organization_id']) && $filter['organization_id'] != '') {
            //     $query->whereHas('employee', function ($q) use ($filter) {
            //         $q->where('organization_id', $filter['organization_id']);
            //     });
            // }

            // if (isset($filter['employee_id']) && $filter['employee_id'] != '') {
            //     $query = $query->where('employee_id', $filter['employee_id']);
            // }

            // if (isset($filter['date_range'])) {
            //     $filterDates = explode(' - ', $filter['date_range']);
            //     $query->where('date', '>=', $filterDates[0]);
            //     $query->where('date', '<=', $filterDates[1]);
            // }

            // if (isset($filter['from_date_nep']) && !empty($filter['from_date_nep'])) {
            //     $query->where('nepali_date', '>=', $filter['from_date_nep']);
            // }

            // if (isset($filter['to_date_nep']) && !empty($filter['to_date_nep'])) {
            //     $query->where('nepali_date', '<=', $filter['to_date_nep']);
            // }

            // if (isset($filter['status']) && $filter['status'] != '') {
            //     $query = $query->where('status', $filter['status']);
            // }

            if (auth()->user()->user_type == 'employee' || auth()->user()->user_type == 'supervisor') {
                $query->where('employee_id', auth()->user()->emp_id);
            } elseif (auth()->user()->user_type == 'division_hr') {
                $query->whereHas('employee', function ($q) {
                    $q->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
                });
            } elseif (auth()->user()->user_type == 'hr' || auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'super_admin') {
                $query->where('employee_id', '!=', null);
            }
        });

        $result = $newShiftRequestModel->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : config('attendance.export-length'));
        return $result;
    }

    public function findRequest($id)
    {
        return NewShiftRequest::find($id);
    }

    public function saveRequest($data)
    {
        $model = NewShiftRequest::create($data);
        return $model;
    }

    public function updateRequest($id, $data)
    {
        return NewShiftRequest::find($id)->update($data);
    }

    public function deleteRequest($id)
    {
        return NewShiftRequest::find($id)->delete();
    } 

}
