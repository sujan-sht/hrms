<?php

namespace App\Modules\ApprovalFlow\Repositories;

use App\Modules\ApprovalFlow\Entities\ApprovalFlow;

class ApprovalFlowRepository implements ApprovalFlowInterface
{
    public function getList()
    {
        return ApprovalFlow::pluck('title', 'id');
    }

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = ApprovalFlow::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['department_id']) && !empty($filter['department_id'])) {
                $query->where('department_id', $filter['department_id']);
            }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));

        return $result;
    }

    public function findOne($id)
    {
        return ApprovalFlow::find($id);
    }

    public function create($data)
    {
        return ApprovalFlow::create($data);
    }

    public function update($id, $data)
    {
        $result = $this->findOne($id);
        return $result->update($data);
    }

    public function delete($id)
    {
        return ApprovalFlow::destroy($id);
    }

    public function fetchApprovals($department_id)
    {
        return ApprovalFlow::where('department_id', $department_id)->first();
    }
}
