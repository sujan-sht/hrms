<?php

namespace App\Modules\LeaveYearSetup\Repositories;

use App\Modules\LeaveYearSetup\Entities\LeaveYearSetup;

class LeaveYearSetupRepository implements LeaveYearSetupInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = LeaveYearSetup::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['start_date']) && !empty($filter['start_date'])) {
                $query->whereDate('start_date', '>=', $filter['start_date']);
            }
            if (isset($filter['end_date']) && !empty($filter['end_date'])) {
                $query->whereDate('end_date', '<=', $filter['end_date']);
            }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));

        return $result;
    }

    public function findOne($id)
    {
        return LeaveYearSetup::find($id);
    }
    public function find()
    {
        return LeaveYearSetup::pluck('leave_year', 'leave_year');
    }
    public function findEnglishLeaveYear()
    {
        return LeaveYearSetup::pluck('leave_year_english', 'leave_year_english');
    }

    public function create($data)
    {
        return LeaveYearSetup::create($data);
    }

    public function update($id, $data)
    {
        $result = $this->findOne($id);
        return $result->update($data);
    }

    public function delete($id)
    {
        return LeaveYearSetup::destroy($id);
    }

    public function getCurrentLeaveYear()
    {
        return LeaveYearSetup::where('status', 1)->pluck('leave_year', 'id');
    }
    public function getLeaveYear()
    {
        return LeaveYearSetup::where('status', 1)->first();
    }

    public function getLeaveYearList()
    {
        // $leaveYearList = LeaveYearSetup::latest()->pluck('leave_year', 'id');
        $leaveYearList = LeaveYearSetup::latest()->get()->mapWithKeys(function ($leaveYear) {
            $value = $leaveYear->calender_type === 'nep'
                ? $leaveYear->leave_year
                : $leaveYear->leave_year_english;

            return [$leaveYear->id => $value];
        });


        return $leaveYearList;
    }

    public function getActiveLeaveYearList()
    {
        // return LeaveYearSetup::where('status',1)->latest()->pluck('leave_year', 'id');
        $leaveYearList = LeaveYearSetup::where('status',1)->latest()->get()->mapWithKeys(function ($leaveYear) {
            $value = $leaveYear->calender_type === 'nep'
                ? $leaveYear->leave_year
                : $leaveYear->leave_year_english;

            return [$leaveYear->id => $value];
        });


        return $leaveYearList;

    }
}
