<?php

namespace App\Modules\PMS\Repositories;

use App\Modules\PMS\Entities\Kpi;

class KPIRepository implements KPIInterface
{
    public function getList()
    {
        $query = Kpi::query();
        if (auth()->user()->user_type == 'division_hr' || auth()->user()->user_type == 'supervisor') {
            $query->whereHas('kraInfo', function ($qry) {
                $qry->where('division_id', optional(auth()->user()->userEmployer)->organization_id);
            });
        }
        $result = $query->pluck('title', 'id');
        return $result;
    }

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = Kpi::when(true, function ($query) use ($filter) {
            if (isset($filter['kra_id']) && !empty($filter['kra_id'])) {
                $query->where('kra_id', $filter['kra_id']);
            }
            if (auth()->user()->user_type == 'division_hr' || auth()->user()->user_type == 'supervisor') {
                $query->whereHas('kraInfo', function ($qry) {
                    $qry->where('division_id', optional(auth()->user()->userEmployer)->organization_id);
                });
            }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));
        return $result;
    }

    public function findOne($id)
    {
        return Kpi::find($id);
    }

    public function create($data)
    {
        return Kpi::create($data);
    }

    public function update($id, $data)
    {
        $result = $this->findOne($id);
        return $result->update($data);
    }

    public function delete($id)
    {
        return Kpi::destroy($id);
    }

    public function getKPIs($kra_id)
    {
        return Kpi::where('kra_id', $kra_id)->pluck('title', 'id');
    }
}
