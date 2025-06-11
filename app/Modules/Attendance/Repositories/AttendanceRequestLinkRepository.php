<?php

namespace App\Modules\Attendance\Repositories;

use App\Modules\Attendance\Entities\AttendanceRequestLink;

class AttendanceRequestLinkRepository implements AttendanceRequestLinkInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = AttendanceRequestLink::when(array_keys($filter, true), function ($query) use ($filter) {
            // if (isset($filter['organization_id'])) {
            //     $query->where('org_id', $filter['organization_id']);
            // }
            // if (isset($filter['employee_id'])) {
            //     $query->where('emp_id', $filter['employee_id']);
            // }
            // if (isset($filter['date_range'])) {
            //     $filterDates = explode(' - ', $filter['date_range']);
            //     $query->where('date', '>=', $filterDates[0]);
            //     $query->where('date', '<=', $filterDates[1]);
            // }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));

        return $result;
    }

    public function find($id)
    {
        return AttendanceRequestLink::find($id);
    }

    public function save($data)
    {
        return AttendanceRequestLink::create($data);
    }

    public function update($id, $data)
    {
        return AttendanceRequestLink::find($id)->update($data);
    }

    public function delete($id)
    {
        return AttendanceRequestLink::find($id)->delete();
    }

    public function findOne($filter)
    {
        $result = AttendanceRequestLink::where($filter)->first();
        return $result;
    }
}
