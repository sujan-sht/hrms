<?php

namespace App\Modules\Payroll\Repositories;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Payroll\Entities\EmployeeBonusSetup;
use App\Modules\Payroll\Entities\EmployeeSetup;
use App\Modules\Payroll\Entities\EmployeeTaxExcludeSetup;
use App\Modules\Payroll\Entities\GrossSalarySetup;
use Illuminate\Support\Facades\DB;

class EmployeeSetupRepository implements EmployeeSetupInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1])
    {
        $result = EmployeeSetup::query();
        if (auth()->user()->user_type != 'admin' && auth()->user()->user_type != 'super_admin') {
            $result->where('created_by', auth()->user()->id);
        }

        $result = $result->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }

    public function find($id)
    {
        return EmployeeSetup::find($id);
    }
    public function findOne($filter = [])
    {
        $result = EmployeeSetup::when(array_keys($filter, true), function ($query) use ($filter) {

            if (isset($filter['reference']) && !empty($filter['reference'])) {
                $query->where('reference', $filter['reference']);
            }
            if (isset($filter['reference_id']) && !empty($filter['reference_id'])) {
                $query->where('reference_id', $filter['reference_id']);
            }
            if (isset($filter['employee_id']) && !empty($filter['employee_id'])) {
                $query->where('employee_id', $filter['employee_id']);
            }

           
        })
            ->first();

        return $result;
    }

    public function getList()
    {
        $result = EmployeeSetup::pluck('title', 'id');
        return $result;
    }

    public function save($data)
    {
        return EmployeeSetup::create($data);
    }

    public function update($id, $data)
    {
        $result = EmployeeSetup::find($id);
        return $result->update($data);
    }

    public function findEmployeeBonus($filter = []){
        $result = EmployeeBonusSetup::when(array_keys($filter, true), function ($query) use ($filter) {

            if (isset($filter['organization_id']) && !empty($filter['organization_id'])) {
                $query->where('organization_id', $filter['organization_id']);
            }
            if (isset($filter['employee_id']) && !empty($filter['employee_id'])) {
                $query->where('employee_id', $filter['employee_id']);
            }
            if (isset($filter['bonus_setup_id']) && !empty($filter['bonus_setup_id'])) {
                $query->where('bonus_setup_id', $filter['bonus_setup_id']);
            }

           
        })
            ->first();

        return $result;
    }
    public function findEmployeeTaxExclude($filter = []){
        $result = EmployeeTaxExcludeSetup::when(array_keys($filter, true), function ($query) use ($filter) {

            if (isset($filter['organization_id']) && !empty($filter['organization_id'])) {
                $query->where('organization_id', $filter['organization_id']);
            }
            if (isset($filter['employee_id']) && !empty($filter['employee_id'])) {
                $query->where('employee_id', $filter['employee_id']);
            }
            if (isset($filter['tax_exclude_setup_id']) && !empty($filter['tax_exclude_setup_id'])) {
                $query->where('tax_exclude_setup_id', $filter['tax_exclude_setup_id']);
            }

           
        })
            ->first();

        return $result;
    }
    
    public function updateOrCreate($data)
    {
        EmployeeSetup::updateOrCreate(
            [
                'employee_id' =>  $data['employee_id'],
                'reference' => $data['reference'],
                'reference_id' => $data['reference_id'],
            ],
            $data
        );
    }

    public function updateOrCreateBonus($data){
        EmployeeBonusSetup::updateOrCreate(
            [
                'organization_id' => $data['organization_id'],
                'employee_id' =>  $data['employee_id'],
                'bonus_setup_id' => $data['bonus_setup_id'],
            ],
            $data
        );
    }

    public function updateOrCreateTaxExclude($data){
        EmployeeTaxExcludeSetup::updateOrCreate(
            [
                'organization_id' => $data['organization_id'],
                'employee_id' =>  $data['employee_id'],
                'tax_exclude_setup_id' => $data['tax_exclude_setup_id'],
            ],
            $data
        );
    }

    public function findAllGrosssalary($limit = null, $filter = [], $sort = ['by' => 'employee_id', 'sort' => 'asc'])
    {
        // dd($filter);
        $result = GrossSalarySetup::when(array_keys($filter, true), function ($query) use ($filter) {

            if (isset($filter['organization_id'])) {
                $query->where('organization_id', $filter['organization_id']);
            }
            if (isset($filter['employee_id'])) {
                $query->where('employee_id', $filter['employee_id']);
            }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }
   
    public function updateOrCreateGrossSalary($data)
    {
        GrossSalarySetup::updateOrCreate(
            [
                'organization_id' => $data['organization_id'],
                'employee_id' =>  $data['employee_id'],
            ],
            $data
        );

    }

    public function updateGrosssalary($id, $data)
    {
        // dd($id);
        $result = GrossSalarySetup::where('employee_id',$id)->first();
        return $result->update($data);
    }
    public function delete($id)
    {
        return EmployeeSetup::destroy($id);
    }

    // public function getActiveDeductionList()
    // {
    //     return  $query =  EmployeeSetup::
    //     with(['deduction' => function ($query) {
    //         $query->select('id','title', 'status');
    //         $query->where('status',11);

    //     }])
    //     ->select('*')
    //     ->where('reference', 'deduction')
    //     ->get()
    //     ->groupBy('employee_id');
    //     // $query->whereHas('deduction', function ($q) {
    //     //     // $q->where('status',11);

    //     // });
    //     $result = $query->get();
    //     $result = $query->groupBy('employee_id');
    //     return $result;
    // }

}
