<?php

namespace App\Modules\Worklog\Repositories;

use App\Modules\Worklog\Entities\Worklog;

class WorklogRepository implements WorklogInterface
{

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $accessibleEmployees = getEmployeeIds(); //Helper Function

        $query = Worklog::query();

        $query->whereHas('workLogDetail', function ($q) use ($accessibleEmployees) {
            $q->whereIn('employee_id', $accessibleEmployees);
        });
        
        if(setting('calendar_type') == 'BS'){
            if (isset($filter['from_nep_date']) && !empty($filter['from_nep_date'])) {
               $query->where('date', '>=', date_converter()->nep_to_eng_convert($filter['from_nep_date']));
            }
            if (isset($filter['to_nep_date']) && !empty($filter['to_nep_date'])) {
               $query->where('date', '<=', date_converter()->nep_to_eng_convert($filter['to_nep_date']));
            }
        }else{
            if (isset($filter['date_range']) &&  !empty($filter['date_range'])) {
                $filterDates = explode(' - ', $filter['date_range']);
                $query->where('date', '>=', $filterDates[0]);
                $query->where('date', '<=', $filterDates[1]);
            }
        }

        if (isset($filter['title']) &&  !empty($filter['title'])) {
            $query->whereHas('workLogDetail', function ($q) use ($filter) {
                $q->where('title', 'like', '%' . $filter['title'] . '%');
            });
        }

        if (isset($filter['employee_id']) && !empty($filter['employee_id'])) {
            $query->whereHas('workLogDetail', function ($q) use ($filter) {
                $q->where('employee_id', $filter['employee_id']);
            });
            // $query->where('employee_id',$filter['employee_id']);
        }

        // if(isset($filter['project_id']) && !empty($filter['project_id']))
        // {
        //     $query->where('project_id',$filter['project_id']);
        // }

        if (isset($filter['status']) && !empty($filter['status'])) {
            $query->whereHas('workLogDetail', function ($q) use ($filter) {
                $q->where('status', $filter['status']);
            });
            // $query->where('status',$filter['status']);
        }

        $result = $query->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }

    public function find($id)
    {
        return Worklog::find($id);
    }

    public function getList()
    {
        $result = Worklog::pluck('title', 'id');
        return $result;
    }


    public function getStatus()
    {
        return Worklog::STATUS;
    }

    public function save($data)
    {
        return Worklog::create($data);
    }

    public function update($id, $data)
    {
        $result = Worklog::find($id);
        return $result->update($data);
    }

    public function delete($id)
    {
        return Worklog::destroy($id);
    }
}
