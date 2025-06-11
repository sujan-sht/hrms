<?php

namespace App\Modules\Payroll\Repositories;

use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Payroll\Entities\Bonus;
use App\Modules\Payroll\Entities\BonusEmployee;
use App\Modules\Payroll\Entities\BonusIncome;
use App\Modules\Payroll\Entities\BonusSetup;
use App\Modules\Payroll\Entities\DeductionSetup;
use App\Modules\Payroll\Entities\EmployeeBonusSetup;
use Illuminate\Support\Facades\DB;
use App\Modules\Payroll\Entities\EmployeeSetup;
use App\Modules\Payroll\Entities\PayrollEmployee;
use App\Modules\Payroll\Entities\TaxSlab;

class BonusRepository implements BonusInterface
{
    public function __construct(

    ) {
        
    }
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $authUser = auth()->user();
        if($authUser->user_type == 'division_hr') {
            $filter['organization'] = optional($authUser->userEmployer)->organization_id;
        }
        $result = Bonus::query();
        if (isset($filter['organization'])) {
            $result->where('organization_id', $filter['organization']);
        }
        if (isset($filter['year'])) {
            $result->where('year', $filter['year']);
        }
        if (isset($filter['month'])) {
            $result->where('month', $filter['month']);
        }
        $result = $result->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }

    public function findOne($id)
    {
        return Bonus::find($id);
    }

    public function create($data)
    {
        DB::beginTransaction();

        try {
            $bonusModel = Bonus::create($data);
            $params['organization_id'] = $bonusModel->organization_id;
            $data['field'] = $bonusModel->calendar_type == 'eng' ? 'archived_date' : 'nep_archived_date';
            $employees = EmployeeBonusSetup::select('employee_id')->whereHas('employee', function ($query) use ($params, $bonusModel, $data) {
                        $query->where('organization_id', $params)->where(function ($query) use ($bonusModel,$data) {
                            $query->where('status', 1)->orWhere(function ($query) use ($bonusModel,$data) {
                                $query->whereYear($data['field'], $bonusModel->year)->whereMonth($data['field'], $bonusModel->month);
                            });
                        });
                    })->distinct()->get();
            // dd($employees);

            foreach ($employees as $employee) {
                // if($employee->employee_id == 1){
                    $bonusEmployeeData = [];
                    $bonusEmployeeData['bonus_id'] = $bonusModel->id;
                    $bonusEmployeeData['employee_id'] = $employee->employee_id;
                    $bonusEmployeeModel = BonusEmployee::create($bonusEmployeeData);

                    $totalIncome = 0;
                    $incomes = EmployeeBonusSetup::select('bonus_setup_id')->whereHas('bonusSetup', function ($query) use ($params) {
                        if (isset($params['organization_id'])) {
                            $query->where('organization_id', $params['organization_id']);
                        }
                        $query->where('status', 11);
                    })->distinct()->get()->map(function ($model) {
                        $model->sort = optional($model->bonusSetup)->order;
                        return $model;
                    })->sortBy('sort');

                    $payrollEmployee = PayrollEmployee::whereHas('payroll',function($query) use($bonusModel){
                        // $query->where('year',$bonusModel->year)->where('month',$bonusModel->month);
                    })->where('employee_id',$employee->employee_id)->where('status',2)->orderBy('id','DESC')->first();
                   
                    if($payrollEmployee){
                        $taxableSalary = $payrollEmployee->yearly_taxable_salary;
                    }
                    else{
                        $taxableSalary = 0;
                    }
                    $oneTimeBonus = EmployeeBonusSetup::whereHas('bonusSetup',function($query){
                        $query->where ('one_time_settlement',11)->where('status',11);
                    })->where('employee_id',$employee->employee_id)->sum('amount');
                    $tds = $this->calculateOneTimeTDSTax($taxableSalary,$oneTimeBonus,$employee->employee_id);
                    foreach ($incomes as $income) {
                        $employeeBonusSetupModel = EmployeeBonusSetup::where(['bonus_setup_id' => $income->bonus_setup_id, 'employee_id' => $employee->employee_id])->first();
                        if (isset($employeeBonusSetupModel)) {
                            $amount = $employeeBonusSetupModel->amount ?: 0;
                        } else {
                            $amount = 0;
                        }
                        $totalIncome += $amount;
                        $bonusIncomeData = [];
                        $bonusIncomeData['bonus_id'] = $bonusModel->id;
                        $bonusIncomeData['bonus_employee_id'] = $bonusEmployeeModel->id;
                        $bonusIncomeData['bonus_setup_id'] = $income->bonus_setup_id;
                        $bonusIncomeData['value'] = $amount;
                        BonusIncome::create($bonusIncomeData);
                    }

                    $bonusEmployeeModel->total_income = $totalIncome;
                    $bonusEmployeeModel->tds = $tds;
                    $bonusEmployeeModel->payable_salary =$totalIncome - $tds;
                    $bonusEmployeeModel->save();
                // }
                
            }        
            

            // all good
            DB::commit();
        } catch (\Exception $e) {
            throw $e;
            // something went wrong
            DB::rollBack();
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {
            $bonusEmployeeModels = BonusEmployee::where('bonus_id', $id)->get();
            foreach ($bonusEmployeeModels as $bonusEmployeeModel) {
                BonusIncome::where('bonus_employee_id', $bonusEmployeeModel->id)->delete();
                $bonusEmployeeModel->delete();
            }
            Bonus::destroy($id);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }

        return true;
    }

    public function calculateOneTimeTDSTax($taxableSalary = 0, $oneTimebonus = 0,$employeeeId){
        $tds = 0;
        $ssfFlag = false;
        $ssf1Flag = false;
        $employeeModel = Employee::with('getMaritalStatus')->where('id', $employeeeId)->first();
        $deductionSetupModel = DeductionSetup::where('short_name', 'SSF')->where('organization_id', $employeeModel->organization_id)->first();
        $ssf1Model = DeductionSetup::where('short_name', 'SSF1')->where('organization_id', $employeeModel->organization_id)->first();

        if ($deductionSetupModel) {
            $ssfId = $deductionSetupModel->id;
            $employeeSsfDeduction = EmployeeSetup::where('employee_id', $employeeeId)
                ->where('reference', 'deduction')
                ->where('reference_id', $ssfId)
                ->first();
            if ($employeeSsfDeduction && $employeeSsfDeduction->amount > 0) {
                $ssfFlag = true;
            }
        }
        if ($ssf1Model) {
            $ssf1Id = $ssf1Model->id;
            $employeeSsf1Deduction = EmployeeSetup::where('employee_id', $employeeeId)
                ->where('reference', 'deduction')
                ->where('reference_id', $ssf1Id)
                ->first();
            if ($employeeSsf1Deduction && $employeeSsf1Deduction->amount > 0) {
                $ssf1Flag = true;
            }
        }
        if (optional($employeeModel->getMaritalStatus)->dropvalue != 'Married') {
            $sstModel = TaxSlab::where('type', 'unmarried')->orderBy('order', 'ASC')->first();
            $taxSlabModels = TaxSlab::where('type', 'unmarried')->where('order', '!=', 0)->orderBy('order', 'ASC')->get();
        } else {
            $sstModel = TaxSlab::where('type', 'married')->orderBy('order', 'ASC')->first();
            $taxSlabModels = TaxSlab::where('type', 'married')->where('order', '!=', 0)->orderBy('order', 'ASC')->get();
        }

        foreach ($taxSlabModels as $taxSlabModel) {
            $taxSlabAmountArray[$taxSlabModel->order] = explode('-', $taxSlabModel->annual_income);
            $taxSlabArray[$taxSlabModel->order] = $taxSlabModel;
        }
        $yearlyTaxableAmount =  $taxableSalary;
       
        $oneTimeBonus  = $oneTimebonus;
        $oneTimeDeductionAmount=0;
        $oneTimededuction=DeductionSetup::where([
            'short_name'=>'ACIT',
            'monthly_deduction'=>10,
            'status'=>11
        ])->first();
        if($oneTimededuction){
            $deductionData=EmployeeSetup::where([
                'employee_id'=>$employeeeId,
                'organization_id'=>$employeeModel->organization_id,
                'reference'=>'deduction',
                'reference_id'=>$oneTimededuction->id,
            ])->first();
            if($deductionData){
                $oneTimeDeductionAmount=$deductionData->amount ?? 0;
            }
        }
        $taxableAmountBeforeBonus = $yearlyTaxableAmount;
        $oneTimeBonus=$oneTimeBonus-$oneTimeDeductionAmount;
        $yearlyTaxableAmount = $yearlyTaxableAmount+$oneTimeBonus;
        // if($employeeeId=='1'){
        //     dd($employeeModel,$yearlyTaxableAmount,$oneTimeDeductionAmount);
        // }
        
        if ($yearlyTaxableAmount <= $sstModel->annual_income) {
            $tds = $sstModel->tax_rate / 100 * $oneTimeBonus ;
            if ($ssfFlag == true || $ssf1Flag == true) {
                $tds = 0;
            }
        }
        elseif ($yearlyTaxableAmount > $taxSlabAmountArray[1][0] && $yearlyTaxableAmount <= $taxSlabAmountArray[1][1]) {
            // 1st slab of tds
            if($taxableAmountBeforeBonus < $sstModel->annual_income){
                $first = $sstModel->annual_income - $taxableAmountBeforeBonus;
                if ($ssfFlag == true || $ssf1Flag == true) {
                    $tds = 0;
                }
                else{
                    $tds = ($sstModel->tax_rate / 100) * $first;
                }
                
                $second = $oneTimeBonus - $first;
                $tds += ($taxSlabArray[1]->tax_rate / 100) * $second;
            }
            else{
                $tds = ($taxSlabArray[1]->tax_rate / 100) * $oneTimeBonus; // calculate amount of 2nd slab of tds
            }
        } elseif ($yearlyTaxableAmount > $taxSlabAmountArray[2][0] && $yearlyTaxableAmount <= $taxSlabAmountArray[2][1]) {
            // 2nd slab of tds
            if($taxableAmountBeforeBonus < $taxSlabAmountArray[1][1]){
                $first = $taxSlabAmountArray[1][1] - $taxableAmountBeforeBonus;
                $tds = ($taxSlabArray[1]->tax_rate / 100) * $first;
                $second = $oneTimeBonus - $first;
                $tds += ($taxSlabArray[2]->tax_rate / 100) * $second;
            }
            else{
                $tds = ($taxSlabArray[2]->tax_rate / 100) * $oneTimeBonus; // calculate amount of 2nd slab of tds
            }
        } elseif ($yearlyTaxableAmount > $taxSlabAmountArray[3][0] && $yearlyTaxableAmount <= $taxSlabAmountArray[3][1]) {
          // 3rd slab of tds
            if($taxableAmountBeforeBonus < $taxSlabAmountArray[2][1]){
                $first = $taxSlabAmountArray[2][1] - $taxableAmountBeforeBonus;
                $tds = ($taxSlabArray[2]->tax_rate / 100) * $first;
                $second = $oneTimeBonus - $first;
                $tds += ($taxSlabArray[3]->tax_rate / 100) * $second;
            }
            else{
                $tds = ($taxSlabArray[3]->tax_rate / 100) * $oneTimeBonus; // calculate amount of 3rd slab of tds
            }
            
        } elseif ($yearlyTaxableAmount > $taxSlabAmountArray[4][0] && $yearlyTaxableAmount <= $taxSlabAmountArray[4][1]) {
            // 4th slab of tds
            if($taxableAmountBeforeBonus < $taxSlabAmountArray[3][1]){
                $first = $taxSlabAmountArray[3][1] - $taxableAmountBeforeBonus;
                $tds = ($taxSlabArray[3]->tax_rate / 100) * $first;
                $second = $oneTimeBonus - $first;
                $tds += ($taxSlabArray[4]->tax_rate / 100) * $second;
            }
            else{
                $tds += ($taxSlabArray[4]->tax_rate / 100) * $oneTimeBonus; // calculate amount of 4th slab of tds
            }
          
        } elseif ($yearlyTaxableAmount > $taxSlabAmountArray[5][0]) {
            if($taxableAmountBeforeBonus < $taxSlabAmountArray[4][1]){
                $first = $taxSlabAmountArray[4][1] - $taxableAmountBeforeBonus;
                $tds = ($taxSlabArray[4]->tax_rate / 100) * $first;
                $second = $oneTimeBonus - $first;
                $tds += ($taxSlabArray[5]->tax_rate / 100) * $second;
            }
            else{
                $tds += ($taxSlabArray[5]->tax_rate / 100) * $oneTimeBonus; // calculate amount of 4th slab of tds
            }
        }  else {
            // no slab of tds
            $tds = 0;
        }

        // if($employeeeId =='1'){
        //     dd($tds);
        // }
        return round($tds);
    }
    

}
