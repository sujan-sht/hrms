<?php

namespace App\Modules\Payroll\Repositories;

use App\Modules\Payroll\Entities\Payroll;
use App\Modules\Payroll\Entities\HoldPayment;
use Illuminate\Http\Request;

class HoldPaymentRepository implements HoldPaymentInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'ASC'], $status = [0, 1])
    {
        $result = HoldPayment::query();
        if (isset($filter['organization'])) {
            $result->where('organization_id', $filter['organization']);
        }
        if (isset($filter['year']) && $filter['year']) {
            $result->where('year', $filter['year']);
        }
        
        if (isset($filter['month']) && $filter['month']) {
            $result->where('month', $filter['month']);
        }

        if (isset($filter['employee']) && $filter['employee']) {
            $result->where('employee_id', $filter['employee']);
        }
       
        $result = $result->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }

    public function find($id)
    {
        return HoldPayment::find($id);
    }

    public function getList($params = [])
    {
        if (isset($params['organization'])) {
            $result = HoldPayment::where('organization', $params['organization'])->pluck('title', 'id');
        } else {
            $result = HoldPayment::pluck('title', 'id');
        }

        return $result;
    }

    public function save($data)
    {
        return HoldPayment::create($data);
    }

    public function update($id, $data)
    {
        $result = HoldPayment::find($id);
        return $result->update($data);
    }
    public function updateStatus($data)
    {
        $result = HoldPayment::where('year', $data['year'])->where('month', $data['month'])->where('organization_id', $data['organization_id'])->where('employee_id', $data['employee_id'])->first();
        // dd($result);
        return $result->update($data);
    }
    public function getStatus()
    {
        return HoldPayment::STATUS;
    }
    public function delete($id)
    {
        return HoldPayment::destroy($id);
    }
    public function getHoldPayment($year, $month, $organizationId)
    {
        return HoldPayment::where('year', $year)->where('month', $month)->where('organization_id', $organizationId)->get();
    }

    public function getHoldPaymentEmployee($year, $month, $organizationId)
    {
        return HoldPayment::where('year', $year)->where('month', $month)->where('organization_id', $organizationId)->pluck('employee_id')->toArray();
    }

    public function getHoldPaymentEmployeeWithStatus($year, $month, $organizationId)
    {
        return HoldPayment::where('year', $year)->where('month', $month)->where('organization_id', $organizationId)->where('status', 1)->pluck('employee_id')->toArray();
    }

    public function getHoldPaymentEmployeeNameList($year, $month, $organizationId)
    {
        $data = [];
        $holdPayments = HoldPayment::where('year', $year)->where('month', $month)->where('status', 1)->where('organization_id', $organizationId)
            ->get();
        if ($holdPayments->count() > 0) {
            foreach ($holdPayments as $holdPayment) {
                $data[$holdPayment->employee_id] = optional($holdPayment->employeeModel)->full_name;
            }
        }

        return $data;
    }

    public function getAllHoldPaymentByEmployee($id)
    {
        return HoldPayment::where('employee_id', $id)->where('status', 1)->get();
    }

    public function getFinalizedPayrollMonth(Request $request)
    {
        $calenderType=$request->data['calender_type'];
        $year=$request->data['year'];
        $finalizedPayrollArray = [];
        Payroll::whereHas('payrollEmployee', function ($query) {
            $query->where('status', '2');
        })
        ->where('organization_id',$request->data['organization_id'])
            ->get()
            ->map(function ($item) use (&$finalizedPayrollArray) {
                return $finalizedPayrollArray[$item->calendar_type][$item->year][] = $item->month;
            });
        if(isset($finalizedPayrollArray) && isset($finalizedPayrollArray[$calenderType][$year]) && count($finalizedPayrollArray)>0){
            $finalizedPayrollArray=$finalizedPayrollArray[$calenderType][$year];
        }else{
            $finalizedPayrollArray=[];
        }
        return $finalizedPayrollArray;  
    }
}
