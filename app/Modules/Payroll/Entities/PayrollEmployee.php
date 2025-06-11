<?php

namespace App\Modules\Payroll\Entities;

use Carbon\Carbon;
use App\Helpers\DateTimeHelper;
use Illuminate\Support\Facades\Date;
use App\Modules\Leave\Entities\Leave;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Leave\Entities\LeaveType;
use App\Modules\Setting\Entities\Setting;
use App\Modules\Dropdown\Entities\Dropdown;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Setting\Entities\OtRateSetup;
use App\Modules\Attendance\Entities\Attendance;
use App\Modules\Holiday\Entities\HolidayDetail;
use App\Modules\Shift\Entities\ShiftGroupMember;
use App\Modules\Leave\Entities\EmployeeLeaveOpening;
use App\Modules\Attendance\Entities\AttendanceRequest;
use App\Modules\Leave\Repositories\LeaveTypeRepository;
use App\Modules\Payroll\Repositories\PayrollRepository;
use App\Modules\FiscalYearSetup\Entities\FiscalYearSetup;
use App\Modules\Leave\Entities\LeaveEncashmentLogActivity;
use App\Modules\Payroll\Repositories\StopPaymentRepository;
use App\Modules\Setting\Repositories\OTRateSetupRepository;
use App\Modules\Attendance\Repositories\AttendanceRepository;
use App\Modules\Payroll\Repositories\EmployeeSetupRepository;
use App\Modules\Payroll\Repositories\TaxExcludeSetupRepository;
use App\Modules\Attendance\Repositories\AttendanceReportRepository;
use App\Modules\LeaveYearSetup\Repositories\LeaveYearSetupRepository;
use App\Modules\Setting\Repositories\FestivalAllowanceSetupRepository;

class PayrollEmployee extends Model
{
    protected $fillable = [
        'payroll_id',
        'employee_id',
        'marital_status',
        'total_days',
        'total_working_days',
        'extra_working_days',
        'paid_leave_days',
        'unpaid_leave_days',
        'unpaid_days',
        'total_days_for_payment',
        'overtime_pay',
        'total_income',
        'annual_income',
        'arrear_amount',
        'fine_penalty',
        'total_deduction',
        'annual_deduction',
        'festival_bonus',
        'leave_amount',
        'monthly_total_deduction',
        'yearly_taxable_salary',
        'sst',
        'tds',
        'extra_working_days_amount',
        'net_salary',
        'single_women_tax_credit',
        'adjustment',
        'adjustment_status',
        'advance_amount',
        'payable_salary',
        'remarks',
        'status',
        'hold_status'
    ];

    /**
     * Relation with payroll
     */
    public function payroll()
    {
        return $this->belongsTo(Payroll::class, 'payroll_id');
    }

    /**
     * Relation with payroll
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function getMaritalStatus()
    {
        return $this->belongsTo(Dropdown::class, 'marital_status');
    }

    // public function ssf(){
    //     return $this->hasmany(PayrollDeduction::class)->orderBy('deduction_setup_id', 'ASC');
    // }

    /**
     * Relation with payroll income
     */
    public function incomes()
    {
        return $this->hasMany(PayrollIncome::class)->orderBy('id', 'ASC');
    }

    /**
     * Relation with payroll income
     */
    public function deductions()
    {
        return $this->hasMany(PayrollDeduction::class)->join('deduction_setups', 'deduction_setups.id', 'payroll_deductions.deduction_setup_id')->select('deduction_setups.*', 'payroll_deductions.id as payroll_deduction_id', 'payroll_deductions.deduction_setup_id', 'payroll_deductions.value')->where('monthly_deduction', 11)->orderBy('payroll_deduction_id', 'ASC');
    }
    public function deductionModel()
    {
        return $this->hasMany(PayrollDeduction::class)->orderBy('id', 'ASC');
    }
    public function taxExcludeValues()
    {
        return $this->hasMany(PayrollTaxExcludeValue::class)->orderBy('id', 'ASC');
    }

    /**
     *
     */
    public function calculateTaxableSalary($totalIncome, $totalDeduction, $festivalBonus = 0, $payrollEmployeeId = null)
    {
        $gross = $totalIncome;
        $deduction = $totalDeduction;
        $fiscalYear = FiscalYearSetup::currentFiscalYear();
        $payrollEmployeeModel = PayrollEmployee::find($payrollEmployeeId);
        $payrollModel = optional($payrollEmployeeModel->payroll);
        $employeeModel = Employee::with('getMaritalStatus')->where('id', $this->employee_id)->first();
        $contractEndDate=$employeeModel->payrollRelatedDetailModel->contract_end_date ?? '';
        if ($payrollModel->calendar_type == 'nep') {
            // $join_month = Carbon::parse($employeeModel->nepali_join_date)->isoFormat('M');
            $joinDate = $employeeModel->nepali_join_date;
            $join_month = explode('-', $joinDate);
            $join_month = (int) $join_month[1];
            $start_fiscal_year = Carbon::parse($fiscalYear->start_date)->isoFormat('Y');
            $end_fiscal_date = $fiscalYear->end_date;
            $terminatedDate = $employeeModel->nep_archived_date;
            // dd($terminatedDate);
        } else {
            $join_month = Carbon::parse($employeeModel->join_date)->isoFormat('M');
            $joinDate = $employeeModel->join_date;
            $start_fiscal_year = Carbon::parse($fiscalYear->start_date_english)->isoFormat('Y');
            $end_fiscal_date = $fiscalYear->end_date_english;
            $terminatedDate = $employeeModel->archived_date;
        }
        if ($terminatedDate) {
            $explodeTerminatedDate = explode('-', $terminatedDate);
            $terminatedMonth = (int) $explodeTerminatedDate[1];
            $terminatedDay = (int) $explodeTerminatedDate[2];
            $terminatedYear = $explodeTerminatedDate[0];
        }
        if ($contractEndDate) {
            $explodeContractEndDate = explode('-', $contractEndDate);
            $contractEndMonth = (int) $explodeContractEndDate[1];
            $contractEndDay = (int) $explodeContractEndDate[2];
            $contractEndYear = $explodeContractEndDate[0];
        }
        $taxableAmount = 0;
        $endMonth = explode('-', $end_fiscal_date);
        $endMonth = (int) $endMonth[1];
        // if($employeeModel->join_date < $fiscalYear->start_date_english) {
        //     $taxableMonth = 12;
        // } else {
        // $taxableMonth = DateTimeHelper::getMonthDiff($fiscalYear->start_date_english, $employeeModel->join_date);
        // if($taxableMonth == 0) {
        //     $taxableMonth = 1;
        // }
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
        if ($payrollModel->month > $endMonth) {
            $remainingMonth = 12 + $endMonth - $payrollModel->month;
        } else{
            $remainingMonth = $endMonth - $payrollModel->month;
        }


        if ($payrollEmployeeId) {
            $payrollEmployeeModel = PayrollEmployee::find($payrollEmployeeId);
            $payrollModel = optional($payrollEmployeeModel->payroll);
            if ($payrollModel->month > $endMonth) {
                $start_fiscal_year = $payrollModel->year;
            } else {
                $start_fiscal_year = $payrollModel->year - 1;
            }
            $joinYear = date('Y', strtotime($joinDate));
            $joinMonth = date('m', strtotime($joinDate));
            $joinDay = date('d', strtotime($joinDate));
            $joinDay = (int) $joinDay;
            $currentDate = date('Y-m-d');
            $dateConverter = new DateConverter();
            $nepaliCurrentDate = $dateConverter->eng_to_nep_convert($currentDate);
            $currentMonth = date('m', strtotime($nepaliCurrentDate));
            $currentMonth = (int)$currentMonth;
            $currentYear = date('Y', strtotime($nepaliCurrentDate));
            $currentYear = (int)$currentYear;
            $employeededuction = EmployeeSetup::whereHas('deduction', function ($query) {
                $query->where('monthly_deduction', 11)->where('status',11);
            })->where('reference', 'deduction')->where('employee_id', $employeeModel->id)->pluck('amount', 'id')->toArray();
            $employeeDeduction = EmployeeSetup::whereHas('deduction', function ($query) {
                $query->where('monthly_deduction', 11)->where('status',11);
            })->where('reference', 'deduction')->where('employee_id', $employeeModel->id)->get();
            $employeeIncome = EmployeeSetup::whereHas('income', function ($query) {
                $query->where('monthly_income', 11)->whereIn('method', [1,2])->where('status',11);
            })->where('reference', 'income')->where('employee_id', $employeeModel->id)->pluck('amount', 'id')->toArray();
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

            $ssfFlag = false;
            $citFlag = false;
            $pfFlag = false;
            $cit = 0;
            $ssf = 0;
            $ssf1 = 0;
            $pf = 0;
            $amount = 0;
            $totalDeduction = 0;
            $deductionArray = [];

            if ($employeeDeduction) {
                foreach ($employeeDeduction as $key => $value) {
                    if ($value->amount > 0) {
                        if (optional($value->deduction)->short_name == 'CIT') {
                            $amount = $value->amount * $taxableMonth;
                            $citFlag = true;
                            $citLimit = 500000;
                            $amount = min($amount, $citLimit);
                            $cit = $amount;
                            $totalDeduction = $amount;
                            $deductionArray['cit'] = $amount;
                        }
                        if (optional($value->deduction)->short_name == 'SSF') {

                            $amount = $value->amount * $taxableMonth;
                            $ssfFlag = true;
                            $ssfLimit = 500000;
                            $amount = min($amount, $ssfLimit);
                            $ssf = $amount;
                            $totalDeduction = $amount;
                            $deductionArray['ssf'] = $amount;
                        }
                        if (optional($value->deduction)->short_name == 'SSF1') {

                            $amount = $value->amount * $taxableMonth;
                            $ssf1 = $amount;
                        }
                        if (optional($value->deduction)->short_name == 'PF') {
                            $amount = $value->amount * $taxableMonth;
                            $pfFlag = true;
                            $ssfLimit = 500000;
                            $amount = min($amount, $ssfLimit);
                            $totalDeduction = $amount;
                            $pf = $amount;
                            $deductionArray['pf'] = $amount;
                        }
                    }
                }

            }

            $employeeYearlyDeduction = EmployeeSetup::whereHas('deduction', function ($query) {
                $query->where('monthly_deduction', 10)->where('status',11);
            })->where('reference', 'deduction')->where('employee_id', $employeeModel->id)->get();
            $additionalCitFlag=false;
            $lifeInsurenceFlag=false;
            $healthIncurenceFlag=false;
            $homeIncurenceFlag=false;
            $acit = 0;
            $li = 0;
            $hi = 0;
            $homei = 0;
            $totalYearlyDeduction=0;
            $deductionYearlyArray = [];
            // if ($employeeYearlyDeduction) {
            //     foreach ($employeeYearlyDeduction as $key => $value) {
            //         if ($value->amount > 0) {
            //             if (optional($value->deduction)->short_name == 'ACIT') {
            //                 $amount = $value->amount;
            //                 $additionalCitFlag = true;
            //                 $acitLimit = 500000;
            //                 $amount = min($amount, $acitLimit);
            //                 $acit = $amount;
            //                 $totalYearlyDeduction= $amount;
            //                 $deductionYearlyArray['acit'] = $amount;
            //             }
            //             if (optional($value->deduction)->short_name == 'LHHI') {
            //                 $amount = $value->amount;
            //                 $lifeInsurenceFlag = true;
            //                 $liLimit = 40000;
            //                 $amount = min($amount, $liLimit);
            //                 $li = $amount;
            //                 $totalYearlyDeduction = $amount;
            //                 $deductionYearlyArray['li'] = $amount;
            //             }
            //             if (optional($value->deduction)->short_name == 'HI') {
            //                 $amount = $value->amount;
            //                 $healthIncurenceFlag = true;
            //                 $hiLimit = 25000;
            //                 $amount = min($amount, $hiLimit);
            //                 $hi = $amount;
            //                 $totalYearlyDeduction = $amount;
            //                 $deductionYearlyArray['hi'] = $amount;
            //             }
            //             if (optional($value->deduction)->short_name == 'HoI') {
            //                 $amount = $value->amount;
            //                 $homeIncurenceFlag = true;
            //                 $homeiLimit = 4500;
            //                 $amount = min($amount, $homeiLimit);
            //                 $homei = $amount;
            //                 $totalYearlyDeduction = $amount;
            //                 $deductionYearlyArray['homei'] = $amount;
            //             }
            //         }
            //     }
            // }
            // dd($deductionYearlyArray);
            $finalYearlyDeduction=0;
            foreach($deductionYearlyArray as $yDeduction){
                $finalYearlyDeduction+=$yDeduction;
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
            })->whereHas('bonusEmployee',function($q) use ($employeeModel){
                $q->where('employee_id',$employeeModel->id);

            })->whereHas('bonusSetup',function ($qa){
                // $qa->where('one_time_settlement',10);
            })->sum('value');

            // dd($previousTotalBonus);

            $salaryPaidMonth = PayrollEmployee::whereHas('payroll', function ($query) use ($start_fiscal_year, $payrollModel, $endMonth) {
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
            })->where('employee_id', $employeeModel->id)->count();
            if($fiscalYear->id == $employeeModel->effective_fiscal_year){
                $employeePreviousIncome = $employeeModel->total_previous_income ?? 0;
                $employeePreviousDeduction = $employeeModel->total_previous_deduction ?? 0;
            }
            else{
                $employeePreviousIncome = 0;
                $employeePreviousDeduction = 0;
            }
            $previousTotalIncome = PayrollEmployee::whereHas('payroll', function ($query) use ($start_fiscal_year, $payrollModel, $endMonth) {
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
            })->where('employee_id', $employeeModel->id)->sum('total_income');
            $previousTotalIncome = $previousTotalIncome + $employeePreviousIncome;

            $previousTotalDeduction = PayrollEmployee::whereHas('payroll', function ($query) use ($start_fiscal_year, $payrollModel, $endMonth) {
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
            })->where('employee_id', $employeeModel->id)->sum('total_deduction');
            $previousTotalDeduction = $previousTotalDeduction + $employeePreviousDeduction;
            $currentMonthIncome = $gross;
            $currentMonthDeduction = $deduction;
            if ($terminatedDate && ($payrollModel->year == $terminatedYear && $payrollModel->month == $terminatedMonth)){
                $totalDeduction = $previousTotalDeduction + $currentMonthDeduction;
            }elseif($contractEndDate && ($payrollModel->year == $contractEndYear && $payrollModel->month == $contractEndMonth)){
                $totalDeduction = $previousTotalDeduction + $currentMonthDeduction;
            }
            else{
                // $totalDeduction = $previousTotalDeduction + $currentMonthDeduction + ($monthlyDeduction * ($taxableMonth - $salaryPaidMonth - 1));
                $totalDeduction = $previousTotalDeduction + $currentMonthDeduction + ($monthlyDeduction * $remainingMonth);
            }
            if ($ssfFlag == true) {
                $ssfLimit = 500000;
                $amount = min($totalDeduction, $ssfLimit);
                $ssf = $amount;
                $totalDeduction = $amount;
            }
            if ($ssfFlag == true && $citFlag == true) {
                if ($totalDeduction > 500000) {
                    $totalDeduction = 500000;
                } else {
                    $totalDeduction = $totalDeduction;
                }
            }
            if ($pfFlag == true && $citFlag == true) {
                if ($totalDeduction > 500000) {
                    $totalDeduction = 500000;
                } else {
                    $totalDeduction = $totalDeduction;
                }
            }
            if ($ssfFlag && $pfFlag == true && $citFlag == true && $additionalCitFlag==true) {
                if ($totalDeduction > 500000) {
                    $totalDeduction = 500000;
                } else {
                    $totalDeduction = $totalDeduction;
                }
            }
            $totalDeduction=$totalDeduction+$finalYearlyDeduction;
            $bonusAmount=0;
            $bonusTds=0;
            $oneTimeBonus = BonusEmployee::where('employee_id',$employeeModel->employee_id)->whereHas('bonus',function($item) use ($payrollModel){
                // $item->where('calendar_type',$payrollModel->calendar_type)->where('year',$payrollModel->year)->where('month',$payrollModel->month);
                $item->where('calendar_type',$payrollModel->calendar_type)->where('year',$payrollModel->year);
            })->get();
            if($oneTimeBonus){
                $bonusAmount=$oneTimeBonus->sum('total_income');
                // $bonusTds=$oneTimeBonus->sum('tds');
            }
            $totalDeduction=$totalDeduction+$bonusTds;
            // $grossSalarySetupModel = GrossSalarySetup::where('employee_id', $employeeModel->id)->first();
            // if ($grossSalarySetupModel) {
                //     $grossSalary = $grossSalarySetupModel->gross_salary;
                // $totalYearlyIncome = $previousTotalIncome + $currentMonthIncome + ($grossSalary  * ($taxableMonth - 1 - $salaryPaidMonth));
                $totalYearlyIncome = $previousTotalIncome + $currentMonthIncome + ($monthlyIncome  * $remainingMonth);
                $oneThirdIncome = $totalYearlyIncome / 3;
                $totalDeduction = min($totalDeduction, $oneThirdIncome);

                // dd($previousTotalIncome , $currentMonthIncome   , ($monthlyIncome  ), ($remainingMonth) , $totalDeduction , $this->annual_deduction );
                // $taxableAmount = ($previousTotalIncome - $previousTotalDeduction) + $currentMonthIncome + (($grossSalary - $monthlyDeduction) * ($taxableMonth - 1 - $salaryPaidMonth)) - $this->annual_deduction + $festivalBonus;
                if ($terminatedDate && ($payrollModel->year == $terminatedYear && $payrollModel->month == $terminatedMonth)){
                    $taxableAmount = $bonusAmount+$previousTotalIncome + $currentMonthIncome + $previousTotalBonus + $this->annual_income - $totalDeduction - $this->annual_deduction + $festivalBonus ;
                }
                else{
                    // $taxableAmount = $previousTotalIncome + $currentMonthIncome + $previousTotalBonus + $this->annual_income + ($grossSalary  * $remainingMonth) - $totalDeduction - $this->annual_deduction + $festivalBonus ;
                    $taxableAmount = $bonusAmount+$previousTotalIncome + $currentMonthIncome + $previousTotalBonus + $this->annual_income + ($monthlyIncome  * $remainingMonth) - $totalDeduction - $this->annual_deduction + $festivalBonus ;
                }


            // } else {
            //     $taxableAmount = 0;
            // }
        }
        //     else {
        //         $grossSalarySetupModel = GrossSalarySetup::where('employee_id', $employeeModel->id)->first();
        //         if($grossSalarySetupModel) {
        //             $grossSalary = $grossSalarySetupModel->gross_salary;

        //             // $totalDays = date_converter()->getTotalDaysInMonth($joinYear, $joinMonth);
        //             // $firstMonthAmount = (($gross - $deduction) / $totalDays) * ($totalDays - $joinDay);
        //             // $taxableAmount = ($gross - $deduction) * ($taxableMonth - 1);
        //             // $taxableAmount = $taxableAmount + $firstMonthAmount - $this->annual_deduction + $festivalBonus;
        //         }
        //     }
        // } else {

        // }
        // if ($taxableMonth == 0) {
        //     $taxableMonth = 1;
        // }
        // $amount = $this->calculateFestivalAllowance($start_fiscal_year, $employeeModel->nepali_join_date,$employeeModel->dashain_allowance);
        // }
        // dd($taxableMonth);
        // }
        // dd($taxableMonth);
        // $taxableAmount = 0;
        // if($payrollEmployeeId) {
        //     $payrollEmployeeModel = PayrollEmployee::find($payrollEmployeeId);
        //     $payrollModel = optional($payrollEmployeeModel->payroll);
        //     $joinYear = date('Y', strtotime($joinDate));
        //     $joinMonth = date('m', strtotime($joinDate));
        //     $joinDay = date('d', strtotime($joinDate));
        //     $joinDay = (int) $joinDay;
        //     if ($payrollModel->year == $joinYear && $payrollModel->month == $joinMonth) {
        //         // $taxableAmount = ($gross * $taxableMonth) - ($deduction * $taxableMonth) - $this->annual_deduction + $festivalBonus;
        //         $taxableAmount = ($gross * $taxableMonth) - ($deduction * $taxableMonth) - $this->annual_deduction + $festivalBonus;
        //     } else {
        //         $grossSalarySetupModel = GrossSalarySetup::where('employee_id', $employeeModel->id)->first();
        //         if($grossSalarySetupModel) {
        //             $grossSalary = $grossSalarySetupModel->gross_salary;
        //             $totalDays = date_converter()->getTotalDaysInMonth($joinYear, $joinMonth);
        //             $firstMonthAmount = (($gross - $deduction) / $totalDays) * ($totalDays - $joinDay);
        //             $taxableAmount = ($gross - $deduction) * ($taxableMonth - 1);
        //             $taxableAmount = $taxableAmount + $firstMonthAmount - $this->annual_deduction + $festivalBonus;
        //         }
        //     }
        // } else {

        // }

        // $taxableAmount = ($gross * $taxableMonth) - ($deduction * $taxableMonth) - $this->annual_deduction + $festivalBonus;

        return round($taxableAmount, 2);
    }

    public function calculateTaxableSalarySST($totalIncome, $totalDeduction, $festivalBonus = 0, $payrollEmployeeId = null)
    {
        $gross = $totalIncome;
        $deduction = $totalDeduction;
        $fiscalYear = FiscalYearSetup::currentFiscalYear();
        $payrollEmployeeModel = PayrollEmployee::find($payrollEmployeeId);
        $payrollModel = optional($payrollEmployeeModel->payroll);
        $employeeModel = Employee::with('getMaritalStatus')->where('id', $this->employee_id)->first();
        $contractEndDate=$employeeModel->payrollRelatedDetailModel->contract_end_date ?? '';
        if ($payrollModel->calendar_type == 'nep') {
            // $join_month = Carbon::parse($employeeModel->nepali_join_date)->isoFormat('M');
            $joinDate = $employeeModel->nepali_join_date;
            $join_month = explode('-', $joinDate);
            $join_month = (int) $join_month[1];
            $start_fiscal_year = Carbon::parse($fiscalYear->start_date)->isoFormat('Y');
            $end_fiscal_date = $fiscalYear->end_date;
            $terminatedDate = $employeeModel->nep_archived_date;
            // dd($terminatedDate);
        } else {
            $join_month = Carbon::parse($employeeModel->join_date)->isoFormat('M');
            $joinDate = $employeeModel->join_date;
            $start_fiscal_year = Carbon::parse($fiscalYear->start_date_english)->isoFormat('Y');
            $end_fiscal_date = $fiscalYear->end_date_english;
            $terminatedDate = $employeeModel->archived_date;
        }
        if ($terminatedDate) {
            $explodeTerminatedDate = explode('-', $terminatedDate);
            $terminatedMonth = (int) $explodeTerminatedDate[1];
            $terminatedDay = (int) $explodeTerminatedDate[2];
            $terminatedYear = $explodeTerminatedDate[0];
        }
        if ($contractEndDate) {
            $explodeContractEndDate = explode('-', $contractEndDate);
            $contractEndMonth = (int) $explodeContractEndDate[1];
            $contractEndDay = (int) $explodeContractEndDate[2];
            $contractEndYear = $explodeContractEndDate[0];
        }
        $taxableAmount = 0;
        $endMonth = explode('-', $end_fiscal_date);
        $endMonth = (int) $endMonth[1];
        // if($employeeModel->join_date < $fiscalYear->start_date_english) {
        //     $taxableMonth = 12;
        // } else {
        // $taxableMonth = DateTimeHelper::getMonthDiff($fiscalYear->start_date_english, $employeeModel->join_date);
        // if($taxableMonth == 0) {
        //     $taxableMonth = 1;
        // }
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
        if ($payrollModel->month > $endMonth) {
            $remainingMonth = 12 + $endMonth - $payrollModel->month;
        } else{
            $remainingMonth = $endMonth - $payrollModel->month;
        }


        if ($payrollEmployeeId) {
            $payrollEmployeeModel = PayrollEmployee::find($payrollEmployeeId);
            $payrollModel = optional($payrollEmployeeModel->payroll);
            if ($payrollModel->month > $endMonth) {
                $start_fiscal_year = $payrollModel->year;
            } else {
                $start_fiscal_year = $payrollModel->year - 1;
            }
            $joinYear = date('Y', strtotime($joinDate));
            $joinMonth = date('m', strtotime($joinDate));
            $joinDay = date('d', strtotime($joinDate));
            $joinDay = (int) $joinDay;
            $currentDate = date('Y-m-d');
            $dateConverter = new DateConverter();
            $nepaliCurrentDate = $dateConverter->eng_to_nep_convert($currentDate);
            $currentMonth = date('m', strtotime($nepaliCurrentDate));
            $currentMonth = (int)$currentMonth;
            $currentYear = date('Y', strtotime($nepaliCurrentDate));
            $currentYear = (int)$currentYear;
            $employeededuction = EmployeeSetup::whereHas('deduction', function ($query) {
                $query->where('monthly_deduction', 11)->where('status',11);
            })->where('reference', 'deduction')->where('employee_id', $employeeModel->id)->pluck('amount', 'id')->toArray();
            $employeeDeduction = EmployeeSetup::whereHas('deduction', function ($query) {
                $query->where('monthly_deduction', 11)->where('status',11);
            })->where('reference', 'deduction')->where('employee_id', $employeeModel->id)->get();
            $employeeIncome = EmployeeSetup::whereHas('income', function ($query) {
                $query->where('monthly_income', 11)->whereIn('method', [1,2])->where('status',11);
            })->where('reference', 'income')->where('employee_id', $employeeModel->id)->pluck('amount', 'id')->toArray();
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

            $ssfFlag = false;
            $citFlag = false;
            $pfFlag = false;
            $cit = 0;
            $ssf = 0;
            $ssf1 = 0;
            $pf = 0;
            $amount = 0;
            $totalDeduction = 0;
            $deductionArray = [];

            if ($employeeDeduction) {
                foreach ($employeeDeduction as $key => $value) {
                    if ($value->amount > 0) {
                        if (optional($value->deduction)->short_name == 'CIT') {
                            $amount = $value->amount * $taxableMonth;
                            $citFlag = true;
                            $citLimit = 500000;
                            $amount = min($amount, $citLimit);
                            $cit = $amount;
                            $totalDeduction = $amount;
                            $deductionArray['cit'] = $amount;
                        }
                        if (optional($value->deduction)->short_name == 'SSF') {

                            $amount = $value->amount * $taxableMonth;
                            $ssfFlag = true;
                            $ssfLimit = 500000;
                            $amount = min($amount, $ssfLimit);
                            $ssf = $amount;
                            $totalDeduction = $amount;
                            $deductionArray['ssf'] = $amount;
                        }
                        if (optional($value->deduction)->short_name == 'SSF1') {

                            $amount = $value->amount * $taxableMonth;
                            $ssf1 = $amount;
                        }
                        if (optional($value->deduction)->short_name == 'PF') {
                            $amount = $value->amount * $taxableMonth;
                            $pfFlag = true;
                            $ssfLimit = 500000;
                            $amount = min($amount, $ssfLimit);
                            $totalDeduction = $amount;
                            $pf = $amount;
                            $deductionArray['pf'] = $amount;
                        }
                    }
                }

            }

            $employeeYearlyDeduction = EmployeeSetup::whereHas('deduction', function ($query) {
                $query->where('monthly_deduction', 10)->where('status',11);
            })->where('reference', 'deduction')->where('employee_id', $employeeModel->id)->get();
            $additionalCitFlag=false;
            $lifeInsurenceFlag=false;
            $healthIncurenceFlag=false;
            $homeIncurenceFlag=false;
            $acit = 0;
            $li = 0;
            $hi = 0;
            $homei = 0;
            $totalYearlyDeduction=0;
            $deductionYearlyArray = [];
            // if ($employeeYearlyDeduction) {
            //     foreach ($employeeYearlyDeduction as $key => $value) {
            //         if ($value->amount > 0) {
            //             if (optional($value->deduction)->short_name == 'ACIT') {
            //                 $amount = $value->amount;
            //                 $additionalCitFlag = true;
            //                 $acitLimit = 500000;
            //                 $amount = min($amount, $acitLimit);
            //                 $acit = $amount;
            //                 $totalYearlyDeduction= $amount;
            //                 $deductionYearlyArray['acit'] = $amount;
            //             }
            //             if (optional($value->deduction)->short_name == 'LHHI') {
            //                 $amount = $value->amount;
            //                 $lifeInsurenceFlag = true;
            //                 $liLimit = 40000;
            //                 $amount = min($amount, $liLimit);
            //                 $li = $amount;
            //                 $totalYearlyDeduction = $amount;
            //                 $deductionYearlyArray['li'] = $amount;
            //             }
            //             if (optional($value->deduction)->short_name == 'HI') {
            //                 $amount = $value->amount;
            //                 $healthIncurenceFlag = true;
            //                 $hiLimit = 25000;
            //                 $amount = min($amount, $hiLimit);
            //                 $hi = $amount;
            //                 $totalYearlyDeduction = $amount;
            //                 $deductionYearlyArray['hi'] = $amount;
            //             }
            //             if (optional($value->deduction)->short_name == 'HoI') {
            //                 $amount = $value->amount;
            //                 $homeIncurenceFlag = true;
            //                 $homeiLimit = 4500;
            //                 $amount = min($amount, $homeiLimit);
            //                 $homei = $amount;
            //                 $totalYearlyDeduction = $amount;
            //                 $deductionYearlyArray['homei'] = $amount;
            //             }
            //         }
            //     }
            // }
            // dd($deductionYearlyArray);
            $finalYearlyDeduction=0;
            foreach($deductionYearlyArray as $yDeduction){
                $finalYearlyDeduction+=$yDeduction;
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
            })->whereHas('bonusEmployee',function($q) use ($employeeModel){
                $q->where('employee_id',$employeeModel->id);
            })->whereHas('bonusSetup',function ($qa){
                // $qa->where('one_time_settlement',10);
            })->sum('value');

            // dd($previousTotalBonus);

            $salaryPaidMonth = PayrollEmployee::whereHas('payroll', function ($query) use ($start_fiscal_year, $payrollModel, $endMonth) {
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
            })->where('employee_id', $employeeModel->id)->count();
            if($fiscalYear->id == $employeeModel->effective_fiscal_year){
                $employeePreviousIncome = $employeeModel->total_previous_income ?? 0;
                $employeePreviousDeduction = $employeeModel->total_previous_deduction ?? 0;
            }
            else{
                $employeePreviousIncome = 0;
                $employeePreviousDeduction = 0;
            }
            $previousTotalIncome = PayrollEmployee::whereHas('payroll', function ($query) use ($start_fiscal_year, $payrollModel, $endMonth) {
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
            })->where('employee_id', $employeeModel->id)->sum('total_income');
            $previousTotalIncome = $previousTotalIncome + $employeePreviousIncome;

            $previousTotalDeduction = PayrollEmployee::whereHas('payroll', function ($query) use ($start_fiscal_year, $payrollModel, $endMonth) {
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
            })->where('employee_id', $employeeModel->id)->sum('total_deduction');
            $previousTotalDeduction = $previousTotalDeduction + $employeePreviousDeduction;
            $currentMonthIncome = $gross;
            $currentMonthDeduction = $deduction;
            if ($terminatedDate && ($payrollModel->year == $terminatedYear && $payrollModel->month == $terminatedMonth)){
                $totalDeduction = $previousTotalDeduction + $currentMonthDeduction;
            }elseif($contractEndDate && ($payrollModel->year == $contractEndYear && $payrollModel->month == $contractEndMonth)){
                $totalDeduction = $previousTotalDeduction + $currentMonthDeduction;
            }
            else{
                // $totalDeduction = $previousTotalDeduction + $currentMonthDeduction + ($monthlyDeduction * ($taxableMonth - $salaryPaidMonth - 1));
                $totalDeduction = $previousTotalDeduction + $currentMonthDeduction + ($monthlyDeduction * $remainingMonth);
            }
            if ($ssfFlag == true) {
                $ssfLimit = 500000;
                $amount = min($totalDeduction, $ssfLimit);
                $ssf = $amount;
                $totalDeduction = $amount;
            }
            if ($ssfFlag == true && $citFlag == true) {
                if ($totalDeduction > 500000) {
                    $totalDeduction = 500000;
                } else {
                    $totalDeduction = $totalDeduction;
                }
            }
            if ($pfFlag == true && $citFlag == true) {
                if ($totalDeduction > 500000) {
                    $totalDeduction = 500000;
                } else {
                    $totalDeduction = $totalDeduction;
                }
            }
            if ($ssfFlag && $pfFlag == true && $citFlag == true && $additionalCitFlag==true) {
                if ($totalDeduction > 500000) {
                    $totalDeduction = 500000;
                } else {
                    $totalDeduction = $totalDeduction;
                }
            }
            $totalDeduction=$totalDeduction+$finalYearlyDeduction;
            $bonusAmount=0;
            $bonusTds=0;
            $oneTimeBonus = BonusEmployee::where('employee_id',$employeeModel->employee_id)->whereHas('bonus',function($item) use ($payrollModel){
                // $item->where('calendar_type',$payrollModel->calendar_type)->where('year',$payrollModel->year)->where('month',$payrollModel->month);
                $item->where('calendar_type',$payrollModel->calendar_type)->where('year',$payrollModel->year);
            })->get();
            if($oneTimeBonus){
                $bonusAmount=$oneTimeBonus->sum('total_income');
                // $bonusTds=$oneTimeBonus->sum('tds');
            }
            $totalDeduction=$totalDeduction+$bonusTds;
            // $grossSalarySetupModel = GrossSalarySetup::where('employee_id', $employeeModel->id)->first();
            // if ($grossSalarySetupModel) {
                //     $grossSalary = $grossSalarySetupModel->gross_salary;
                // $totalYearlyIncome = $previousTotalIncome + $currentMonthIncome + ($grossSalary  * ($taxableMonth - 1 - $salaryPaidMonth));
                $totalYearlyIncome = $previousTotalIncome + $currentMonthIncome + ($monthlyIncome  * $remainingMonth);
                $oneThirdIncome = $totalYearlyIncome / 3;
                $totalDeduction = min($totalDeduction, $oneThirdIncome);

                // dd($previousTotalIncome , $currentMonthIncome   , ($monthlyIncome  ), ($remainingMonth) , $totalDeduction , $this->annual_deduction );
                // $taxableAmount = ($previousTotalIncome - $previousTotalDeduction) + $currentMonthIncome + (($grossSalary - $monthlyDeduction) * ($taxableMonth - 1 - $salaryPaidMonth)) - $this->annual_deduction + $festivalBonus;
                if ($terminatedDate && ($payrollModel->year == $terminatedYear && $payrollModel->month == $terminatedMonth)){
                    $taxableAmount = $bonusAmount+$previousTotalIncome + $currentMonthIncome + $previousTotalBonus + $this->annual_income - $totalDeduction - $this->annual_deduction + $festivalBonus ;
                }
                else{
                    // $taxableAmount = $previousTotalIncome + $currentMonthIncome + $previousTotalBonus + $this->annual_income + ($grossSalary  * $remainingMonth) - $totalDeduction - $this->annual_deduction + $festivalBonus ;
                    $taxableAmount = $bonusAmount+$previousTotalIncome + $currentMonthIncome + $previousTotalBonus + $this->annual_income + ($monthlyIncome  * $remainingMonth) - $totalDeduction - $this->annual_deduction + $festivalBonus ;
                }


            // } else {
            //     $taxableAmount = 0;
            // }
        }
        //     else {
        //         $grossSalarySetupModel = GrossSalarySetup::where('employee_id', $employeeModel->id)->first();
        //         if($grossSalarySetupModel) {
        //             $grossSalary = $grossSalarySetupModel->gross_salary;

        //             // $totalDays = date_converter()->getTotalDaysInMonth($joinYear, $joinMonth);
        //             // $firstMonthAmount = (($gross - $deduction) / $totalDays) * ($totalDays - $joinDay);
        //             // $taxableAmount = ($gross - $deduction) * ($taxableMonth - 1);
        //             // $taxableAmount = $taxableAmount + $firstMonthAmount - $this->annual_deduction + $festivalBonus;
        //         }
        //     }
        // } else {

        // }
        // if ($taxableMonth == 0) {
        //     $taxableMonth = 1;
        // }
        // $amount = $this->calculateFestivalAllowance($start_fiscal_year, $employeeModel->nepali_join_date,$employeeModel->dashain_allowance);
        // }
        // dd($taxableMonth);
        // }
        // dd($taxableMonth);
        // $taxableAmount = 0;
        // if($payrollEmployeeId) {
        //     $payrollEmployeeModel = PayrollEmployee::find($payrollEmployeeId);
        //     $payrollModel = optional($payrollEmployeeModel->payroll);
        //     $joinYear = date('Y', strtotime($joinDate));
        //     $joinMonth = date('m', strtotime($joinDate));
        //     $joinDay = date('d', strtotime($joinDate));
        //     $joinDay = (int) $joinDay;
        //     if ($payrollModel->year == $joinYear && $payrollModel->month == $joinMonth) {
        //         // $taxableAmount = ($gross * $taxableMonth) - ($deduction * $taxableMonth) - $this->annual_deduction + $festivalBonus;
        //         $taxableAmount = ($gross * $taxableMonth) - ($deduction * $taxableMonth) - $this->annual_deduction + $festivalBonus;
        //     } else {
        //         $grossSalarySetupModel = GrossSalarySetup::where('employee_id', $employeeModel->id)->first();
        //         if($grossSalarySetupModel) {
        //             $grossSalary = $grossSalarySetupModel->gross_salary;
        //             $totalDays = date_converter()->getTotalDaysInMonth($joinYear, $joinMonth);
        //             $firstMonthAmount = (($gross - $deduction) / $totalDays) * ($totalDays - $joinDay);
        //             $taxableAmount = ($gross - $deduction) * ($taxableMonth - 1);
        //             $taxableAmount = $taxableAmount + $firstMonthAmount - $this->annual_deduction + $festivalBonus;
        //         }
        //     }
        // } else {

        // }

        // $taxableAmount = ($gross * $taxableMonth) - ($deduction * $taxableMonth) - $this->annual_deduction + $festivalBonus;

        return round($taxableAmount, 2);
    }

    public function calculateSalaryPaidMonth($payrollEmployeeId = null){
        $fiscalYear = FiscalYearSetup::currentFiscalYear();
        $payrollEmployeeModel = PayrollEmployee::find($payrollEmployeeId);
        $payrollModel = optional($payrollEmployeeModel->payroll);
        $employeeModel = Employee::with('getMaritalStatus')->where('id', $this->employee_id)->first();
        if ($payrollModel->calendar_type == 'nep') {
            $joinDate = $employeeModel->nepali_join_date;
            $join_month = explode('-', $joinDate);
            $join_month = (int) $join_month[1];
            $start_fiscal_year = Carbon::parse($fiscalYear->start_date_english)->isoFormat('Y');
            $end_fiscal_date = $fiscalYear->end_date;
            $terminatedDate = $employeeModel->nep_archived_date;
        } else {
            $join_month = Carbon::parse($employeeModel->join_date)->isoFormat('M');
            $joinDate = $employeeModel->join_date;
            $start_fiscal_year = Carbon::parse($fiscalYear->start_date)->isoFormat('Y');
            $end_fiscal_date = $fiscalYear->end_date_english;
            $terminatedDate = $employeeModel->archived_date;
        }
        if ($terminatedDate) {
            $explodeTerminatedDate = explode('-', $terminatedDate);
            $terminatedMonth = (int) $explodeTerminatedDate[1];
            $terminatedDay = (int) $explodeTerminatedDate[2];
            $terminatedYear = $explodeTerminatedDate[0];
        }
        $endMonth = explode('-', $end_fiscal_date);
        $endMonth = (int) $endMonth[1];
        if ($payrollEmployeeId) {
            if ($payrollModel->month > $endMonth) {
                $start_fiscal_year = $payrollModel->year;
            } else {
                $start_fiscal_year = $payrollModel->year - 1;
            }
        }
        $salaryPaidMonth = PayrollEmployee::whereHas('payroll', function ($query) use ($start_fiscal_year, $payrollModel, $endMonth) {
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
        })->where('employee_id', $employeeModel->id)->count();
        return $salaryPaidMonth;
    }
    /**
     *
     */
    public function calculateSST($totalIncome, $totalDeduction, $festivalBonus = 0, $payrollEmployeeId = null, $organizationId = null)
    {
        $sst = 0;
        $ssfFlag = false;
        $ssf1Flag = false;
        $citFlag = true;
        $payrollEmployeeModel = PayrollEmployee::find($payrollEmployeeId);
        $payrollModel = optional($payrollEmployeeModel->payroll);
        $fiscalYear = FiscalYearSetup::currentFiscalYear();
        $employeeModel = Employee::with('getMaritalStatus')->where('id', $this->employee_id)->first();
        $stopPaymentObj = new StopPaymentRepository();
        $payrollRepository = new PayrollRepository($stopPaymentObj, new TaxExcludeSetupRepository);
        $end_fiscal_date = $fiscalYear->end_date;
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
        $endMonth = explode('-', $end_fiscal_date);
        $endMonth = (int) $endMonth[1];
        if ($payrollEmployeeId) {
            $payrollEmployeeModel = PayrollEmployee::find($payrollEmployeeId);
            $payrollModel = optional($payrollEmployeeModel->payroll);
            $organizationId = $payrollModel->organization_id;
        }
        if ($terminatedDate) {
            $explodeTerminatedDate = explode('-', $terminatedDate);
            $terminatedMonth = (int) $explodeTerminatedDate[1];
            $terminatedDay = (int) $explodeTerminatedDate[2];
            $terminatedYear = $explodeTerminatedDate[0];
        }
        if ($payrollModel->month > $endMonth) {
            $remainingMonth = 12 + $endMonth - $payrollModel->month;
        } else{
            $remainingMonth = $endMonth - $payrollModel->month;
        }

        $deductionSetupModel = DeductionSetup::where('short_name', 'SSF')->where('organization_id', $organizationId)->first();
        $ssf1Model = DeductionSetup::where('short_name', 'SSF1')->where('organization_id', $organizationId)->first();

        if ($deductionSetupModel) {
            $ssfId = $deductionSetupModel->id;
            $employeeSsfDeduction = EmployeeSetup::where('employee_id', $this->employee_id)
                ->where('reference', 'deduction')
                ->where('reference_id', $ssfId)
                ->first();
            if ($employeeSsfDeduction && $employeeSsfDeduction->amount > 0) {
                $ssfFlag = true;
            }
        }
        if ($ssf1Model) {
            $ssf1Id = $ssf1Model->id;
            $employeeSsf1Deduction = EmployeeSetup::where('employee_id', $this->employee_id)
                ->where('reference', 'deduction')
                ->where('reference_id', $ssf1Id)
                ->first();
            if ($employeeSsf1Deduction && $employeeSsf1Deduction->amount > 0) {
                $ssf1Flag = true;
            }
        }
        $gross = $totalIncome;
        $deduction = $totalDeduction;
        if (optional($employeeModel->getMaritalStatus)->dropvalue == 'Single' || optional($employeeModel->getMaritalStatus)->dropvalue == 'Divorcee') {
            // $taxSlabModel = TaxSlab::where('organization_id', $employeeModel->organization_id)->where('type', 'unmarried')->orderBy('order', 'ASC')->first();
            $taxSlabModel = TaxSlab::where('type', 'unmarried')->orderBy('order', 'ASC')->first();
        } else {
            // $taxSlabModel = TaxSlab::where('organization_id', $employeeModel->organization_id)->where('type', 'married')->orderBy('order', 'ASC')->first();
            $taxSlabModel = TaxSlab::where('type', 'married')->orderBy('order', 'ASC')->first();
        }
        // if($employeeModel->join_date < $fiscalYear->start_date_english) {
        //     $taxableMonth = 12;
        // } else {
        //     $taxableMonth = DateTimeHelper::getMonthDiff($fiscalYear->start_date_english, $employeeModel->join_date);
        //     if($taxableMonth == 0) {
        //         $taxableMonth = 1;
        //     }
        // }

        if ($payrollModel->calendar_type == 'nep') {
            if ($joinDate < $fiscalYear->start_date) {
                $taxableMonth = 12;
            } else {
                if ($join_month > $endMonth) {
                    $taxableMonth = 15 - $join_month + 1;
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
        if ($payrollModel->month > $endMonth) {
            $remainingMonth = 12 + $endMonth - $payrollModel->month;
        } else{
            $remainingMonth = $endMonth - $payrollModel->month;
        }
        // if ($employeeModel->nepali_join_date < $fiscalYear->start_date) {
        //     $taxableMonth = 12;
        // } else {
        //     if ($join_month > $endMonth) {
        //         $taxableMonth = 15 - $join_month + 1;
        //     } elseif ($join_month < $endMonth) {
        //         $taxableMonth = $endMonth - $join_month + 1;
        //     } else {
        //         $taxableMonth = 1;
        //     }
        // }
        // $amount = $this->calculateFestivalAllowance($start_fiscal_year, $employeeModel->nepali_join_date,$employeeModel->dashain_allowance);
        // dd($amount);

        //Festival Allowance
        // $festivalObj = new FestivalAllowanceSetupRepository();
        // $festivalAllowance = $festivalObj->find(1);
        // if ($festivalAllowance) {
        //     if (strlen($festivalAllowance->month) == 1) {
        //         $month = '0' . $festivalAllowance->month;
        //     } else {
        //         $month = $festivalAllowance->month;
        //     }

        //     $allowance_date = $start_fiscal_year . '-' . $month . '-01';
        //     $monthFromJoining = DateTimeHelper::getMonthDiff($allowance_date, $employeeModel->nepali_join_date);
        //     if ($festivalAllowance->method == 1) {
        //         $amount = $festivalAllowance->amount;
        //     } elseif ($festivalAllowance->method == 2) {
        //         if ($festivalAllowance->salary_type == 2) {
        //             $employeeGrossSalary = GrossSalarySetup::where('employee_id', $this->employee_id)->first();
        //             $per = $festivalAllowance->percentage;
        //             $amount = ($per / 100) * $employeeGrossSalary->gross_salary;
        //         }
        //         elseif ($festivalAllowance->salary_type == 1) {
        //             $employeeBasicSalary = EmployeeSetup::whereHas('income',function($query){
        //                 $query->where('short_name','BS');
        //             })->where('employee_id',$this->employee_id)->where('reference','income')->first();
        //             $amount = ($festivalAllowance->percentage / 100) * $employeeBasicSalary->amount;
        //         }

        //     }
        //     else{
        //         $amount = 0;
        //     }
        // }
        // else {
        //     $amount = 0;
        // }
        // dd($amount);


        //Taxable Amount
        // if ($monthFromJoining < $festivalAllowance->eligible_month) {
        //     $taxableAmount = ($taxableMonth * $gross) - $deduction;

        // } else {
        //     $taxableAmount = ($taxableMonth * $gross) - $deduction + $amount;
        // }
        // $taxableAmount = ($gross * $taxableMonth) - ($deduction * $taxableMonth) - $this->annual_deduction + $festivalBonus;
        $taxableAmount =  $this->calculateTaxableSalarySST($totalIncome, $totalDeduction, $festivalBonus, $payrollEmployeeId);
        // dd($taxableAmount);
        if ($taxableAmount >= $taxSlabModel->annual_income) {
            // $sst = $taxSlabModel->tax_amount / $taxableMonth;
            $sst = $taxSlabModel->tax_amount;
        } else {
            $sst = ($taxSlabModel->tax_rate / 100) * $taxableAmount;
            // $sst = $sst / $taxableMonth;
        }
        if($fiscalYear->id == $employeeModel->effective_fiscal_year){
            $employeePreviousSst = $employeeModel->total_sst_paid ?? 0;
            $employeePreviousTds = $employeeModel->total_tds_paid ?? 0;
            if(optional($employeeModel->getMaritalStatus)->dropvalue == 'Single' && optional($employeeModel->getGender)->dropvalue == 'Female'){
                $employeePreviousSst = ($employeePreviousSst / 90) * 100;
            }
        }
        else{
            $employeePreviousSst = 0;
            $employeePreviousTds = 0;
        }
        $previousTaxPaid = $payrollRepository->calculatePayrollDataSum($start_fiscal_year, $payrollModel, $endMonth,$employeeModel->id,'sst');

        $sst = $sst - $previousTaxPaid - $employeePreviousSst ;

        if ($sst != 0) {
            $sst = $sst / ($remainingMonth + 1);
        }

        if ($ssfFlag == true || $ssf1Flag == true) {
            // $grossSalarySetupModel = GrossSalarySetup::where('employee_id', $this->employee_id)->first();
            // if($grossSalarySetupModel) {
            //     $grossSalary = $grossSalarySetupModel->gross_salary;
            //     $basicSalary = $grossSalary * 0.6; // 60% of gross salary
            //     $ssfAmount = $basicSalary * 0.31; // 31% of basic salary
            //     $sstOfBasic = $ssfAmount * 0.01; // 1% of ssfAmount
            //     if($sstOfBasic > $sst) {
            //         $sst = 0;
            //     } else {
            //         $sst = $sst - $sstOfBasic;
            //     }
            // }
            $sst = 0;
        }
        if($sst < 0){
            $sst = 0;
        }


        return round($sst, 2);
    }

    /**
     *
     */
    public function calculateTDS($totalIncome, $totalDeduction, $festivalBonus = 0, $payrollEmployeeId = null)
    {
        $tds = 0;
        $gross = $totalIncome;
        $deduction = $totalDeduction;
        $fiscalYear = FiscalYearSetup::currentFiscalYear();
        $employeeModel = Employee::where('id', $this->employee_id)->first();
        $payrollEmployeeModel = PayrollEmployee::find($payrollEmployeeId);
        $payrollModel = optional($payrollEmployeeModel->payroll);
        $fiscalYear = FiscalYearSetup::currentFiscalYear();
        $employeeModel = Employee::with('getMaritalStatus')->where('id', $this->employee_id)->first();
        $stopPaymentObj = new StopPaymentRepository();
        $payrollRepository = new PayrollRepository($stopPaymentObj, new TaxExcludeSetupRepository);
        $end_fiscal_date = $fiscalYear->end_date;
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
        $endMonth = explode('-', $end_fiscal_date);
        $endMonth = (int) $endMonth[1];
        if ($payrollModel->calendar_type == 'nep') {
            if ($joinDate < $fiscalYear->start_date) {
                $taxableMonth = 12;
            } else {
                if ($join_month > $endMonth) {
                    $taxableMonth = 15 - $join_month + 1;
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
                if ($join_month > 8) {
                    $taxableMonth = 20 - $join_month;
                } elseif ($join_month < 8) {
                    $taxableMonth = 8 - $join_month;
                } else {
                    $taxableMonth = 1;
                }
            }
        }
        if ($payrollModel->month > $endMonth) {
            $remainingMonth = 12 + $endMonth - $payrollModel->month;
        } else{
            $remainingMonth = $endMonth - $payrollModel->month;
        }
        // dd($taxableMonth);
        if (optional($employeeModel->getMaritalStatus)->dropvalue == 'Single' || optional($employeeModel->getMaritalStatus)->dropvalue == 'Divorcee') {
            // $taxSlabModels = TaxSlab::where('organization_id', $employeeModel->organization_id)->where('type', 'unmarried')->where('order', '!=', 0)->orderBy('order', 'ASC')->get();
            $taxSlabModels = TaxSlab::where('type', 'unmarried')->where('order', '!=', 0)->orderBy('order', 'ASC')->get();
        } else {
            // $taxSlabModels = TaxSlab::where('organization_id', $employeeModel->organization_id)->where('type', 'married')->where('order', '!=', 0)->orderBy('order', 'ASC')->get();
            $taxSlabModels = TaxSlab::where('type', 'married')->where('order', '!=', 0)->orderBy('order', 'ASC')->get();
        }

        foreach ($taxSlabModels as $taxSlabModel) {
            $taxSlabAmountArray[$taxSlabModel->order] = explode('-', $taxSlabModel->annual_income);
            $taxSlabArray[$taxSlabModel->order] = $taxSlabModel;
        }
        // $amount = $this->calculateFestivalAllowance($start_fiscal_year, $employeeModel->nepali_join_date,$employeeModel->dashain_allowance);
        $yearlyTaxableAmount =  $this->calculateTaxableSalarySST($totalIncome, $totalDeduction, $festivalBonus, $payrollEmployeeId);
        if ($yearlyTaxableAmount > $taxSlabAmountArray[1][0] && $yearlyTaxableAmount <= $taxSlabAmountArray[1][1]) {
            // 1st slab of tds
            $taxableAmount = $yearlyTaxableAmount - ($taxSlabAmountArray[1][0]);
            $tds += ($taxSlabArray[1]->tax_rate / 100) * $taxableAmount; // calculate amount of 1st slab of tds
        } elseif ($yearlyTaxableAmount > $taxSlabAmountArray[2][0] && $yearlyTaxableAmount <= $taxSlabAmountArray[2][1]) {
            // 2nd slab of tds
            $tds += ($taxSlabArray[1]->tax_rate / 100) * ($taxSlabAmountArray[1][1] - $taxSlabAmountArray[1][0]); // calculate amount of 1st slab of tds
            $taxableAmount = $yearlyTaxableAmount - ($taxSlabAmountArray[2][0]);
            $tds += ($taxSlabArray[2]->tax_rate / 100) * $taxableAmount; // calculate amount of 2nd slab of tds
        } elseif ($yearlyTaxableAmount > $taxSlabAmountArray[3][0] && $yearlyTaxableAmount <= $taxSlabAmountArray[3][1]) {
            // 3rd slab of tds
            $tds += ($taxSlabArray[1]->tax_rate / 100) * ($taxSlabAmountArray[1][1] - $taxSlabAmountArray[1][0]); // calculate amount of 1st slab of tds
            $tds += ($taxSlabArray[2]->tax_rate / 100) * ($taxSlabAmountArray[2][1] - $taxSlabAmountArray[2][0]); // calculate amount of 2nd slab of tds
            $taxableAmount = $yearlyTaxableAmount - ($taxSlabAmountArray[3][0]);
            $tds += ($taxSlabArray[3]->tax_rate / 100) * $taxableAmount; // calculate amount of 3rd slab of tds
        } elseif ($yearlyTaxableAmount > $taxSlabAmountArray[4][0] && $yearlyTaxableAmount <= $taxSlabAmountArray[4][1]) {
            // 4th slab of tds
            $tds += ($taxSlabArray[1]->tax_rate / 100) * ($taxSlabAmountArray[1][1] - $taxSlabAmountArray[1][0]); // calculate amount of 1st slab of tds
            $tds += ($taxSlabArray[2]->tax_rate / 100) * ($taxSlabAmountArray[2][1] - $taxSlabAmountArray[2][0]); // calculate amount of 2nd slab of tds
            $tds += ($taxSlabArray[3]->tax_rate / 100) * ($taxSlabAmountArray[3][1] - $taxSlabAmountArray[3][0]); // calculate amount of 3rd slab of tds
            $taxableAmount = $yearlyTaxableAmount - ($taxSlabAmountArray[4][0]);
            $tds += ($taxSlabArray[4]->tax_rate / 100) * $taxableAmount; // calculate amount of 4th slab of tds
        } elseif ($yearlyTaxableAmount > $taxSlabAmountArray[5][0]) {
            // dd(1);
            // 4th slab of tds
            $tds += ($taxSlabArray[1]->tax_rate / 100) * ($taxSlabAmountArray[1][1] - $taxSlabAmountArray[1][0]); // calculate amount of 1st slab of tds
            $tds += ($taxSlabArray[2]->tax_rate / 100) * ($taxSlabAmountArray[2][1] - $taxSlabAmountArray[2][0]); // calculate amount of 2nd slab of tds
            $tds += ($taxSlabArray[3]->tax_rate / 100) * ($taxSlabAmountArray[3][1] - $taxSlabAmountArray[3][0]); // calculate amount of 3rd slab of tds
            $tds += ($taxSlabArray[4]->tax_rate / 100) * ($taxSlabAmountArray[4][1] - $taxSlabAmountArray[4][0]); // calculate amount of 4th slab of tds
            $taxableAmount = $yearlyTaxableAmount - ($taxSlabAmountArray[5][0]);
            $tds += ($taxSlabArray[5]->tax_rate / 100) * $taxableAmount; // calculate amount of 4th slab of tds
        }  else {
            // no slab of tds
            $tds = 0;
        }

        if($fiscalYear->id == $employeeModel->effective_fiscal_year){
            $employeePreviousSst = $employeeModel->total_sst_paid ?? 0;
            $employeePreviousTds = $employeeModel->total_tds_paid ?? 0;
            if(optional($employeeModel->getMaritalStatus)->dropvalue == 'Single' && optional($employeeModel->getGender)->dropvalue == 'Female'){
                $employeePreviousTds = ($employeePreviousTds / 90) * 100;
            }
        }
        else{
            $employeePreviousSst = 0;
            $employeePreviousTds = 0;
        }
        $totalTax = $tds;
        $previousTaxPaid = $payrollRepository->calculatePayrollDataSum($start_fiscal_year, $payrollModel, $endMonth,$employeeModel->id,'tds');
        $oneTimeBonus = BonusEmployee::where('employee_id',$employeeModel->employee_id)->whereHas('bonus',function($item) use ($payrollModel){
            // $item->where('calendar_type',$payrollModel->calendar_type)->where('year',$payrollModel->year);
        })->get();
        if($oneTimeBonus){
            $bonusTds=$oneTimeBonus->sum('tds');
        }
        // dd( $totalTax , $previousTaxPaid , $employeePreviousTds,$bonusTds);
        $totalTax = $totalTax - $previousTaxPaid - $employeePreviousTds-$bonusTds ;
        // dd($totalTax , $previousTaxPaid ,$employeePreviousTds,$bonusTds);

        if ($totalTax != 0) {
            $tds = $totalTax / ($remainingMonth + 1);
        }
        if($tds < 0){
            $tds = 0;
        }
        // dd($tds);

        return round($tds, 2);
    }

    public function calculateLeave($calender_type, $year, $month)
    {
        $attendanceRepo = new AttendanceReportRepository();
        $employeeModel = Employee::where('id', $this->employee_id)->first();
        $settingInfo = Setting::find(1);
        $grossSalary = optional($employeeModel->employeeGrossSalarySetup)->gross_salary;
        $leaveAmountSetup = LeaveAmountSetup::where('organization_id',$employeeModel->organization_id)->first();
        if($leaveAmountSetup){
            $leaveSetupIncomes = $leaveAmountSetup->leaveAmountDetail->pluck('income_setup_id')->toArray();
            $leaveDeductionAmount = EmployeeSetup::where('reference','income')->where('employee_id',$this->employee_id)->whereIn('reference_id',$leaveSetupIncomes)->whereHas('income',function($query){
                $query->where('monthly_income',11);
            })->sum('amount');
        }
        $currentEngDate = date('Y-m-d');
        $data['leave_amount'] = 0;
        if(isset($employeeModel->not_affect_on_payroll) && $employeeModel->not_affect_on_payroll == 1){
            $data['unpaid_days'] = 0;
            $data['paidLeaveTaken'] = 0;
            $data['unpaidLeaveTaken'] = 0;
            return $data;
        }
        else{
            $joinDate = $calender_type == 'eng' ? $employeeModel->join_date : $employeeModel->nepali_join_date;
            $terminatedDate = $calender_type == 'eng' ? $employeeModel->archived_date : $employeeModel->nep_archived_date;
            if($joinDate){
                $joinYear =(int) explode('-' ,$joinDate)[0];
                $joinMonth =(int) explode('-' ,$joinDate)[1];
                $joinDay =(int) explode('-' ,$joinDate)[2];
            }

            if($terminatedDate){
                $terminatedYear =(int) explode('-' ,$terminatedDate)[0];
                $terminatedMonth =(int) explode('-' ,$terminatedDate)[1];
                $terminatedDay =(int) explode('-' ,$terminatedDate)[2];
            }
            if ($calender_type == 'eng') {
                $data = [];
                $day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                if ($year == date('Y') && $month == (int) date('m')) {
                    $currentDay = (int)date('d');
                }
                else{
                    $currentDay = $day;
                }
                if ($terminatedDate && ($year == $terminatedYear && $month == $terminatedMonth)){
                    $currentDay = $terminatedDay;
                    $startDay = 1;
                    $data['unpaid_days'] = $day - $terminatedDay;
                }
                elseif ($year == $joinYear && $month == $joinMonth){
                    $startDay = $joinDay;
                    $data['unpaid_days'] = $joinDay - 1;
                }
                else{
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
                $data['unpaid_leave'] = Leave::with('leaveTypeModel')->whereHas('leaveTypeModel', function ($query) {
                    return $query->where('leave_type', 11);
                })->whereBetween('date', [$start_date, $end_date])->where('employee_id', $this->employee_id)->where('status', 3)->count();
                $data['paidLeaveTaken'] = Leave::where('employee_id', $this->employee_id)->whereYear('date', $year)->whereMonth('date', $month)->where('status', 3)->whereHas('leaveTypeModel', function ($query) {
                    $query->where('leave_type', 10);
                })->get()->sum('day');
                $data['unpaidLeaveTaken'] = Leave::where('employee_id', $this->employee_id)->whereYear('date', $year)->whereMonth('date', $month)->where('status', 3)->whereHas('leaveTypeModel', function ($query) {
                    $query->where('leave_type', 11);
                })->get()->sum('day');
                if($settingInfo->leave_deduction_from_biometric == 11){
                    for ($i = $startDay; $i <= $currentDay; $i++) {
                        $fulldate = $year . '-' . $month . '-' . sprintf("%02d", $i);

                        $status = $attendanceRepo->checkStatus($employeeModel, 'nepali_date', $fulldate);
                        if($status && $status == 'A'){
                            $data['unpaid_days'] += 1;
                        }
                    }
                }
                if($leaveAmountSetup){
                    $data['leave_amount'] =round((($data['unpaidLeaveTaken'] + $data['unpaid_days']) / $day )* $leaveDeductionAmount,2) ;
                }
                elseif($grossSalary){
                    $data['leave_amount'] =round((($data['unpaidLeaveTaken'] + $data['unpaid_days']) / $day )* $grossSalary,2) ;
                }
                else{
                    $data['leave_amount'] = 0;
                }
                return $data;
            } else {
                $data = [];
                $cal = new DateConverter();
                $nepDateArray = date_converter()->eng_to_nep(date('Y'), date('m'), date('d'));
                $total_nepali_days = $cal->getTotalDaysInMonth($year, $month);
                if ($year == $nepDateArray['year'] && $month == $nepDateArray['month']) {
                    $currentDay = $nepDateArray['date'];
                }
                else{
                    $currentDay = $total_nepali_days;
                }
                if ($terminatedDate && ($year == $terminatedYear && $month == $terminatedMonth)){
                    $currentDay = $terminatedDay;
                    $startDay = 1;
                    $data['unpaid_days'] = $total_nepali_days - $terminatedDay;
                }
                elseif ($year == $joinYear && $month == $joinMonth){
                    $startDay = $joinDay;
                    $data['unpaid_days'] = $joinDay - 1;
                }
                else{
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
                $data['paidLeaveTaken'] = Leave::where('employee_id', $this->employee_id)->whereBetween('nepali_date', [$start_date, $end_date])->where('status', 3)->whereHas('leaveTypeModel', function ($query) {
                    $query->where('leave_type', 10);
                })->get()->sum('day');

                // $data['unpaidLeaveTaken'] = Leave::where('employee_id', $this->employee_id)->whereYear('nepali_date', $year)->whereMonth('nepali_date', $month)->where('status', 3)->whereHas('leaveTypeModel', function ($query) {
                //     $query->where('leave_type', 11);
                // })->get()->sum('day');
                $data['unpaidLeaveTaken'] = Leave::where('employee_id', $this->employee_id)->whereBetween('nepali_date', [$start_date, $end_date])->where('status', 3)->whereHas('leaveTypeModel', function ($query) {
                    $query->where('leave_type', 11);
                })->get()->sum('day');
                if($settingInfo->leave_deduction_from_biometric == 11){
                    for ($i = $startDay; $i <= $currentDay; $i++) {
                        $fulldate = $year . '-' . $month . '-' . sprintf("%02d", $i);

                        $status = $attendanceRepo->checkStatus($employeeModel, 'nepali_date', $fulldate);
                        if($status && $status == 'A'){
                            $data['unpaid_days'] += 1;
                        }
                    }
                }
                if($leaveAmountSetup){
                    $data['leave_amount'] =round((($data['unpaidLeaveTaken'] + $data['unpaid_days']) / $total_nepali_days )* $leaveDeductionAmount,2) ;
                }
                elseif($grossSalary){
                    $data['leave_amount'] =round((($data['unpaidLeaveTaken'] + $data['unpaid_days']) / $total_nepali_days )* $grossSalary,2) ;
                }
                else{
                    $data['leave_amount'] = 0;
                }

                return $data;
            }
        }

    }

    public function calculateAttendance($calender_type, $year, $month)
    {
        $data = [];
        $attendanceRepo = new AttendanceReportRepository();
        $otRateSetupRepo = new OTRateSetupRepository();
        $employeeSetupRepo = new EmployeeSetupRepository();
        $shiftHour = 0;
        $data['total_ot_hour'] = 0;
        $data['total_ot_amount'] = 0;
        $employeeModel = Employee::where('id', $this->employee_id)->first();
        if(isset($employeeModel->not_affect_on_payroll) && $employeeModel->not_affect_on_payroll == 1){
            $data['total_ot_hour'] = 0;
            $data['total_ot_amount'] = 0;
            $data['working_days'] = 0;
            $data['extra_working_days'] = 0;
            if ($calender_type == 'eng') {
                $data['total_days'] = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            }
            else{
                $data['total_days'] = date_converter()->getTotalDaysInMonth($year, $month);
            }
        }
        else{
            if ($calender_type == 'eng') {
                $data['total_days'] = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                if (strlen($month) == 1) {
                    $month = '0' . $month;
                } else {
                    $month = $month;
                }
                $start_date = $year . '-' . $month . '-01';
                $end_date = $year . '-' . $month . '-' . $data['total_days'];

                $leaveModel =  Leave::where('employee_id',  $this->employee_id)->whereYear('date', $year)->whereMonth('date', $month)->where('status', '!=', 4);
                $data['working_days'] = Attendance::whereYear('date', $year)->whereMonth('date', $month)->where('emp_id', $this->employee_id)->count() - $leaveModel->where('leave_kind', 1)->get()->sum('day');
                $data['extra_working_days'] = AttendanceRequest::whereBetween('date', [$start_date, $end_date])->where('employee_id', $this->employee_id)->where('status', 3)->where('type', 5)->count();
                if ($employeeModel && optional($employeeModel->payrollRelatedDetailModel)->ot == '11') {
                    $empShift = optional(optional(ShiftGroupMember::where('group_member', $employeeModel->id)->orderBy('id', 'DESC')->first())->group)->shift;
                    $perDayShift = isset($empShift) ? (strtotime($empShift->end_time) - strtotime($empShift->start_time)) / 3600 : 8;
                    // dd($perDayShift);
                    $otModels = Attendance::where('emp_id', $this->employee_id)->whereYear('date', $year)->whereMonth('date', $month)->where('total_working_hr', '>', $perDayShift)->get();
                    if ($otModels) {
                        foreach ($otModels as $otModel) {
                            $otHour = $otModel->total_working_hr - $perDayShift;
                            $holiday = HolidayDetail::whereHas('holiday',function($query) use($employeeModel){
                                $query->where('organization_id', $employeeModel->organization_id);
                                $query->orWhereNull('organization_id');
                            })->where('eng_date', $otModel->date)->first();
                            if ($holiday) {
                                $data['organization_id'] = $employeeModel->organization_id;
                                $data['ot_type'] = 2;
                                $otRateobj = new OTRateSetupRepository();
                                $otRateModel = $otRateobj->findOne($data);
                                $otRate = $otRateModel->rate ?? 0;
                                $otAmount = $otRate * $otHour;
                                // dd($otAmount);
                            } else {
                                $data['organization_id'] = $employeeModel->organization_id;
                                $data['ot_type'] = 1;
                                $otRateobj = new OTRateSetupRepository();
                                $otRateModel = $otRateobj->findOne($data);
                                $otRate = $otRateModel->rate ?? 0;
                                $otAmount = $otRate * $otHour;
                            }

                            $data['total_ot_hour'] += $otHour;
                            $data['total_ot_amount'] += $otAmount;
                        }
                    }
                    $employeeBasicSalary = EmployeeSetup::whereHas('income',function($query){
                        $query->where('short_name','BS');
                    })->where('reference','income')->where('employee_id',$this->employee_id)->first();
                    if( $employeeBasicSalary){
                        $data['total_ot_amount'] = (($data['total_ot_hour']/ $perDayShift) * 1.5) * ($employeeBasicSalary->amount / $data['total_days']);
                    }
                }
            } else {
                $cal = new DateConverter();
                $data['total_days'] = $cal->getTotalDaysInMonth($year, $month);
                if (strlen($month) == 1) {
                    $month = '0' . $month;
                } else {
                    $month = $month;
                }
                $start_date = $year . '-' . $month . '-01';
                $end_date = $year . '-' . $month . '-' . $data['total_days'];
                // $data['working_days'] = Attendance::whereBetween('nepali_date', [$start_date,$end_date])->where('emp_id', $this->employee_id)->where('total_working_hr' ,'>' , 5)->count();
                $leaveModel =  Leave::where('employee_id',  $this->employee_id)->whereBetween('nepali_date', [$start_date, $end_date])->where('status', '!=', 4);
                $data['working_days'] = Attendance::whereBetween('nepali_date', [$start_date, $end_date])->where('emp_id', $this->employee_id)->count() - $leaveModel->where('leave_kind', 1)->get()->sum('day');
                $data['extra_working_days'] = AttendanceRequest::whereBetween('nepali_date', [$start_date, $end_date])->where('employee_id', $this->employee_id)->where('status', 3)->where('type', 5)->count();
                if ($employeeModel && optional($employeeModel->payrollRelatedDetailModel)->ot == '11') {
                    $empShift = optional(optional(ShiftGroupMember::where('group_member', $employeeModel->id)->orderBy('id', 'DESC')->first())->group)->shift;
                    $perDayShift = isset($empShift) ? (strtotime($empShift->end_time) - strtotime($empShift->start_time)) / 3600 : 8;
                    $otModels = Attendance::where('emp_id', $this->employee_id)->whereYear('nepali_date', $year)->whereMonth('nepali_date', $month)->where('total_working_hr', '>', $perDayShift)->get();
                    for ($i = 1; $i <= $data['total_days']; $i++) {
                        $fulldate = $year . '-' . $month . '-' . sprintf("%02d", $i);
                        $atd =  $employeeModel->getSingleAttendance('nepali_date', $fulldate);
                        $holiday = HolidayDetail::whereHas('holiday',function($query) use($employeeModel){
                                        $query->where('organization_id', $employeeModel->organization_id);
                                        $query->orWhereNull('organization_id');
                                    })->where('nep_date', $fulldate)->first();
                        if($holiday && optional($holiday->holiday)->is_festival !=11 && $atd){
                            $otHour = $atd->total_working_hr;
                            $data['total_ot_hour'] += $otHour;
                            $publicHolidayOt =$otRateSetupRepo->findOne(['organization_id' => $employeeModel->organization_id, 'ot_type' => 2 ]);
                            if($publicHolidayOt){
                                if($publicHolidayOt->ot_basis == 1){
                                    $publicHolidayOtIncomeHeadings = $publicHolidayOt->incomeHeadingDetail;
                                    $otIncome = 0;
                                    if(count($publicHolidayOtIncomeHeadings) > 0){
                                        foreach($publicHolidayOtIncomeHeadings as $key=>$value){
                                            $income = $employeeSetupRepo->findOne(['reference' => 'income','reference_id' => $value->income_setup_id,'employee_id' => $this->employee_id]);
                                            if($income){
                                                $otIncome += $income->amount;
                                            }
                                        }
                                    }
                                    $otIncome = $otIncome * $publicHolidayOt->times_value;
                                    $otAmount = ($otIncome / ($data['total_days'] *$perDayShift)) * $otHour;
                                    $data['total_ot_amount'] += $otAmount;
                                }
                                else{
                                    $employeeotRate = $employeeModel->findOtByType($this->employee_id,2);
                                    if($employeeotRate){
                                        $otIncome = $employeeotRate->rate;
                                    }
                                    else{
                                        $otIncome = $publicHolidayOt->rate;
                                    }
                                    $otAmount = $otIncome * $otHour;
                                    $data['total_ot_amount'] += $otAmount;
                                }
                            }
                        }
                        elseif($holiday && optional($holiday->holiday)->is_festival ==11 && $atd){
                            $otHour = $atd->total_working_hr;
                            $data['total_ot_hour'] += $otHour;
                            $festivalHolidayOt =$otRateSetupRepo->findOne(['organization_id' => $employeeModel->organization_id, 'ot_type' => 3 ]);
                            if($festivalHolidayOt){
                                if($festivalHolidayOt->ot_basis == 1){
                                    $festivalHolidayOtIncomeHeadings = $festivalHolidayOt->incomeHeadingDetail;
                                    $otIncome = 0;
                                    if(count($festivalHolidayOtIncomeHeadings) > 0){
                                        foreach($festivalHolidayOtIncomeHeadings as $key=>$value){
                                            $income = $employeeSetupRepo->findOne(['reference' => 'income','reference_id' => $value->income_setup_id,'employee_id' => $this->employee_id]);
                                            if($income){
                                                $otIncome += $income->amount;
                                            }
                                        }
                                    }
                                    $otIncome = $otIncome * $festivalHolidayOt->times_value;
                                    $otAmount = ($otIncome / ($data['total_days'] *$perDayShift)) * $otHour;
                                    $data['total_ot_amount'] += $otAmount;
                                }
                                else{
                                    $employeeotRate = $employeeModel->findOtByType($this->employee_id,3);
                                    if($employeeotRate){
                                        $otIncome = $employeeotRate->rate;
                                    }
                                    else{
                                        $otIncome = $festivalHolidayOt->rate;
                                    }
                                    $otAmount = $otIncome * $otHour;
                                    $data['total_ot_amount'] += $otAmount;
                                }
                            }
                        }
                        elseif($atd && $atd->total_working_hr > $perDayShift && !$holiday){
                            $otHour = $atd->total_working_hr - $perDayShift;
                            $data['total_ot_hour'] += $otHour;
                            $standardOt =$otRateSetupRepo->findOne(['organization_id' => $employeeModel->organization_id, 'ot_type' => 1 ]);
                            if($standardOt){
                                if($standardOt->ot_basis == 1){
                                    $standardOtIncomeHeadings = $standardOt->incomeHeadingDetail;
                                    $otIncome = 0;
                                    if(count($standardOtIncomeHeadings) > 0){
                                        foreach($standardOtIncomeHeadings as $key=>$value){
                                            $income = $employeeSetupRepo->findOne(['reference' => 'income','reference_id' => $value->income_setup_id,'employee_id' => $this->employee_id]);
                                            if($income){
                                                $otIncome += $income->amount;
                                            }
                                        }
                                    }
                                    $otIncome = $otIncome * $standardOt->times_value;
                                    $otAmount = ($otIncome / ($data['total_days'] *$perDayShift)) * $otHour;
                                    $data['total_ot_amount'] += $otAmount;
                                }
                                else{
                                    $employeeotRate = $employeeModel->findOtByType($this->employee_id,2);
                                    if($employeeotRate){
                                        $otIncome = $employeeotRate->rate;
                                    }
                                    else{
                                        $otIncome = $standardOt->rate;
                                    }
                                    $otAmount = $otIncome  * $otHour;
                                    $data['total_ot_amount'] += $otAmount;
                                }
                            }
                        }
                        else{
                            //nothing
                        }
                    }
                    $data['total_ot_amount'] = round($data['total_ot_amount'],2);
                    // dd($otModels);
                    // if ($otModels) {
                    //     foreach ($otModels as $otModel) {
                    //         $otHour = $otModel->total_working_hr - $perDayShift;
                    //         $holiday = HolidayDetail::whereHas('holiday',function($query) use($employeeModel){
                    //             $query->where('organization_id', $employeeModel->organization_id);
                    //             $query->orWhereNull('organization_id');
                    //         })->where('nep_date', $otModel->date)->first();
                    //         // $holiday = HolidayDetail::where('nep_date', $otModel->nepali_date)->first();
                    //         if ($holiday) {
                    //             $data['organization_id'] = $employeeModel->organization_id;
                    //             $data['ot_type'] = 2;
                    //             $otRateobj = new OTRateSetupRepository();
                    //             $otRateModel = $otRateobj->findOne($data);
                    //             $otRate = $otRateModel->rate ?? 0;
                    //             $otAmount = $otRate * $otHour;
                    //             // dd($otAmount);
                    //         } else {
                    //             $data['organization_id'] = $employeeModel->organization_id;
                    //             $data['ot_type'] = 1;
                    //             $otRateobj = new OTRateSetupRepository();
                    //             $otRateModel = $otRateobj->findOne($data);
                    //             $otRate = $otRateModel->rate ?? 0;
                    //             $otAmount = $otRate * $otHour;
                    //         }

                    //         $data['total_ot_hour'] += $otHour;
                    //         // dd($data['total_ot_hour']);
                    //         $data['total_ot_amount'] += $otAmount;
                    //     }
                    // }
                    // $employeeBasicSalary = EmployeeSetup::whereHas('income',function($query){
                    //     $query->where('short_name','BS');
                    // })->where('reference','income')->where('employee_id',$this->employee_id)->first();
                    // if( $employeeBasicSalary){
                    //     $data['total_ot_amount'] = (($data['total_ot_hour']/ $perDayShift) * 1.5) * ($employeeBasicSalary->amount / $data['total_days']);
                    // }
                }

            }
        }

        return $data;
    }

    public function calculateFestivalAllowance($start_fiscal_year, $employee_nepali_join_date, $dashain_allowance)
    {
        $amount = 0;
        $festivalObj = new FestivalAllowanceSetupRepository();
        $festivalAllowance = $festivalObj->find(1);
        if ($festivalAllowance) {
            if ($dashain_allowance == 11) {
                if (strlen($festivalAllowance->month) == 1) {
                    $month = '0' . $festivalAllowance->month;
                } else {
                    $month = $festivalAllowance->month;
                }

                $allowance_date = $start_fiscal_year . '-' . $month . '-01';
                $monthFromJoining = DateTimeHelper::getMonthDiff($allowance_date, $employee_nepali_join_date);
                if ($monthFromJoining >= $festivalAllowance->eligible_month) {
                    if ($festivalAllowance->method == 1) {
                        $amount = $festivalAllowance->amount;
                    } elseif ($festivalAllowance->method == 2) {
                        if ($festivalAllowance->salary_type == 2) {
                            $employeeGrossSalary = GrossSalarySetup::where('employee_id', $this->employee_id)->first();
                            $per = $festivalAllowance->percentage;
                            $amount = ($per / 100) * $employeeGrossSalary->gross_salary;
                        } elseif ($festivalAllowance->salary_type == 1) {
                            $employeeBasicSalary = EmployeeSetup::whereHas('income', function ($query) {
                                $query->where('short_name', 'BS');
                            })->where('employee_id', $this->employee_id)->where('reference', 'income')->first();
                            $amount = ($festivalAllowance->percentage / 100) * $employeeBasicSalary->amount;
                        }
                    } else {
                        $amount = 0;
                    }
                }
            }
        }

        return round($amount, 2);
    }

    public function getEncashmentDetails($employee,$leaveDataDetail,$searchData,$leaveYearSetupDetail,$encashmentIncomeData,$payable_days){
        $searchData['organization_id'] = $employee->employee->organization_id;
        $searchData['leave_year_id'] = $leaveYearSetupDetail->id;
        $leaveYear['leaveYearList'] =(new LeaveYearSetupRepository())->getLeaveYearList();
        $leaveYearId = $leaveYear['leaveYearList'][$leaveYearSetupDetail->id];
        $totalEncashDays=0;
        $amount=0;
        $leaveArrayDetails=[];
        foreach ($leaveDataDetail['leaveTypeList'] as $leave) {
            $searchData['leave_type_id'] = $leave;
            $encashmentDetail=$leaveArrayDetails[$leave]=$this->getLeaveSummaries($searchData, $employee->employee->organization_id, $leaveYearId, $employee->employee->employee_id);
            if($encashmentDetail['encashable']){
                if($encashmentDetail['encashable'] > 0){
                    $totalEncashDays+=$encashmentDetail['encashable'];
                    if($totalEncashDays > 0){
                        $leaveAmount=(collect($encashmentIncomeData)->sum()/$payable_days) * $totalEncashDays;
                        $leaveArrayDetails[$leave]['amount']=$leaveAmount;
                        $amount+=$leaveAmount;
                    }
                }
            }

        }
        return [
            'leaveArrayDetails'=>$leaveArrayDetails,
            'amount'=>$amount
        ];

    }

    public function getLeaveSummaries($filter, $id, $leaveYear, $empId)
    {
        $leave_year_id = $filter['leave_year_id'];
        $leaveTypeQuery = (new LeaveTypeRepository())->getAllLeaveTypes($id, $leave_year_id);
        if (!empty($filter['leave_type_id'])) {
            $leaveTypeQuery = $leaveTypeQuery->where('id', $filter['leave_type_id']);
        }
        $data['allLeaveTypes'] = (new LeaveTypeRepository())->getAllLeaveTypes($id, $leave_year_id);
        $query = Employee::query();
        $query->where('status', 1)
              ->where('organization_id', $id)
              ->where('employee_id', $empId);
        if (auth()->user()->user_type == 'employee') {
            $query->where('id', auth()->user()->emp_id);
        } elseif (auth()->user()->user_type == 'supervisor') {
            $employeeIds = Employee::getSubordinates(auth()->user()->id);
            array_push($employeeIds, auth()->user()->emp_id);
            $query->whereIn('id', $employeeIds);
        }
        if (isset($filter['employee_id']) && !empty($filter['employee_id'])) {
            $query->whereIn('employee_id', $filter['employee_id']);
        }
        $employee = $query->first();
        if (!$employee) {
            return null;
        }
        $leaveTypeQuery = LeaveType::query();
        $employeeLeaveDetails = [
            'encashable' => 0,
            'encashable_limit'=>0
        ];
        $leaveType = $leaveTypeQuery->where([
            'id' => $filter['leave_type_id'],
            'organization_id' => $id,
            'leave_year_id' => $filter['leave_year_id']
        ])->first();
        if ($leaveType) {
            $empJoiningDate = explode('-', $employee->nepali_join_date);
            $status = false;
            $empJoiningMonth = (int) $empJoiningDate[1];
            if ($empJoiningDate[0] != $leaveYear) {
                $empJoiningMonth = 1;
                $status = true;
            }
            $totalDays = 0;
            for ($i = $empJoiningMonth; $i <= 12; $i++) {
                $totalDaysInMonth = date_converter()->getTotalDaysInMonth($leaveYear, $i);

                if ($i == $empJoiningMonth && !$status) {
                    $totalDaysInMonth = $totalDaysInMonth - $empJoiningDate[2];
                }

                $totalDays += $totalDaysInMonth;
            }
            $leaveTotalDays = ($leaveType->number_of_days / 366) ?? 0;
            $leaveTypeTotalLeaveDays = round($totalDays * $leaveTotalDays, 2);
            $thresholdLimit = $leaveType->max_encashable_days ?? 0;
            $employeeLeaveOpening = EmployeeLeaveOpening::where('leave_year_id', $leave_year_id)
                ->where('organization_id', $id)
                ->where('employee_id', $employee->id)
                ->where('leave_type_id', $leaveType->id)
                ->first();
            $openiningLeave = $employeeLeaveOpening->opening_leave ?? 0;
            $employeTypeWiseLeave = Leave::where([
                    'employee_id' => $employee->id,
                    'leave_type_id' => $leaveType->id
                ])
                ->where('status', 3)
                ->count() ?? 0;
            $employeeLeaveDetails['balance'] = round($openiningLeave + $leaveTypeTotalLeaveDays - $employeTypeWiseLeave, 2);
            $employeeLeaveDetails['leave_type_id']=$leaveType->id;
            $employeeLeaveDetails['encashable_limit']=$thresholdLimit;
            $employeeLeaveDetails['encashable'] = 0;
            if ($employeeLeaveDetails['balance'] > $thresholdLimit && $leaveType->encashable_status != 10) {
                $employeeLeaveDetails['encashable'] = round($employeeLeaveDetails['balance'] - $thresholdLimit, 2);
            }
        }
        return $employeeLeaveDetails;
    }

    public function getEncashmentLog($payrollEmployee){
        $amount=0;
        $leaveLog=LeaveEncashmentLogActivity::where([
            'employee_id'=>$payrollEmployee->employee_id,
            'payroll_id'=>$payrollEmployee->payroll_id
        ])->first();
        if($leaveLog){
            $amount=$leaveLog->leaveEncashmentLog->encashed_amount ?? 0;
        }
        return $amount;
    }

     protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Created post: ');
        });

        static::updated(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Updated post: ');
        });

        static::deleted(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Deleted post: ' . $model);
        });
    }

}
