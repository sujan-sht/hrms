<?php

namespace App\Modules\Api\Http\Controllers\Payroll;

use App\Modules\Api\Http\Controllers\ApiController;
use App\Modules\FiscalYearSetup\Entities\FiscalYearSetup;
use App\Modules\FiscalYearSetup\Repositories\FiscalYearSetupRepository;
use App\Modules\Payroll\Entities\GrossSalarySetup;
use App\Modules\Payroll\Entities\Payroll;
use App\Modules\Payroll\Entities\PayrollDeduction;
use App\Modules\Payroll\Entities\PayrollEmployee;
use App\Modules\Payroll\Entities\PayrollIncome;
use App\Modules\Payroll\Repositories\PayrollInterface;
use App\Modules\Payroll\Repositories\PayrollRepository;
use App\Modules\Setting\Entities\PayrollCalenderTypeSetting;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class PayrollController extends ApiController
{

    protected $payroll;


    public function __construct(
        PayrollInterface $payroll
    )
    {
        $this->payroll = $payroll;

    }
    public function getList()
    {
        try{
            $payrollList = Payroll::whereHas('payrollEmployee',function($query){
                $query->where('status',2);
            })->where('account_status','Pending')->get();
            $data['payrollList'] = [];
            foreach ($payrollList as $value) {
                $month = $value->calendar_type == 'nep' ? date_converter()->_get_nepali_month($value->month) : date_converter()->_get_english_month($value->month);
                $data['payrollList'][] = [
                    'id' => $value['id'],
                    'year' => $value['year'],
                    'month' => $month,
                    'organization_name' => optional($value->organization)->name,
                    'organisation_code' => optional($value->organization)->organisation_code,
                ];
            }
            return  $this->respond(['status' => true, 'payrollList' => $data['payrollList']]);
        }catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    public function getData(Request $request){
        $input = $request->all();
        $payroll_id = $input['payroll_id'];
        $payrollInfo = $this->payroll->findOne($payroll_id);
        try{
            $sst = PayrollEmployee::where('payroll_id',$payroll_id)->sum('sst');
            $tds = PayrollEmployee::where('payroll_id',$payroll_id)->sum('tds');
            $singleWomenTax = PayrollEmployee::where('payroll_id',$payroll_id)->sum('single_women_tax_credit');

            $data['payrollIncomes'] = PayrollIncome::whereHas('payrollEmployee', function($query) use($payroll_id) {
                $query->whereHas('payroll', function($q) use($payroll_id) {
                    $q->where('id', $payroll_id); 
                });
            })
            ->whereHas('incomeSetup', function($query) {
                $query->where('monthly_income', 11);
            })
            ->groupBy('income_setup_id')
            ->selectRaw('income_setup_id, income_setups.title as title, SUM(value) as total_value')
            ->join('income_setups', 'payroll_incomes.income_setup_id', '=', 'income_setups.id')
            ->get();

            $data['payrollDeductions'] = PayrollDeduction::whereHas('payrollEmployee', function($query) use($payroll_id) {
                $query->whereHas('payroll', function($q) use($payroll_id) {
                    $q->where('id', $payroll_id); 
                });
            })
            ->whereHas('deductionSetup', function($query) {
                $query->where('monthly_deduction', 11);
            })
            ->groupBy('deduction_setup_id')
            ->selectRaw('deduction_setup_id, deduction_setups.title as title, SUM(value) as total_value')
            ->join('deduction_setups', 'payroll_deductions.deduction_setup_id', '=', 'deduction_setups.id')
            ->get();

            $data['arrearAmount'] =PayrollEmployee::where('payroll_id',$payroll_id)->sum('arrear_amount');
            $data['overTimePay'] =PayrollEmployee::where('payroll_id',$payroll_id)->sum('overtime_pay');
            $data['leaveAmount'] = PayrollEmployee::where('payroll_id',$payroll_id)->sum('leave_amount');
            $data['finePenalty'] = PayrollEmployee::where('payroll_id',$payroll_id)->sum('fine_penalty');
            $data['totalTax'] = $sst + $tds - $singleWomenTax;
            $data['festivalBonus'] = PayrollEmployee::where('payroll_id',$payroll_id)->sum('festival_bonus');
            $data['adjustment'] = PayrollEmployee::where('payroll_id',$payroll_id)->sum('adjustment');
            $data['advance'] = PayrollEmployee::where('payroll_id',$payroll_id)->sum('advance_amount');
            $data['advanceList'] = PayrollEmployee::where('payroll_id',$payroll_id)->where('advance_amount','>',0)->pluck('advance_amount');
            $data['payableSalary'] = PayrollEmployee::where('payroll_id',$payroll_id)->sum('payable_salary');
            return  $this->respond(['status' => true, 'data' => $data]);
        }catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
       
    }
    
    public function getTdsReport(Request $request)
    {
        $filter = $request->all();
        try {
            $user = auth()->user();
            $inputData['employee_id'] = $user->emp_id;
            $explodeFiscalYear = explode('/' ,$filter['year']);
            $year = $explodeFiscalYear[0];
            $data = [];
            $data['fiscal_year'] = $filter['year'];
            $data['tdsdata'] = [];
            $payrollEmployeeList =  PayrollEmployee::whereHas('payroll',function($query) use($year){
                $query->where('year',$year)->where('month','>', 3);
                $query->orWhere('year',$year+1)->where('month','<=', 3);
            })->where('employee_id',$inputData['employee_id'])->where('status',2)->get();
            foreach($payrollEmployeeList as $payrollEmployee){
                $month = date_converter()->_get_nepali_month(optional($payrollEmployee->payroll)->month);
                $sst = $payrollEmployee->sst ?? 0;
                $tds = $payrollEmployee->tds ?? 0;
                $data['tdsdata'][] = [
                    'month' => $month,
                    'amount' => round(($sst + $tds),2)
                ];
            }

            return  $this->respond(['status' => true, 'data' => $data]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    public function paySlip(Request $request){
        $filter = $request->all();
        try {
            $user = auth()->user();
            $inputData['employee_id'] = $user->emp_id;
            
            $inputData['organization_id'] = optional($user->userEmployer)->organization_id;
            $calendar_type = PayrollCalenderTypeSetting::where('organization_id',$inputData['organization_id'])->first()->calendar_type;
            if($calendar_type == 'eng'){
                $currentFiscalYear = FiscalYearSetup::where('status',1)->first();
                $current_fiscal_year = $currentFiscalYear->fiscal_year_english;
                $start_fiscal_year = $currentFiscalYear->start_date_english;
            }
            else{
                $currentFiscalYear = FiscalYearSetup::where('status',1)->first();
                $current_fiscal_year = $currentFiscalYear->fiscal_year;
                $start_fiscal_year = $currentFiscalYear->start_date;
            }
            $startMonth = explode('-', $start_fiscal_year);
            $startMonth = (int) $startMonth[1];
            if(isset($filter['year'])){
                $filter['year'] = $filter['year'];
            }
            else{
                $filter['year'] = $current_fiscal_year;
            }
            $explodeFiscalYear = explode('/' ,$filter['year']);
            $year = $explodeFiscalYear[0];
            $grossSalary = GrossSalarySetup::where('employee_id',$inputData['employee_id'])->first();
            if($grossSalary){
                $gross = $grossSalary->gross_salary;
            }
            else{
                $gross = 0;
            }
            $data = [];
            $data['fiscal_year'] = $filter['year'];
            $data['paySlipData'] = [];
            $payrollEmployeeList =  PayrollEmployee::whereHas('payroll',function($query) use($year,$startMonth){
                $query->where('year',$year)->where('month','>=', $startMonth);
                $query->orWhere('year',$year+1)->where('month','<', $startMonth);
            })->where('employee_id',$inputData['employee_id'])->where('status',2)->get();

            foreach($payrollEmployeeList as $key=>$payrollEmployee){
                $year = optional($payrollEmployee->payroll)->year;
                $month = sprintf("%02d", optional($payrollEmployee->payroll)->month);
                $days = date_converter()->getTotalDaysInMonth($year, $month);
                $fullDate = $year . '-' . $month . '-' . $days;
                $englishFullDate = date_converter()->nep_to_eng_convert($fullDate);
                $englishDate = date('F,Y', strtotime($englishFullDate));
                // $month = date_converter()->_get_nepali_month(optional($payrollEmployee->payroll)->month);
                $month = optional($payrollEmployee->payroll)->calendar_type == 'nep' ? date_converter()->_get_nepali_month(optional($payrollEmployee->payroll)->month) : date_converter()->_get_english_month(optional($payrollEmployee->payroll)->month);
                $sst = $payrollEmployee->sst ?? 0;
                $tds = $payrollEmployee->tds ?? 0;
                if(count($payrollEmployee->incomes)> 0){
                    foreach ($payrollEmployee->incomes as $income){
                        $data['paySlipData'][$key]['income'][] = [
                        'title' => $income->incomeSetup->title,
                        'amount' => $income->value
                    ];
                    }
                }else{
                    $data['paySlipData'][$key]['income'] = [];
                }
                
                if(count($payrollEmployee->deductions)> 0){
                    foreach ($payrollEmployee->deductions as $deduction){
                        $data['paySlipData'][$key]['deduction'][] = [
                        'title' => $deduction->deductionSetup->title,
                        'amount' => $deduction->value
                    ];
                    }
                }else{
                    $data['paySlipData'][$key]['deduction'] = [];
                }
                
                $data['paySlipData'][$key]['name'] = optional($payrollEmployee->employee)->getFullName();
                $data['paySlipData'][$key]['department'] = optional(optional($payrollEmployee->employee)->department)->dropvalue;
                $data['paySlipData'][$key]['designation'] = optional(optional($payrollEmployee->employee)->designation)->dropvalue;
                $data['paySlipData'][$key]['dateOfJoin'] = optional($payrollEmployee->employee)->join_date;
                $data['paySlipData'][$key]['monthNepali'] = $month . ',' .$payrollEmployee->payroll->year ;
                $data['paySlipData'][$key]['monthEnglish'] = $englishDate;
                $data['paySlipData'][$key]['contractDeadline'] = optional(optional($payrollEmployee->employee)->payrollRelatedDetailModel)->contract_end_date ;
                $data['paySlipData'][$key]['month'] = $month;
                $data['paySlipData'][$key]['grossSalary'] = $gross;
                $data['paySlipData'][$key]['totalTax'] = round(($sst + $tds),2);
                $data['paySlipData'][$key]['netSalary'] = $payrollEmployee->net_salary;
            }
            // dd($data);

            return  $this->respond(['status' => true, 'data' => $data]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }
    public function getFiscalYear()
    {
        try {
            $user = Auth::user();
            $inputData['employee_id'] = $user->emp_id;
            $inputData['authUserType'] = $user->user_type;
            $inputData['organization_id'] = optional($user->userEmployer)->organization_id;
            $calendar_type = PayrollCalenderTypeSetting::where('organization_id',$inputData['organization_id'])->first()->calendar_type;
            if($calendar_type == 'eng'){
                $fiscalYearList = (new FiscalYearSetupRepository)->findEnglishFiscalYear();
            }
            else{
                $fiscalYearList = (new FiscalYearSetupRepository)->find();
            }
            $data['fiscalYearList'] = setObjectIdAndName($fiscalYearList);

            return  $this->respond([
                'status' => true,
                'data' => $data
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    public function upateAccountStatus(Request $request){
        $input = $request->all();
        try {
            $payrollInfo = $this->payroll->findOne($input['payroll_id']);
            if($payrollInfo){
                $this->payroll->update($input['payroll_id'],['account_status' => 'Completed']);
                return  $this->respond([
                    'status' => true,
                ]);
            }
        }catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }

    }
}
