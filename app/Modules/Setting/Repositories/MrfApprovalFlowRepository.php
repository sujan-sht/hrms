<?php
namespace App\Modules\Setting\Repositories;

use App\Modules\Setting\Entities\MrfApprovalFlow;

class MrfApprovalFlowRepository implements MrfApprovalFlowInterface
{

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = MrfApprovalFlow::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['organization_id']) && !empty($filter['organization_id'])) {
                $query->where('organization_id', $filter['organization_id']);
            }
        })
        ->orderBy($sort['by'], $sort['sort'])
        ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));

        return $result;
    }

    public function findOne($id) {
        return MrfApprovalFlow::find($id);
    }

    public function create($data) {
        return MrfApprovalFlow::create($data);
    }

    public function update($id,$data) {
        $result = $this->findOne($id);
        return $result->update($data);
    }
  
    public function delete($id) {
        $result = $this->findOne($id);
        return $result->delete();
    }
}
