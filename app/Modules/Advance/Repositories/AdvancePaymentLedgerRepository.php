<?php

namespace App\Modules\Advance\Repositories;

use App\Modules\Advance\Entities\Advance;
use App\Modules\Advance\Entities\AdvanceSettlement;
use App\Modules\Advance\Entities\AdvancePaymentLedger;

class AdvancePaymentLedgerRepository implements AdvancePaymentLedgerInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'ASC'])
    {
        $result = AdvancePaymentLedger::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['employee']) && !empty($filter['employee'])) {
                $query->whereHas('advanceModel', function ($qry) use ($filter) {
                    $qry->where('employee_id', $filter['employee']);
                });
            }
        })
        ->orderBy($sort['by'], $sort['sort'])
        ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));

        return $result;
    }

    public function findOne($id)
    {
        return AdvancePaymentLedger::find($id);
    }

    public function create($data)
    {
        $result = AdvancePaymentLedger::create($data);

        return $result;
    }


    public function update($id, $data)
    {
        $model = $this->findOne($id);

        $result = $model->update($data);

        return $result;
    }


    public function delete($id)
    {
        $result = AdvancePaymentLedger::destroy($id);
        
        return $result;
    }

}
