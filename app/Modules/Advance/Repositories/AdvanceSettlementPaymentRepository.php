<?php

namespace App\Modules\Advance\Repositories;

use App\Modules\Advance\Entities\AdvanceSettlementPayment;

class AdvanceSettlementPaymentRepository implements AdvanceSettlementPaymentInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'ASC'])
    {
        $result = AdvanceSettlementPayment::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['advance']) && !empty($filter['advance'])) {
                $query->where('advance_id', $filter['advance']);
            }
            if (isset($filter['status']) && !empty($filter['status'])) {
                $query->where('status', $filter['status']);
            }
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
        return AdvanceSettlementPayment::find($id);
    }

    public function create($data)
    {
        $result = AdvanceSettlementPayment::create($data);

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
        $result = AdvanceSettlementPayment::destroy($id);
        
        return $result;
    }

}
