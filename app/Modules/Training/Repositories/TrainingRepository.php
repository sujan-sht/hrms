<?php

namespace App\Modules\Training\Repositories;

use App\Modules\Training\Entities\Training;

class TrainingRepository implements TrainingInterface
{
    public function getList()
    {
        return Training::pluck('title', 'id');
    }

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        if(auth()->user()->user_type == 'division_hr') {
            $filter['organization'] = optional(auth()->user()->userEmployer)->organization_id;
        }
        $result = Training::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['organization'])) {
                $query->where('division_id', $filter['organization']);
            }
            if (isset($filter['fiscal_year_id']) && !empty($filter['fiscal_year_id'])) {
                $query->where('fiscal_year_id', $filter['fiscal_year_id']);
            }
            if (isset($filter['division_id']) && !empty($filter['division_id'])) {
                $query->where('division_id', $filter['division_id']);
            }
            if (isset($filter['type']) && !empty($filter['type'])) {
                $query->where('type', $filter['type']);
            }
            if (isset($filter['location']) && !empty($filter['location'])) {
                $query->where('location', $filter['location']);
            }
            if (isset($filter['facilitator']) && !empty($filter['facilitator'])) {
                $query->where('facilitator', $filter['facilitator']);
            }
            if (isset($filter['month']) && !empty($filter['month'])) {
                $query->where('month', $filter['month']);
            }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));

        return $result;
    }

    public function findOne($id)
    {
        return Training::find($id);
    }

    public function create($data)
    {
        return Training::create($data);
    }

    public function update($id, $data)
    {
        $result = $this->findOne($id);
        return $result->update($data);
    }

    public function delete($id)
    {
        return Training::destroy($id);
    }

    public function countFacilitation()
    {
        $result['internal'] = Training::where('facilitator', 'internal')->count();
        $result['external'] = Training::where('facilitator', 'external')->count();
        $result['grand_total'] = Training::count();
        return $result;
    }

    public function countLocation()
    {
        $result['physical'] = Training::where('location', 'physical')->count();
        $result['virtual'] = Training::where('location', 'virtual')->count();
        $result['grand_total'] = Training::count();
        return $result;
    }

    public function countType()
    {
        $result['behavioural'] = Training::where('type', 'behavioural')->count();
        $result['functional'] = Training::where('type', 'functional')->count();
        $result['grand_total'] = Training::count();
        return $result;
    }

    public function no_of_mandays_month_and_division_wise()
    {
        $result = Training::get();
        if(auth()->user()->user_type == 'division_hr') {
            // $filter['organization'] = optional(auth()->user()->userEmployer)->organization_id;
            $result = Training::where('division_id',optional(auth()->user()->userEmployer)->organization_id)->get();
        }
        // return Training::get();
        return $result;
    }
}
