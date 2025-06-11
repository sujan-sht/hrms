<?php

namespace App\Modules\Payroll\Repositories;

use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Attendance\Entities\Attendance;
use App\Modules\Attendance\Repositories\AttendanceReportRepository;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Leave\Entities\Leave;
use App\Modules\Payroll\Entities\ArrearAdjustment;
use App\Modules\Payroll\Entities\ArrearAdjustmentDetail;
use Illuminate\Support\Facades\DB;
use App\Modules\Payroll\Entities\Payroll;
use App\Modules\Payroll\Entities\EmployeeSetup;
use App\Modules\Payroll\Entities\EmployeeTaxExcludeSetup;
use App\Modules\Payroll\Entities\PayrollIncome;
use App\Modules\Payroll\Entities\PayrollEmployee;
use App\Modules\Payroll\Entities\PayrollDeduction;
use App\Modules\Payroll\Entities\PayrollTaxExcludeValue;
use App\Modules\Payroll\Entities\StopPayment;
use App\Modules\Payroll\Entities\TaxSlab;
use App\Modules\Payroll\Entities\ThresholdBenefitSetup;
use App\Modules\Setting\Entities\Setting;
use Carbon\Carbon;

class PayrollRepository implements PayrollInterface
{
    private $stopPaymentObj;
    private $taxExcludedObj;
    public function __construct(
        StopPaymentInterface $stopPaymentObj,
        TaxExcludeSetupInterface $taxExcludedObj
    ) {
        $this->stopPaymentObj = $stopPaymentObj;
        $this->taxExcludedObj = $taxExcludedObj;
    }
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            $filter['organization'] = optional($authUser->userEmployer)->organization_id;
        }
        $result = Payroll::query();
        if (isset($filter['year']) && $filter['year']) {
            $filter['year'] = $filter['year'];
        }
        if (isset($filter['eng_year']) && $filter['eng_year']) {
            $filter['year'] = $filter['eng_year'];
        }
        if (isset($filter['month']) && $filter['month']) {
            $filter['month'] = $filter['month'];
        }
        if (isset($filter['eng_month']) && $filter['eng_month']) {
            $filter['month'] = $filter['eng_month'];
        }

        // $filter['year'] = (isset($filter['year']) && $filter['year']) ? $filter['year'] : $filter['eng_year'];
        // $filter['month'] = (isset($filter['month']) && $filter['month']) ? $filter['month'] : $filter['eng_month'];
        // if (auth()->user()->user_type != 'admin' && auth()->user()->user_type != 'super_admin') {
        //     $result->where('created_by', auth()->user()->id);
        // }
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
        return Payroll::with('payrollEmployees', 'payrollEmployees.incomes')->find($id);
    }
    public function getEmployeePayrollList($id)
    {
        return PayrollEmployee::where('employee_id', $id)->where('status', 2)->get();
    }

    public function create($data)
    {
        DB::beginTransaction();

        try {
            $payrollModel = Payroll::create($data);
            if ($payrollModel->calendar_type == 'nep') {
                $cal = new DateConverter();
                $data['total_days'] = $cal->getTotalDaysInMonth($payrollModel->year, $payrollModel->month);
                if (strlen($payrollModel->month) == 1) {
                    $month = '0' . $payrollModel->month;
                } else {
                    $month = $payrollModel->month;
                }
                $start_date = $payrollModel->year . '-' . $month . '-01';
                $end_date = $payrollModel->year . '-' . $month . '-' . $data['total_days'];
            } else {
                $data['total_days'] = cal_days_in_month(CAL_GREGORIAN, $payrollModel->month, $payrollModel->year);
                if (strlen($payrollModel->month) == 1) {
                    $month = '0' . $payrollModel->month;
                } else {
                    $month = $payrollModel->month;
                }
                $start_date = $payrollModel->year . '-' . $month . '-01';
                $start_date = date('Y-m-d', strtotime($start_date));
                $end_date = $payrollModel->year . '-' . $month . '-' . $data['total_days'];
                $end_date = date('Y-m-d', strtotime($end_date));
            }
            // dd($payrollModel);
            $year =  $payrollModel->year;
            $month = $payrollModel->month;
            $params['organization_id'] = $payrollModel->organization_id;

            // $employees = EmployeeSetup::select('employee_id')->whereHas('employee', function ($query) use ($params) {
            //     $query->where('organization_id', $params);
            // })->distinct()->get();
            if ($payrollModel->calendar_type == 'nep') {
                $payrollDate = $year . '-' . sprintf("%02d", $month) . '-' . $data['total_days'];
                $payrollFirstDate = $year . '-' . sprintf("%02d", $month) . '-' . '01';
                $employees = EmployeeSetup::select('employee_id')->whereHas('employee', function ($query) use ($params, $payrollModel, $payrollDate, $payrollFirstDate) {
                    $query->where('organization_id', $params);
                    $query->where('nepali_join_date', '<=', $payrollDate);
                    $query->where(function ($q) use ($payrollModel, $payrollFirstDate) {
                        // $q->whereYear('nep_archived_date','>=' , $payrollModel->year)->whereMonth('nep_archived_date', '>=', $payrollModel->month);
                        $q->where('nep_archived_date', '>=', $payrollFirstDate);
                        $q->orWhere('nep_archived_date', null);
                    });
                })->distinct()->get();
            } else {
                $payrollDate = $year . '-' . sprintf("%02d", $month) . '-' . $data['total_days'];
                $payrollFirstDate = $year . '-' . sprintf("%02d", $month) . '-' . '01';
                // dd( $payrollDate);
                $employees = EmployeeSetup::select('employee_id')->whereHas('employee', function ($query) use ($params, $payrollModel, $payrollDate, $payrollFirstDate) {
                    $query->where('organization_id', $params);
                    $query->where('join_date', '<=', $payrollDate);
                    $query->where(function ($q) use ($payrollModel, $payrollFirstDate) {
                        // $q->whereYear('archived_date','>=' , $payrollModel->year)->whereMonth('archived_date', '>=', $payrollModel->month);
                        $q->where('archived_date', '>=', $payrollFirstDate);
                        $q->orWhere('archived_date', null);
                    });
                })->distinct()->get();
                // dd($employees->toArray());
            }
            $holdEmployeeIds = $payrollModel->holdPayment();
            $releaseEmployeeIds = $payrollModel->releasePayment();
            foreach ($employees as $employee) {
                $check_stop_payment = $this->stopPaymentObj->getStopPayment($payrollModel->calendar_type, $employee->employee_id, $start_date, $end_date);
                if (empty($check_stop_payment)) {
                    $payrollEmployeeData = [];
                    $payrollEmployeeData['payroll_id'] = $payrollModel->id;
                    $payrollEmployeeData['employee_id'] = $employee->employee_id;
                    $payrollEmployeeData['hold_status'] =    in_array($employee->employee_id, $holdEmployeeIds);
                    $payrollEmployeeModel = PayrollEmployee::create($payrollEmployeeData);
                    # payroll income
                    $totalIncome = 0;
                    $annualIncome = 0;
                    $incomes = EmployeeSetup::select('reference_id')->where('reference', 'income')->whereHas('income', function ($query) use ($params) {
                        if (isset($params['organization_id'])) {
                            $query->where('organization_id', $params['organization_id']);
                        }
                        $query->where('status', 11);
                    })->distinct()->get()->map(function ($model) {
                        $model->sort = optional($model->income)->order;
                        return $model;
                    })->sortBy('sort');
                    $leaveData = $this->calculateAttendance($payrollModel->calendar_type, $year, $month, $employee->employee_id);
                    $payable_days =  $data['total_days'] - ($leaveData['unpaid_days'] + $leaveData['unpaidLeaveTaken']);
                    $working_days = $leaveData['working_days'];
                    $arrearAdjustmentData = [];
                    $relesaeStatus = false;
                    if (in_array($employee->employee_id, $releaseEmployeeIds['employeeIds'])) {
                        $relesaeStatus = true;
                    }
                    foreach ($incomes as $income) {
                        $employeeSetupModel = EmployeeSetup::where(['reference' => 'income', 'reference_id' => $income->reference_id, 'employee_id' => $employee->employee_id])->first();
                        if (isset($employeeSetupModel)) {
                            // $amount = $employeeSetupModel->amount ?: 0;
                            if (optional($income->income)->monthly_income == 11) {
                                if (optional($income->income)->daily_basis_status == 11) {
                                    if ($working_days == 0) {
                                        $amount = 0;
                                    } else {
                                        $amount = $employeeSetupModel->amount * $working_days;
                                    }
                                } else {
                                    if (optional($income->income)->method != 3) {
                                        $amount = round((($employeeSetupModel->amount ?? 0) / $data['total_days']) * $payable_days, 2);
                                    } else {
                                        $amount = $employeeSetupModel->amount ?? 0;
                                    }
                                }
                            } else {
                                $amount =  $employeeSetupModel->amount ?? 0;
                            }
                        } else {
                            $amount = 0;
                        }
                        if ($relesaeStatus) {
                            $realeseDataValue = $releaseEmployeeIds['releaseData'][$employee->employee_id];
                            if ($realeseDataValue['incomes'][$income->reference_id]) {
                                $amount += $realeseDataValue['incomes'][$income->reference_id];
                            }
                        }

                        $arrearValue = ArrearAdjustmentDetail::whereHas('arrearAdjustment', function ($query) use ($year, $month, $employee) {
                            $query->where('year', $year)->where('month', $month)->where('emp_id', $employee->employee_id);
                        })->where('income_setup_id', $income->reference_id)->first();
                        $arrear_amount = $arrearValue ? $arrearValue->arrear_amount : 0;
                        if ($arrearValue) {
                            if ($arrearValue->income_type == 'add') {
                                $amount += $arrear_amount;
                            } else {
                                $amount -= $arrear_amount;
                            }
                            $incomeData = $income->income;
                            $arrearAdjustmentData[$incomeData->short_name] = [
                                'income_id' => $incomeData->id,
                                'income_short_name' => $incomeData->short_name,
                                'amount' => $arrear_amount,
                                'type' => $arrearValue->income_type
                            ];
                        }

                        if (optional($income->income)->monthly_income == 11) {
                            $totalIncome += $amount;
                        } else {
                            $annualIncome += $amount;
                        }
                        if (isset($arrearAdjustmentData) && count($arrearAdjustmentData) > 0) {
                            $value = $income->income;
                            if ($value->method == 2) {
                                $adjustmentAmount = 0;
                                $afterAdjustedAmount = 0;
                                foreach ($value->incomeDetail as $incomeDetail) {
                                    if ($incomeDetail->salary_type == 3) {
                                        $per = $incomeDetail->percentage;
                                        if (isset($arrearAdjustmentData['GR']) && isset($arrearAdjustmentData['GR'])) {
                                            $fetchArray = $arrearAdjustmentData['GR'];
                                            $adjustmentAmount = $fetchArray['amount'] ?? 0;
                                            $afterAdjustedAmount = ($per / 100) * $adjustmentAmount;
                                            if ($fetchArray['type'] == 'add') {
                                                $amount += $afterAdjustedAmount;
                                            } else {
                                                $amount -= $afterAdjustedAmount;
                                            }
                                        }
                                    } elseif ($incomeDetail->salary_type == 2) {
                                        $per = $incomeDetail->percentage;
                                        if (isset($arrearAdjustmentData['G']) && isset($arrearAdjustmentData['G'])) {
                                            $fetchArray = $arrearAdjustmentData['G'];
                                            $adjustmentAmount = $fetchArray['amount'] ?? 0;
                                            $afterAdjustedAmount = ($per / 100) * $adjustmentAmount;
                                            if ($fetchArray['type'] == 'add') {
                                                $amount += $afterAdjustedAmount;
                                            } else {
                                                $amount -= $afterAdjustedAmount;
                                            }
                                        }
                                    } else {
                                        $per = $incomeDetail->percentage;
                                        if (isset($arrearAdjustmentData['BS']) && isset($arrearAdjustmentData['BS'])) {
                                            $fetchArray = $arrearAdjustmentData['BS'];
                                            $adjustmentAmount = $fetchArray['amount'] ?? 0;
                                            $afterAdjustedAmount = ($per / 100) * $adjustmentAmount;
                                            if ($fetchArray['type'] == 'add') {
                                                $amount += $afterAdjustedAmount;
                                            } else {
                                                $amount -= $afterAdjustedAmount;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        // $totalIncome += $amount;
                        $payrollIncomeData = [];
                        $payrollIncomeData['payroll_id'] = $payrollModel->id;
                        $payrollIncomeData['payroll_employee_id'] = $payrollEmployeeModel->id;
                        $payrollIncomeData['income_setup_id'] = $income->reference_id;
                        $payrollIncomeData['value'] = $amount;
                        PayrollIncome::create($payrollIncomeData);
                    }
                    # payroll deduction
                    $totalDeduction = 0;
                    $annualDeduction = 0;
                    $deductions = EmployeeSetup::select('reference_id')->where('reference', 'deduction')->whereHas('deduction', function ($query) use ($params) {
                        if (isset($params['organization_id'])) {
                            $query->where('organization_id', $params['organization_id']);
                        }
                        $query->where('status', 11);
                    })->distinct()->get()->map(function ($model) {
                        $model->sort = optional($model->deduction)->order;
                        return $model;
                    })->sortBy('sort');
                    foreach ($deductions as $deduction) {
                        $employeeSetupModel = EmployeeSetup::where(['reference' => 'deduction', 'reference_id' => $deduction->reference_id, 'employee_id' => $employee->employee_id])->first();
                        if ($employeeSetupModel) {
                            if (optional($deduction->deduction)->monthly_deduction == 11) {
                                if (optional($deduction->deduction)->method != 3) {
                                    $amount = round((($employeeSetupModel->amount ?? 0) / $data['total_days']) * $payable_days, 2);
                                } else {
                                    $amount = $employeeSetupModel->amount ?? 0;
                                }
                            } else {
                                if (in_array(optional($deduction->deduction)->short_name, ['LI', 'HI', 'MI'])) {
                                    $thresholdLimit = ThresholdBenefitSetup::where('deduction_setup_id', $deduction->reference_id)->first();
                                    $thresholdAmount = $thresholdLimit ? $thresholdLimit->amount : 0;
                                    $amount = min($thresholdAmount, $employeeSetupModel->amount);
                                } else {
                                    $amount =  $employeeSetupModel->amount ?? 0;
                                }
                            }
                        } else {
                            $amount = 0;
                        }
                        if ($relesaeStatus) {
                            if ($realeseDataValue['deductions'][$deduction->reference_id]) {
                                $amount += $realeseDataValue['deductions'][$deduction->reference_id];
                            }
                        }
                        // $amount = $employeeSetupModel->amount ?? 0;
                        if (optional($deduction->deduction)->monthly_deduction == 11) {
                            $totalDeduction += $amount;
                        } else {
                            $annualDeduction += $amount;
                        }
                        if (isset($arrearAdjustmentData) && count($arrearAdjustmentData) > 0) {
                            $value = $deduction->deduction;
                            if ($value->method == 2) {
                                $adjustmentAmount = 0;
                                $afterAdjustedAmount = 0;
                                foreach ($value->deductionDetail as $deductionDetail) {
                                    $deductionIncome = $deductionDetail->income->short_name;
                                    $per = $deductionDetail->percentage;
                                    if (isset($arrearAdjustmentData[$deductionIncome]) && isset($arrearAdjustmentData[$deductionIncome])) {
                                        $fetchArray = $arrearAdjustmentData[$deductionIncome];
                                        $adjustmentAmount = $fetchArray['amount'] ?? 0;
                                        $afterAdjustedAmount = ($per / 100) * $adjustmentAmount;
                                        if ($fetchArray['type'] == 'add') {
                                            $amount += $afterAdjustedAmount;
                                        } else {
                                            $amount -= $afterAdjustedAmount;
                                        }
                                    }
                                }
                            }
                        }
                        $payrollDeductionData = [];
                        $payrollDeductionData['payroll_id'] = $payrollModel->id;
                        $payrollDeductionData['payroll_employee_id'] = $payrollEmployeeModel->id;
                        $payrollDeductionData['deduction_setup_id'] = $deduction->reference_id;
                        $payrollDeductionData['value'] = $amount;
                        PayrollDeduction::create($payrollDeductionData);
                    }

                    $totalTaxExclude = 0;
                    $taxExcludes = EmployeeTaxExcludeSetup::select('tax_exclude_setup_id')->whereHas('taxExclude', function ($query) use ($params) {
                        if (isset($params['organization_id'])) {
                            $query->where('organization_id', $params['organization_id']);
                        }
                        $query->where('status', 11);
                    })->distinct()->get()->map(function ($model) {
                        $model->sort = optional($model->taxExclude)->order;
                        return $model;
                    })->sortBy('sort');

                    foreach ($taxExcludes as $taxExclude) {
                        $employeeTaxExcludeSetupModel = EmployeeTaxExcludeSetup::where(['tax_exclude_setup_id' => $taxExclude->tax_exclude_setup_id, 'employee_id' => $employee->employee_id])->first();
                        if (isset($employeeTaxExcludeSetupModel)) {
                            $amount = $employeeTaxExcludeSetupModel->amount ?: 0;
                        } else {
                            $amount = 0;
                        }
                        $totalTaxExclude += $amount;
                        $payrollTaxExcludeData = [];
                        $payrollTaxExcludeData['payroll_id'] = $payrollModel->id;
                        $payrollTaxExcludeData['payroll_employee_id'] = $payrollEmployeeModel->id;
                        $payrollTaxExcludeData['tax_exclude_setup_id'] = $taxExclude->tax_exclude_setup_id;
                        $payrollTaxExcludeData['value'] = $amount;
                        PayrollTaxExcludeValue::create($payrollTaxExcludeData);
                    }

                    // $arrear_data = ArrearAdjustment::where('emp_id', $employee->employee_id)->where('status', 0)->get();
                    // dd($arrear_data);
                    $total_arrear = 0;
                    // if ($arrear_data->count() > 0) {
                    //     foreach ($arrear_data as $arrear_value) {
                    //         // dd($arrear_value);
                    //         if ($payrollModel->calender_type == 'nep') {
                    //             $nep_arr = explode('-', $arrear_value->nep_effective_date);
                    //             if ($nep_arr[0] < $year) {
                    //                 $diff_months = 12 - $nep_arr[1] + $month;
                    //             } else {
                    //                 $diff_months = $month - $nep_arr[1];
                    //             }
                    //         } else {
                    //             $this_month = Carbon::parse($year . '-' . $month . '-01')->floorMonth();
                    //             // dd( $this_month);
                    //             $start_month = Carbon::parse($arrear_value->effective_date)->floorMonth();
                    //             // dd($start_month );
                    //             $diff_months = $start_month->diffInMonths($this_month);
                    //             // dd($diff_months);
                    //         }
                    //         $total_arrear += $arrear_value->arrear_amt * $diff_months;

                    //         ArrearAdjustment::where('emp_id', $employee->employee_id)->update([
                    //             'status' => 1
                    //         ]);

                    //         // $this->arrearAdjustment->update($arrear_value->id, ['status' => 1]);
                    //     }
                    // }

                    // update payroll employee
                    // $payrollEmployeeModel->total_income = $totalIncome;
                    // $payrollEmployeeModel->total_deduction = $totalDeduction;
                    $payrollEmployeeModel->annual_income = $annualIncome;
                    $payrollEmployeeModel->annual_deduction = $annualDeduction;
                    $payrollEmployeeModel->arrear_amount = $total_arrear;
                    $payrollEmployeeModel->save();
                }
            }

            // all good
            DB::commit();
        } catch (\Exception $e) {
            throw $e;
            // something went wrong
            DB::rollBack();
        }
    }


    public function calculateAdjustmentAmount() {}

    public function findPayrollEmployee($id)
    {
        return PayrollEmployee::find($id);
    }

    public function update($id, $data)
    {
        $result = Payroll::find($id);
        return $result->update($data);
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {
            $payrollEmployeeModels = PayrollEmployee::where('payroll_id', $id)->get();
            foreach ($payrollEmployeeModels as $payrollEmployeeModel) {
                PayrollIncome::where('payroll_employee_id', $payrollEmployeeModel->id)->delete();
                PayrollDeduction::where('payroll_employee_id', $payrollEmployeeModel->id)->delete();
                PayrollTaxExcludeValue::where('payroll_employee_id', $payrollEmployeeModel->id)->delete();
                $payrollEmployeeModel->delete();
            }
            Payroll::destroy($id);

            DB::commit();
        } catch (\Exception $e) {

            DB::rollBack();
            throw $e;
        }

        return true;
    }

    public function findAllPayrollEmployee($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'ASC'], $status = [0, 1])
    {
        $result = PayrollEmployee::query();
        // dd($filter);
        if (isset($filter['organization_id'])) {
            // dd($filter['organization_id']);
            $result->whereHas('payroll', function ($query) use ($filter) {
                $query->where('organization_id', $filter['organization_id']);
            });
        }
        if (isset($filter['employee_id'])) {
            $result->where('employee_id', $filter['employee_id']);
        }
        if (isset($filter['year'])) {
            $result->whereHas('payroll', function ($query) use ($filter) {
                $query->where('year', $filter['year']);
            });
        }
        if (isset($filter['payroll_id'])) {
            $result->where('payroll_id', $filter['payroll_id']);
        }
        // if(auth()->user()->user_type != 'admin' && auth()->user()->user_type != 'super_admin')
        // {
        //     $result->where('created_by',auth()->user()->id);
        // }

        $result = $result->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }
    public function calculatePayrollDataSum($start_fiscal_year, $payrollModel, $endMonth, $employeeId, $field)
    {

        return PayrollEmployee::whereHas('payroll', function ($query) use ($start_fiscal_year, $payrollModel, $endMonth) {
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
        })->where('employee_id', $employeeId)->sum($field);
    }
    public function taxDetail($taxableSalary = 0, $employeeModel)
    {
        $tax = [];
        if (optional($employeeModel->getMaritalStatus)->dropvalue == 'Single' || optional($employeeModel->getMaritalStatus)->dropvalue == 'Divorcee') {
            $sstModel = TaxSlab::where('type', 'unmarried')->orderBy('order', 'ASC')->first();
            $taxSlabModels = TaxSlab::where('type', 'unmarried')->where('order', '!=', 0)->orderBy('order', 'ASC')->get();
        } else {
            $sstModel = TaxSlab::where('type', 'married')->orderBy('order', 'ASC')->first();
            $taxSlabModels = TaxSlab::where('type', 'married')->where('order', '!=', 0)->orderBy('order', 'ASC')->get();
        }
        // dd($sstModel,$taxSlabModels);

        foreach ($taxSlabModels as $taxSlabModel) {
            $taxSlabAmountArray[$taxSlabModel->order] = explode('-', $taxSlabModel->annual_income);
            $taxSlabArray[$taxSlabModel->order] = $taxSlabModel;
        }
        $yearlyTaxableAmount =  $taxableSalary;

        if ($yearlyTaxableAmount <= $sstModel->annual_income) {
            $tax[1]['amount'] = $yearlyTaxableAmount;
            $tax[1]['rate'] = $sstModel->tax_rate;
            $tax[1]['tds'] = $sstModel->tax_rate / 100 * $yearlyTaxableAmount;
        } elseif ($yearlyTaxableAmount > $taxSlabAmountArray[1][0] && $yearlyTaxableAmount <= $taxSlabAmountArray[1][1]) {
            // dd(1);
            // 1st slab of tax
            $tax[1]['amount'] = $sstModel->annual_income;
            $tax[1]['rate'] = $sstModel->tax_rate;
            $tax[1]['tds'] = $sstModel->tax_rate / 100 * $sstModel->annual_income;
            $taxableAmount = $yearlyTaxableAmount - ($taxSlabAmountArray[1][0]);
            $tax[2]['amount'] = $taxableAmount;
            $tax[2]['rate'] = $taxSlabArray[1]->tax_rate;
            $tax[2]['tds'] = ($taxSlabArray[1]->tax_rate / 100) * $taxableAmount; // calculate amount of 1st slab of tax
        } elseif ($yearlyTaxableAmount > $taxSlabAmountArray[2][0] && $yearlyTaxableAmount <= $taxSlabAmountArray[2][1]) {
            // 2nd slab of tax
            $tax[1]['amount'] = $sstModel->annual_income;
            $tax[1]['rate'] = $sstModel->tax_rate;
            $tax[1]['tds'] = $sstModel->tax_rate / 100 * $sstModel->annual_income;
            $tax[2]['amount'] = $taxSlabAmountArray[1][1] - $taxSlabAmountArray[1][0];
            $tax[2]['rate'] = $taxSlabArray[1]->tax_rate;
            $tax[2]['tds'] = ($taxSlabArray[1]->tax_rate / 100) * ($taxSlabAmountArray[1][1] - $taxSlabAmountArray[1][0]); // calculate amount of 1st slab of tax
            $taxableAmount = $yearlyTaxableAmount - ($taxSlabAmountArray[2][0]);
            $tax[3]['amount'] = $taxableAmount;
            $tax[3]['rate'] = $taxSlabArray[2]->tax_rate;
            $tax[3]['tds'] = ($taxSlabArray[2]->tax_rate / 100) * $taxableAmount; // calculate amount of 2nd slab of tax
        } elseif ($yearlyTaxableAmount > $taxSlabAmountArray[3][0] && $yearlyTaxableAmount <= $taxSlabAmountArray[3][1]) {
            // 3rd slab of tax
            $tax[1]['amount'] = $sstModel->annual_income;
            $tax[1]['rate'] = $sstModel->tax_rate;
            $tax[1]['tds'] = $sstModel->tax_rate / 100 * $sstModel->annual_income;
            $tax[2]['amount'] = $taxSlabAmountArray[1][1] - $taxSlabAmountArray[1][0];
            $tax[2]['rate'] = $taxSlabArray[1]->tax_rate;
            $tax[2]['tds'] = ($taxSlabArray[1]->tax_rate / 100) * ($taxSlabAmountArray[1][1] - $taxSlabAmountArray[1][0]); // calculate amount of 1st slab of tax
            $tax[3]['amount'] = $taxSlabAmountArray[2][1] - $taxSlabAmountArray[2][0];
            $tax[3]['rate'] = $taxSlabArray[2]->tax_rate;
            $tax[3]['tds'] = ($taxSlabArray[2]->tax_rate / 100) * ($taxSlabAmountArray[2][1] - $taxSlabAmountArray[2][0]); // calculate amount of 2nd slab of tax
            $taxableAmount = $yearlyTaxableAmount - ($taxSlabAmountArray[3][0]);
            $tax[4]['amount'] = $taxableAmount;
            $tax[4]['rate'] = $taxSlabArray[3]->tax_rate;
            $tax[4]['tds'] = ($taxSlabArray[3]->tax_rate / 100) * $taxableAmount; // calculate amount of 3rd slab of tax
        } elseif ($yearlyTaxableAmount > $taxSlabAmountArray[4][0] && $yearlyTaxableAmount <= $taxSlabAmountArray[4][1]) {
            $tax[1]['amount'] = $sstModel->annual_income;
            $tax[1]['rate'] = $sstModel->tax_rate;
            $tax[1]['tds'] = $sstModel->tax_rate / 100 * $sstModel->annual_income;
            $tax[2]['amount'] = $taxSlabAmountArray[1][1] - $taxSlabAmountArray[1][0];
            $tax[2]['rate'] = $taxSlabArray[1]->tax_rate;
            $tax[2]['tds'] = ($taxSlabArray[1]->tax_rate / 100) * ($taxSlabAmountArray[1][1] - $taxSlabAmountArray[1][0]); // calculate amount of 1st slab of tax
            $tax[3]['amount'] = $taxSlabAmountArray[2][1] - $taxSlabAmountArray[2][0];
            $tax[3]['rate'] = $taxSlabArray[2]->tax_rate;
            $tax[3]['tds'] = ($taxSlabArray[2]->tax_rate / 100) * ($taxSlabAmountArray[2][1] - $taxSlabAmountArray[2][0]); // calculate amount of 2nd slab of tax
            $tax[4]['amount'] = $taxSlabAmountArray[3][1] - $taxSlabAmountArray[3][0];
            $tax[4]['rate'] = $taxSlabArray[3]->tax_rate;
            $tax[4]['tds'] = ($taxSlabArray[3]->tax_rate / 100) * ($taxSlabAmountArray[3][1] - $taxSlabAmountArray[3][0]); // calculate amount of 3rd slab of tax
            $taxableAmount = $yearlyTaxableAmount - ($taxSlabAmountArray[4][0]);
            $tax[5]['amount'] = $taxableAmount;
            $tax[5]['rate'] = $taxSlabArray[4]->tax_rate;
            $tax[5]['tds'] = ($taxSlabArray[4]->tax_rate / 100) * $taxableAmount; // calculate amount of 4th slab of tax
        } elseif ($yearlyTaxableAmount > $taxSlabAmountArray[5][0]) {
            // dd(1);
            // 4th slab of tax
            $tax[1]['amount'] = $sstModel->annual_income;
            $tax[1]['rate'] = $sstModel->tax_rate;
            $tax[1]['tds'] = $sstModel->tax_rate / 100 * $sstModel->annual_income;
            $tax[2]['amount'] = $taxSlabAmountArray[1][1] - $taxSlabAmountArray[1][0];
            $tax[2]['rate'] = $taxSlabArray[1]->tax_rate;
            $tax[2]['tds'] = ($taxSlabArray[1]->tax_rate / 100) * ($taxSlabAmountArray[1][1] - $taxSlabAmountArray[1][0]); // calculate amount of 1st slab of tax
            $tax[3]['amount'] = $taxSlabAmountArray[2][1] - $taxSlabAmountArray[2][0];
            $tax[3]['rate'] = $taxSlabArray[2]->tax_rate;
            $tax[3]['tds'] = ($taxSlabArray[2]->tax_rate / 100) * ($taxSlabAmountArray[2][1] - $taxSlabAmountArray[2][0]); // calculate amount of 2nd slab of tax
            $tax[4]['amount'] = $taxSlabAmountArray[3][1] - $taxSlabAmountArray[3][0];
            $tax[4]['rate'] = $taxSlabArray[3]->tax_rate;
            $tax[4]['tds'] = ($taxSlabArray[3]->tax_rate / 100) * ($taxSlabAmountArray[3][1] - $taxSlabAmountArray[3][0]); // calculate amount of 3rd slab of tax
            $tax[5]['amount'] = $taxSlabAmountArray[4][1] - $taxSlabAmountArray[4][0];
            $tax[5]['rate'] = $taxSlabArray[4]->tax_rate;
            $tax[5]['tds'] = ($taxSlabArray[4]->tax_rate / 100) * ($taxSlabAmountArray[4][1] - $taxSlabAmountArray[4][0]); // calculate amount of 4th slab of tax
            $taxableAmount = $yearlyTaxableAmount - ($taxSlabAmountArray[5][0]);
            $tax[6]['amount'] = $taxableAmount;
            $tax[6]['rate'] = $taxSlabArray[5]->tax_rate;
            $tax[6]['tds'] = ($taxSlabArray[5]->tax_rate / 100) * $taxableAmount; // calculate amount of 4th slab of tax
        } else {
            // no slab of tax
            $tax = [];
        }

        return $tax;
    }

    public function latestOne()
    {
        return Payroll::latest()->first();
    }

    public function findByOrganizationId($oraganizationId)
    {
        return Payroll::where('organization_id', $oraganizationId)->get()->toArray();
    }

    public function calculateAttendance($calender_type, $year, $month, $employee_id)
    {
        $attendanceRepo = new AttendanceReportRepository();
        $employeeModel = Employee::where('id', $employee_id)->first();
        $settingInfo = Setting::find(1);
        $currentEngDate = date('Y-m-d');

        if (isset($employeeModel->not_affect_on_payroll) && $employeeModel->not_affect_on_payroll == 1) {
            $data['unpaid_days'] = 0;
            $data['paidLeaveTaken'] = 0;
            $data['unpaidLeaveTaken'] = 0;
            return $data;
        } else {
            $joinDate = $calender_type == 'eng' ? $employeeModel->join_date : $employeeModel->nepali_join_date;
            $terminatedDate = $calender_type == 'eng' ? $employeeModel->archived_date : $employeeModel->nep_archived_date;
            if ($joinDate) {
                $joinYear = (int) explode('-', $joinDate)[0];
                $joinMonth = (int) explode('-', $joinDate)[1];
                $joinDay = (int) explode('-', $joinDate)[2];
            }

            if ($terminatedDate) {
                $terminatedYear = (int) explode('-', $terminatedDate)[0];
                $terminatedMonth = (int) explode('-', $terminatedDate)[1];
                $terminatedDay = (int) explode('-', $terminatedDate)[2];
            }
            if ($calender_type == 'eng') {
                $data = [];
                $day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                if ($year == date('Y') && $month == (int) date('m')) {
                    $currentDay = (int)date('d');
                } else {
                    $currentDay = $day;
                }
                if ($terminatedDate && ($year == $terminatedYear && $month == $terminatedMonth)) {
                    $currentDay = $terminatedDay;
                    $startDay = 1;
                    $data['unpaid_days'] = $day - $terminatedDay;
                } elseif ($year == $joinYear && $month == $joinMonth) {
                    $startDay = $joinDay;
                    $data['unpaid_days'] = $joinDay - 1;
                } else {
                    $startDay = 1;
                    $data['unpaid_days'] = 0;
                }
                if (strlen($month) == 1) {
                    $month = '0' . $month;
                } else {
                    $month = $month;
                }
                $start_date = $year . '-' . $month . '-01';
                $end_date = $year . '-' . $month . '-' . $day;
                $leaveModel =  Leave::where('employee_id',  $employee_id)->whereYear('date', $year)->whereMonth('date', $month)->where('status', '!=', 4);
                $data['working_days'] = Attendance::whereYear('date', $year)->whereMonth('date', $month)->where('emp_id', $employee_id)->count() - $leaveModel->where('leave_kind', 1)->get()->sum('day');
                $data['unpaid_leave'] = Leave::with('leaveTypeModel')->whereHas('leaveTypeModel', function ($query) {
                    return $query->where('leave_type', 11);
                })->whereBetween('date', [$start_date, $end_date])->where('employee_id', $employee_id)->where('status', 3)->count();
                $data['unpaidLeaveTaken'] = Leave::where('employee_id', $employee_id)->whereYear('date', $year)->whereMonth('date', $month)->where('status', 3)->whereHas('leaveTypeModel', function ($query) {
                    $query->where('leave_type', 11);
                })->get()->sum('day');
                if ($settingInfo->leave_deduction_from_biometric == 11) {
                    for ($i = $startDay; $i <= $currentDay; $i++) {
                        $fulldate = $year . '-' . $month . '-' . sprintf("%02d", $i);

                        $status = $attendanceRepo->checkStatus($employeeModel, 'nepali_date', $fulldate);
                        if ($status && $status == 'A') {
                            $data['unpaid_days'] += 1;
                        }
                    }
                }
                return $data;
            } else {
                $data = [];
                $cal = new DateConverter();
                $nepDateArray = date_converter()->eng_to_nep(date('Y'), date('m'), date('d'));
                $total_nepali_days = $cal->getTotalDaysInMonth($year, $month);
                if ($year == $nepDateArray['year'] && $month == $nepDateArray['month']) {
                    $currentDay = $nepDateArray['date'];
                } else {
                    $currentDay = $total_nepali_days;
                }
                if ($terminatedDate && ($year == $terminatedYear && $month == $terminatedMonth)) {
                    $currentDay = $terminatedDay;
                    $startDay = 1;
                    $data['unpaid_days'] = $total_nepali_days - $terminatedDay;
                } elseif ($year == $joinYear && $month == $joinMonth) {
                    $startDay = $joinDay;
                    $data['unpaid_days'] = $joinDay - 1;
                } else {
                    $startDay = 1;
                    $data['unpaid_days'] = 0;
                }
                $nep_start_date = $year . '-' . $month . '-01';
                if (strlen($month) == 1) {
                    $month = '0' . $month;
                } else {
                    $month = $month;
                }
                $start_date = $year . '-' . $month . '-01';
                $end_date = $year . '-' . $month . '-' . $total_nepali_days;

                $leaveModel =  Leave::where('employee_id',  $employee_id)->whereBetween('nepali_date', [$start_date, $end_date])->where('status', '!=', 4);
                $data['working_days'] = Attendance::whereBetween('nepali_date', [$start_date, $end_date])->where('emp_id', $employee_id)->count() - $leaveModel->where('leave_kind', 1)->get()->sum('day');

                $data['unpaidLeaveTaken'] = Leave::where('employee_id', $employee_id)->whereBetween('nepali_date', [$start_date, $end_date])->where('status', 3)->whereHas('leaveTypeModel', function ($query) {
                    $query->where('leave_type', 11);
                })->get()->sum('day');
                if ($settingInfo->leave_deduction_from_biometric == 11) {
                    for ($i = $startDay; $i <= $currentDay; $i++) {
                        $fulldate = $year . '-' . $month . '-' . sprintf("%02d", $i);

                        $status = $attendanceRepo->checkStatus($employeeModel, 'nepali_date', $fulldate);
                        if ($status && $status == 'A') {
                            $data['unpaid_days'] += 1;
                        }
                    }
                }
                return $data;
            }
        }
    }

    public function getTaxCalculation($totalIncome, $totalDeduction, $festivalBonus = 0, $payrollEmployeeId = null){
        dd('hello');

    }
}
