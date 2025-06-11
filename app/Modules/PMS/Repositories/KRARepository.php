<?php

namespace App\Modules\PMS\Repositories;

use App\Modules\PMS\Entities\Kra;

class KRARepository implements KRAInterface
{
    public function getList()
    {
        $query = Kra::query();
        if (auth()->user()->user_type == 'division_hr' || auth()->user()->user_type == 'supervisor') {
            $query->where('division_id', optional(auth()->user()->userEmployer)->organization_id);
        }
        $result = $query->pluck('title', 'id');
        return $result;
    }

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        if (auth()->user()->user_type == 'division_hr' || auth()->user()->user_type == 'supervisor') {
            $filter['division_id'] = optional(auth()->user()->userEmployer)->organization_id;
        }
        $result = Kra::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['department_id']) && !empty($filter['department_id'])) {
                $query->where('department_id', $filter['department_id']);
            }
            if (isset($filter['division_id']) && !empty($filter['division_id'])) {
                $query->where('division_id', $filter['division_id']);
            }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));

        return $result;
    }

    public function findOne($id)
    {
        return Kra::find($id);
    }

    public function create($data)
    {
        return Kra::create($data);
    }

    public function update($id, $data)
    {
        $result = $this->findOne($id);
        return $result->update($data);
    }

    public function delete($id)
    {
        return Kra::destroy($id);
    }
}
