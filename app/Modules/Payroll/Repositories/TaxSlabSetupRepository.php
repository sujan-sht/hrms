<?php

namespace App\Modules\Payroll\Repositories;

use App\Modules\Payroll\Entities\TaxSlab;

class TaxSlabSetupRepository implements TaxSlabSetupInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1])
    {
        $result = TaxSlab::query();
        if (auth()->user()->user_type != 'admin' && auth()->user()->user_type != 'super_admin') {
            $result->where('created_by', auth()->user()->id);
        }

        if (isset($filter['organization_id'])) {
            $result->where('organization_id', $filter['organization_id']);
        }

        $result = $result->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }

    public function find($id)
    {
        return TaxSlab::find($id);
    }

    public function getList()
    {
        $result = TaxSlab::pluck('title', 'id');
        return $result;
    }

    public function save($data)
    {
        return TaxSlab::create($data);
    }

    public function update($id, $data)
    {
        $result = TaxSlab::find($id);
        return $result->update($data);
    }

    public function updateOrCreate($data)
    {
        return TaxSlab::updateOrCreate([
            // 'organization_id' => $data['organization_id'],
            'order'=>$data['order'],
            'type' => $data['type'],
        ], $data);
    }

    public function delete($id)
    {
        return TaxSlab::destroy($id);
    }

    public function getTaxSlabFromOrganization()
    {
        $result = TaxSlab::query();
        // $result->where('organization_id', $org_id);
        $result = $result->get();
        return $result;
    }
}
