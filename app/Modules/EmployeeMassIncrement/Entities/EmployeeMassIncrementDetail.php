<?php

namespace App\Modules\EmployeeMassIncrement\Entities;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Payroll\Entities\IncomeSetup;
use App\Modules\Payroll\Entities\GrossSalarySetup;
use App\Modules\Organization\Entities\Organization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Employee\Repositories\EmployeeRepository;
use App\Modules\Payroll\Repositories\IncomeSetupInterface;
use App\Modules\Payroll\Repositories\IncomeSetupRepository;
use App\Modules\Payroll\Repositories\EmployeeSetupRepository;
use App\Modules\EmployeeMassIncrement\Http\Controllers\EmployeeMassIncrementController;

class EmployeeMassIncrementDetail extends Model
{
    protected $fillable = [
        'employee_mass_increment_id',
        'income_setup_id',
        'exiting_amount',
        'increased_amount',
        'effective_date',
        'status'

    ];

    public function employeeMassIncrement(){
        return $this->hasOne(EmployeeMassIncrement::class,'id','employee_mass_increment_id');
    }

    public function getLatestAmount()
    {
        $request=[
            'organizationId'=>$this->employeeMassIncrement->organization_id,
            'employeeId'=>$this->employeeMassIncrement->employee_id,
            'selectedIncome'=>$this->income_setup_id
        ];
        return $this->fetchIncomeLatest(new Request($request)) ?? 0;
    }

    public function fetchIncomeLatest($request)
    {
        
        $incomeAmount = 0;
        $employee = Employee::where('id', $request->employeeId)->first();
        if ($employee) {
            if($request->selectedIncome==0){
                $grossSalary=GrossSalarySetup::where([
                    'organization_id' => $request->organizationId,
                    'employee_id'=> $employee->employee_id
                ])->first();
                if($grossSalary){
                    $incomeAmount=$grossSalary->gross_salary;
                }
            }else{
                $incomeSetup = IncomeSetup::where('id', $request->selectedIncome)->first();
                if ($incomeSetup) {
                    $organization = Organization::where('id', $request->organizationId)->first();
                    if ($organization) {
                        $filter = [
                            "organization_id" => $organization->id,
                            "employee_id" => $employee->employee_id
                        ];
                        if (isset($filter['organization_id'])) {
                            $incomeList = (new IncomeSetupRepository())->getList(['organizationId' => $filter['organization_id']])->toArray();
                            $income = (new IncomeSetupRepository())->findAll(null, ['organizationId' => $filter['organization_id']]);
                        }
                        $data['employeeList'] = [];
                        if (isset($filter['organization_id'])) {
                            $data['employeeList'] = (new EmployeeRepository())->getEmployeeByOrganization($filter['organization_id'], $filter)->map(function ($employeeList) use ($incomeList, $income) {
                                $employeeGrossSalarySetup = $employeeList->employeeGrossSalarySetup;
                                $gross_salary = optional($employeeGrossSalarySetup)->gross_salary ?? 0;
                                $grade = optional($employeeGrossSalarySetup)->grade ?? 0;
                                $basics = 0;
                                foreach ($income as $key => $value) {
                                    $filter = [
                                        'reference' => 'income',
                                        'reference_id' => $value->id,
                                        'employee_id' => $employeeList->id
                                    ];
                                    $employeeIncome =(new EmployeeSetupRepository())->findOne($filter);
                                    if (!$employeeIncome) {
                                        if ($value->method == 2) {
                                            if ($value->short_name == 'BS') {
                                                $amount = 0;
                                                foreach ($value->incomeDetail as $incomeDetail) {
                                                    $per = $incomeDetail->percentage;
                                                    $basic = ($per / 100) * $gross_salary;
                                                    $basics += $basic;
                                                    $amount += ($per / 100) * $gross_salary;
                                                }
                                            } else {
                                                $amount = 0;
                                                foreach ($value->incomeDetail as $incomeDetail) {
                                                    if ($incomeDetail->salary_type == 2) {
                                                        $per = $incomeDetail->percentage;
                                                        $amount += ($per / 100) * $gross_salary;
                                                    } elseif ($incomeDetail->salary_type == 3) {
                                                        $per = $incomeDetail->percentage;
                                                        $amount += ($per / 100) * $grade;
                                                    } else {
                                                        $per = $incomeDetail->percentage;
                                                        $amount += ($per / 100) * $basics;
                                                    }
                                                }
                                            }
                                        } else {
                                            $amount = $value->amount;
                                        }
                                        // if (!in_array($value->id, $pluckIncome) || in_array($value->id, $pluckIncome ) ){
                                        $expectIncome[] = [
                                            'id' => '',
                                            'amount' => round($amount, 2),
                                            'reference_id' => $value->id
                                        ];
                                        // }
                                    } else {
                                        $expectIncome[] = [
                                            'id' => '',
                                            'amount' => round($employeeIncome->amount, 2),
                                            'reference_id' => $value->id
                                        ];
                                    }
                                }
                                if (isset($expectIncome)) {
                                    $employeeList->employeeIncomeSetup = $expectIncome;
                                }

                                return $employeeList;
                            });
                        }
                        if ($data['employeeList'] && count($data['employeeList']) > 0) {
                            $filterData = collect($data['employeeList'][0]['employeeIncomeSetup'])->where('reference_id', $incomeSetup->id)->first();
                            
                            if ($filterData) {
                                $incomeAmount = $filterData['amount'];
                            }
                        }
                    }
                }
            }
        }



        return $incomeAmount;
    }
}
