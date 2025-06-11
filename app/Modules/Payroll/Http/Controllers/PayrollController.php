<?php

namespace App\Modules\Payroll\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Foreach_;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Modules\Leave\Entities\LeaveType;
use App\Modules\Payroll\Entities\Payroll;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Payroll\Entities\BonusIncome;
use App\Modules\Payroll\Entities\HoldPayment;
use App\Modules\Payroll\Entities\IncomeSetup;
use App\Modules\Payroll\Entities\FullAndFinal;
use App\Modules\Payroll\Entities\EmployeeSetup;
use App\Modules\Payroll\Entities\PayrollIncome;
use App\Modules\Payroll\Entities\DeductionSetup;
use App\Modules\Payroll\Entities\PayrollEmployee;
use App\Modules\Leave\Entities\LeaveEncashmentLog;
use App\Modules\Payroll\Entities\GrossSalarySetup;
use App\Modules\Payroll\Entities\PayrollDeduction;
use App\Modules\Advance\Entities\AdvanceSettlement;
use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Leave\Entities\LeaveEncashmentSetup;
use App\Modules\Payroll\Http\Requests\PayrollRequest;
use App\Modules\Advance\Repositories\AdvanceInterface;
use App\Modules\Payroll\Repositories\PayrollInterface;
use App\Modules\Setting\Repositories\SettingInterface;
use App\Modules\LeaveYearSetup\Entities\LeaveYearSetup;
use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Payroll\Entities\PayrollTaxExcludeValue;
use App\Modules\Branch\Http\Controllers\BranchController;
use App\Modules\FiscalYearSetup\Entities\FiscalYearSetup;
use App\Modules\Advance\Entities\AdvanceSettlementPayment;
use App\Modules\Leave\Entities\LeaveEncashmentLogActivity;
use App\Modules\Payroll\Repositories\HoldPaymentInterface;
use App\Modules\Payroll\Repositories\IncomeSetupInterface;
use App\Modules\BulkUpload\Service\Import\PayrollDataImport;
use App\Modules\Setting\Entities\PayrollCalenderTypeSetting;
use App\Modules\Payroll\Repositories\DeductionSetupInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\FiscalYearSetup\Repositories\FiscalYearSetupInterface;
use App\Modules\Advance\Repositories\AdvanceSettlementPaymentInterface;

class PayrollController extends Controller
{
    protected $employee;
    protected $organization;
    private $payrollObj;
    private $organizationObj;
    private $branchObj;
    private $employeeObj;
    private $incomeSetupObj;
    private $deductionSetupObj;
    private $settingObj;
    private $fiscalYearObj;
    private $dropdown;
    private $holdPaymentObj;
    private $advanceSettlementPayment;
    private $advanceObj;


    /**
     *
     */
    public function __construct(
        EmployeeInterface $employee,
        OrganizationInterface $organization,
        PayrollInterface $payrollObj,
        OrganizationInterface $organizationObj,
        BranchInterface $branchObj,
        EmployeeInterface $employeeObj,
        IncomeSetupInterface $incomeSetupObj,
        DeductionSetupInterface $deductionSetupObj,
        SettingInterface $settingObj,
        FiscalYearSetupInterface $fiscalYearObj,
        DropdownInterface $dropdown,
        HoldPaymentInterface $holdPaymentObj,
        AdvanceSettlementPaymentInterface $advanceSettlementPayment,
        AdvanceInterface $advanceObj
    ) {
        $this->employee = $employee;
        $this->organization = $organization;
        $this->payrollObj = $payrollObj;
        $this->organizationObj = $organizationObj;
        $this->branchObj = $branchObj;
        $this->employeeObj = $employeeObj;
        $this->incomeSetupObj = $incomeSetupObj;
        $this->deductionSetupObj = $deductionSetupObj;
        $this->settingObj = $settingObj;
        $this->fiscalYearObj = $fiscalYearObj;
        $this->dropdown = $dropdown;
        $this->holdPaymentObj = $holdPaymentObj;
        $this->advanceSettlementPayment = $advanceSettlementPayment;
        $this->advanceObj = $advanceObj;
    }

    /**
     *
     */
    public function index(Request $request)
    {
        $filter = $request->all();

        $data['payrollModels'] = $this->payrollObj->findAll(20, $filter);
        $data['organizationList'] = $this->organizationObj->getList();
        $data['employeeList'] = $this->employeeObj->getList();
        $data['branchList'] = $this->branchObj->getList();

        $yearArray = [];
        $firstYear = 2022;
        $lastYear = (int) date('Y');
        $dateConverter = new DateConverter();
        for ($i = $firstYear; $i <= $lastYear; $i++) {
            $yearArray[$i] = $i;
        }
        $data['yearList'] = $yearArray;
        $data['nepaliYearList'] = $dateConverter->getNepYears();
        $data['monthList'] = $dateConverter->getEngMonths();
        $data['nepaliMonthList'] = $dateConverter->getNepMonths();

        return view('payroll::payroll.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data['isEdit'] = false;
        $data['organizationList'] = $this->organizationObj->getList();
        $data['employeeList'] = $this->employeeObj->getList();

        $yearArray = [];
        $firstYear = 2022;
        $lastYear = (int) date('Y');
        $dateConverter = new DateConverter();
        for ($i = $firstYear; $i <= $lastYear; $i++) {
            $yearArray[$i] = $i;
        }
        // $data['calendarTypeList'] = $dateConverter->getCalendarTypes();
        $data['calendarTypeList'] = [
            'eng' => 'English'
        ];
        $data['nepcalendarTypeList'] = [
            'nep' => 'Nepali'
        ];
        $data['yearList'] = $dateConverter->getEngYears();
        $data['nepaliYearList'] = $dateConverter->getNepYears();
        $data['monthList'] = $dateConverter->getEngMonths();
        $data['nepaliMonthList'] = $dateConverter->getNepMonths();

        return view('payroll::payroll.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $inputData = $request->all();
        $inputData['calendar_type'] = $inputData['calendar_type'] ?? $inputData['eng_calendar_type'];
        if ($inputData['calendar_type'] == 'nep') {
            $inputData = [
                'organization_id' => $inputData['organization_id'],
                'calendar_type' => $inputData['calendar_type'],
                'year' => $inputData['year'],
                'month' => $inputData['month'],
            ];
            $payrollModel = Payroll::where('organization_id', $request->organization_id)->where('year', $request->year)->where('month', $request->month)->get();
        } else {
            $inputData = [
                'organization_id' => $inputData['organization_id'],
                'calendar_type' => $inputData['eng_calendar_type'],
                'year' => $inputData['eng_year'],
                'month' => $inputData['eng_month'],
            ];
            $payrollModel = Payroll::where('organization_id', $request->organization_id)->where('year', $request->eng_year)->where('month', $request->eng_month)->get();
        }
        // dd($inputData);
        // DB::beginTransaction();
        try {
            if (count($payrollModel) > 0) {
                toastr()->warning('Payroll Already Exists');
            } else {
                $this->payrollObj->create($inputData);

                toastr()->success('Data Created Successfully');
            }
        } catch (\Throwable $e) {
            throw $e;
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('payroll.index'));
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $payrollModel = $this->payrollObj->findOne($id);
        // $payrollModel = $payrollModel::whereHas('payrollEmployees',function($query){
        //     $query->whereHas('incomes',function($q){
        //         $q->whereHas('incomeSetup',function($iq){
        //             $iq->orderBy('order','ASC');
        //         });
        //     });
        // })->get();

        // $payrollModel = $payrollModel::with('payrollEmployees.incomes.incomeSetup')->get();
        // foreach ($payrollModel as $key => $value) {
        //     $pE= $value->payrollEmployees;
        //     foreach($pE as $p){
        //         if(!is_null($p->income)){
        //             $incomes = $p->income->collect()->orderBy('income_setup_id', 'DESC');
        //         }else{
        //             $incomes = [];
        //         }
        //     }
        // }
        // $payrollModel = $payrollModel::with(['payrollEmployees.incomes.incomeSetup' => function($query) {
        //     $query->orderBy('order','ASC');
        // }])->get();

        // dd($payrollModel);
        if ($payrollModel->calendar_type == 'nep') {
            $date_type = 'nepali_date';
        } else {
            $date_type = 'date';
        }
        $payrollModel->payrollEmployees->map(function ($model) use ($payrollModel, $date_type) {
            $advanceSettleModels = AdvanceSettlementPayment::whereHas('advanceModel', function ($query) use ($model) {
                $query->where('employee_id', $model->employee_id)->where('approval_status', 3);
            })
                ->where($date_type, 'like', $payrollModel->year . '-' . sprintf("%02d", $payrollModel->month) . '-%')
                ->get();

            if (count($advanceSettleModels) > 0) {
                $advanceAmount = 0;
                foreach ($advanceSettleModels as $advanceSettleModel) {
                    $advanceAmount += $advanceSettleModel->amount;
                }
                $model->advanceAmount = $advanceAmount;
            }

            return $model;
        });
        $data['payrollModel'] = $payrollModel;
        $incomes = $payrollModel->getIncomes();
// $data['incomes'] = $incomes;
         // Prepend new values at the beginning
        $data['incomes'] = array_merge(
            [
                '1' => 'Basic Salary',
                '2' => 'Leave Deduction',
            ],
            $incomes
        );


        $data['taxExcludeValues'] = $payrollModel->getTaxExcludeValues();
        $data['deductions'] = $payrollModel->getDeductions();
        // $data['incomes'] = $this->incomeSetupObj->getList();
        // $data['deductions'] = $this->deductionSetupObj->getList();
        // $data['deductions'] = $this->deductionSetupObj->getMonthlyDeductionList();
        $data['leaveEncashmentSetupStatus'] = false;
        if ($payrollModel->checkCompleted()) {
            $leaveEncashmentLog = LeaveEncashmentLogActivity::where('payroll_id', $payrollModel->id)->first();
            if ($leaveEncashmentLog) {
                $data['leaveEncashmentSetupStatus'] = true;
            }
            return view('payroll::payroll.lock', $data);
        }

        $leaveEncashmentSetup = LeaveEncashmentSetup::where([
            'organization_id' => $payrollModel->organization_id,
            'month' => $payrollModel->month,
        ])->get();
        $leaveDataDetail['leaveTypeList'] = null;
        $data['leaveEncashmentIncomeIds'] = [];
        if ($leaveEncashmentSetup && count($leaveEncashmentSetup) > 0) {
            $leaveYearSetupDetail = LeaveYearSetup::where('leave_year', $payrollModel->year)->first();
            if ($leaveYearSetupDetail) {
                $leaveTypeLists = LeaveType::where([
                    'status' => 11,
                    'encashable_status' => 11
                ])->where('leave_year_id', $leaveYearSetupDetail->id)->pluck('id');
                if ($leaveTypeLists && count($leaveTypeLists) > 0) {
                    $data['leaveEncashmentSetupStatus'] = true;
                    $data['leaveDataDetail']['leaveTypeList'] = $leaveTypeLists;
                    $data['leaveYearSetupDetail'] = $leaveYearSetupDetail;
                    $data['searchData'] = [
                        "leave_year_id" => $leaveYearSetupDetail->id,
                    ];
                    $data['leaveEncashmentIncomeIds'] = $leaveEncashmentSetup->first() ? json_decode($leaveEncashmentSetup->first()->income_type) : [];
                }
            }
        }
        // dd($data['payrollModel']);
        return view('payroll::payroll.show', $data);
    }
    public function showResignedEmployee($id)
    {
        $payrollModel = $this->payrollObj->findOne($id);
        if ($payrollModel->calendar_type == 'nep') {
            $date_type = 'nepali_date';
        } else {
            $date_type = 'date';
        }
        $payrollModel->payrollEmployees->map(function ($model) use ($payrollModel, $date_type) {
            $advanceSettleModels = AdvanceSettlementPayment::whereHas('advanceModel', function ($query) use ($model) {
                $query->where('employee_id', $model->employee_id)->where('approval_status', 3);
            })
                ->where($date_type, 'like', $payrollModel->year . '-' . sprintf("%02d", $payrollModel->month) . '-%')
                ->get();

            if (count($advanceSettleModels) > 0) {
                $advanceAmount = 0;
                foreach ($advanceSettleModels as $advanceSettleModel) {
                    $advanceAmount += $advanceSettleModel->amount;
                }
                $model->advanceAmount = $advanceAmount;
            }

            return $model;
        });

        $data['payrollModel'] = $payrollModel;
        $data['incomes'] = $payrollModel->getIncomes();
        $data['taxExcludeValues'] = $payrollModel->getTaxExcludeValues();
        $data['deductions'] = $payrollModel->getDeductions();
        // $data['incomes'] = $this->incomeSetupObj->getList();
        // $data['deductions'] = $this->deductionSetupObj->getList();
        // $data['deductions'] = $this->deductionSetupObj->getMonthlyDeductionList();

        if ($payrollModel->checkCompleted()) {
            return view('payroll::payroll.lockresigned', $data);
        }

        return view('payroll::payroll.show', $data);
    }
    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        // $data['isEdit'] = true;
        // $data['payrollModel'] = $this->payrollObj->findOne($id);
        // $data['organizationList'] = $this->organizationObj->getList();
        // $data['employeeList'] = $this->employeeObj->getList();

        // return view('payroll::payroll.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(PayrollRequest $request, $id)
    {
        // $data = $request->all();

        // try {
        //     $this->payrollObj->update($id, $data);

        //     toastr()->success('Data Updated Successfully');
        // } catch (\Throwable $e) {
        //     toastr()->error('Something Went Wrong !!!');
        // }

        // return redirect(route('payroll.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $LeaveEncashmentLogActivity = LeaveEncashmentLogActivity::where('payroll_id', $id)->get();

            foreach ($LeaveEncashmentLogActivity as $activity) {
                $activity->leaveEncashmentLog->status = '1';
                $activity->leaveEncashmentLog->encashed_amount = 0;
                $activity->leaveEncashmentLog->save();
                $activity->delete();
            }
            $this->payrollObj->delete($id);

            toastr()->success('Data Deleted Successfully');
        } catch (\Throwable $e) {
            dd($e);
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }

    /**
     *
     */
    public function draft(Request $request, $id)
    {
        $inputData = $request->all();
        $payrollModel = $this->payrollObj->findOne($id);
        // DB::beginTransaction();
        try {
            # update payroll income for mannual entry
            if (isset($inputData['payroll_income']) && count($inputData['payroll_income']) > 0) {
                foreach ($inputData['payroll_income'] as $payrollIncomeId => $value) {
                    $payrollIncomeData['value'] = $inputData['payroll_income'][$payrollIncomeId];
                    $payrollIncomeModel = PayrollIncome::find($payrollIncomeId);

                    // if($inputData['status'] == 2 || (optional($payrollIncomeModel->incomeSetup)->short_name != 'SSF' && $payrollIncomeModel->incomeSetup->daily_basis_status == 10)) {
                    $payrollIncomeModel->update($payrollIncomeData);
                    // }
                }
            }
            # update payroll deduction for mannual entry
            if (isset($inputData['payroll_deduction']) && count($inputData['payroll_deduction']) > 0) {
                foreach ($inputData['payroll_deduction'] as $payrollDeductionId => $value) {
                    $payrollDeductionData['value'] = $value;
                    $payrollDeductionModel = PayrollDeduction::find($payrollDeductionId);
                    // if($inputData['status'] == 2 || optional($payrollDeductionModel->deductionSetup)->method == 3 ){
                    $payrollDeductionModel->update($payrollDeductionData);
                    // }
                }
            }
            if (isset($inputData['payroll_tax_exclude_value']) && count($inputData['payroll_tax_exclude_value']) > 0) {
                foreach ($inputData['payroll_tax_exclude_value'] as $payrollTaxExcludevalueId => $value) {
                    $payrollTaxExcludeData['value'] = $value;
                    // dd($payrollTaxExcludeData);
                    $payrollTaxExcludeModel = PayrollTaxExcludeValue::find($payrollTaxExcludevalueId);
                    $payrollTaxExcludeModel->update($payrollTaxExcludeData);
                }
            }
            if (count($inputData['total_income']) > 0) {
                foreach ($inputData['total_income'] as $payrollEmployeeId => $value) {
                    $payrollEmployeeData['total_income'] = $value;
                    $payrollEmployeeData['marital_status'] = $inputData['marital_status'][$payrollEmployeeId];
                    $payrollEmployeeData['total_days'] = $inputData['total_days'][$payrollEmployeeId];
                    $payrollEmployeeData['total_working_days'] = $inputData['total_working_days'][$payrollEmployeeId];
                    $payrollEmployeeData['extra_working_days'] = $inputData['extra_working_days'][$payrollEmployeeId];
                    $payrollEmployeeData['unpaid_leave_days'] = $inputData['unpaid_leave_days'][$payrollEmployeeId];
                    $payrollEmployeeData['paid_leave_days'] = $inputData['paid_leave_days'][$payrollEmployeeId];
                    $payrollEmployeeData['overtime_pay'] = $inputData['overtime_pay'][$payrollEmployeeId];
                    $payrollEmployeeData['fine_penalty'] = $inputData['fine_penalty'][$payrollEmployeeId];
                    $payrollEmployeeData['total_deduction'] = $inputData['total_deduction'][$payrollEmployeeId];
                    $payrollEmployeeData['festival_bonus'] = $inputData['festival_bonus'][$payrollEmployeeId];
                    $payrollEmployeeData['leave_amount'] = $inputData['leave_amount'][$payrollEmployeeId];
                    // $payrollEmployeeData['monthly_total_deduction'] = $inputData['monthly_total_deduction'][$payrollEmployeeId];
                    $payrollEmployeeData['yearly_taxable_salary'] = $inputData['yearly_taxable_salary'][$payrollEmployeeId];
                    // $payrollEmployeeData['sst'] = $inputData['sst'][$payrollEmployeeId];
                    $payrollEmployeeData['sst'] = $inputData['sst'][$payrollEmployeeId];
                    $payrollEmployeeData['tds'] = $inputData['tds'][$payrollEmployeeId];
                    // $payrollEmployeeData['extra_working_days_amount'] = $inputData['extra_working_days_amount'][$payrollEmployeeId];
                    $payrollEmployeeData['net_salary'] = $inputData['net_salary'][$payrollEmployeeId];
                    $payrollEmployeeData['single_women_tax_credit'] = $inputData['single_women_tax_credit'][$payrollEmployeeId];
                    $payrollEmployeeData['adjustment_status'] = $inputData['adjustment_status'][$payrollEmployeeId];
                    $payrollEmployeeData['adjustment'] = $inputData['adjustment'][$payrollEmployeeId];
                    $payrollEmployeeData['advance_amount'] = $inputData['advance_amount'][$payrollEmployeeId];
                    $payrollEmployeeData['payable_salary'] = $inputData['payable_salary'][$payrollEmployeeId];
                    $payrollEmployeeData['remarks'] = $inputData['remark'][$payrollEmployeeId];
                    $payrollEmployeeData['status'] = $inputData['status'];

                    $payrollEmployeeModel = PayrollEmployee::find($payrollEmployeeId);
                    $payrollEmployeeModel->update($payrollEmployeeData);
                    if ($inputData['status'] == 2) {
                        if ($payrollModel->calendar_type == 'nep') {
                            $date_type = 'nepali_date';
                        } else {
                            $date_type = 'date';
                        }
                        $advanceSettleModels = AdvanceSettlementPayment::whereHas('advanceModel', function ($query) use ($payrollEmployeeModel) {
                            $query->where('employee_id', $payrollEmployeeModel->employee_id)->where('approval_status', 3);
                        })
                            ->where($date_type, 'like', $payrollModel->year . '-' . sprintf("%02d", $payrollModel->month) . '-%')
                            ->get();

                        if (count($advanceSettleModels) > 0) {
                            foreach ($advanceSettleModels as $advanceSettleModel) {
                                $data['status'] = 11;
                                $this->advanceSettlementPayment->update($advanceSettleModel->id, $data);
                                $count = AdvanceSettlementPayment::where('advance_id', $advanceSettleModel->advance_id)->where('status', 10)->count();
                                if ($count == 0) {
                                    $advanceStatus['status'] = 3;
                                    $this->advanceObj->updateAdvanceStatus($advanceSettleModel->advance_id, $advanceStatus);
                                } else {
                                    $advanceStatus['status'] = 2;
                                    $this->advanceObj->updateAdvanceStatus($advanceSettleModel->advance_id, $advanceStatus);
                                }
                            }
                        }

                        if (isset($inputData['encashmentArrayDetails']) && count($inputData['encashmentArrayDetails']) > 0) {
                            foreach ($inputData['encashmentArrayDetails'] as $employeeId => $value) {
                                if (!empty($value)) {
                                    foreach (json_decode($value) as $id => $data) {
                                        $leaveEncashmentLog = LeaveEncashmentLog::where([
                                            'employee_id' => $employeeId,
                                            'leave_type_id' => $id
                                        ])->first();
                                        $leaveData = [
                                            'employee_id' => $employeeId,
                                            'leave_type_id' => $id,
                                            'encashment_threshold' => $data->encashable_limit,
                                            'leave_remaining' => $data->balance,
                                            'exceeded_balance' => $data->encashable,
                                            'total_balance' => $data->balance,
                                            'eligible_encashment' => $data->encashable,
                                            'encashed_date' => Carbon::now()->format('Y-m-d H:i:s'),
                                            'encashed_amount' => $data->amount,
                                            'status' => 2
                                        ];
                                        if (!$leaveEncashmentLog) {
                                            $leaveEncashmentLog = new LeaveEncashmentLog();
                                        }
                                        $leaveEncashmentLog->fill($leaveData);
                                        $leaveEncashmentLog->save();
                                        if ($leaveEncashmentLog->leaveEncashmentLogActivity) {
                                            $leaveEncashmentLog->leaveEncashmentLogActivity->delete();
                                        }
                                        LeaveEncashmentLogActivity::insert([
                                            'leave_encashment_log_id' => $leaveEncashmentLog->id,
                                            'encashed_leave_balance' => $data->encashable ?? 0,
                                            'payroll_id' => $payrollModel->id,
                                            'employee_id' => $employeeId
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            toastr()->success('Payroll updated Successfully');
        } catch (\Throwable $th) {
            throw $th;
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }


    public function viewEmployee(Request $request, $id)
    {
        $filter = $request->all();
        $filter['payroll_id'] = $id;
        $payrollModel = $this->payrollObj->findOne($id);
        $filter['organization_id'] = $payrollModel->organization_id;
        $data['payrollModel'] = $payrollModel;
        $data['employeeList'] = $this->employeeObj->employeeListWithFilter($filter);
        $data['payrollEmployeeModels'] = $this->payrollObj->findAllPayrollEmployee($limit = 50, $filter);
        $data['holdPayment'] = $this->holdPaymentObj->getHoldPaymentEmployeeWithStatus($payrollModel->year, $payrollModel->month, $payrollModel->organization_id);
        return view('payroll::payroll.view-employee', $data);
    }


    public function employeeHistory($id)
    {
        $payrollEmployee = $this->payrollObj->findPayrollEmployee($id);
        $payrollModel = $this->payrollObj->findOne($payrollEmployee->payroll_id);
        $data['deductions'] = $payrollModel->getDeductions();
        $data['incomes'] = $payrollModel->getIncomes();
        $data['payrollEmployee'] = $payrollEmployee;
        return view('payroll::payroll.employee-history', $data);
    }

    public function employeeSalarySlip($id)
    {
        $payrollEmployee = $this->payrollObj->findPayrollEmployee($id);
        $data['incomes'] = $this->incomeSetupObj->getList();
        $data['deductions'] = $this->deductionSetupObj->getMonthlyDeductionList();
        $data['payrollEmployee'] = $payrollEmployee;
        $data['setting'] = $this->settingObj->getData();
        return view('payroll::payroll.employee-salary-slip', $data);
    }
    public function exportStaticData(Request $request)
    {
        $filter = $request->all();
        $payrollModel = $this->payrollObj->findOne($filter['payroll_id']);
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
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Year');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Month');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Employee Id');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Employee Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Over Time Pay');
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Fine and Penalty');
        $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Festival Bonus');

        $num = 2;
        $incr = 0;

        foreach ($payrollModel->payrollEmployees as $key => $value) {
            // dd($value->fine_penalty);

            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $num, ++$incr);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $num, $payrollModel->year);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $num, $payrollModel->month);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $num, $value->employee_id);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $num, optional($value->employee)->getFullName());
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $num, $value->overtime_pay ?? 0);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $num, $value->fine_penalty ?? 0);
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $num, $value->festival_bonus ?? 0);

            $num++;
        }

        $writer = new Xlsx($objPHPExcel);
        $file = 'employee_payroll_data';
        $filename = $file . '.xlsx';
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $writer->save('php://output');
    }
    public function uploadPayrollStaticData(Request $request)
    {
        // dd($request);
        $files = $request->upload_payroll_static_data;
        // dd($files);
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($files);
        \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        array_shift($sheetData);

        $import_file = PayrollDataImport::import($sheetData);

        if ($import_file) {
            toastr()->success('Payroll Data  Imported Successfully');
        }

        return redirect()->back();
    }

    public function salaryTransfer($id)
    {
        $payrollModel = $this->payrollObj->findOne($id);
        $data['holdPayment'] = $this->holdPaymentObj->getHoldPaymentEmployee($payrollModel->year, $payrollModel->month, $payrollModel->organization_id);
        $data['payrollModel'] = $payrollModel;
        $data['setting'] = $this->settingObj->getData();
        return view('payroll::payroll.salary-transfer-letter', $data);
    }
    public function holdPayment($id)
    {
        $data['payrollModel'] = $payrollModel = $this->payrollObj->findOne($id);
        $data['holdPayments'] = $this->holdPaymentObj->getHoldPayment($payrollModel->year, $payrollModel->month, $payrollModel->organization_id);
        $data['employeeList'] = $this->holdPaymentObj->getHoldPaymentEmployeeNameList($payrollModel->year, $payrollModel->month, $payrollModel->organization_id);
        $data['statusList'] =  $status = $this->holdPaymentObj->getStatus();
        $data['employeeList'] = $this->holdPaymentObj->getHoldPaymentEmployeeNameList($payrollModel->year, $payrollModel->month, $payrollModel->organization_id);
        $finalizedPayrollArray = [];
        Payroll::whereHas('payrollEmployee', function ($query) {
            $query->where('status', '2');
        })
            ->where('organization_id', $data['payrollModel']->organization_id)
            ->get()
            ->map(function ($item) use (&$finalizedPayrollArray) {
                return $finalizedPayrollArray[$item->calendar_type][$item->year][] = $item->month;
            });
        $data['finalizedPayrollArray'] = $finalizedPayrollArray;
        return view('payroll::payroll.hold-payment', $data);
    }
    public function logReport(Request $request)
    {
        $filter = $request->all();
        $yearArray = [];
        $data['tax'] = [];
        $firstYear = 2022;
        $lastYear = (int) date('Y');
        $dateConverter = new DateConverter();
        for ($i = $firstYear; $i <= $lastYear; $i++) {
            $yearArray[$i] = $i;
        }
        $data['fiscalYearList'] = $this->fiscalYearObj->find();
        $data['fiscalYear'] = $this->fiscalYearObj->findAll();
        $data['englishFiscalYearList'] = [];
        foreach ($data['fiscalYear'] as $key => $value) {
            $startFiscalDate = $value->start_date_english;
            $endFiscalDate = $value->end_date_english;
            $startFiscalYear = date('Y', strtotime($startFiscalDate));
            $endFiscalYear = date('y', strtotime($endFiscalDate));
            $fiscalYear = $startFiscalYear . '/' . $endFiscalYear;
            $data['englishFiscalYearList'][$fiscalYear] =  $fiscalYear;
        }
        $fiscalYear = FiscalYearSetup::currentFiscalYear();
        $data['yearList'] = $yearArray;
        $data['employeeList'] = $this->employeeObj->getList();
        $data['organizationList'] = $this->organizationObj->getList();
        $data['branchList'] = $this->branchObj->getList();
        $data['nepaliYearList'] = $dateConverter->getNepYears();
        $data['nepaliMonthList'] = $dateConverter->getNepMonths();
        if (isset($filter['organization_id'])) {
            if (isset($filter['year']) && $filter['year']) {
                $filter['year'] = $filter['year'];
                $startMonth = $fiscalYear->end_date;
                $startMonth = explode('-', $startMonth);
                $startMonth = (int) $startMonth[1];
                $data['months'] = $months =  [
                    4 => 'Shrawan',
                    5 =>  'Bhadra',
                    6 => 'Ashwin',
                    7 => 'Kartik',
                    8 => 'Mangsir',
                    9 => 'Poush',
                    10 => 'Magh',
                    11 => 'Falgun',
                    12 => 'Chaitra',
                    1 => 'Baisakh',
                    2 => 'Jestha',
                    3 => 'Ashad',
                ];
            } else {
                $filter['year'] = $filter['eng_year'];
                $startMonth = $fiscalYear->end_date_english;
                $startMonth = explode('-', $startMonth);
                $startMonth = (int) $startMonth[1];
                $data['months'] = $months =  [
                    7 => 'July',
                    8 => 'August',
                    9 => 'September',
                    10 => 'October',
                    11 => 'November',
                    12 => 'December',
                    1 => 'January',
                    2 => 'February',
                    3 => 'March',
                    4 => 'April',
                    5 =>  'May',
                    6 => 'June',
                ];
            }
            $explodeFiscalYear = explode('/', $filter['year']);
            $data['year'] =  $year = $explodeFiscalYear[0];
            $data['organizationModel'] = $this->organizationObj->findOne($filter['organization_id']);
            $data['employeeModel'] = $this->employeeObj->find($filter['employee_id']);
            $incomes = IncomeSetup::where('organization_id', $filter['organization_id'])->get();
            $data['lastMonthPayroll'] = PayrollEmployee::where('employee_id', $filter['employee_id'])->where('status', 2)->latest()->first();
            $data['incomeSetups'] = IncomeSetup::where('organization_id', $filter['organization_id'])->get()->map(function ($incomes) use ($months, $year, $filter, $startMonth) {
                foreach ($months as $key => $value) {
                    if ($key > $startMonth) {
                        $income = PayrollIncome::whereHas('payroll', function ($query) use ($key, $year, $filter) {
                            $query->where('year', $year)->where('month', $key);
                        })->whereHas('payrollEmployee', function ($q) use ($filter) {
                            $q->where('employee_id', $filter['employee_id'])->where('status', 2);
                        })->where('income_setup_id', $incomes->id)->first();
                        if ($income) {
                            $month[$value] = $income->value;
                        } else {
                            $month[$value] = 0;
                        }
                    } else {
                        $income = PayrollIncome::whereHas('payroll', function ($query) use ($key, $year, $filter) {
                            $query->where('year', $year + 1)->where('month', $key);
                        })->whereHas('payrollEmployee', function ($q) use ($filter) {
                            $q->where('employee_id', $filter['employee_id'])->where('status', 2);
                        })->where('income_setup_id', $incomes->id)->first();
                        if ($income) {
                            $month[$value] = $income->value;
                        } else {
                            $month[$value] = 0;
                        }
                    }
                }

                $incomes->income = $month;
                $incomes->show_status = (array_sum($month) > 0) ? true : false;
                return $incomes;
            });
            $data['deductionSetups'] = DeductionSetup::where('organization_id', $filter['organization_id'])->where('monthly_deduction', 11)->get()->map(function ($deductions) use ($months, $year, $filter, $startMonth) {
                foreach ($months as $key => $value) {
                    if ($key > $startMonth) {
                        $deduction = PayrollDeduction::whereHas('payroll', function ($query) use ($key, $year, $filter) {
                            $query->where('year', $year)->where('month', $key);
                        })->whereHas('payrollEmployee', function ($q) use ($filter) {
                            $q->where('employee_id', $filter['employee_id'])->where('status', 2);
                        })->where('deduction_setup_id', $deductions->id)->first();
                        if ($deduction) {
                            $month[$value] = $deduction->value;
                        } else {
                            $month[$value] = 0;
                        }
                    } else {
                        $deduction = PayrollDeduction::whereHas('payroll', function ($query) use ($key, $year, $filter) {
                            $query->where('year', $year + 1)->where('month', $key);
                        })->whereHas('payrollEmployee', function ($q) use ($filter) {
                            $q->where('employee_id', $filter['employee_id'])->where('status', 2);
                        })->where('deduction_setup_id', $deductions->id)->first();
                        if ($deduction) {
                            $month[$value] = $deduction->value;
                        } else {
                            $month[$value] = 0;
                        }
                    }
                }
                $deductions->deduction = $month;
                $deductions->show_status = (array_sum($month) > 0) ? true : false;
                return $deductions;
            });
            foreach ($months as $key => $value) {
                if ($key > $startMonth) {
                    $payrollEmployee = PayrollEmployee::whereHas('payroll', function ($query) use ($key, $year, $filter) {
                        $query->where('year', $year)->where('month', $key);
                    })->where('employee_id', $filter['employee_id'])->where('status', 2)->first();
                    if ($payrollEmployee) {
                        $data['tax']['sst'][$value] = $payrollEmployee->sst;
                        $data['tax']['tds'][$value] = $payrollEmployee->tds;
                    } else {
                        $data['tax']['sst'][$value] = 0;
                        $data['tax']['tds'][$value] = 0;
                    }
                } else {
                    $payrollEmployee = PayrollEmployee::whereHas('payroll', function ($query) use ($key, $year, $filter) {
                        $query->where('year', $year + 1)->where('month', $key);
                    })->where('employee_id', $filter['employee_id'])->first();
                    if ($payrollEmployee) {
                        $data['tax']['sst'][$value] = $payrollEmployee->sst;
                        $data['tax']['tds'][$value] = $payrollEmployee->tds;
                    } else {
                        $data['tax']['sst'][$value] = 0;
                        $data['tax']['tds'][$value] = 0;
                    }
                }
            }
            $data['taxDetail'] = $this->payrollObj->taxDetail($data['lastMonthPayroll']->yearly_taxable_salary, $data['employeeModel']);
            $data['payrollEmployeeModels'] =  PayrollEmployee::whereHas('payroll', function ($query) use ($year, $filter) {
                $query->where('year', $year)->where('month', '>', 3);
                $query->orWhere('year', $year + 1)->where('month', '<=', 3);
                $query->where('organization_id', $filter['organization_id']);
            })->where('employee_id', $filter['employee_id'])->get();

            // dd($data['payrollEmployeeModels']);
        }
        return view('payroll::payroll.log-report.report', $data);
    }

    public function ssfReport(Request $request)
    {
        $filter = $request->all();
        $data['organizationList'] = $this->organizationObj->getList();
        $data['branchList'] = $this->branchObj->getList();
        $data['employeePluck'] = $this->employeeObj->getList();
        $data['fiscalYearList'] = $this->fiscalYearObj->find();
        $data['fiscalYear'] = $this->fiscalYearObj->findAll();
        $data['englishFiscalYearList'] = [];
        foreach ($data['fiscalYear'] as $key => $value) {
            $startFiscalDate = $value->start_date_english;
            $endFiscalDate = $value->end_date_english;
            $startFiscalYear = date('Y', strtotime($startFiscalDate));
            $endFiscalYear = date('y', strtotime($endFiscalDate));
            $fiscalYear = $startFiscalYear . '/' . $endFiscalYear;
            $data['englishFiscalYearList'][$fiscalYear] =  $fiscalYear;
        }
        $fiscalYear = FiscalYearSetup::currentFiscalYear();
        $startMonth = $fiscalYear->end_date;
        $startMonth = explode('-', $startMonth);
        $startMonth = (int) $startMonth[1];
        $yearArray = [];
        $firstYear = 2022;
        $lastYear = (int) date('Y');
        $dateConverter = new DateConverter();
        if (isset($filter['employee_id'])) {
            $data['employee'] = $filter['employee_id'];
        }
        for ($i = $firstYear; $i <= $lastYear; $i++) {
            $yearArray[$i] = $i;
        }
        $data['yearList'] = $yearArray;
        $data['monthList'] = $dateConverter->getEngMonths();
        $data['nepaliYearList'] = $dateConverter->getNepYears();
        $data['nepaliMonthList'] = $dateConverter->getNepMonths();
        $ssf_amount = 0;
        if (isset($filter['organization_id']) && (isset($filter['year']) || isset($filter['eng_year']))) {
            if (isset($filter['year']) && $filter['year']) {
                $filter['year'] = $filter['year'];
                $startMonth = $fiscalYear->end_date;
                $startMonth = explode('-', $startMonth);
                $startMonth = (int) $startMonth[1];
            } else {
                $filter['year'] = $filter['eng_year'];
                $startMonth = $fiscalYear->end_date_english;
                $startMonth = explode('-', $startMonth);
                $startMonth = (int) $startMonth[1];
            }
            $explodeFiscalYear = explode('/', $filter['year']);
            $data['year'] =  $year = $explodeFiscalYear[0];
            $payrollModel = Payroll::where(function ($query) use ($year, $startMonth) {
                $query->where(function ($query) use ($year, $startMonth) {
                    $query->where('year', $year)->where('month', '>', $startMonth);
                });
                $query->orWhere(function ($query) use ($year, $startMonth) {
                    $query->where('year', $year + 1)->where('month', '<=', $startMonth);
                });
            })->where('organization_id', $filter['organization_id'])->get();
            $all_ssf = [];
            $data['finalData'] = [];
            $data['deduction'] = [];
            $sumn1 = [];
            $sum = [];
            foreach ($payrollModel as $key => $value) {
                foreach ($value->payrollEmployees as $payrollEmployee => $payrollEmp) {
                    $employeeName = $payrollEmp->employee->getFullName();
                    $employeeModel = $this->employeeObj->find($payrollEmp->employee_id);
                    foreach ($payrollEmp->deductions as $deduction => $ded) {
                        $sum[$ded->title] = $ded->value;
                        // $t[] = [
                        //     "id" => $ded->deduction_setup_id,
                        //     "name" => $ded->title,
                        //     "value" => $ded->value
                        // ];
                    }
                    if (!isset($sumn1[$payrollEmp->employee_id])) {
                        $sumn1[$payrollEmp->employee_id] = [];
                    }
                    foreach ($sum as $k => $v) {
                        if (!isset($sumn1[$payrollEmp->employee_id][$k])) {
                            $sumn1[$payrollEmp->employee_id][$k] = 0;
                        }
                        $sumn1[$payrollEmp->employee_id][$k] = $sumn1[$payrollEmp->employee_id][$k] + $v;
                    }
                    $data['finalData'][$payrollEmp->employee_id] = [
                        'name' => $employeeName,
                        'code' => $employeeModel->employee_code,
                        // 'deduction' => $t,
                        'sum' => $sumn1[$payrollEmp->employee_id]
                    ];
                    $data['deduction'] = ['deduction' => $sumn1[$payrollEmp->employee_id]];
                }
            }
            // dd( $data['finalData']);

            // foreach ($payrollModel as $key => $value) {
            //     $final_ssf = [];
            //     foreach ($value->payrollEmployees as $key => $payrollEmployee) {
            //         if (!isset($filter['employee_id']) || (isset($filter['employee_id']) && ($filter['employee_id'] == $payrollEmployee->employee_id))) {
            //             $ssf_amount = 0;
            //             foreach ($payrollEmployee->deductions as $deduction) {
            //                 if ($deduction->short_name == 'SSF') {
            //                     $amount = $deduction->value ?? 0;
            //                     $ssf_amount += $amount;
            //                 }
            //                 if ($deduction->short_name == 'SSF1') {
            //                     $amount = $deduction->value ?? 0;
            //                     $ssf_amount += $amount;
            //                 } else {
            //                     $amount = 0;
            //                 }
            //             }
            //             $final_ssf[$payrollEmployee->employee->getFullName()] = $ssf_amount;
            //         }
            //     }
            //     $all_ssf[$value->id] = $final_ssf;

            // }

            // dd($all_ssf);
            // $final2_ssf = [];

            // foreach ($all_ssf as $key => $value) {
            //     foreach ($value as $key => $item) {
            //         if (isset($final2_ssf[$key])) {
            //             $final2_ssf[$key] += $item;
            //         } else {
            //             $final2_ssf[$key] = $item;
            //         }
            //         // array_push($data,[$key,$item]);
            //     }
            // }

            // $data['final2_ssf'] = $final2_ssf;

            // dd($final2_ssf);



        }
        // dd($data['employeeList']);
        return view('payroll::ssf-report.report', $data);
    }

    public function tdsReport(Request $request)
    {
        $filter = $request->all();
        // dd($filter);
        $data['organizationList'] = $this->organizationObj->getList();
        $data['branchList'] = $this->branchObj->getList();
        $data['employeePluck'] = $this->employeeObj->getList();
        $yearArray = [];
        $firstYear = 2022;
        $lastYear = (int) date('Y');
        $dateConverter = new DateConverter();
        for ($i = $firstYear; $i <= $lastYear; $i++) {
            $yearArray[$i] = $i;
        }
        $data['yearList'] = $yearArray;
        // $data['payrollModel'] = [];
        $data['monthList'] = $dateConverter->getEngMonths();
        // dd($data['monthList']);
        $data['nepaliYearList'] = $dateConverter->getNepYears();
        $data['nepaliMonthList'] = $dateConverter->getNepMonths();
        if (isset($filter['organization_id']) && (isset($filter['year']) || isset($filter['eng_year']))  && isset($filter['month']) || (isset($filter['eng_month']))) {
            $data['year'] = (isset($filter['year']) && $filter['year']) ? $filter['year'] : $filter['eng_year'];
            $data['month'] = isset($filter['month']) ? $filter['month'] : $filter['eng_month'];
            $payrollModel = Payroll::where('organization_id', $filter['organization_id'])->where('year', $data['year'])->where('month', $data['month'])->first();
            // dd($payrollModel);
            $data['payrollModel'] = $payrollModel;
        }
        // dd($data['payrollModel']);
        return view('payroll::tds-report.report', $data);
    }

    public function YearlyTaxReport(Request $request)
    {
        return view('payroll::yearly-tax-forecast.report');
    }

    public function yearlyPaySlip(Request $request)
    {
        $filter = $request->all();
        // dd($filter);
        $data['organizationList'] = $this->organizationObj->getList();
        $data['branchList'] = $this->branchObj->getList();
        $data['employeePluck'] = $this->employeeObj->getList();
        $data['fiscalYearList'] = $this->fiscalYearObj->find();
        $data['fiscalYear'] = $this->fiscalYearObj->findAll();
        $data['englishFiscalYearList'] = [];
        foreach ($data['fiscalYear'] as $key => $value) {
            $startFiscalDate = $value->start_date_english;
            $endFiscalDate = $value->end_date_english;
            $startFiscalYear = date('Y', strtotime($startFiscalDate));
            $endFiscalYear = date('y', strtotime($endFiscalDate));
            $fiscalYear = $startFiscalYear . '/' . $endFiscalYear;
            $data['englishFiscalYearList'][$fiscalYear] =  $fiscalYear;
        }
        $fiscalYear = FiscalYearSetup::currentFiscalYear();
        $data['setting'] = $this->settingObj->getData();
        $dateConverter = new DateConverter();
        $data['nepaliYearList'] = $dateConverter->getNepYears();
        $data['nepaliMonthList'] = $dateConverter->getNepMonths();
        if (isset($filter['organization_id']) && isset($filter['employee_id']) && (isset($filter['year']) || isset($filter['eng_year']))) {
            $data['employee'] = $this->employeeObj->find($filter['employee_id']);
            $data['incomes'] = $this->incomeSetupObj->getList();
            $data['deductions'] = $this->deductionSetupObj->getMonthlyDeductionList();
            if (isset($filter['year']) && $filter['year']) {
                $filter['year'] = $filter['year'];
                $endMonth = $fiscalYear->end_date;
                $endMonth = explode('-', $endMonth);
                $endMonth = (int) $endMonth[1];
            } else {
                $filter['year'] = $filter['eng_year'];
                $endMonth = $fiscalYear->end_date_english;
                $endMonth = explode('-', $endMonth);
                $endMonth = (int) $endMonth[1];
            }
            $data['paySlipYear'] = $filter['year'];
            $explodeFiscalYear = explode('/', $filter['year']);
            $data['year'] =  $year = $explodeFiscalYear[0];
            $data['payrollEmployee'] = PayrollEmployee::whereHas('payroll', function ($query) use ($year, $endMonth) {
                $query->where(function ($query) use ($year, $endMonth) {
                    $query->where('year', $year)->where('month', '>', $endMonth);
                });
                $query->orWhere(function ($query) use ($year, $endMonth) {
                    $query->where('year', $year + 1)->where('month', '<=', $endMonth);
                });
            })->where('employee_id', $data['employee']->id)->get();
            // dd($data['payrollEmployee']);

            $data['previousTotalIncome'] = PayrollEmployee::whereHas('payroll', function ($query) use ($year, $endMonth) {
                $query->where(function ($query) use ($year, $endMonth) {
                    $query->where('year', $year)->where('month', '>', $endMonth);
                });
                $query->orWhere(function ($query) use ($year, $endMonth) {
                    $query->where('year', $year + 1)->where('month', '<=', $endMonth);
                });
            })->where('employee_id', $data['employee']->id)->sum('total_income');

            $data['previousTotalDeduction'] = PayrollEmployee::whereHas('payroll', function ($query) use ($year, $endMonth) {
                $query->where(function ($query) use ($year, $endMonth) {
                    $query->where('year', $year)->where('month', '>', $endMonth);
                });
                $query->orWhere(function ($query) use ($year, $endMonth) {
                    $query->where('year', $year + 1)->where('month', '<=', $endMonth);
                });
            })->where('employee_id', $data['employee']->id)->sum('total_deduction');
            $data['previousNetSalary'] = PayrollEmployee::whereHas('payroll', function ($query) use ($year, $endMonth) {
                $query->where(function ($query) use ($year, $endMonth) {
                    $query->where('year', $year)->where('month', '>', $endMonth);
                });
                $query->orWhere(function ($query) use ($year, $endMonth) {
                    $query->where('year', $year + 1)->where('month', '<=', $endMonth);
                });
            })->where('employee_id', $data['employee']->id)->sum('net_salary');
            $data['previousSst'] = PayrollEmployee::whereHas('payroll', function ($query) use ($year, $endMonth) {
                $query->where(function ($query) use ($year, $endMonth) {
                    $query->where('year', $year)->where('month', '>', $endMonth);
                });
                $query->orWhere(function ($query) use ($year, $endMonth) {
                    $query->where('year', $year + 1)->where('month', '<=', $endMonth);
                });
            })->where('employee_id', $data['employee']->id)->sum('sst');
            $data['previousTds'] = PayrollEmployee::whereHas('payroll', function ($query) use ($year, $endMonth) {
                $query->where(function ($query) use ($year, $endMonth) {
                    $query->where('year', $year)->where('month', '>', $endMonth);
                });
                $query->orWhere(function ($query) use ($year, $endMonth) {
                    $query->where('year', $year + 1)->where('month', '<=', $endMonth);
                });
            })->where('employee_id', $data['employee']->id)->sum('tds');
            $data['previousAdvance'] = PayrollEmployee::whereHas('payroll', function ($query) use ($year, $endMonth) {
                $query->where(function ($query) use ($year, $endMonth) {
                    $query->where('year', $year)->where('month', '>', $endMonth);
                });
                $query->orWhere(function ($query) use ($year, $endMonth) {
                    $query->where('year', $year + 1)->where('month', '<=', $endMonth);
                });
            })->where('employee_id', $data['employee']->id)->sum('advance_amount');
            $data['fineAndPenalty'] = PayrollEmployee::whereHas('payroll', function ($query) use ($year, $endMonth) {
                $query->where(function ($query) use ($year, $endMonth) {
                    $query->where('year', $year)->where('month', '>', $endMonth);
                });
                $query->orWhere(function ($query) use ($year, $endMonth) {
                    $query->where('year', $year + 1)->where('month', '<=', $endMonth);
                });
            })->where('employee_id', $data['employee']->id)->sum('fine_penalty');
            $data['payableSalary'] = PayrollEmployee::whereHas('payroll', function ($query) use ($year, $endMonth) {
                $query->where(function ($query) use ($year, $endMonth) {
                    $query->where('year', $year)->where('month', '>', $endMonth);
                });
                $query->orWhere(function ($query) use ($year, $endMonth) {
                    $query->where('year', $year + 1)->where('month', '<=', $endMonth);
                });
            })->where('employee_id', $data['employee']->id)->sum('payable_salary');
            return view('payroll::yearly-pay-slip.report', $data);
        }

        return view('payroll::yearly-pay-slip.index', $data);
    }

    /**
     *
     */
    public function departmentwiseReport(Request $request, $payrollId)
    {
        $filter = $request->all();
        $data['payrollModel'] = $this->payrollObj->findOne($payrollId);
        $data['departmentList'] = $this->dropdown->getFieldBySlug('department');
        // dd($data['departmentList']);
        if (isset($filter['department_id'])) {
            $data['payrollEmployeeDetails'] = PayrollEmployee::whereHas('employee', function ($query) use ($filter) {
                $query->where('department_id', $filter['department_id']);
            })->where('payroll_id', $payrollId)->get()->map(function ($model) {
                $model->department = optional(optional($model->employee)->department)->dropvalue;
                return $model;
            })->groupBy('department');
        } else {
            $data['payrollEmployeeDetails'] = PayrollEmployee::where('payroll_id', $payrollId)->get()->map(function ($model) {
                $model->department = optional(optional($model->employee)->department)->dropvalue;
                return $model;
            })->groupBy('department');
        }


        return view('payroll::payroll.report.departmentwise', $data);
    }

    /**
     *
     */
    public function irdReport($payrollId)
    {
        $payrollModel = $this->payrollObj->findOne($payrollId);
        $data['payrollModel'] = $payrollModel;
        $data['payrollEmployeeDetails'] = $payrollModel->payrollEmployees;

        return view('payroll::payroll.report.ird', $data);
    }
    public function fnfSettlement(Request $request)
    {
        $filter = $request->all();
        $fullAndFinalEmployee = FullAndFinal::pluck('employee_id')->toArray();
        $archivedEmployee = Employee::where('status', '0')->pluck('employee_id')->toArray();
        $employeeList = collect($this->employeeObj->getList())->map(function ($item, $index) use ($archivedEmployee) {
            if (in_array($index, $archivedEmployee)) {
                return $item;
            }
        })->whereNotNull();
        $data['organizationList'] = $this->organizationObj->getList();
        $data['branchList'] = $this->branchObj->getList();
        if (in_array($request->employee_id, $fullAndFinalEmployee)) {
            toastr()->error('Employee Report has generated !!!');
            return redirect()->route('payroll.fnfSettlement');
        }
        $data['employeeList'] = collect($employeeList)->map(function ($item, $index) use ($fullAndFinalEmployee) {
            if (!in_array($index, $fullAndFinalEmployee)) {
                return $item;
            }
            // dd($item,$index);
        })->whereNotNull();
        $retirenmentData = [];
        $advancePayment = 0;
        $adjustmentPayment = 0;
        $employeeData = Employee::where('id', $request->employee_id)->first();
        $taxData = [
            'sst' => 0,
            'tds' => 0
        ];
        if (isset($filter['organization_id'])) {
            $deductionItem = ['SSF', 'CIT', 'PF'];
            $organizationDeduction = DeductionSetup::whereIn('short_name', $deductionItem)->orderBy('id', 'DESC')->pluck('short_name');
            $payrollData = Payroll::where('organization_id', $filter['organization_id'])->get()->map(function ($query) use ($filter) {
                if ($query->checkCompleted()) {
                    return $query->payrollEmployees->where('employee_id', $filter['employee_id'])->values()->first();
                }
            })->whereNotNull();
            $holdMergedData = [
                'incomes' => [],
                'deductions' => []
            ];
            $incomesData = [];
            $deductionData = [];
            collect($payrollData->where('hold_status', '1')->map(function ($item) use ($filter, &$incomesData, &$deductionData) {
                $payroll = $item->payroll;
                $holdPaymentStatus = true;
                $holdPayment = HoldPayment::where(
                    [
                        'organization_id' => $payroll->organization_id,
                        'employee_id' => $item->employee_id,
                        'calendar_type' => $payroll->calendar_type,
                        'year' => $payroll->year,
                        'month' => $payroll->month,
                        'status' => 2
                    ]
                )->first();
                if ($holdPayment) {
                    $holdPaymentStatus = false;
                }
                if ($holdPaymentStatus) {
                    $incomes = $item->incomes;
                    $deductions = $item->deductions;
                    $incomesData = $incomes->map(function ($item) {
                        return [
                            'title' => $item->incomeSetup->title,
                            'income_setup_id' => $item->income_setup_id
                        ];
                    })->pluck('title', 'income_setup_id')->toArray();
                    $deductionData = $deductions->pluck('title', 'deduction_setup_id')->toArray();
                    $payrollIncomes = $incomes->pluck('value', 'income_setup_id');
                    $payrollDeductions = $deductions->pluck('value', 'deduction_setup_id');
                    return [
                        'incomes' => $payrollIncomes,
                        'deductions' => $payrollDeductions
                    ];
                }
            })->whereNotNull()->toArray())->map(function ($entry) use (&$holdMergedData) {
                foreach ($entry['incomes'] as $incomeId => $value) {
                    $holdMergedData['incomes'][$incomeId] = ($holdMergedData['incomes'][$incomeId] ?? 0) + $value;
                }
                foreach ($entry['deductions'] as $deductionId => $value) {
                    $holdMergedData['deductions'][$deductionId] = ($holdMergedData['deductions'][$deductionId] ?? 0) + $value;
                }
                return $holdMergedData;
            });
            $payrollData->where('hold_status', '0')->map(function ($item) use ($organizationDeduction, &$retirenmentData, &$taxData, &$advancePayment, &$adjustmentPayment) {
                $taxData['sst'] += $item->sst ?? 0;
                $taxData['tds'] += $item->tds ?? 0;
                $advancePayment += $item->advance_amount;
                $adjustmentPayment += $item->adjustment;
                $item->deductions->whereIn('short_name', $organizationDeduction)->map(function ($q) use (&$retirenmentData) {
                    $retirenmentData[$q->short_name][] = $q->value;
                });
            });
            $retirenmentData = collect($retirenmentData)->map(function ($item, $key) {
                return $key = array_sum($item);
            });

            $data['leaveDetails'] = null;

            $employeeGrossSalarySetup = $employeeData->employeeGrossSalarySetup;
            $gross_salary = optional($employeeGrossSalarySetup)->gross_salary ?? 0;
            $grade = optional($employeeGrossSalarySetup)->grade ?? 0;
            $finalIncomeAmount = 0;
            LeaveEncashmentSetup::where('organization_id', $filter['organization_id'])->pluck('income_type')->map(function ($item) use (&$finalIncomeAmount, $filter, $gross_salary, $grade) {
                $incomeSetups = IncomeSetup::whereIn('id', json_decode($item))->get();
                $basics = 0;
                foreach ($incomeSetups as $key => $value) {
                    $amount = 0;
                    if ($value->method == 2) {
                        if ($value->short_name == 'BS') {
                            foreach ($value->incomeDetail as $incomeDetail) {
                                $per = $incomeDetail->percentage;
                                $basic = ($per / 100) * $gross_salary;
                                $basics += $basic;
                                $amount += ($per / 100) * $gross_salary;
                            }
                        } else {
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
                    $finalIncomeAmount += $amount;
                }
            });
            $finalIncomeAmount = $finalIncomeAmount / 30;
            $data['leaveDetails'] = LeaveEncashmentLog::where([
                'employee_id' => $filter['employee_id'],
            ])->with('leaveType')->get()->map(function ($item) use ($finalIncomeAmount) {
                return [
                    'title' => $item->leaveType->name,
                    'total_balance' => $item->total_balance,
                    'eligible_encashment' => $item->eligible_encashment,
                    'amount' => round($finalIncomeAmount *  $item->eligible_encashment, 2)
                ];
            });
            $data['paymentOnHold'] = collect($holdMergedData['incomes'])->sum() - collect($holdMergedData['deductions'])->sum();
            $data['organizationDeduction'] = $organizationDeduction;
            $data['retirenmentData'] = $retirenmentData;
            $data['holdMergedData'] = $holdMergedData;
            $data['incomesData'] = $incomesData;
            $data['deductionData'] = $deductionData;
            $data['taxData'] = $taxData;
            $data['advancePayment'] = $advancePayment;
            $data['adjustmentPayment'] = $adjustmentPayment;
            $data['employee'] = $employeeData;
            $data['formData'] = base64_encode(json_encode($data));
        }
        return view('payroll::payroll.fnf-settlement.test', $data);
    }

    public function fnfSettlementReports(Request $request)
    {
        $employeeData = FullAndFinal::pluck('employee_id')->toArray();
        $data['fullAndFinalModel'] = FullAndFinal::when($request->employee_id, function ($item) use ($request) {
            return $item->where('employee_id', $request->employee_id);
        })
            ->paginate(10);
        $data['employeeList'] = collect($this->employeeObj->getList())->map(function ($item, $index) use ($employeeData) {
            if (in_array($index, $employeeData)) {
                return $item;
            }
        })->whereNotNull();
        return view('payroll::payroll.fnf-settlement.reports', $data);
    }

    public function fnfSettlementReportsView(Request $request, $id)
    {
        $fullAndFinal = FullAndFinal::findOrFail($id);
        $data = json_decode(base64_decode($fullAndFinal->form_data));
        $data->fine_penalty = $fullAndFinal->fine_penalty;
        $data->adjustment = $fullAndFinal->adjustment;
        $data->remarks = $fullAndFinal->remarks;
        $data->employeeData = $fullAndFinal->employee;
        $result['finalData'] = $data;
        return view('payroll::payroll.fnf-settlement.view', $result);
    }

    public function getIncomeWiseAmount($employeeId, $income)
    {
        $amount = 0;
        $incomeDetails = $income->incomeDetail;
        foreach ($income as $key => $value) {
            $filter = [
                'reference' => 'income',
                'reference_id' => $value->id,
                'employee_id' => $employeeList->id
            ];
            $employeeIncome = $this->employeeSetup->findOne($filter);
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
                }
            }
        }
        dd('ok');
    }

    public function salaryType($type)
    {
        dd('Sumit', $type);

        switch ($type) {
            case 1:
                break;
            case 2:
                break;
            case 3:
                break;
            default:
                return 0;
        }
    }
    // public function fnfSettlement(Request $request)
    // {
    //     $filter = $request->all();
    //     $data['organizationList'] = $this->organizationObj->getList();
    //     $data['branchList'] = $this->branchObj->getList();
    //     $data['employeeList'] = $this->employeeObj->getList();
    //     if (isset($filter['organization_id'])) {
    //         $payrollModel = Payroll::where('organization_id', $filter['organization_id'])->get();
    //         $all_ssf = [];
    //         $data['finalData'] = [];
    //         $data['deduction'] = [];
    //         $sumn1 = [];
    //         $sumn2 = [];
    //         $deductionSum = [];
    //         $incomeSum = [];
    //         $totalLeaveAmount = [];
    //         $totalDeduction = [];
    //         $totalIncome = [];
    //         $totalSst = [];
    //         $totalTds = [];
    //         $netSalary = [];
    //         foreach ($payrollModel as $key => $value) {
    //             foreach ($value->payrollEmployees as $payrollEmployee => $payrollEmp) {
    //                 if ((isset($filter['employee_id']) && $filter['employee_id'] == $payrollEmp->employee_id) || !isset($filter['employee_id'])) {
    //                     $employeeName = $payrollEmp->employee->getFullName();
    //                     $employeeModel = $this->employeeObj->find($payrollEmp->employee_id);
    //                     $totalDeduction[$payrollEmp->employee_id] = PayrollEmployee::where('employee_id', $payrollEmp->employee_id)->sum('total_deduction');
    //                     $totalLeaveAmount[$payrollEmp->employee_id] = PayrollEmployee::where('employee_id', $payrollEmp->employee_id)->sum('leave_amount');
    //                     $totalIncome[$payrollEmp->employee_id] = PayrollEmployee::where('employee_id', $payrollEmp->employee_id)->sum('total_income');
    //                     $totalSst[$payrollEmp->employee_id] = PayrollEmployee::where('employee_id', $payrollEmp->employee_id)->sum('sst');
    //                     $totalTds[$payrollEmp->employee_id] = PayrollEmployee::where('employee_id', $payrollEmp->employee_id)->sum('tds');
    //                     $netSalary[$payrollEmp->employee_id] = PayrollEmployee::where('employee_id', $payrollEmp->employee_id)->sum('net_salary');
    //                     foreach ($payrollEmp->deductions as $deduction => $ded) {
    //                         $deductionSum[$ded->title] = $ded->value;
    //                     }
    //                     foreach ($payrollEmp->incomes as $income => $inc) {
    //                         $incomeSum[optional($inc->incomeSetup)->title] = $inc->value;
    //                     }
    //                     if (!isset($sumn1[$payrollEmp->employee_id])) {
    //                         $sumn1[$payrollEmp->employee_id] = [];
    //                         $sumn2[$payrollEmp->employee_id] = [];
    //                     }
    //                     foreach ($deductionSum as $k => $v) {
    //                         if (!isset($sumn1[$payrollEmp->employee_id][$k])) {
    //                             $sumn1[$payrollEmp->employee_id][$k] = 0;
    //                         }
    //                         $sumn1[$payrollEmp->employee_id][$k] = $sumn1[$payrollEmp->employee_id][$k] + $v;
    //                     }
    //                     foreach ($incomeSum as $s => $t) {
    //                         if (!isset($sumn2[$payrollEmp->employee_id][$s])) {
    //                             $sumn2[$payrollEmp->employee_id][$s] = 0;
    //                         }
    //                         $sumn2[$payrollEmp->employee_id][$s] = $sumn2[$payrollEmp->employee_id][$s] + $t;
    //                     }

    //                     $data['finalData'][$payrollEmp->employee_id] = [
    //                         'name' => $employeeName,
    //                         'code' => $employeeModel->employee_code,
    //                         'totalDeduction' => $totalDeduction[$payrollEmp->employee_id],
    //                         'totalIncome' => $totalIncome[$payrollEmp->employee_id],
    //                         'totalLeaveAmount' => $totalLeaveAmount[$payrollEmp->employee_id],
    //                         'totalSst' => $totalSst[$payrollEmp->employee_id],
    //                         'totalTds' => $totalTds[$payrollEmp->employee_id],
    //                         'netSalary' => $netSalary[$payrollEmp->employee_id],
    //                         'deductionSum' => $sumn1[$payrollEmp->employee_id],
    //                         'incomeSum' => $sumn2[$payrollEmp->employee_id]
    //                     ];
    //                     $data['income'] = ['income' => $sumn2[$payrollEmp->employee_id]];

    //                     $data['deduction'] = ['deduction' => $sumn1[$payrollEmp->employee_id]];
    //                     // dd($data['income'],$data['deduction']);
    //                 }
    //             }
    //         }
    //         // dd($data['finalData']);
    //     }
    //     return view('payroll::payroll.fnf-settlement.report', $data);
    // }




    /**
     * Ajax controller
     */
    public function reCalculate(Request $request)
    {
        $inputData = $request->all();

        $payrollEmployeeId = $inputData['payrollEmployeeId'];
        $totalIncome = $inputData['totalIncome'];
        $totalDeduction = $inputData['totalDeduction'];
        $festivalBonus = $inputData['festivalBonus'];

        $data = [];
        $payrollEmployeeModel = PayrollEmployee::find($payrollEmployeeId);
        if ($payrollEmployeeModel) {
            $data['taxableSalary'] = $payrollEmployeeModel->calculateTaxableSalary($totalIncome, $totalDeduction, $festivalBonus, $payrollEmployeeId);
            $data['sst'] = $payrollEmployeeModel->calculateSST($totalIncome, $totalDeduction, $festivalBonus, $payrollEmployeeId);
            // $data['sst'] = 0;
            $data['tds'] = $payrollEmployeeModel->calculateTDS($totalIncome, $totalDeduction, $festivalBonus, $payrollEmployeeId);
            if (optional(optional($payrollEmployeeModel->employee)->getMaritalStatus)->dropvalue == 'Single' && optional(optional($payrollEmployeeModel->employee)->getGender)->dropvalue == 'Female') {
                $data['single_women_tax'] = round(0.1 * ($data['sst'] + $data['tds']), 2);
            } else {
                $data['single_women_tax'] = 0;
            }
        }

        return response()->json($data, 200);
    }




    public function taxCalculation($id)
    {
        $data['payrollEmployee'] = $payrollEmployee =  $this->payrollObj->findPayrollEmployee($id);
        $data['payrollModel'] = $payrollModel =  $this->payrollObj->findOne($data['payrollEmployee']->payroll_id);
        $fiscalYear = FiscalYearSetup::currentFiscalYear();
        $employeeModel = Employee::where('id', $payrollEmployee->employee_id)->first();

        if ($payrollModel->calendar_type == 'nep') {
            $joinDate = $employeeModel->nepali_join_date;
            $join_month = explode('-', $joinDate);
            $join_month = (int) $join_month[1];
            $start_fiscal_year = Carbon::parse($fiscalYear->start_date)->isoFormat('Y');
            $end_fiscal_date = $fiscalYear->end_date;
            $terminatedDate = $employeeModel->nep_archived_date;
        } else {
            $join_month = Carbon::parse($employeeModel->join_date)->isoFormat('M');
            $joinDate = $employeeModel->join_date;
            $start_fiscal_year = Carbon::parse($fiscalYear->start_date_english)->isoFormat('Y');
            $end_fiscal_date = $fiscalYear->end_date_english;
            $terminatedDate = $employeeModel->archived_date;
        }
        $taxableAmount = 0;
        $endMonth = explode('-', $end_fiscal_date);
        $endMonth = (int) $endMonth[1];

        if ($payrollModel->month > $endMonth) {
            $start_fiscal_year = $payrollModel->year;
        } else {
            $start_fiscal_year = $payrollModel->year - 1;
        }

        if ($payrollModel->calendar_type == 'nep') {
            if ($joinDate < $fiscalYear->start_date) {
                $taxableMonth = 12;
            } else {
                if ($join_month > $endMonth) {
                    $taxableMonth = 12 + $endMonth - $join_month + 1;
                } elseif ($join_month < $endMonth) {
                    $taxableMonth = $endMonth - $join_month + 1;
                } else {
                    $taxableMonth = 1;
                }
            }
        } else {
            if ($joinDate < $fiscalYear->start_date_english) {
                $taxableMonth = 12;
            } else {
                if ($join_month > $endMonth) {
                    $taxableMonth = 12 + $endMonth - $join_month + 1;
                } elseif ($join_month < $endMonth) {
                    $taxableMonth = $endMonth - $join_month + 1;
                } else {
                    $taxableMonth = 1;
                }
            }
        }
        $data['taxableMonth'] = $taxableMonth;

        $data['salaryPaidMonth'] = $salaryPaidMonth = PayrollEmployee::whereHas('payroll', function ($query) use ($start_fiscal_year, $payrollModel, $endMonth) {
            if ($payrollModel->month > $endMonth) {
                $query->where(function ($query) use ($payrollModel, $start_fiscal_year, $endMonth) {
                    $query->where('year', $start_fiscal_year)->where('month', '>', $endMonth)->where('month', '<', (int)$payrollModel->month);
                });
            } else {
                $query->where(function ($query) use ($payrollModel, $start_fiscal_year, $endMonth) {
                    $query->where('year', $start_fiscal_year)->where('month', '>', $endMonth);
                });
                $query->orWhere(function ($query) use ($payrollModel, $start_fiscal_year, $endMonth) {
                    $query->where('year', $start_fiscal_year + 1)->where('month', '<=', $endMonth)->where('month', '<', (int)$payrollModel->month);
                });
            }
        })->where('employee_id', $payrollEmployee->employee_id)->count();


        $data['previousTotalIncome'] = $previousTotalIncome = PayrollEmployee::whereHas('payroll', function ($query) use ($start_fiscal_year, $payrollModel, $endMonth) {
            if ($payrollModel->month > $endMonth) {
                $query->where(function ($query) use ($payrollModel, $start_fiscal_year, $endMonth) {
                    $query->where('year', $start_fiscal_year)->where('month', '>', $endMonth)->where('month', '<', (int)$payrollModel->month);
                });
            } else {
                $query->where(function ($query) use ($payrollModel, $start_fiscal_year, $endMonth) {
                    $query->where('year', $start_fiscal_year)->where('month', '>', $endMonth);
                });
                $query->orWhere(function ($query) use ($payrollModel, $start_fiscal_year, $endMonth) {
                    $query->where('year', $start_fiscal_year + 1)->where('month', '<=', $endMonth)->where('month', '<', (int)$payrollModel->month);
                });
            }
        })->where('employee_id', $payrollEmployee->employee_id)->sum('total_income');


        $employeededuction = EmployeeSetup::whereHas('deduction', function ($query) {
            $query->where('monthly_deduction', 11);
        })->where('reference', 'deduction')->where('employee_id', $employeeModel->id)->pluck('amount', 'id')->toArray();

        $employeeDeduction = EmployeeSetup::whereHas('deduction', function ($query) {
            $query->where('monthly_deduction', 11);
        })->where('reference', 'deduction')->where('employee_id', $employeeModel->id)->get();
        $monthlyDeduction = 0;
        if ($employeededuction) {
            foreach ($employeededuction as $key => $value) {
                $monthlyDeduction = $monthlyDeduction + $value;
            }
        }
        $data['previousTotalDeduction'] = $previousTotalDeduction = PayrollEmployee::whereHas('payroll', function ($query) use ($start_fiscal_year, $payrollModel, $endMonth) {
            if ($payrollModel->month >  $endMonth) {
                $query->where(function ($query) use ($payrollModel, $start_fiscal_year, $endMonth) {
                    $query->where('year', $start_fiscal_year)->where('month', '>', $endMonth)->where('month', '<', (int)$payrollModel->month);
                });
            } else {
                $query->where(function ($query) use ($payrollModel, $start_fiscal_year, $endMonth) {
                    $query->where('year', $start_fiscal_year)->where('month', '>', $endMonth);
                });
                $query->orWhere(function ($query) use ($payrollModel, $start_fiscal_year, $endMonth) {
                    $query->where('year', $start_fiscal_year + 1)->where('month', '<=', $endMonth)->where('month', '<', (int)$payrollModel->month);
                });
            }
        })->where('employee_id', $payrollEmployee->employee_id)->sum('total_deduction');


        $data['totalDeduction'] = $totalDeduction = $payrollEmployee->total_deduction ? $payrollEmployee->total_deduction : 0;
        $data['taxableAmount'] = $taxableAmount = $payrollEmployee->yearly_taxable_salary ? round($payrollEmployee->yearly_taxable_salary, 2) : 0;
        $data['monthlyDeduction'] = $monthlyDeduction;
        $data['taxDetail'] = $this->payrollObj->taxDetail($taxableAmount, $employeeModel);
        return view('payroll::payroll.tax-calculation', $data);
    }



    public function taxCalculationBackup($id)
    {
        $data['payrollEmployee'] = $payrollEmployee =  $this->payrollObj->findPayrollEmployee($id);
        $data['payrollModel'] = $payrollModel =  $this->payrollObj->findOne($data['payrollEmployee']->payroll_id);
        $fiscalYear = FiscalYearSetup::currentFiscalYear();
        $employeeModel = Employee::where('id', $payrollEmployee->employee_id)->first();

        if ($payrollModel->calendar_type == 'nep') {
            $joinDate = $employeeModel->nepali_join_date;
            $join_month = explode('-', $joinDate);
            $join_month = (int) $join_month[1];
            $start_fiscal_year = Carbon::parse($fiscalYear->start_date)->isoFormat('Y');
            $end_fiscal_date = $fiscalYear->end_date;
            $terminatedDate = $employeeModel->nep_archived_date;
        } else {
            $join_month = Carbon::parse($employeeModel->join_date)->isoFormat('M');
            $joinDate = $employeeModel->join_date;
            $start_fiscal_year = Carbon::parse($fiscalYear->start_date_english)->isoFormat('Y');
            $end_fiscal_date = $fiscalYear->end_date_english;
            $terminatedDate = $employeeModel->archived_date;
        }
        $taxableAmount = 0;
        $endMonth = explode('-', $end_fiscal_date);
        $endMonth = (int) $endMonth[1];

        if ($payrollModel->month > $endMonth) {
            $start_fiscal_year = $payrollModel->year;
        } else {
            $start_fiscal_year = $payrollModel->year - 1;
        }

        if ($payrollModel->calendar_type == 'nep') {
            if ($joinDate < $fiscalYear->start_date) {
                $taxableMonth = 12;
            } else {
                if ($join_month > $endMonth) {
                    $taxableMonth = 12 + $endMonth - $join_month + 1;
                } elseif ($join_month < $endMonth) {
                    $taxableMonth = $endMonth - $join_month + 1;
                } else {
                    $taxableMonth = 1;
                }
            }
        } else {
            if ($joinDate < $fiscalYear->start_date_english) {
                $taxableMonth = 12;
            } else {
                if ($join_month > $endMonth) {
                    $taxableMonth = 12 + $endMonth - $join_month + 1;
                } elseif ($join_month < $endMonth) {
                    $taxableMonth = $endMonth - $join_month + 1;
                } else {
                    $taxableMonth = 1;
                }
            }
        }
        $data['taxableMonth'] = $taxableMonth;
        $data['salaryPaidMonth'] = $salaryPaidMonth = PayrollEmployee::whereHas('payroll', function ($query) use ($start_fiscal_year, $payrollModel, $endMonth) {
            if ($payrollModel->month > $endMonth) {
                $query->where(function ($query) use ($payrollModel, $start_fiscal_year, $endMonth) {
                    $query->where('year', $start_fiscal_year)->where('month', '>', $endMonth)->where('month', '<', (int)$payrollModel->month);
                });
            } else {
                $query->where(function ($query) use ($payrollModel, $start_fiscal_year, $endMonth) {
                    $query->where('year', $start_fiscal_year)->where('month', '>', $endMonth);
                });
                $query->orWhere(function ($query) use ($payrollModel, $start_fiscal_year, $endMonth) {
                    $query->where('year', $start_fiscal_year + 1)->where('month', '<=', $endMonth)->where('month', '<', (int)$payrollModel->month);
                });
            }
        })->where('employee_id', $payrollEmployee->employee_id)->count();


        $data['previousTotalIncome'] = $previousTotalIncome = PayrollEmployee::whereHas('payroll', function ($query) use ($start_fiscal_year, $payrollModel, $endMonth) {
            if ($payrollModel->month > $endMonth) {
                $query->where(function ($query) use ($payrollModel, $start_fiscal_year, $endMonth) {
                    $query->where('year', $start_fiscal_year)->where('month', '>', $endMonth)->where('month', '<', (int)$payrollModel->month);
                });
            } else {
                $query->where(function ($query) use ($payrollModel, $start_fiscal_year, $endMonth) {
                    $query->where('year', $start_fiscal_year)->where('month', '>', $endMonth);
                });
                $query->orWhere(function ($query) use ($payrollModel, $start_fiscal_year, $endMonth) {
                    $query->where('year', $start_fiscal_year + 1)->where('month', '<=', $endMonth)->where('month', '<', (int)$payrollModel->month);
                });
            }
        })->where('employee_id', $payrollEmployee->employee_id)->sum('total_income');


        $employeededuction = EmployeeSetup::whereHas('deduction', function ($query) {
            $query->where('monthly_deduction', 11);
        })->where('reference', 'deduction')->where('employee_id', $employeeModel->id)->pluck('amount', 'id')->toArray();

        $employeeDeduction = EmployeeSetup::whereHas('deduction', function ($query) {
            $query->where('monthly_deduction', 11);
        })->where('reference', 'deduction')->where('employee_id', $employeeModel->id)->get();
        $monthlyDeduction = 0;
        if ($employeededuction) {
            foreach ($employeededuction as $key => $value) {
                $monthlyDeduction = $monthlyDeduction + $value;
            }
        }
        $data['previousTotalDeduction'] = $previousTotalDeduction = PayrollEmployee::whereHas('payroll', function ($query) use ($start_fiscal_year, $payrollModel, $endMonth) {
            if ($payrollModel->month >  $endMonth) {
                $query->where(function ($query) use ($payrollModel, $start_fiscal_year, $endMonth) {
                    $query->where('year', $start_fiscal_year)->where('month', '>', $endMonth)->where('month', '<', (int)$payrollModel->month);
                });
            } else {
                $query->where(function ($query) use ($payrollModel, $start_fiscal_year, $endMonth) {
                    $query->where('year', $start_fiscal_year)->where('month', '>', $endMonth);
                });
                $query->orWhere(function ($query) use ($payrollModel, $start_fiscal_year, $endMonth) {
                    $query->where('year', $start_fiscal_year + 1)->where('month', '<=', $endMonth)->where('month', '<', (int)$payrollModel->month);
                });
            }
        })->where('employee_id', $payrollEmployee->employee_id)->sum('total_deduction');


        $attendance = $payrollEmployee->calculateAttendance($payrollModel->calendar_type, $payrollModel->year, $payrollModel->month);
        $leave = $payrollEmployee->calculateLeave($payrollModel->calendar_type, $payrollModel->year, $payrollModel->month);
        $total_paid_leave = $leave['paidLeaveTaken'];
        $total_leave = $leave['unpaid_days'] + $leave['unpaidLeaveTaken'];

        $totalDeduction = 0;
        $totalIncome = 0;
        $totalTaxExcludeAmount = 0;
        $total_days = 0;

        foreach ($payrollEmployee->incomes as $income) {
            if (optional($income->incomeSetup)->monthly_income == 11) {
                $incomeModel = optional($income->incomeSetup);
                if ($incomeModel->daily_basis_status == 11) {
                    if ($attendance['working_days'] == 0) {
                        $incomeAmount = 0;
                    } else {
                        $incomeAmount = $income->value * $attendance['working_days'];
                    }
                } else {
                    $incomeAmount = $income->value;
                }
                if ($incomeModel->short_name == 'SSF') {
                    if ($total_leave > 0) {
                        $incomeAmount = round((($income->value ?? 0) / $attendance['total_days']) * ($attendance['total_days'] - $total_leave), 2);
                    }
                }
                $totalIncome = $totalIncome + $incomeAmount;
            }
        }

        foreach ($payrollEmployee->deductions as $deduction) {
            $deductionModel = optional($deduction->deductionSetup);
            if ($deductionModel->method != 3) {
                if ($total_leave > 0) {
                    $deductionAmount = round((($deduction->value ?? 0) / $attendance['total_days']) * ($attendance['total_days'] - $total_leave), 2);
                } else {
                    $deductionAmount = $deduction->value ?? 0;
                }
            } else {
                $deductionAmount = $deduction->value ?? 0;
            }

            $totalDeduction = $totalDeduction + $deductionAmount;
        }


        $leaveAmount = $payrollEmployee->leave_amount ?? $leave['leave_amount'];
        $fineAndPenalty = $payrollEmployee->fine_penalty ?? 0;
        $data['totalDeduction'] = $totalDeduction = $payrollEmployee->total_deduction ? $payrollEmployee->total_deduction : $totalDeduction + $leaveAmount + $fineAndPenalty;

        $arrear_amount = $payrollEmployee->arrear_amount ?? 0;
        $overTimePay = $payrollEmployee->overtime_pay ?? $attendance['total_ot_amount'];
        $festivalBonus = $payrollEmployee->festival_bonus ? $payrollEmployee->festival_bonus : 0;
        $totalIncome = $payrollEmployee->total_income ? $payrollEmployee->total_income : $totalIncome + $arrear_amount + $overTimePay;
        $data['taxableAmount'] = $taxableAmount = round($payrollEmployee->calculateTaxableSalary($totalIncome, $totalDeduction, $festivalBonus, $payrollEmployee->id), 2);
        $data['monthlyDeduction'] = $monthlyDeduction;
        $data['taxDetail'] = $this->payrollObj->taxDetail($taxableAmount, $employeeModel);
        return view('payroll::payroll.tax-calculation', $data);
    }

    public function payrollReport(Request $request)
    {

        // $payrollModel = $this->payrollObj->latestOne();
        $data['getAllFilterIncome'] = [];
        $data['getAllFilterDeduction'] = [];
        $data['getAllStaticColumn'] = [];
        $query = Payroll::query();
        if ($request->organization_id) {
            $query->where('organization_id', $request->organization_id);
        }
        if ($request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->year_id) {
            $query->where('year', $request->year_id);
        }
        if ($request->month_id) {
            $query->where('month', $request->month_id);
        }
        $payrollModel = null;
        $payrollEmployeeAll = null;
        $data['payrollEmployees'] = [];
        if ($request->hasAny(['organization_id', 'branch_id', 'year_id', 'month_id'])) {
            if ($request->filled(['organization_id', 'branch_id', 'year_id', 'month_id'])) {
                $payrollModel = Payroll::select('payrolls.id')
                    ->where('payrolls.organization_id', $request->get('organization_id'))
                    ->where('payrolls.branch_id', $request->get('branch_id'))
                    ->where('payrolls.year', $request->get('year_id'))
                    ->where('payrolls.month', $request->get('month_id'))
                    ->first();

                $CheckPayrollEmployee = PayrollEmployee::where('status', 2)
                    ->where('payroll_id', $payrollModel->id)
                    ->orderBy('updated_at', 'desc')->first();

                if ($CheckPayrollEmployee) {
                    $payrollEmployeeQuery = PayrollEmployee::where('status', 2)
                        ->where('payroll_id', $payrollModel->id)
                        ->orderBy('updated_at', 'desc');
                    $payrollEmployeeAll = $payrollEmployeeQuery;
                    $payrollEmployeeAll = $payrollEmployeeAll->get();
                    if ($request->employee_id) {
                        $payrollEmployeeQuery->where('employee_id', $request->employee_id);
                    }
                    $payrollEmployeeModel = $payrollEmployeeQuery->get();
                    $data['payrollEmployees'] = $payrollEmployeeModel;
                } else {
                    $payrollModel = null;
                }
            }
        } else {
            $payrollEmployeeModel = PayrollEmployee::where('status', 2)
                ->orderBy('updated_at', 'desc')
                ->get();
            $payrollEmployeeAll = $payrollEmployeeModel;
            $data['payrollEmployees'] = $payrollEmployeeModel;
            if (count($payrollEmployeeAll)) {
                $payrollFirstData = $payrollEmployeeAll->first();
                $payrollModel = $query->where('id', $payrollFirstData['payroll_id'])->first();
            }
        }
        $data['payrollEmployees'] = $payrollEmployees = optional($payrollModel)->payrollEmployees;

        $data['calenderType'] = $calenderType = optional($payrollModel)->calendar_type;
        $data['payrollYear'] = $payrollYear = optional($payrollModel)->year;
        $data['payrollMonth'] = $payrollMonth = optional($payrollModel)->month;
        $data['organizationId'] = $organizationId =  optional($payrollModel)->organization_id;
        $data['organizationList'] = $organizationList =  $this->organizationObj->getAll();
        $data['branchId'] = $branchId =  optional($payrollModel)->branch_id;
        $data['branchList'] = $branchList =  $this->branchObj->getList();
        $payrollModelAll = $this->payrollObj->findByOrganizationId($organizationId);
        $data['year'] = $year = $this->getYear($payrollModelAll, optional($payrollModel)->organization_id);
        $data['month'] = $month = $this->getMonth($payrollModelAll, optional($payrollModel)->year);
        $data['payrollModel'] = $payrollModel;
        $data['taxExcludeValues'] = optional($payrollModel)->getTaxExcludeValues();
        $data['deductions'] = $deductions = optional($payrollModel)->getDeductions() ?? [];
        $data['incomes'] = $incomes = optional($payrollModel)->getIncomes() ?? [];

        $data['staticIncomes'] = $staticIncomes = $this->getStaticIncome();
        $data['incomeCount'] = count($incomes) + count($staticIncomes);
        $data['staticDeduction'] = $staticDeduction = $this->getStaticDeduction();
        $data['staticColumn'] = $staticColumn = $this->StaticColumn();
        $data['deductionCount'] = count($deductions) + count($staticDeduction);

        if (!is_null($payrollModel)) {
            $data['getAllFilterIncome'] = $getAllFilterIncome = $this->getAllFilterIncome($incomes, $staticIncomes);
            if ($request->has(['incomes_id']) && !is_null($request->get('incomes_id'))) {

                $filterDatas = $this->getFilterIncome($getAllFilterIncome, $request->get('incomes_id'));
                $data['incomes'] = $incomes = $filterDatas['incomes'];
                $data['incomeCount'] = $filterDatas['count'];
                $data['staticIncomes'] = $staticIncomes = $filterDatas['staticIncomes'];
            }
            $data['getAllFilterDeduction'] = $getAllFilterDeduction = $this->getAllFilterDeduction($deductions, $staticDeduction);
            if ($request->has(['deduction_id']) && !is_null($request->get('deduction_id'))) {
                $filterDatas = $this->getFilterDeduction($getAllFilterDeduction, $request->get('deduction_id'));
                $data['deductions'] = $deductions = $filterDatas['deduction'];
                $data['deductionCount'] = $filterDatas['count'];
                $data['staticDeduction'] = $staticDeduction = $filterDatas['staticDeduction'];
            }

            $data['getAllStaticColumn'] = $getAllStaticColumn = $staticColumn;
            if ($request->has(['column_id']) && !is_null($request->get('column_id'))) {
                $data['staticColumn'] = $staticColumn = $this->getDataByIds($getAllStaticColumn, $request->get('column_id'));
            }
        }
        return view('payroll::reports.index', $data);
    }

    public function citReport(Request $request)
    {
        $query = Payroll::query();
        if ($request->organization_id) {
            $query->where('organization_id', $request->organization_id);
        }
        if ($request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->year_id) {
            $query->where('year', $request->year_id);
        }
        if ($request->month_id) {
            $query->where('month', $request->month_id);
        }

        $payrollModel = null;
        $payrollEmployeeAll = null;
        $data['payrollEmployees'] = [];
        if ($request->hasAny(['organization_id', 'branch_id', 'year_id', 'month_id'])) {
            // if ($request->filled(['organization_id','branch_id', 'year_id', 'month_id'])) {
            $payrollModel = $query->first();
            if ($payrollModel) {
                $payrollEmployeeQuery = PayrollEmployee::where('status', 2)
                    ->where('payroll_id', $payrollModel->id)
                    ->orderBy('updated_at', 'desc');
                $payrollEmployeeAll = $payrollEmployeeQuery;
                $payrollEmployeeAll = $payrollEmployeeAll->get();
                if ($request->employee_id) {
                    $payrollEmployeeQuery->where('employee_id', $request->employee_id);
                }
                $payrollEmployeeModel = $payrollEmployeeQuery->get();
                $data['payrollEmployees'] = $payrollEmployeeModel;
            }
            // }
        } else {
            $payrollEmployeeModel = PayrollEmployee::where('status', 2)
                ->orderBy('updated_at', 'desc')
                ->get();
            $payrollEmployeeAll = $payrollEmployeeModel;
            $data['payrollEmployees'] = $payrollEmployeeModel;
            if (count($payrollEmployeeAll)) {
                $payrollFirstData = $payrollEmployeeAll->first();
                $payrollModel = $query->where('id', $payrollFirstData['payroll_id'])->first();
            }
        }
        $data['employeeList'] = $employeeList = $this->getEmployeeList($payrollEmployeeAll);

        $data['calenderType'] = $calenderType = optional($payrollModel)->calendar_type;
        $data['organizationId'] = $organizationId =  optional($payrollModel)->organization_id;
        $data['organizationList'] = $organizationList =  $this->organizationObj->getAll();
        $data['branchId'] = $branchId =  optional($payrollModel)->branch_id;
        $data['branchList'] = $branchList =  $this->branchObj->getList();
        $payrollModelAll = $this->payrollObj->findByOrganizationId($organizationId);
        $data['year'] = $year = $this->getYear($payrollModelAll, optional($payrollModel)->organization_id);
        $data['month'] = $month = $this->getMonth($payrollModelAll, optional($payrollModel)->year);
        $data['payrollModel'] = $payrollModel;

        return view('payroll::reports.cit', $data);
    }

    public function ssfReports(Request $request)
    {
        $query = Payroll::query();
        if ($request->organization_id) {
            $query->where('organization_id', $request->organization_id);
        }
        if ($request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->year_id) {
            $query->where('year', $request->year_id);
        }
        if ($request->month_id) {
            $query->where('month', $request->month_id);
        }
        $payrollModel = null;
        $payrollEmployeeAll = null;
        $data['payrollEmployees'] = [];
        if ($request->hasAny(['organization_id', 'branch_id', 'year_id', 'month_id'])) {
            if ($request->filled(['organization_id', 'branch_id', 'year_id', 'month_id'])) {
                $payrollModel = $query->first();
                if ($payrollModel) {
                    $payrollEmployeeQuery = PayrollEmployee::where('status', 2)
                        ->where('payroll_id', $payrollModel->id)
                        ->orderBy('updated_at', 'desc');
                    $payrollEmployeeAll = $payrollEmployeeQuery;
                    $payrollEmployeeAll = $payrollEmployeeAll->get();
                    if ($request->employee_id) {
                        $payrollEmployeeQuery->where('employee_id', $request->employee_id);
                    }
                    $payrollEmployeeModel = $payrollEmployeeQuery->get();
                    $data['payrollEmployees'] = $payrollEmployeeModel;
                }
            }
        } else {
            $payrollEmployeeModel = PayrollEmployee::where('status', 2)
                ->orderBy('updated_at', 'desc')
                ->get();
            $payrollEmployeeAll = $payrollEmployeeModel;
            $data['payrollEmployees'] = $payrollEmployeeModel;
            if (count($payrollEmployeeAll)) {
                $payrollFirstData = $payrollEmployeeAll->first();
                $payrollModel = $query->where('id', $payrollFirstData['payroll_id'])->first();
            }
        }
        $data['employeeList'] = $employeeList = $this->getEmployeeList($payrollEmployeeAll);

        $data['calenderType'] = $calenderType = optional($payrollModel)->calendar_type;
        $data['organizationId'] = $organizationId =  optional($payrollModel)->organization_id;
        $data['organizationList'] = $organizationList =  $this->organizationObj->getAll();
        $data['branchId'] = $branchId =  optional($payrollModel)->brach_id;
        $data['branchList'] = $branchList =  $this->branchObj->getList();
        $payrollModelAll = $this->payrollObj->findByOrganizationId($organizationId);
        $data['year'] = $year = $this->getYear($payrollModelAll, optional($payrollModel)->organization_id);
        $data['month'] = $month = $this->getMonth($payrollModelAll, optional($payrollModel)->year);
        $data['payrollModel'] = $payrollModel;

        return view('payroll::reports.ssf', $data);
    }

    public function pfReport(Request $request)
    {

        $query = Payroll::query();
        if ($request->organization_id) {
            $query->where('organization_id', $request->organization_id);
        }
        if ($request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->year_id) {
            $query->where('year', $request->year_id);
        }
        if ($request->month_id) {
            $query->where('month', $request->month_id);
        }
        $payrollModel = null;
        $payrollEmployeeAll = null;
        $data['payrollEmployees'] = [];
        if ($request->hasAny(['organization_id', 'branch_id', 'year_id', 'month_id'])) {
            if ($request->filled(['organization_id', 'branch_id', 'year_id', 'month_id'])) {
                $payrollModel = $query->first();
                if ($payrollModel) {
                    $payrollEmployeeQuery = PayrollEmployee::where('status', 2)
                        ->where('payroll_id', $payrollModel->id)
                        ->orderBy('updated_at', 'desc');
                    $payrollEmployeeAll = $payrollEmployeeQuery;
                    $payrollEmployeeAll = $payrollEmployeeAll->get();
                    if ($request->employee_id) {
                        $payrollEmployeeQuery->where('employee_id', $request->employee_id);
                    }
                    $payrollEmployeeModel = $payrollEmployeeQuery->get();
                    $data['payrollEmployees'] = $payrollEmployeeModel;
                }
            }
        } else {
            $payrollEmployeeModel = PayrollEmployee::where('status', 2)
                ->orderBy('updated_at', 'desc')
                ->get();
            $payrollEmployeeAll = $payrollEmployeeModel;
            $data['payrollEmployees'] = $payrollEmployeeModel;
            if (count($payrollEmployeeAll)) {
                $payrollFirstData = $payrollEmployeeAll->first();
                $payrollModel = $query->where('id', $payrollFirstData['payroll_id'])->first();
            }
        }
        $data['employeeList'] = $employeeList = $this->getEmployeeList($payrollEmployeeAll);

        $data['calenderType'] = $calenderType = optional($payrollModel)->calendar_type;
        $data['organizationId'] = $organizationId =  optional($payrollModel)->organization_id;
        $data['organizationList'] = $organizationList =  $this->organizationObj->getAll();
        $data['branchId'] = $branchId =  optional($payrollModel)->branch_id;
        $data['branchList'] = $branchList =  $this->branchObj->getList();
        $payrollModelAll = $this->payrollObj->findByOrganizationId($organizationId);
        $data['year'] = $year = $this->getYear($payrollModelAll, optional($payrollModel)->organization_id);
        $data['month'] = $month = $this->getMonth($payrollModelAll, optional($payrollModel)->year);
        $data['payrollModel'] = $payrollModel;

        return view('payroll::reports.pf', $data);
    }

    public function tdsReports(Request $request)
    {

        $query = Payroll::query();
        if ($request->organization_id) {
            $query->where('organization_id', $request->organization_id);
        }
        if ($request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->year_id) {
            $query->where('year', $request->year_id);
        }
        if ($request->month_id) {
            $query->where('month', $request->month_id);
        }
        $payrollModel = null;
        $payrollEmployeeAll = null;
        $data['payrollEmployees'] = [];
        if ($request->hasAny(['organization_id', 'branch_id', 'year_id', 'month_id'])) {
            if ($request->filled(['organization_id', 'branch_id', 'year_id', 'month_id'])) {
                $payrollModel = $query->first();
                if ($payrollModel) {
                    $payrollEmployeeQuery = PayrollEmployee::where('status', 2)
                        ->where('payroll_id', $payrollModel->id)
                        ->orderBy('updated_at', 'desc');
                    $payrollEmployeeAll = $payrollEmployeeQuery;
                    $payrollEmployeeAll = $payrollEmployeeAll->get();
                    if ($request->employee_id) {
                        $payrollEmployeeQuery->where('employee_id', $request->employee_id);
                    }
                    $payrollEmployeeModel = $payrollEmployeeQuery->get();
                    $data['payrollEmployees'] = $payrollEmployeeModel;
                }
            }
        } else {
            $payrollEmployeeModel = PayrollEmployee::where('status', 2)
                ->orderBy('updated_at', 'desc')
                ->get();
            $payrollEmployeeAll = $payrollEmployeeModel;
            $data['payrollEmployees'] = $payrollEmployeeModel;
            if (count($payrollEmployeeAll)) {
                $payrollFirstData = $payrollEmployeeAll->first();
                $payrollModel = $query->where('id', $payrollFirstData['payroll_id'])->first();
            }
        }
        $data['employeeList'] = $employeeList = $this->getEmployeeList($payrollEmployeeAll);

        $data['calenderType'] = $calenderType = optional($payrollModel)->calendar_type;
        $data['organizationId'] = $organizationId =  optional($payrollModel)->organization_id;
        $data['organizationList'] = $organizationList =  $this->organizationObj->getAll();
        $data['branchId'] = $branchId =  optional($payrollModel)->branch_id;
        $data['branchList'] = $branchList =  $this->branchObj->getList();
        $payrollModelAll = $this->payrollObj->findByOrganizationId($organizationId);
        $data['year'] = $year = $this->getYear($payrollModelAll, optional($payrollModel)->organization_id);
        $data['month'] = $month = $this->getMonth($payrollModelAll, optional($payrollModel)->year);
        $data['payrollModel'] = $payrollModel;

        return view('payroll::reports.tds', $data);
    }

    public function branchPayrollReport(Request $request)
    {
        $data = $request->all();
        $dateConverter = new DateConverter();
        // $data['organizationList'] = $this->organizationObj->getList();
        $data['branchList'] = $this->branchObj->getList();
        $data['nepaliYearList'] = $dateConverter->getNepYears();
        $data['nepaliMonthList'] = $dateConverter->getNepMonths();
        $data['payrollModel'] = null;


        if (isset($data['branch_id'])) {
            $data['payrollModel'] = $payrollModel = Payroll::where('year', $data['year'])->where('month', $data['month'])->first();
            $data['incomes'] = $payrollModel ? $payrollModel->getIncomes() : [];
            $data['taxExcludeValues'] = $payrollModel ? $payrollModel->getTaxExcludeValues() : [];
            $data['deductions'] = $payrollModel ? $payrollModel->getDeductions() : [];
            $data['payrollEmployees'] = PayrollEmployee::whereHas('employee', function ($query) use ($data) {
                $query->where('branch_id', $data['branch_id']);
            })->whereHas('payroll', function ($query) use ($data) {
                $query->where('year', $data['year'])
                    ->where('month', $data['month']);
            })->where('status', 2)->get();
            // dd($data['payrollEmployees']);
        }
        return view('payroll::reports.branchwisereport', $data);
    }

    public function branchSummaryReport(Request $request)
    {
        $filter = $request->all();
        $dateConverter = new DateConverter();
        // $data['organizationList'] = $this->organizationObj->getList();
        $data['branchList'] = $this->branchObj->getList();
        $data['nepaliYearList'] = $dateConverter->getNepYears();
        $data['nepaliMonthList'] = $dateConverter->getNepMonths();
        $data['payrollModel'] = null;

        if (isset($filter['year']) && isset($filter['month'])) {
            $data['payrollModel'] = $payrollModel = Payroll::where('year', $filter['year'])->where('month', $filter['month'])->first();
            $data['incomes'] = $payrollModel ? $payrollModel->getIncomes() : [];
            $data['taxExcludeValues'] = $payrollModel ? $payrollModel->getTaxExcludeValues() : [];
            $data['deductions'] = $payrollModel ? $payrollModel->getDeductions() : [];
            $payrollId = $payrollModel ? $payrollModel->id : null;
            if (isset($filter['branch_id'])) {
                $data['payrollEmployeeDetails'] = PayrollEmployee::whereHas('employee', function ($query) use ($filter) {
                    $query->where('branch_id', $filter['branch_id']);
                })->where('payroll_id', $payrollId)->where('status', 2)->get()->map(function ($model) {
                    $model->branch = optional(optional($model->employee)->branchModel)->name;
                    return $model;
                })->groupBy('branch');
                // $data['payrollIncomes'] = PayrollIncome::
            } else {
                // dd(1);
                $data['payrollEmployeeDetails'] = PayrollEmployee::where('payroll_id', $payrollId)->where('status', 2)->get()->map(function ($model) {
                    $model->branch = optional(optional($model->employee)->branchModel)->name;
                    return $model;
                })->groupBy('branch');

                $data['branchWiseIncomeReport'] = Payroll::with([
                    'payrollEmployees.employee.branchModel',
                    'payrollEmployees.incomes',
                ])
                    ->where('id', $payrollId) // Filter by payroll ID
                    ->get()
                    ->flatMap(function ($payroll) {
                        return $payroll->payrollEmployees->flatMap(function ($payrollEmployee) {
                            $branchName = optional($payrollEmployee->employee->branchModel)->name;
                            return $payrollEmployee->incomes
                                ->where('incomeSetup.monthly_income', 11) // Apply the condition here
                                ->map(function ($income) use ($branchName) {
                                    return [
                                        'branch' => $branchName,
                                        'income_setup_id' => $income->income_setup_id,
                                        'value' => $income->value,
                                    ];
                                });
                        });
                    })
                    ->groupBy('branch')
                    ->map(function ($group) {
                        return $group->groupBy('income_setup_id')
                            ->mapWithKeys(function ($incomeGroup, $incomeSetupId) {
                                return [$incomeSetupId => $incomeGroup->sum('value')];
                            });
                    });

                $data['branchWiseDeductionReport'] = Payroll::with([
                    'payrollEmployees.employee.branchModel',
                    'payrollEmployees.deductionModel.deductionSetup', // Load deduction setups
                ])
                    ->where('id', $payrollId)
                    ->get()
                    ->flatMap(function ($payroll) {
                        return $payroll->payrollEmployees->flatMap(function ($payrollEmployee) {
                            $branchName = optional($payrollEmployee->employee->branchModel)->name;

                            return $payrollEmployee->deductionModel
                                ->where('deductionSetup.monthly_deduction', 11) // Apply the condition here
                                ->map(function ($deduction) use ($branchName) {
                                    return [
                                        'branch' => $branchName,
                                        'deduction_setup_id' => $deduction->deduction_setup_id,
                                        'value' => $deduction->value,
                                    ];
                                });
                        });
                    })
                    ->groupBy('branch')
                    ->map(function ($group) {
                        return $group->groupBy('deduction_setup_id')
                            ->mapWithKeys(function ($deductionGroup, $deductionSetupId) {
                                return [$deductionSetupId => $deductionGroup->sum('value')];
                            });
                    });
                // dd($data['branchWiseDeductionReport']);


                // dd( $data['branchWiseDeductionReport']);


            }
        }

        return view('payroll::reports.branchsummaryreport', $data);
    }

    public function annualProjectionReport(Request $request)
    {
        $filter = $request->all();
        $data['organizationList'] = $this->organizationObj->getList();
        if (isset($filter['organization_id'])) {
            $fiscalYear = FiscalYearSetup::currentFiscalYear();
            $data['employeeList'] = $this->employeeObj->getList();
            $data['organizationList'] = $this->organizationObj->getList();

            if (isset($filter['organization_id'])) {
                $startMonth = $fiscalYear->end_date;
                $startMonth = explode('-', $startMonth);
                $startMonth = (int) $startMonth[1];
                $data['months'] = $months =  [
                    4 => 'Shrawan',
                    5 =>  'Bhadra',
                    6 => 'Ashwin',
                    7 => 'Kartik',
                    8 => 'Mangsir',
                    9 => 'Poush',
                    10 => 'Magh',
                    11 => 'Falgun',
                    12 => 'Chaitra',
                    1 => 'Baisakh',
                    2 => 'Jestha',
                    3 => 'Ashad',
                ];
                $explodeFiscalYear = explode('/', $fiscalYear->fiscal_year);
                $data['year'] =  $year = $explodeFiscalYear[0];
                $data['employees'] = EmployeeSetup::select('employee_id')
                    ->whereHas('employee', function ($query) use ($filter) {
                        $query->where('organization_id', $filter['organization_id']);
                        $query->whereHas('payrollRelatedDetailModel', function ($q) use ($filter) {
                            if (isset($filter['job_type']) && in_array($filter['job_type'], [11, 12])) {
                                $q->where('job_type', $filter['job_type']);
                            }
                        });
                    })
                    ->distinct()
                    ->get()
                    ->map(function ($model) use ($months, $startMonth, $year) {
                        $employeee_id = $model->employee_id;

                        $employeeModel = $this->employeeObj->find($employeee_id);
                        $contract_end_date = optional($employeeModel->payrollRelatedDetailModel)->contract_end_date;
                        $terminatedDate = $employeeModel->nep_archived_date;

                        $contract_end_nep_date = $contract_end_date
                            ? date_converter()->eng_to_nep_convert($contract_end_date)
                            : null;

                        $monthlyIncome = EmployeeSetup::whereHas('income', function ($query) use ($employeee_id) {
                            $query->where('monthly_income', 11)
                                ->whereIn('method', [1, 2])
                                ->where('status', 11);
                        })
                            ->where('reference', 'income')
                            ->where('employee_id', $employeee_id)
                            ->sum('amount');

                        foreach ($months as $key => $value) {
                            $currentYear = ($key > $startMonth) ? $year : $year + 1;

                            // Check if contract end or terminated date is before the current year and month
                            $currentNepaliDate = $currentYear . '-' . sprintf("%02d", $key) . '-01';

                            if (
                                ($contract_end_nep_date && $contract_end_nep_date < $currentNepaliDate) ||
                                ($terminatedDate && $terminatedDate < $currentNepaliDate)
                            ) {
                                $month[$value] = 0;
                            } else {
                                $income = PayrollEmployee::whereHas('payroll', function ($query) use ($key, $currentYear, $employeee_id) {
                                    $query->where('year', $currentYear)
                                        ->where('month', $key);
                                })
                                    ->where('employee_id', $employeee_id)
                                    ->where('status', 2)
                                    ->first();

                                if ($income) {
                                    $month[$value] = $income->total_income;
                                } else {
                                    $month[$value] = $monthlyIncome;
                                }
                            }
                        }

                        $model->incomes = $month;
                        return $model;
                    });
            }
        }
        return view('payroll::reports.annualprojection', $data);
    }


    public function getOrganizationYearMonth(Request $request)
    {
        $organizationId = $request->get('organization_id');
        $payrollEmployeesList = [];
        $incomelist = [];
        $deductionList = [];
        $payrollModelAll = $this->payrollObj->findByOrganizationId($organizationId);
        $payrollIds = $this->getPayrollId($payrollModelAll);
        $payrollModel = Payroll::where('id', $payrollIds)->first();
        if (!is_null($payrollModel)) {
            $payrollEmployees = $payrollModel->payrollEmployees;
            $payrollEmployeesList = $this->getEmployeeList($payrollEmployees);

            $incomes = $payrollModel->getIncomes();
            $staticIncomes = $this->getStaticIncome();
            $incomelist = $this->getAllFilterIncome($incomes, $staticIncomes);

            $deductions = $payrollModel->getDeductions();
            $staticDeduction = $this->getStaticDeduction();
            $deductionList = $this->getAllFilterDeduction($deductions, $staticDeduction);
        }

        if (empty($payrollModelAll)) {
            return response()->json([
                'years' => [],
                'months' => [],
                'employee' => [],
                'incomes' => [],
                'deduction' => [],
            ]);
        }
        $years = array_unique(array_column($payrollModelAll, 'year'));
        $yearOptions = array_combine($years, $years);

        $currentYear = max($years);
        $months = $this->getMonth($payrollModelAll, $currentYear);

        return response()->json([
            'years' => $yearOptions,
            'months' => $months,
            'employee' => $payrollEmployeesList,
            'incomes' => $incomelist,
            'deductions' => $deductionList,
        ]);
    }

    public function getOrganizationMonth(Request $request)
    {
        $organizationId = $request->get('organization_id');
        $year = $request->get('year');
        $payrollModelAll = $this->payrollObj->findByOrganizationId($organizationId);
        if (empty($payrollModelAll)) {
            return response()->json([
                'months' => [],
            ]);
        }
        $months = $this->getMonth($payrollModelAll, $year);
        return response()->json([
            'months' => $months,
        ]);
    }

    public function getFilterIncome($incomes, $ids)
    {
        $data = [];
        $checkValues = array_keys($this->getStaticIncome());
        $staticIds = array_filter($ids, function ($value) use ($checkValues) {
            return in_array($value, $checkValues);
        });
        $incomeIds = array_diff($ids, $checkValues);
        $staticIds = array_values($staticIds);
        $incomeIds = array_values($incomeIds);

        $data['incomes'] = $this->getDataByIds($incomes, $incomeIds);
        $data['count'] = count($ids);
        $data['staticIncomes'] = $this->getStaticIncomeByKey($staticIds);
        return $data;
    }

    public function getDataByIds($incomes, $ids)
    {
        $idsAsKeys = array_flip($ids);
        return array_intersect_key($incomes, $idsAsKeys);
    }

    public function getFilterDeduction($deductions, $ids)
    {

        $data = [];
        $checkValues = array_keys($this->getStaticDeduction());
        $staticIds = array_filter($ids, function ($value) use ($checkValues) {
            return in_array($value, $checkValues);
        });
        $deductionIds = array_diff($ids, $checkValues);
        $staticIds = array_values($staticIds);
        $deductionIds = array_values($deductionIds);

        $data['deduction'] = $this->getDataByIds($deductions, $deductionIds);
        $data['count'] = count($ids);
        $data['staticDeduction'] = $this->getStaticDeductionByKey($staticIds);
        return $data;
    }

    public function StaticColumn()
    {

        return [
            '001' => 'Join Date',
            '002' => 'Marital Status',
            '003' => 'Gender',
            '004' => 'Total Days',
            '005' => 'Total Worked Days',
            '006' => 'Extra Working Days',
            '007' => 'Total Paid Leave Days',
            '008' => 'Total Unpaid Leave Days'
        ];
    }

    public function getStaticIncome()
    {
        $incomes['001'] = 'Arrear Amount';
        $incomes['002'] = 'Over-Time Pay';
        return $incomes;
    }

    public function getStaticDeduction()
    {
        $deduction['001'] = 'Leave Amount';
        $deduction['002'] = 'Fine & Penalty';
        return $deduction;
    }

    public function getStaticDeductionByKey($key)
    {
        $staticIncome = $this->getStaticDeduction();
        return $this->getDataByIds($staticIncome, $key);
    }

    public function getStaticIncomeByKey($key)
    {
        $staticIncome = $this->getStaticIncome();
        return $this->getDataByIds($staticIncome, $key);
    }

    public function getAllFilterIncome($incomes, $staticIncomes)
    {
        return $incomes + $staticIncomes;
    }

    public function getAllFilterDeduction($deductions, $staticDeduction)
    {
        return $deductions + $staticDeduction;
    }

    public function getPayrollId($payrollModelAll)
    {
        if (!count($payrollModelAll)) {
            return [];
        }
        $reindexedArray = array_values($payrollModelAll);
        return $reindexedArray[0]['id'] ?? null;
    }

    public function getEmployeeList($payrollEmployeeList)
    {
        if (is_null($payrollEmployeeList)) {
            return [];
        }
        $employeeIds = array_unique(array_column($payrollEmployeeList->toArray(), 'employee_id'));
        return $this->employeeObj->getEmployeeByIDs($employeeIds);
    }

    public function getMonth($payrollModelAll, $year)
    {
        $payrollModelCollection = collect($payrollModelAll);
        $filteredPayrolls = $payrollModelCollection->filter(function ($payroll) use ($year) {
            return $payroll['year'] == $year;
        });
        $calendarType = $filteredPayrolls->first()['calendar_type'] ?? 'nep';
        $months = $filteredPayrolls->pluck('month')->unique();
        $monthNames = $months->mapWithKeys(function ($month) use ($calendarType) {
            if ($calendarType == 'nep') {
                return [$month => date_converter()->_get_nepali_month($month)];
            } else {
                return [$month => date_converter()->_get_english_month($month)];
            }
        });
        return $monthNames->toArray();
    }

    public function getYear($payrollModelAll, $organizationId)
    {
        $filteredPayrolls = array_filter($payrollModelAll, function ($payroll) use ($organizationId) {
            return $payroll['organization_id'] == $organizationId;
        });
        $years = array_column($filteredPayrolls, 'year');
        $years = array_unique($years);
        return array_combine($years, $years);
    }

    public function saveFullandfinal(Request $request)
    {
        DB::beginTransaction();
        try {
            $formData = json_decode(base64_decode($request->form_data));
            $data = [
                'employee_id' => $formData->employee->employee_id,
                'form_data' => $request->form_data,
                'fine_penalty' => $request->fine_penalty,
                'adjustment' => $request->adjustment,
                'remarks' => $request->remarks,
            ];
            FullAndFinal::create($data);
            DB::commit();
            toastr()->success('Report generated successfully !!!');
            return redirect()->route('payroll.fnfSettlement');
        } catch (\Throwable $th) {
            DB::rollBack();
            toastr()->error('Something Went Wrong !!!');
            return redirect()->back();
        }
    }

    public function fnfSettlementProjectionReports(Request $request)
    {
        $data['organizationList'] = $this->organization->getList();
        $data['employeePluck'] = $this->employee->getList();
        $filter = $request->all();
        $data['organizationList'] = $this->organizationObj->getList();
        $data['branchList'] = $this->branchObj->getList();
        $data['employeeList'] = $this->employeeObj->getList();
        $retirenmentData = [];
        $advancePayment = 0;
        $adjustmentPayment = 0;
        $employeeData = Employee::where('id', $request->employee_id)->first();
        $taxData = [
            'sst' => 0,
            'tds' => 0
        ];
        if (isset($filter['organization_id'])) {
            $deductionItem = ['SSF', 'CIT', 'PF'];
            $organizationDeduction = DeductionSetup::whereIn('short_name', $deductionItem)->orderBy('id', 'DESC')->pluck('short_name');
            $payrollData = Payroll::where('organization_id', $filter['organization_id'])->get()->map(function ($query) use ($filter) {
                if ($query->checkCompleted()) {
                    return $query->payrollEmployees->where('employee_id', $filter['employee_id'])->values()->first();
                }
            })->whereNotNull();
            $holdMergedData = [
                'incomes' => [],
                'deductions' => []
            ];
            $incomesData = [];
            $deductionData = [];
            collect($payrollData->where('hold_status', '1')->map(function ($item) use ($filter, &$incomesData, &$deductionData) {
                $payroll = $item->payroll;
                $holdPaymentStatus = true;
                $holdPayment = HoldPayment::where(
                    [
                        'organization_id' => $payroll->organization_id,
                        'employee_id' => $item->employee_id,
                        'calendar_type' => $payroll->calendar_type,
                        'year' => $payroll->year,
                        'month' => $payroll->month,
                        'status' => 2
                    ]
                )->first();
                if ($holdPayment) {
                    $holdPaymentStatus = false;
                }
                if ($holdPaymentStatus) {
                    $incomes = $item->incomes;
                    $deductions = $item->deductions;
                    $incomesData = $incomes->map(function ($item) {
                        return [
                            'title' => $item->incomeSetup->title,
                            'income_setup_id' => $item->income_setup_id
                        ];
                    })->pluck('title', 'income_setup_id')->toArray();
                    $deductionData = $deductions->pluck('title', 'deduction_setup_id')->toArray();
                    $payrollIncomes = $incomes->pluck('value', 'income_setup_id');
                    $payrollDeductions = $deductions->pluck('value', 'deduction_setup_id');
                    return [
                        'incomes' => $payrollIncomes,
                        'deductions' => $payrollDeductions
                    ];
                }
            })->whereNotNull()->toArray())->map(function ($entry) use (&$holdMergedData) {
                foreach ($entry['incomes'] as $incomeId => $value) {
                    $holdMergedData['incomes'][$incomeId] = ($holdMergedData['incomes'][$incomeId] ?? 0) + $value;
                }
                foreach ($entry['deductions'] as $deductionId => $value) {
                    $holdMergedData['deductions'][$deductionId] = ($holdMergedData['deductions'][$deductionId] ?? 0) + $value;
                }
                return $holdMergedData;
            });
            $payrollData->where('hold_status', '0')->map(function ($item) use ($organizationDeduction, &$retirenmentData, &$taxData, &$advancePayment, &$adjustmentPayment) {
                $taxData['sst'] += $item->sst ?? 0;
                $taxData['tds'] += $item->tds ?? 0;
                $advancePayment += $item->advance_amount;
                $adjustmentPayment += $item->adjustment;
                $item->deductions->whereIn('short_name', $organizationDeduction)->map(function ($q) use (&$retirenmentData) {
                    $retirenmentData[$q->short_name][] = $q->value;
                });
            });
            $retirenmentData = collect($retirenmentData)->map(function ($item, $key) {
                return $key = array_sum($item);
            });

            $data['leaveDetails'] = null;

            $employeeGrossSalarySetup = $employeeData->employeeGrossSalarySetup;
            $gross_salary = optional($employeeGrossSalarySetup)->gross_salary ?? 0;
            $grade = optional($employeeGrossSalarySetup)->grade ?? 0;
            $finalIncomeAmount = 0;
            LeaveEncashmentSetup::where('organization_id', $filter['organization_id'])->pluck('income_type')->map(function ($item) use (&$finalIncomeAmount, $filter, $gross_salary, $grade) {
                $incomeSetups = IncomeSetup::whereIn('id', json_decode($item))->get();
                $basics = 0;
                foreach ($incomeSetups as $key => $value) {
                    $amount = 0;
                    if ($value->method == 2) {
                        if ($value->short_name == 'BS') {
                            foreach ($value->incomeDetail as $incomeDetail) {
                                $per = $incomeDetail->percentage;
                                $basic = ($per / 100) * $gross_salary;
                                $basics += $basic;
                                $amount += ($per / 100) * $gross_salary;
                            }
                        } else {
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
                    $finalIncomeAmount += $amount;
                }
            });
            $finalIncomeAmount = $finalIncomeAmount / 30;
            $data['leaveDetails'] = LeaveEncashmentLog::where([
                'employee_id' => $filter['employee_id'],
            ])->with('leaveType')->get()->map(function ($item) use ($finalIncomeAmount) {
                return [
                    'title' => $item->leaveType->name,
                    'total_balance' => $item->total_balance,
                    'eligible_encashment' => $item->eligible_encashment,
                    'amount' => round($finalIncomeAmount *  $item->eligible_encashment, 2)
                ];
            });
            $data['paymentOnHold'] = collect($holdMergedData['incomes'])->sum() - collect($holdMergedData['deductions'])->sum();
            $data['organizationDeduction'] = $organizationDeduction;
            $data['retirenmentData'] = $retirenmentData;
            $data['holdMergedData'] = $holdMergedData;
            $data['incomesData'] = $incomesData;
            $data['deductionData'] = $deductionData;
            $data['taxData'] = $taxData;
            $data['advancePayment'] = $advancePayment;
            $data['adjustmentPayment'] = $adjustmentPayment;
            $data['employee'] = $employeeData;
        }
        return view('payroll::payroll.fnf-settlement.projectionview', $data);
    }

    public function getTaxCalculation(Request $request)
    {
        try {
            $data['payrollEmployee'] = $payrollEmployee =  $this->payrollObj->findPayrollEmployee($request->employeePayrollEmployee);
            $data['payrollModel'] = $payrollModel =  $this->payrollObj->findOne($data['payrollEmployee']->payroll_id);
            $fiscalYear = FiscalYearSetup::currentFiscalYear();
            $employeeModel = Employee::where('id', $payrollEmployee->employee_id)->first();

            if ($payrollModel->calendar_type == 'nep') {
                $joinDate = $employeeModel->nepali_join_date;
                $join_month = explode('-', $joinDate);
                $join_month = (int) $join_month[1];
                $start_fiscal_year = Carbon::parse($fiscalYear->start_date)->isoFormat('Y');
                $end_fiscal_date = $fiscalYear->end_date;
                $terminatedDate = $employeeModel->nep_archived_date;
            } else {
                $join_month = Carbon::parse($employeeModel->join_date)->isoFormat('M');
                $joinDate = $employeeModel->join_date;
                $start_fiscal_year = Carbon::parse($fiscalYear->start_date_english)->isoFormat('Y');
                $end_fiscal_date = $fiscalYear->end_date_english;
                $terminatedDate = $employeeModel->archived_date;
            }
            $taxableAmount = 0;
            $endMonth = explode('-', $end_fiscal_date);
            $endMonth = (int) $endMonth[1];

            if ($payrollModel->month > $endMonth) {
                $start_fiscal_year = $payrollModel->year;
            } else {
                $start_fiscal_year = $payrollModel->year - 1;
            }

            if ($payrollModel->calendar_type == 'nep') {
                if ($joinDate < $fiscalYear->start_date) {
                    $taxableMonth = 12;
                } else {
                    if ($join_month > $endMonth) {
                        $taxableMonth = 12 + $endMonth - $join_month + 1;
                    } elseif ($join_month < $endMonth) {
                        $taxableMonth = $endMonth - $join_month + 1;
                    } else {
                        $taxableMonth = 1;
                    }
                }
            } else {
                if ($joinDate < $fiscalYear->start_date_english) {
                    $taxableMonth = 12;
                } else {
                    if ($join_month > $endMonth) {
                        $taxableMonth = 12 + $endMonth - $join_month + 1;
                    } elseif ($join_month < $endMonth) {
                        $taxableMonth = $endMonth - $join_month + 1;
                    } else {
                        $taxableMonth = 1;
                    }
                }
            }
            $data['taxableMonth'] = $taxableMonth;

            $data['salaryPaidMonth'] = $salaryPaidMonth = PayrollEmployee::whereHas('payroll', function ($query) use ($start_fiscal_year, $payrollModel, $endMonth) {
                if ($payrollModel->month > $endMonth) {
                    $query->where(function ($query) use ($payrollModel, $start_fiscal_year, $endMonth) {
                        $query->where('year', $start_fiscal_year)->where('month', '>', $endMonth)->where('month', '<', (int)$payrollModel->month);
                    });
                } else {
                    $query->where(function ($query) use ($payrollModel, $start_fiscal_year, $endMonth) {
                        $query->where('year', $start_fiscal_year)->where('month', '>', $endMonth);
                    });
                    $query->orWhere(function ($query) use ($payrollModel, $start_fiscal_year, $endMonth) {
                        $query->where('year', $start_fiscal_year + 1)->where('month', '<=', $endMonth)->where('month', '<', (int)$payrollModel->month);
                    });
                }
            })->where('employee_id', $payrollEmployee->employee_id)->count();


            $data['previousTotalIncome'] = $previousTotalIncome = PayrollEmployee::whereHas('payroll', function ($query) use ($start_fiscal_year, $payrollModel, $endMonth) {
                if ($payrollModel->month > $endMonth) {
                    $query->where(function ($query) use ($payrollModel, $start_fiscal_year, $endMonth) {
                        $query->where('year', $start_fiscal_year)->where('month', '>', $endMonth)->where('month', '<', (int)$payrollModel->month);
                    });
                } else {
                    $query->where(function ($query) use ($payrollModel, $start_fiscal_year, $endMonth) {
                        $query->where('year', $start_fiscal_year)->where('month', '>', $endMonth);
                    });
                    $query->orWhere(function ($query) use ($payrollModel, $start_fiscal_year, $endMonth) {
                        $query->where('year', $start_fiscal_year + 1)->where('month', '<=', $endMonth)->where('month', '<', (int)$payrollModel->month);
                    });
                }
            })->where('employee_id', $payrollEmployee->employee_id)->sum('total_income');

            $employeededuction = EmployeeSetup::whereHas('deduction', function ($query) {
                $query->where('monthly_deduction', 11);
            })->where('reference', 'deduction')->where('employee_id', $employeeModel->id)->pluck('amount', 'id')->toArray();

            $employeeDeduction = EmployeeSetup::whereHas('deduction', function ($query) {
                $query->where('monthly_deduction', 11);
            })->where('reference', 'deduction')->where('employee_id', $employeeModel->id)->get();
            $monthlyDeduction = 0;
            if ($employeededuction) {
                foreach ($employeededuction as $key => $value) {
                    $monthlyDeduction = $monthlyDeduction + $value;
                }
            }
            $data['previousTotalDeduction'] = $previousTotalDeduction = PayrollEmployee::whereHas('payroll', function ($query) use ($start_fiscal_year, $payrollModel, $endMonth) {
                if ($payrollModel->month >  $endMonth) {
                    $query->where(function ($query) use ($payrollModel, $start_fiscal_year, $endMonth) {
                        $query->where('year', $start_fiscal_year)->where('month', '>', $endMonth)->where('month', '<', (int)$payrollModel->month);
                    });
                } else {
                    $query->where(function ($query) use ($payrollModel, $start_fiscal_year, $endMonth) {
                        $query->where('year', $start_fiscal_year)->where('month', '>', $endMonth);
                    });
                    $query->orWhere(function ($query) use ($payrollModel, $start_fiscal_year, $endMonth) {
                        $query->where('year', $start_fiscal_year + 1)->where('month', '<=', $endMonth)->where('month', '<', (int)$payrollModel->month);
                    });
                }
            })->where('employee_id', $payrollEmployee->employee_id)->sum('total_deduction');


            $data['totalDeduction'] = $totalDeduction = $payrollEmployee->total_deduction ? $payrollEmployee->total_deduction : 0;
            $data['taxableAmount'] = $taxableAmount = $payrollEmployee->yearly_taxable_salary ? round($payrollEmployee->yearly_taxable_salary, 2) : 0;

            $data['monthlyDeduction'] = $monthlyDeduction;
            $projectedTotalIncome = 0;
            $projectedTotalDeduction = 0;
            $incomes = [];
            $deductions = [];
            if ($payrollModel->month > $endMonth) {
                $remainingMonth = 12 + $endMonth - $payrollModel->month;
            } else {
                $remainingMonth = $endMonth - $payrollModel->month;
            }
            $employeeSaveIncome = PayrollIncome::where([
                'payroll_id' => $payrollModel->id,
                'payroll_employee_id' => $payrollEmployee->id
            ])->pluck('value', 'income_setup_id')->toArray();
            $employeeIncomeSetupValue = EmployeeSetup::whereHas('income', function ($query) {
                $query->where('monthly_income', 11)->where('status', 11);
            })->where('reference', 'income')->where('employee_id', $employeeModel->id)->get();
            $employeeDeductionSetupValue = EmployeeSetup::whereHas('deduction', function ($query) {
                $query->where('monthly_deduction', 11)->where('status', 11);
            })->where('reference', 'deduction')->where('employee_id', $employeeModel->id)->get();
            $employeeIncome = EmployeeSetup::whereHas('income', function ($query) {
                $query->where('monthly_income', 11)->where('status', 11);
            })->where('reference', 'income')->where('employee_id', $employeeModel->id)->get()->each(function ($item) use ($employeeSaveIncome, &$projectedTotalIncome) {
                $projectedTotalIncome += $item->amount;
                if (isset($employeeSaveIncome[$item->reference_id])) {
                    if ($employeeSaveIncome[$item->reference_id] && $employeeSaveIncome[$item->reference_id] > 0) {
                        $item->amount = $employeeSaveIncome[$item->reference_id];
                    }
                }
            })->pluck('amount', 'id')->toArray();
            $data['employeeModel'] = $employeeModel;
            $employeeSaveDeduction = PayrollDeduction::where([
                'payroll_id' => $payrollModel->id,
                'payroll_employee_id' => $payrollEmployee->id
            ])->pluck('value', 'deduction_setup_id')->toArray();
            $employeededuction = EmployeeSetup::whereHas('deduction', function ($query) {
                $query->where('monthly_deduction', 11)->where('status', 11);
            })->where('reference', 'deduction')->where('employee_id', $employeeModel->id)->get()->each(function ($item) use ($employeeSaveDeduction, &$projectedTotalDeduction) {
                $projectedTotalDeduction += $item->amount;
                if (isset($employeeSaveDeduction[$item->reference_id])) {
                    if ($employeeSaveDeduction[$item->reference_id] && $employeeSaveDeduction[$item->reference_id] > 0) {
                        $item->amount = $employeeSaveDeduction[$item->reference_id];
                    }
                }
            })->pluck('amount', 'id')->toArray();

            $monthlyDeduction = 0;
            $monthlyIncome = 0;
            if ($employeededuction) {
                foreach ($employeededuction as $key => $value) {
                    $monthlyDeduction = $monthlyDeduction + $value;
                }
            }
            if ($employeeIncome) {
                foreach ($employeeIncome as $key => $value) {
                    $monthlyIncome = $monthlyIncome + $value;
                }
            }
            foreach ($employeeIncomeSetupValue as $key => $income) {
                $amount = $income->amount;
                if (isset($employeeSaveIncome[$income->reference_id])) {
                    if ($employeeSaveIncome[$income->reference_id] && $employeeSaveIncome[$income->reference_id] > 0) {
                        $amount = $employeeSaveIncome[$income->reference_id];
                    }
                }
                $incomes[] = [
                    'id' => $income->reference_id,
                    'title' => $income->income->title,
                    'amount' => $income->amount
                ];
            }
            foreach ($employeeDeductionSetupValue as $key => $deduction) {
                $amount = $deduction->amount;
                if (isset($employeeSaveDeduction[$deduction->reference_id]) && $employeeSaveDeduction[$deduction->reference_id] > 0) {
                    $amount = $employeeSaveDeduction[$deduction->reference_id];
                }
                $deductions[] = [
                    'id' => $deduction->reference_id,
                    'title' => $deduction->deduction->title,
                    'amount' => $amount
                ];
            }
            if ($taxableAmount == 0) {
                $data['taxableAmount'] = round($payrollEmployee->calculateTaxableSalary($monthlyIncome, $monthlyDeduction, 0, $payrollEmployee->id), 2) ?? 0;
            }

            $previousTotalBonus  = BonusIncome::whereHas('bonus', function ($query) use ($start_fiscal_year, $payrollModel, $endMonth) {
                if ($payrollModel->month > $endMonth) {
                    $query->where(function ($query) use ($payrollModel, $start_fiscal_year, $endMonth) {
                        $query->where('year', $start_fiscal_year)->where('month', '>', $endMonth)->where('month', '<=', (int)$payrollModel->month);
                    });
                } else {
                    $query->where(function ($query) use ($payrollModel, $start_fiscal_year, $endMonth) {
                        $query->where('year', $start_fiscal_year)->where('month', '>', $endMonth);
                    });
                    $query->orWhere(function ($query) use ($payrollModel, $start_fiscal_year, $endMonth) {
                        $query->where('year', $start_fiscal_year + 1)->where('month', '<=', $endMonth)->where('month', '<=', (int)$payrollModel->month);
                    });
                }
            })->whereHas('bonusEmployee', function ($q) use ($employeeModel) {
                $q->where('employee_id', $employeeModel->id);
            })->whereHas('bonusSetup', function ($qa) {
                // $qa->where('one_time_settlement',10);
            })->sum('value');

            $data['incomes'] = $incomes;
            $data['deductions'] = $deductions;
            $data['monthlyIncome'] = $monthlyIncome;
            $data['monthlyDeduction'] = $monthlyDeduction;
            $data['previousTotalIncome'] = $previousTotalIncome;
            $data['remainingMonth'] = $remainingMonth;
            $data['projectedTotalIncome'] = $projectedTotalIncome;
            $data['projectedTotalDeduction'] = $projectedTotalDeduction;
            $data['taxDetail'] = $this->payrollObj->taxDetail($taxableAmount, $employeeModel);
            $taxView = view('payroll::payroll.tax-calculation-new', $data);
            $response = [
                'error' => false,
                'view' => $taxView->render(),
                'msg' => 'success'
            ];

            return response()->json($response, 200);
        } catch (\Throwable $th) {
            $response = [
                'error' => true,
                'view' => null,
                'msg' => 'error'
            ];
            return response()->json($response, 200);
        }
    }
}
