<?php

namespace App\Modules\Payroll\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Service\Import\ImportFile;
use Illuminate\Routing\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Modules\Employee\Entities\Employee;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Payroll\Entities\IncomeSetup;
use App\Modules\Payroll\Entities\EmployeeSetup;
use App\Modules\Payroll\Entities\GrossSalarySetup;
use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Payroll\Repositories\BonusSetupInterface;
use App\Modules\Payroll\Repositories\IncomeSetupInterface;
use App\Modules\Payroll\Repositories\EmployeeSetupInterface;
use App\Modules\Payroll\Repositories\DeductionSetupInterface;
use App\Modules\Payroll\Repositories\TaxExcludeSetupInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\BulkUpload\Service\Import\EmployeeBonusSetupImport;
use App\Modules\BulkUpload\Service\Import\EmployeeGrossSalaryImport;
use App\Modules\BulkUpload\Service\Import\EmployeeIncomeSetupImport;
use App\Modules\Payroll\Repositories\ThresholdBenefitSetupInterface;
use App\Modules\EmployeeMassIncrement\Entities\EmployeeMassIncrement;
use App\Modules\BulkUpload\Service\Import\EmployeeDeductionSetupImport;
use App\Modules\BulkUpload\Service\Import\EmployeeTaxExcludeSetupImport;
use App\Modules\EmployeeMassIncrement\Entities\EmployeeMassIncrementDetail;

class EmployeeSetupController extends Controller
{
    protected $employee;
    protected $organization;
    protected $income;
    protected $branch;
    protected $deduction;
    protected $bonus;
    protected $employeeSetup;
    protected $thresholdBenefit;
    protected $taxExcludeSetup;

    public function __construct(
        EmployeeInterface $employee,
        OrganizationInterface $organization,
        IncomeSetupInterface $income,
        DeductionSetupInterface $deduction,
        BonusSetupInterface $bonus,
        BranchInterface $branch,
        EmployeeSetupInterface $employeeSetup,
        ThresholdBenefitSetupInterface $thresholdBenefit,
        TaxExcludeSetupInterface $taxExcludeSetup
    ) {
        $this->employee = $employee;
        $this->organization = $organization;
        $this->income = $income;
        $this->branch = $branch;
        $this->deduction = $deduction;
        $this->bonus = $bonus;
        $this->employeeSetup = $employeeSetup;
        $this->thresholdBenefit = $thresholdBenefit;
        $this->taxExcludeSetup = $taxExcludeSetup;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function income(Request $request)
    {
        $filter = ($request->all());
        $data['title'] = 'Assign Employeee Income';
        $data['organizationList'] = $this->organization->getList();
        $data['branchList'] = $this->branch->getList();
        $data['employeePluck'] = $this->employee->getList();
        if (isset($filter['organization_id'])) {
            $data['incomeList'] = $incomeList = $this->income->getList(['organizationId' => $filter['organization_id']])->toArray();
            $data['income'] = $income = $this->income->findAll(null, ['organizationId' => $filter['organization_id']]);
        } else {
            $data['incomeList'] = [];
            $data['income'] = [];
        }
        $data['employeeList'] = [];
        $expectIncome = [];
        $employeeUpdatedIncomeValue=[];
        if (isset($filter['organization_id'])) {
            $data['employeeList'] = $this->employee->getEmployeeByOrganization($filter['organization_id'], $filter)->map(function ($employeeList) use ($incomeList, $income) {
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
                    $employeeIncome = $this->employeeSetup->findOne($filter);
                    if ($value->method == '2') {

                        if ($value->short_name == 'BS') {
                            $amount = 0;

                            foreach ($value->incomeDetail as $incomeDetail) {

                                $per = $incomeDetail->percentage;
                                $basic = ($per / 100) * $gross_salary;
                                $basics += $basic;
                                $amount += ($per / 100) * $gross_salary;
                                $amount=$amount;
                            }
                        } else {
                            $amount = 0;
                            foreach ($value->incomeDetail as $incomeDetail) {
                                if ($incomeDetail->salary_type == 2) {
                                    $per = $incomeDetail->percentage;
                                    $amount += ($per / 100) * $gross_salary;
                                } elseif ($incomeDetail->salary_type == 3) {
                                    $grade = 0;
                                    $gradeIncomeSetup = IncomeSetup::where([
                                        'organization_id' => $value->organization_id,
                                        'short_name' => 'GR'
                                    ])->first();
                                    if ($gradeIncomeSetup) {
                                        if($gradeIncomeSetup->amount !=0){
                                            $grade = $gradeIncomeSetup->amount;
                                        }else{
                                            $updatedValue=collect($expectIncome)->where('reference_id',$gradeIncomeSetup->id)->first();
                                            if($updatedValue && count($updatedValue) > 0){
                                                $grade=$updatedValue['amount'];
                                            }
                                        }
                                    }
                                    // Old value
                                    $per = $incomeDetail->percentage;
                                    $amount += ($per / 100) * $grade;

                                    // Old valu
                                } else {
                                    $per = $incomeDetail->percentage;
                                    $amount += ($per / 100) * $basics;

                                }
                            }
                        }
                        $expectIncome[] = [
                            'id' => '',
                            'amount' => round($amount, 2),
                            'reference_id' => $value->id,
                            'method' => $value->method,
                            'status'=>$employeeIncome->status ?? 11,
                            'emp_id'=>$employeeList->employee_id,
                            'final_amount'=>$employeeIncome->amount ?? 0
                        ];
                    } else {
                            $massIncrement = EmployeeMassIncrement::where(
                                ['organization_id' => $employeeList->organization_id, 'employee_id' => $employeeList->id]
                            )
                            ->with('details')
                            ->get()
                            ->flatMap->details
                            ->map(fn($q) => [
                                'income_setup_id' => $q->income_setup_id,
                                'increased_amount' => $q->increased_amount,
                                'effective_date' => $q->effective_date,
                                'status'=>$q->status
                            ]);
                            $currentDateInNep=date_converter()->eng_to_nep_convert(Carbon::now()->format('Y-m-d'));
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
                                            $grade = 0;

                                            $gradeIncomeSetup = IncomeSetup::where([
                                                'organization_id' => $value->organization_id,
                                                'short_name' => 'GR'
                                            ])->first();
                                            if ($gradeIncomeSetup) {
                                                $grade = $gradeIncomeSetup->amount;
                                            }
                                            // Old value
                                            $per = $incomeDetail->percentage;
                                            $amount += ($per / 100) * $grade;

                                            // Old valu
                                        } else {
                                            $per = $incomeDetail->percentage;
                                            $amount += ($per / 100) * $basics;
                                        }
                                    }
                                }
                            } else {

                                $incrementAmount=0;
                                if($massIncrement && count($massIncrement) > 0){
                                    $incrementData=$massIncrement->whereIn('income_setup_id',$value->id)->where('effective_date','<=',$currentDateInNep)->where('status',false);
                                    if(isset($incrementData) && count($incrementData) > 0){
                                        foreach($incrementData as $increment){
                                            $incrementAmount+=$increment['increased_amount'] ?? 0;
                                        }
                                    }
                                }
                                $amount = $value->amount+$incrementAmount;
                            }
                            $expectIncome[] = [
                                'id' => '',
                                'amount' => round($amount, 2),
                                'reference_id' => $value->id,
                                'method' => $value->method,
                                'status'=>$employeeIncome->status ?? 11,
                                'emp_id'=>$employeeList->employee_id,
                                'final_amount'=>round($amount  ?? 0, 2)
                            ];
                        } else {
                            $incrementAmount=0;
                                if($massIncrement && count($massIncrement) > 0){
                                    $incrementData=$massIncrement->whereIn('income_setup_id',$value->id)->where('effective_date','<=',$currentDateInNep)->where('status',false);
                                    if(isset($incrementData) && count($incrementData) > 0){
                                        foreach($incrementData as $increment){
                                            $incrementAmount+=$increment['increased_amount'] ?? 0;
                                        }
                                    }
                                }
                            $expectIncome[] = [
                                'id' => '',
                                'amount' => round($employeeIncome->amount+$incrementAmount, 2),
                                'reference_id' => $value->id,
                                'method' => $value->method,
                                'status'=>$employeeIncome->status ?? 11,
                                'emp_id'=>$employeeList->employee_id,
                                'final_amount'=>$employeeIncome->amount  ?? 0
                            ];
                        }
                    }
                }
                if (isset($expectIncome)) {
                    $employeeList->employeeIncomeSetup = $expectIncome;
                }
                return $employeeList;
            });
        }
        return view('payroll::employee-setup.income', $data);
    }
    public function fetchIncomeUpdateCalculation(Request $request)
    {

        try {
            $collectedValueData = [];
            $employee = Employee::where('id', $request->employeeId)->first();
            $updateFieldValue = $request->updateFieldValue;
            foreach (json_decode($request->collectedValue, true) as $fromrequest) {
                $collectedValueData[$fromrequest['id']] = $fromrequest['value'];
            }
            $filter = [
                'organization_id' => $employee->organization_id,
                'employee_id' => $employee->employee_id
            ];
            $income = $this->income->findAll(null, ['organizationId' => $filter['organization_id']]);
            $expectIncome = [];
            $data['employeeList'] = $this->employee->getEmployeeByOrganization($filter['organization_id'], $filter)->map(function ($employeeList) use ($income, &$expectIncome, $collectedValueData) {
                $employeeGrossSalarySetup = $employeeList->employeeGrossSalarySetup;
                $gross_salary = optional($employeeGrossSalarySetup)->gross_salary ?? 0;
                $grade = optional($employeeGrossSalarySetup)->grade ?? 0;
                $basics = 0;
                foreach ($income as $key => $value) {
                    if ($value->method == 2) {
                        if ($value->short_name == 'BS') {
                            $amount = 0;
                            foreach ($value->incomeDetail as $incomeDetail) {
                                $per = $incomeDetail->percentage;
                                $basic = $collectedValueData[$value->id];
                                $basics += $basic;
                                $amount += $collectedValueData[$value->id];
                            }
                        } else {
                            $amount = 0;
                            foreach ($value->incomeDetail as $incomeDetail) {
                                if ($incomeDetail->salary_type == 2) {
                                    $per = $incomeDetail->percentage;
                                    $amount += $collectedValueData[$incomeDetail->income_setup_id];
                                } elseif ($incomeDetail->salary_type == 3) {
                                    $grade = 0;
                                    $gradeIncomeSetup = IncomeSetup::where([
                                        'organization_id' => $value->organization_id,
                                        'short_name' => 'GR'
                                    ])->first();
                                    if ($gradeIncomeSetup) {
                                        $grade = $collectedValueData[$gradeIncomeSetup->id];
                                    }
                                    // Old value
                                    $per = $incomeDetail->percentage;
                                    $amount += ($per / 100) * $grade;
                                    // Old value
                                } else {
                                    $per = $incomeDetail->percentage;
                                    $amount += ($per / 100) * $basics;
                                }
                            }
                        }
                    } else {
                        $amount = $value->amount;
                    }
                    $expectIncome[] = [
                        'id' => '',
                        'amount' => round($amount, 2),
                        'reference_id' => $value->id,
                        'method' => $value->method
                    ];
                }
            });
            $response = [
                'error' => false,
                'data' => collect($expectIncome)->whereIn('reference_id', $updateFieldValue),
                'msg' => 'success'
            ];
        } catch (\Throwable $th) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'error'
            ];
        }
        return response()->json($response, 200);
    }

    public function deduction(Request $request)
    {
        $filter = ($request->all());

        $data['title'] = 'Assign Employeee Deduction';
        $data['organizationList'] = $this->organization->getList();
        $deductionList = [];
        if (isset($filter['organization_id'])) {
            $deductionList = $this->deduction->getList(['organizationId' => $filter['organization_id']]);
        }
        $data['deductionList'] = $deductionList;
        $data['branchList'] = $this->branch->getList();
        $data['employeePluck'] = $this->employee->getList();
        $data['employeeList'] = [];
        if (isset($filter['organization_id'])) {
            $data['employeeList'] = $this->employee->getEmployeeByOrganization($filter['organization_id'], $filter)->map(function ($employeeList) use ($deductionList) {
                $employeeDeductionSetup = $employeeList->employeeDeductionSetup;
                $pluckDeduction = $employeeDeductionSetup->pluck('reference_id')->toArray();
                $deductionArray = $employeeDeductionSetup;
                foreach ($deductionList as $key => $value) {
                    // dd($value);
                    if (!in_array($key, $pluckDeduction)) {
                        $deductionArray[] = [
                            'id' => '',
                            'amount' => '',
                            'reference_id' => $key,
                            'status' => ''
                        ];
                    }
                }
                // dd($deductionArray);
                // dd($employeeList);
                return $employeeList;
            });
        }

        return view('payroll::employee-setup.deduction', $data);
    }

    public function deductionTest(Request $request)
    {
        $filters = ($request->all());

        $data['title'] = 'Assign Employeee Deduction';
        $data['organizationList'] = $this->organization->getList();
        $data['branchList'] = $this->branch->getList();
        $deductionList = [];
        if (isset($filter['organization_id'])) {
            $deductionList = $this->deduction->getList(['organizationId' => $filter['organization_id']]);
        }
        $data['deductionList'] = $this->employeeSetup->getDeductionList($filters);
        $data['filtersValue'] = $filters;
        $grossSalarySetupType = GrossSalarySetupSetting::first();
        if (!$grossSalarySetupType) {
            toastr('Set Travel allowance setup first', 'error');
            return redirect()->route('allowance.create');
        }
        switch ($grossSalarySetupType->gross_salary_type) {
            case "1": //Employee
                $filterValue = [
                    'filterName' => 'Employee',
                    'filterDatas' => $this->employee->getList(),
                ];
                $columns = [
                    'Employee Name'
                ];
                break;
            case "2": //Level
                $filterValue = [
                    'filterName' => 'Level',
                    'filterDatas' => $this->level->getList()
                ];
                $columns = [
                    'Title',
                    'Short Code'
                ];
                break;
            case "3": //Designation
                $filterValue = [
                    'filterName' => 'Designation',
                    'filterDatas' => $this->designation->getList()
                ];
                $columns = [
                    'Title',
                    'Short Code'
                ];
                break;
            default:
                break;
        }
        $data['columns'] = $columns;
        $data['filterValue'] = $filterValue;
        $data['getSetWiseAllowaceSetups'] = $this->employeeSetup->getSetupWiseDeductionData($data, $grossSalarySetupType, $filters, $data['deductionList'], null);
        $data['grossSalarySetupType'] = $grossSalarySetupType;
        return view('payroll::employee-setup.deduction', $data);
    }

    public function bonus(Request $request)
    {
        $filter = ($request->all());
        $data['title'] = 'Assign Employeee Bonus';
        $data['organizationList'] = $this->organization->getList();
        $data['branchList'] = $this->branch->getList();
        $data['monthList'] = date_converter()->getEngMonths();
        $data['nepaliMonthList'] = date_converter()->getNepMonths();
        $data['employeePluck'] = $this->employee->getList();
        if (isset($filter['organization_id'])) {
            $data['bonusList'] = $bonusList = $this->bonus->getList(['organizationId' => $filter['organization_id']])->toArray();
            $data['bonus'] = $bonus = $this->bonus->findAllActive(null, ['organizationId' => $filter['organization_id']]);
        } else {
            $data['bonusList'] = [];
            $data['bonus'] = [];
        }
        $data['employeeList'] = [];
        $expectBonus = [];
        if (isset($filter['organization_id'])) {
            $data['employeeList'] = $this->employee->getEmployeeByOrganization($filter['organization_id'], $filter)->map(function ($employeeList) use ($bonusList, $bonus,$expectBonus) {
                $employeeGrossSalarySetup = $employeeList->employeeGrossSalarySetup;
                $gross_salary = optional($employeeGrossSalarySetup)->gross_salary;
                $basics = 0;
                foreach ($bonus as $key => $value) {
                    $filter = [
                        'employee_id' => $employeeList->id,
                        'organization_id' => $employeeList->organization_id,
                        'bonus_setup_id' => $value->id,
                    ];
                    $employeeBonus = $this->employeeSetup->findEmployeeBonus($filter);
                    $employeeSetupModel = EmployeeSetup::whereHas('income', function ($query) {
                        $query->where('short_name', 'BS');
                    })->where('employee_id', $employeeList->id)->first();
                    if ($employeeSetupModel) {
                        $employeeBasicSalary = $employeeSetupModel->amount;
                    } else {
                        $employeeBasicSalary = 0;
                    }
                    if (!$employeeBonus) {
                        if ($value->method == 2) {
                            $employee_income = 0;
                            $amount = 0;

                            foreach ($value->bonusDetail as $detail => $det) {
                                $incomeModel = $employeeList->employeeIncomeSetup->where('reference_id', $det->income_id)->first();
                                if ($incomeModel) {
                                    $employee_income =  $incomeModel->amount;
                                }
                                $amount += ($det->percentage / 100) * $employee_income;
                            }
                        } else {
                            $amount = $value->amount;
                        }
                        $expectBonus[] = [
                            'id' => '',
                            'amount' => round($amount, 2),
                            'bonus_setup_id' => $value->id
                        ];
                    } else {
                        $expectBonus[] = [
                            'id' => '',
                            'amount' => round($employeeBonus->amount, 2),
                            'bonus_setup_id' => $value->id
                        ];
                    }
                }
                $employeeList->employeeBonusSetup = $expectBonus;
                return $employeeList;
            });
        }
        return view('payroll::employee-setup.bonus', $data);
    }

    public function storeBonus(Request $request)
    {
        $data = $request->except(['_token']);
        try {
            foreach ($data as $key => $values) {
                foreach ($values as $bonusId => $value) {
                    $amount = $value;
                    $employee = $this->employee->find($key);
                    $inputArray = [
                        'employee_id' =>  $key,
                        'organization_id' => $employee->organization_id,
                        'bonus_setup_id' => $bonusId,
                        'amount' => $amount,
                    ];
                    $this->employeeSetup->updateOrCreateBonus($inputArray);
                }
            }
            toastr()->success('Employee Bonus Assigned Successfully');
        } catch (\Throwable $e) {
            // dd($e);
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('employeeSetup.bonus'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function storeIncome(Request $request)
    {
        // return $request;
        $data = $request->except(['_token']);
        try {
            $this->store($data, 'income');
            toastr()->success('Employee Income Assigned Successfully');
        } catch (\Throwable $e) {
            // dd($e);
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('employeeSetup.income'));
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function storeDeduction(Request $request)
    {
        $inputData = $request->all();

        $data = $request->except(['_token', 'setupType', 'organization_id']);
        try {
            $this->store($data, 'deduction');
            toastr()->success('Employee Deduction Assigned Successfully');
        } catch (\Throwable $e) {
            throw $e;
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('employeeSetup.deduction'));
    }

    public function viewDeduction(Request $request)
    {
        $filter = ($request->all());
        $params['organizationId'] = $filter['organization_id'];
        $data['title'] = 'Employeee Deduction';
        $data['deductionList'] = $deductionList = $this->deduction->getList($params)->toArray();
        // dd($data['deductionList']);
        $data['deduction'] = $deduction = $this->deduction->findAll();
        $data['employeeList'] = [];
        if (isset($filter['organization_id'])) {

            $data['employeeList'] = $this->employee->getEmployeeByOrganization($filter['organization_id'], $filter)->map(function ($employeeList) use ($deductionList, $deduction) {
                $employeeList->employeeDeductionSetup = $employeeList->employeeDeductionSetup;
                return $employeeList;
            });
        }
        // dd($data['employeeList']);
        return view('payroll::employee-setup.view-deduction', $data);
    }

    public function store($data, $type)
    {
        foreach ($data as $key => $values) {
            $citFlag = false;
            $ssfFlag = false;
            $pfFlag = false;
            $pf = 0;
            $cit = 0;
            foreach ($values as $refId => $value) {
                $amount = $type == 'deduction' ?  $value['amount'] : $value['amount'];
                $employee = $this->employee->find($key);
                if ($type == 'deduction') {
                    $deductionModel = $this->deduction->find($refId);
                    if ($type == 'deduction' && $value['status'] == '11' && $deductionModel->method == 2) {
                        $deductionModel = $this->deduction->find($refId);
                        $thresholdBenefit = $this->thresholdBenefit->find($refId);

                        $threshold_benefit_amount = $thresholdBenefit->amount ?? 0;
                        if ($deductionModel->method == 1) {
                            $employee_threshold_amount = optional($employee->employeeThresholdDetail)->where('deduction_setup_id', $refId)->first()->amount ?? 0;
                            if ($employee_threshold_amount <= $threshold_benefit_amount) {
                                $amount = $employee_threshold_amount;
                            } elseif ($employee_threshold_amount > $threshold_benefit_amount) {
                                $amount = $threshold_benefit_amount;
                            }
                        } else {
                            $employee_income = 0;
                            $amount = 0;
                            foreach ($deductionModel->deductionDetail as $detail => $det) {
                                $incomeModel = $employee->employeeIncomeSetup->where('reference_id', $det->income_id)->first();
                                if ($incomeModel) {
                                    $employee_income =  $incomeModel->amount;
                                }
                                $amount += ($det->percentage / 100) * $employee_income;
                            }
                        }
                    }

                    if ($type == 'deduction' && $value['status'] == '11' && in_array($deductionModel->method, [1, 3])) {
                        $amount = $value['amount'];
                    }

                    if ($type == 'deduction' && $value['status'] == '10') {
                        $amount = 0;
                    }
                }
                $inputArray = [
                    'employee_id' =>  $key,
                    'organization_id' => $employee->organization_id,
                    'reference' => $type,
                    'reference_id' => $refId,
                    'amount' => $amount,
                    'status' => $type == 'deduction' ?  $value['status'] : ($value['status'] ?? 11)
                ];
                $massIncrement = EmployeeMassIncrement::where(
                    ['organization_id' => $employee->organization_id, 'employee_id' => $key]
                )
                ->with('details')
                ->get()
                ->flatMap->details
                ->map(fn($q) => [
                    'id'=>$q->id,
                    'income_setup_id' => $q->income_setup_id,
                    'increased_amount' => $q->increased_amount,
                    'effective_date' => $q->effective_date,
                    'status'=>$q->status
                ]);
                $currentDateInNep=date_converter()->eng_to_nep_convert(Carbon::now()->format('Y-m-d'));
                if($massIncrement && count($massIncrement) > 0){
                    $incrementData=$massIncrement->whereIn('income_setup_id',$refId)->where('effective_date','<=',$currentDateInNep)->where('status',false);
                    if(isset($incrementData) && count($incrementData) > 0){
                        foreach($incrementData as $increment){
                            $massDetail=EmployeeMassIncrementDetail::where('id',$increment['id'])->first();
                            $massDetail->status=true;
                            $massDetail->save();
                        }
                    }
                }
                $this->employeeSetup->updateOrCreate($inputArray);
            }
        }
    }

    public function taxExclude(Request $request)
    {
        $filter = ($request->all());
        $data['title'] = 'Assign Employeee Tax Exclude';
        $data['organizationList'] = $this->organization->getList();
        $data['branchList'] = $this->branch->getList();
        $data['monthList'] = date_converter()->getEngMonths();
        $data['nepaliMonthList'] = date_converter()->getNepMonths();
        $data['employeePluck'] = $this->employee->getList();
        if (isset($filter['organization_id'])) {
            $data['taxExcludeList'] = $taxExcludeList = $this->taxExcludeSetup->getList(['organizationId' => $filter['organization_id']])->toArray();
            $data['taxExclude'] = $taxExclude = $this->taxExcludeSetup->findAll(null, ['organizationId' => $filter['organization_id']]);
        } else {
            $data['taxExcludeList'] = [];
            $data['taxExclude'] = [];
        }
        $data['employeeList'] = [];
        $expectBonus = [];
        if (isset($filter['organization_id'])) {
            $data['employeeList'] = $this->employee->getEmployeeByOrganization($filter['organization_id'], $filter)->map(function ($employeeList) use ($taxExcludeList, $taxExclude) {
                $employeeGrossSalarySetup = $employeeList->employeeGrossSalarySetup;
                $gross_salary = optional($employeeGrossSalarySetup)->gross_salary;
                $basics = 0;
                foreach ($taxExclude as $key => $value) {
                    $filter = [
                        'employee_id' => $employeeList->id,
                        'organization_id' => $employeeList->organization_id,
                        'tax_exclude_setup_id' => $value->id,
                    ];
                    $employeeTaxExclude = $this->employeeSetup->findEmployeeTaxExclude($filter);
                    if (!$employeeTaxExclude) {
                        $amount = $value->amount;
                        $expectBonus[] = [
                            'id' => '',
                            'amount' => round($amount, 2),
                            'tax_exclude_setup_id' => $value->id
                        ];
                    } else {
                        $expectBonus[] = [
                            'id' => '',
                            'amount' => round($employeeTaxExclude->amount, 2),
                            'tax_exclude_setup_id' => $value->id
                        ];
                    }
                }
                $employeeList->employeeTaxExcludeSetup = $expectBonus;
                return $employeeList;
            });
        }
        return view('payroll::employee-setup.tax-exclude', $data);
    }

    public function storeTaxExclude(Request $request)
    {
        $data = $request->except(['_token']);
        try {
            foreach ($data as $key => $values) {
                foreach ($values as $taxExcludeId => $value) {
                    $amount = $value;
                    $employee = $this->employee->find($key);
                    $inputArray = [
                        'employee_id' =>  $key,
                        'organization_id' => $employee->organization_id,
                        'tax_exclude_setup_id' => $taxExcludeId,
                        'amount' => $amount,
                    ];
                    $this->employeeSetup->updateOrCreateTaxExclude($inputArray);
                }
            }
            toastr()->success('Employee Bonus Assigned Successfully');
        } catch (\Throwable $e) {
            // dd($e);
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('employeeSetup.taxExclude'));
    }

    public function showIncomes(Request $request)
    {
        $filter = ($request->all());
        $data['title'] = 'Assign Employeee Income';
        $data['organizationList'] = $this->organization->getList();
        $data['branchList'] = $this->branch->getList();
        $data['employeePluck'] = $this->employee->getList();
        $data['incomeList'] = $incomeList = $this->income->getList()->toArray();
        $data['income'] = $income = $this->income->findAll();
        $data['employeeList'] = [];
        $expectIncome = [];
        if (isset($filter['organization_id'])) {

            $data['employeeList'] = $this->employee->getEmployeeByOrganization($filter['organization_id'], $filter)->map(function ($employeeList) use ($incomeList, $income) {
                $employeeList->employeeIncomeSetup = $employeeList->employeeIncomeSetup;
                return $employeeList;
            });
        }
        return view('payroll::employee-setup.view-incomes', $data);
    }

    public function grossSalary(Request $request)
    {
        $filter = ($request->all());
        $data['title'] = 'Assign Employeee Gross Salary';
        $data['organizationList'] = $this->organization->getList();
        $data['branchList'] = $this->branch->getList();
        $data['employeePluck'] = $this->employee->getList();
        $data['employeeList'] = [];
        if (isset($filter['organization_id'])) {
            $data['employeeList'] = $this->employee->getEmployeeByOrganization($filter['organization_id'], $filter)->map(function ($employeeList) {
                $employeeGrossSalarySetup = $employeeList->employeeGrossSalarySetup;
                return $employeeList;
            });
        }
        return view('payroll::employee-setup.gross-salary', $data);
    }

    public function storeGrossSalary(Request $request)
    {
        $data = $request->except(['_token']);
        try {
            foreach ($data['gross_salary'] as $key => $value) {
                $employee = $this->employee->find($key);
                $inputArray = [
                    'employee_id' =>  $key,
                    'organization_id' => $employee->organization_id,
                    'gross_salary' => $value == null ? 0 : $value,
                    'grade' => $data['grade'][$key],
                ];
                $this->employeeSetup->updateOrCreateGrossSalary($inputArray);
            }
            toastr()->success('Employee Gross Salary Assigned Successfully');
        } catch (\Throwable $e) {
            // dd($e);
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('employeeSetup.grossSalary'));
    }

    public function uploadGrossSalary(Request $request)
    {
        // dd($request);
        $files = $request->upload_employee_gross_salary;
        // dd($files);
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($files);
        \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        array_shift($sheetData);

        $import_file = EmployeeGrossSalaryImport::import($sheetData);

        if ($import_file) {
            toastr()->success('Employee Gross Salary Imported Successfully');
        }

        return redirect()->route('employeeSetup.grossSalary');
    }

    public function exportGrossSalary(Request $request)
    {
        // dd(request()->all());
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],

        ];

        $objPHPExcel = new Spreadsheet();
        $worksheet = $objPHPExcel->getActiveSheet();

        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'S.N');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Employee Id');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Employee Code');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Employee Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Organization Id');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Gross Salary');

        $employeeInfo = $this->employee->findAll('', request()->all());
        // dd( $employeeInfo);
        $num = 2;

        foreach ($employeeInfo as $key => $value) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $num, ++$key);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $num, $value->id);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $num, $value->employee_code);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $num, $value->getFullName());
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $num, $value->organization_id);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $num, $value->employeeGrossSalarySetup ? optional($value->employeeGrossSalarySetup)->gross_salary : 0);

            $num++;
        }

        $writer = new Xlsx($objPHPExcel);
        $file = 'employee_gross_salary';
        $filename = $file . '.xlsx';
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $writer->save('php://output');
    }
    public function exportIncome(Request $request)
    {
        $filter = $request->all();
        $data['incomes'] = $incomes = $this->income->findAll(null, ['organizationId' => $filter['organization_id']]);

        // Style array for header
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'FFFF00',
                ],
            ],

        ];

        $objPHPExcel = new Spreadsheet();
        $worksheet = $objPHPExcel->getActiveSheet();

        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'S.N');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Employee Id');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Employee Code');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Employee Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Organization Id');
        // $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Reference');
        // $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Income Type Id');
        // $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Income Type');
        // $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Amount');
        // $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Status');

        $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray($styleArray);

        $ascii_title = ord('F');
        foreach ($incomes as $inc => $income) {
            $char_title = chr($ascii_title);
            $objPHPExcel->getActiveSheet(0)->SetCellValue($char_title . '1',  $income->title);
            $objPHPExcel->getActiveSheet()->getStyle($char_title . '1')->applyFromArray($styleArray);
            $ascii_title++;
        }

        $employeeInfo = $this->employee->findAll($limit = 10000, request()->all());
        $data['incomes'] = $incomes = $this->income->findAll(null, ['organizationId' => $filter['organization_id']]);
        $num = 2;
        $incr = 0;

        $ascii = ord('F');
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $num, ++$incr);
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $num, null);
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $num, null);
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $num, null);
        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $num, null);

        $objPHPExcel->getActiveSheet()->getStyle('A' . $num)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('B' . $num)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('C' . $num)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('D' . $num)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('E' . $num)->applyFromArray($styleArray);
        // $objPHPExcel->getActiveSheet()->SetCellValue('E' . $num, 'income');
        foreach ($incomes as $inc => $income) {
            $order_qty_value = chr($ascii);
            $objPHPExcel->getActiveSheet(0)->SetCellValue($order_qty_value . $num, $income->id);
            $objPHPExcel->getActiveSheet()->getStyle($order_qty_value . $num)->applyFromArray($styleArray);
            $ascii++;
        }
        $num++;
        foreach ($employeeInfo as $key => $value) {
            // dd($value);
            // foreach ($incomes as $inc => $income) {
            // $filter = [
            //     'reference' => 'income',
            //     'reference_id' => $income->id,
            //     'employee_id' => $value->id
            // ];
            // $employeeIncome = $this->employeeSetup->findOne($filter);
            $ascii_val = ord('F');

            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $num, ++$incr);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $num, $value->id);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $num, $value->employee_code);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $num, $value->getFullName());
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $num, $value->organization_id);
            // $objPHPExcel->getActiveSheet()->SetCellValue('E' . $num, 'income');
            foreach ($incomes as $inc => $income) {
                $filter = [
                    'reference' => 'income',
                    'reference_id' => $income->id,
                    'employee_id' => $value->id
                ];
                $employeeIncome = $this->employeeSetup->findOne($filter);
                $order_qty_value = chr($ascii_val);
                $objPHPExcel->getActiveSheet(0)->SetCellValue($order_qty_value . $num, $employeeIncome ? $employeeIncome->amount : 0);
                $ascii_val++;
            }
            // $objPHPExcel->getActiveSheet()->SetCellValue('F' . $num, $income->id);
            // $objPHPExcel->getActiveSheet()->SetCellValue('G' . $num, $income->title);
            // $objPHPExcel->getActiveSheet()->SetCellValue('H' . $num, $employeeIncome ? $employeeIncome->amount : 0);
            // $objPHPExcel->getActiveSheet()->SetCellValue('I' . $num, 11);

            $num++;
            // }

        }

        $writer = new Xlsx($objPHPExcel);
        $file = 'employee_income';
        $filename = $file . '.xlsx';
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $writer->save('php://output');
    }
    public function exportDeduction(Request $request)
    {
        $filter = $request->all();
        $data['deductions'] = $deductions = $this->deduction->findAll($limit= 10000, ['organizationId' => $filter['organization_id']]);
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'FFFF00',
                ],
            ],

        ];

        $objPHPExcel = new Spreadsheet();
        $worksheet = $objPHPExcel->getActiveSheet();

        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'S.N');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Employee Id');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Employee Code');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Employee Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Organization Id');

        $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray($styleArray);
        // $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Reference');
        // $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Income Type Id');
        // $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Income Type');
        // $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Amount');
        // $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Status');
        $ascii_title = ord('F');
        foreach ($deductions as $dec => $deduction) {
            $char_title = chr($ascii_title);
            $objPHPExcel->getActiveSheet(0)->SetCellValue($char_title . '1',  $deduction->title);
            $objPHPExcel->getActiveSheet()->getStyle($char_title . '1')->applyFromArray($styleArray);
            $ascii_title++;
        }

        $employeeInfo = $this->employee->findAll($limit = 10000, request()->all());
        $num = 2;
        $incr = 0;

        $ascii = ord('F');
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $num, ++$incr);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $num)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $num, null);
        $objPHPExcel->getActiveSheet()->getStyle('B' . $num)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $num, null);
        $objPHPExcel->getActiveSheet()->getStyle('C' . $num)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $num, null);
        $objPHPExcel->getActiveSheet()->getStyle('D' . $num)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $num, null);
        $objPHPExcel->getActiveSheet()->getStyle('E' . $num)->applyFromArray($styleArray);
        // $objPHPExcel->getActiveSheet()->SetCellValue('E' . $num, 'income');
        foreach ($deductions as $inc => $deduction) {
            $order_qty_value = chr($ascii);
            $objPHPExcel->getActiveSheet(0)->SetCellValue($order_qty_value . $num, $deduction->id);
            $objPHPExcel->getActiveSheet()->getStyle($order_qty_value . $num)->applyFromArray($styleArray);
            $ascii++;
        }
        $num++;
        foreach ($employeeInfo as $key => $value) {
            // dd($value);
            // foreach ($deductions as $inc => $deduction) {
            // $filter = [
            //     'reference' => 'income',
            //     'reference_id' => $deduction->id,
            //     'employee_id' => $value->id
            // ];
            // $employeeIncome = $this->employeeSetup->findOne($filter);
            $ascii_val = ord('F');

            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $num, ++$incr);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $num, $value->id);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $num, $value->employee_code);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $num, $value->getFullName());
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $num, $value->organization_id);
            // $objPHPExcel->getActiveSheet()->SetCellValue('E' . $num, 'income');
            foreach ($deductions as $inc => $deduction) {
                $filter = [
                    'reference' => 'deduction',
                    'reference_id' => $deduction->id,
                    'employee_id' => $value->id
                ];
                $employeeDeduction = $this->employeeSetup->findOne($filter);
                $order_qty_value = chr($ascii_val);
                $objPHPExcel->getActiveSheet(0)->SetCellValue($order_qty_value . $num, $employeeDeduction ? $employeeDeduction->amount : 0);
                $ascii_val++;
            }
            // $objPHPExcel->getActiveSheet()->SetCellValue('F' . $num, $deduction->id);
            // $objPHPExcel->getActiveSheet()->SetCellValue('G' . $num, $deduction->title);
            // $objPHPExcel->getActiveSheet()->SetCellValue('H' . $num, $employeeIncome ? $employeeIncome->amount : 0);
            // $objPHPExcel->getActiveSheet()->SetCellValue('I' . $num, 11);

            $num++;
            // }

        }

        $writer = new Xlsx($objPHPExcel);
        $file = 'employee_deduction';
        $filename = $file . '.xlsx';
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $writer->save('php://output');
    }
    public function exportBonus(Request $request)
    {
        $filter = $request->all();
        $data['bonuses'] = $bonuses = $this->bonus->findAllActive(null, ['organizationId' => $filter['organization_id']]);
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'FFFF00',
                ],
            ],

        ];

        $objPHPExcel = new Spreadsheet();
        $worksheet = $objPHPExcel->getActiveSheet();

        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'S.N');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Employee Code');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Employee Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Organization Id');

        $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray($styleArray);

        $ascii_title = ord('E');
        foreach ($bonuses as $dec => $bonus) {
            $char_title = chr($ascii_title);
            $objPHPExcel->getActiveSheet(0)->SetCellValue($char_title . '1',  $bonus->title);
            $objPHPExcel->getActiveSheet()->getStyle($char_title . '1')->applyFromArray($styleArray);
            $ascii_title++;
        }

        $employeeInfo = $this->employee->findAll('', request()->all());
        $num = 2;
        $incr = 0;

        $ascii = ord('E');
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $num, ++$incr);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $num)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $num, null);
        $objPHPExcel->getActiveSheet()->getStyle('B' . $num)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $num, null);
        $objPHPExcel->getActiveSheet()->getStyle('C' . $num)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $num, null);
        $objPHPExcel->getActiveSheet()->getStyle('D' . $num)->applyFromArray($styleArray);
        foreach ($bonuses as $inc => $bonus) {
            $order_qty_value = chr($ascii);
            $objPHPExcel->getActiveSheet(0)->SetCellValue($order_qty_value . $num, $bonus->id);
            $objPHPExcel->getActiveSheet()->getStyle($order_qty_value . $num)->applyFromArray($styleArray);
            $ascii++;
        }
        $num++;

        foreach ($employeeInfo as $key => $value) {
            $ascii_val = ord('E');

            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $num, ++$incr);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $num, $value->employee_code);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $num, $value->getFullName());
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $num, $value->organization_id);
            foreach ($bonuses as $inc => $bonus) {
                $filter = [
                    'bonus_setup_id' => $bonus->id,
                    'employee_id' => $value->id
                ];
                $employeeBonus = $this->employeeSetup->findEmployeeBonus($filter);
                $order_qty_value = chr($ascii_val);
                $objPHPExcel->getActiveSheet(0)->SetCellValue($order_qty_value . $num, $employeeBonus ? $employeeBonus->amount : 0);
                $ascii_val++;
            }

            $num++;
        }

        $writer = new Xlsx($objPHPExcel);
        $file = 'employee_bonus';
        $filename = $file . '.xlsx';
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $writer->save('php://output');
    }
    public function exportTaxExclude(Request $request)
    {
        $filter = $request->all();
        $data['taxExcludes'] = $taxExcludes = $this->taxExcludeSetup->findAll(null, ['organizationId' => $filter['organization_id']]);
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'FFFF00',
                ],
            ],

        ];

        $objPHPExcel = new Spreadsheet();
        $worksheet = $objPHPExcel->getActiveSheet();

        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'S.N');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Employee Id');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Employee Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Organization Id');
        // $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Reference');
        // $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Income Type Id');
        // $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Income Type');
        // $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Amount');
        // $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Status');

        $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray($styleArray);

        $ascii_title = ord('E');
        foreach ($taxExcludes as $inc => $taxExcludes) {
            $char_title = chr($ascii_title);
            $objPHPExcel->getActiveSheet(0)->SetCellValue($char_title . '1',  $taxExcludes->title);
            $objPHPExcel->getActiveSheet()->getStyle($char_title . '1')->applyFromArray($styleArray);
            $ascii_title++;
        }

        $employeeInfo = $this->employee->findAll('', request()->all());
        $data['taxExcludes'] = $taxExcludes = $this->taxExcludeSetup->findAll(null, ['organizationId' => $filter['organization_id']]);
        $num = 2;
        $incr = 0;

        $ascii = ord('E');
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $num, ++$incr);
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $num, null);
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $num, null);
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $num, null);

        $objPHPExcel->getActiveSheet()->getStyle('A' . $num)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('B' . $num)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('C' . $num)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('D' . $num)->applyFromArray($styleArray);
        // $objPHPExcel->getActiveSheet()->SetCellValue('E' . $num, 'income');
        foreach ($taxExcludes as $inc => $taxExclude) {
            $order_qty_value = chr($ascii);
            $objPHPExcel->getActiveSheet(0)->SetCellValue($order_qty_value . $num, $taxExclude->id);
            $objPHPExcel->getActiveSheet()->getStyle($order_qty_value . $num)->applyFromArray($styleArray);
            $ascii++;
        }
        $num++;

        foreach ($employeeInfo as $key => $value) {
            $ascii_val = ord('E');

            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $num, ++$incr);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $num, $value->id);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $num, $value->getFullName());
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $num, $value->organization_id);
            foreach ($taxExcludes as $inc => $taxExclude) {
                $filter = [
                    'tax_exclude_setup_id' => $taxExclude->id,
                    'employee_id' => $value->id
                ];
                $employeeTaxExclude = $this->employeeSetup->findEmployeeTaxExclude($filter);
                $order_qty_value = chr($ascii_val);
                $objPHPExcel->getActiveSheet(0)->SetCellValue($order_qty_value . $num, $employeeTaxExclude ? $employeeTaxExclude->amount : 0);
                $ascii_val++;
            }
            $num++;
        }

        $writer = new Xlsx($objPHPExcel);
        $file = 'employee_tax_exclude';
        $filename = $file . '.xlsx';
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $writer->save('php://output');
    }
    public function uploadEmployeeIncome(Request $request)
    {
        // dd($request);
        $files = $request->upload_employee_income_setup;
        // dd($files);
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($files);
        \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        array_shift($sheetData);

        $import_file = EmployeeIncomeSetupImport::import($sheetData);

        if ($import_file) {
            toastr()->success('Employee Income Setup Imported Successfully');
        }

        return redirect()->route('employeeSetup.income');
    }
    public function uploadEmployeeDeduction(Request $request)
    {
        // dd($request);
        $files = $request->upload_employee_deduction_setup;
        // dd($files);
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($files);
        \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        array_shift($sheetData);

        $import_file = EmployeeDeductionSetupImport::import($sheetData);

        if ($import_file) {
            toastr()->success('Employee Gross Salary Imported Successfully');
        }

        return redirect()->route('employeeSetup.deduction');
    }
    public function uploadEmployeeBonus(Request $request)
    {
        $files = $request->upload_employee_bonus_setup;
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($files);
        \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        array_shift($sheetData);

        $import_file = EmployeeBonusSetupImport::import($sheetData);

        if ($import_file) {
            toastr()->success('Employee Bonus Setup Imported Successfully');
        }

        return redirect()->route('employeeSetup.bonus');
    }
    public function uploadEmployeeTaxExclude(Request $request)
    {
        $files = $request->upload_employee_tax_exclude_setup;
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($files);
        \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        array_shift($sheetData);

        $import_file = EmployeeTaxExcludeSetupImport::import($sheetData);

        if ($import_file) {
            toastr()->success('Employee Tax Exclude Setup Imported Successfully');
        }

        return redirect()->route('employeeSetup.taxExclude');
    }
}
