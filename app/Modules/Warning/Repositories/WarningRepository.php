<?php

namespace App\Modules\Warning\Repositories;

use App\Modules\Warning\Entities\Warning;

class WarningRepository implements WarningInterface
{
    public function getList($filter = [])
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr' || $authUser->user_type == 'employee') {
            $filter['organization_id'] = optional($authUser->userEmployer)->organization_id;
        }

        $models = Warning::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['except_id']) && !empty($filter['except_id'])) {
                $query->where('id', '!=', $filter['except_id']);
            }
            if (isset($filter['organization_id']) && !empty($filter['organization_id'])) {
                $query->where('id', $filter['organization_id']);
            }
        })->pluck('name', 'id');

        return $models;
    }

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        if(isset($filter['from_date'])){
            if (setting('calendar_type') == 'BS'){
                $filter['from_date']=date_converter()->nep_to_eng_convert($filter['from_date']);
            }
        }
        if(isset($filter['to_date'])){
            if (setting('calendar_type') == 'BS'){
                $filter['to_date']=date_converter()->nep_to_eng_convert($filter['to_date']);
            }
        }

        // $filter['date']
        
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr' || $authUser->user_type == 'supervisor' || $authUser->user_type == 'employee') {
            $filter['organization_id'] = optional($authUser->userEmployer)->organization_id;
        }
        if($authUser->user_type == 'employee'){
            $filter['employee_id']=[$authUser->emp_id];
        }
        $result = Warning::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['organization_id']) && !empty($filter['organization_id'])) {
                $query->where('organization_id', $filter['organization_id']);
            }
            if (isset($filter['date_range'])) {
                $filterDates = explode(' - ', $filter['date_range']);
                $query->where('date', '>=', $filterDates[0]);
                $query->where('date', '<=', $filterDates[1]);
            }
            if (isset($filter['from_date']) && !empty($filter['from_date'])) {
                $query->where('date', '>=', $filter['from_date']);
            }

            if (isset($filter['to_date']) && !empty($filter['to_date'])) {
                $query->where('date', '<=', $filter['to_date']);
            }
            if(isset($filter['employee_id']) && !empty($filter['employee_id']))
            {
                foreach ($filter['employee_id'] as $id) {
                    $query->orWhereJsonContains('employee_id', $id);
                }
                
            }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));
        return $result;
    }

    public function findOne($id)
    {
        return Warning::find($id);
    }

    public function create($data)
    {
        return Warning::create($data);
    }

    public function update($id, $data)
    {
        $result = $this->findOne($id);

        return $result->update($data);
    }

    public function delete($id)
    {
        return Warning::destroy($id);
    }

    
}
