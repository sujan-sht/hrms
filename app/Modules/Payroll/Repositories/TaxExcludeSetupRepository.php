<?php

namespace App\Modules\Payroll\Repositories;

use App\Modules\Payroll\Entities\TaxExcludeSetup;

class TaxExcludeSetupRepository implements TaxExcludeSetupInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'order', 'sort' => 'ASC'], $status = [0, 1])
    {
        $result = TaxExcludeSetup::query();
        if(isset($filter['organizationId'])) {
            $result->where('organization_id', $filter['organizationId']);
        }
        $result = $result->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));

        return $result;
    }

    public function find($id)
    {
        return TaxExcludeSetup::find($id);
    }

    public function getList($params=[])
    {
        if(isset($params['organizationId'])) {
            $result = TaxExcludeSetup::where('organization_id', $params['organizationId'])->where('status',11)->orderBy('order','asc')->pluck('title', 'id');
        } else {
            $result = TaxExcludeSetup::where('status',11)->orderBy('order','asc')->pluck('title', 'id');
        }

        return $result;
    }

    public function save($data)
    {
        return TaxExcludeSetup::create($data);
    }
    public function update($id, $data)
    {
        $result = TaxExcludeSetup::find($id);
        
        return $result->update($data);
    }

    public function delete($id)
    {
        return TaxExcludeSetup::destroy($id);
    }
}
