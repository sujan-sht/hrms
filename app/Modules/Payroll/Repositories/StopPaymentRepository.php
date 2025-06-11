<?php

namespace App\Modules\Payroll\Repositories;

use App\Modules\Payroll\Entities\StopPayment;

class StopPaymentRepository implements StopPaymentInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'ASC'], $status = [0, 1])
    {
        $result = StopPayment::query();
        if (isset($filter['organizationId'])) {
            $result->where('organization_id', $filter['organizationId']);
        }
        $result = $result->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }

    public function find($id)
    {
        return StopPayment::find($id);
    }

    public function getList($params = [])
    {
        if (isset($params['organizationId'])) {
            $result = StopPayment::where('organization_id', $params['organizationId'])->pluck('title', 'id');
        } else {
            $result = StopPayment::pluck('title', 'id');
        }

        return $result;
    }

    public function save($data)
    {
        return StopPayment::create($data);
    }

    public function update($id, $data)
    {
        $result = StopPayment::find($id);
        return $result->update($data);
    }

    public function delete($id)
    {
        return StopPayment::destroy($id);
    }
    public function getStopPayment($calendar_type, $employee_id, $start_date, $end_date)
    {
        // dd($calendar_type, $employee_id, $start_date, $end_date);
        // dd($start_date);
        if ($calendar_type == 'nep') {
            $result = StopPayment::where('employee_id', $employee_id)
                // ->where(function ($query) use ($start_date, $end_date) {
                //     $query->wherebetween('nep_from_date', [$start_date, $end_date])->where()
                //         ->orwherebetween('nep_to_date', [$start_date, $end_date]);
                // })
                ->where('nep_from_date','<=',$start_date)->where('nep_to_date','>=', $start_date)
                ->first();
            return $result;
        } else {
            $result = StopPayment::where('employee_id', $employee_id)
                // ->where(function ($query) use ($start_date, $end_date) {
                //     $query->whereBetween('from_date', [$start_date, $end_date])
                //         ->orWhereBetween('to_date', [$start_date, $end_date]);
                // })
                ->where('from_date','<=',$start_date)->where('to_date','>=', $start_date)
                ->first();
            return $result;
        }
    }
}
