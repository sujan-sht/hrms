<?php

namespace App\Modules\Leave\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Modules\Leave\Entities\Leave;
use App\Modules\Leave\Entities\LeaveType;
use App\Exports\LeaveOverviewReportExport;
use App\Modules\Employee\Entities\Employee;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Leave\Entities\LeaveOverview;
use App\Modules\Employee\Entities\EmployeeLeave;
use App\Modules\Leave\Exports\LeaveOverViewReport;
use App\Modules\Leave\Imports\LeaveOverViewImport;
use App\Modules\Leave\Repositories\LeaveInterface;
use App\Modules\Leave\Entities\EmployeeLeaveOpening;
use App\Modules\Leave\Repositories\LeaveTypeInterface;
use App\Modules\LeaveYearSetup\Entities\LeaveYearSetup;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Employee\Repositories\EmployeeRepository;
use App\Modules\FiscalYearSetup\Entities\FiscalYearSetup;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\BulkUpload\Service\Import\PreviousLeaveDetailImport;
use App\Modules\LeaveYearSetup\Repositories\LeaveYearSetupInterface;

class LeaveOverViewController extends Controller
{
    private $leave;
    private $organization;
    private $leaveTypeObj;
    protected $leaveType;
    private $leaveYearSetup;
    protected $employee;
    protected $leaveCode;
    protected $leaveName;
    public function __construct(
        LeaveInterface $leave,
        OrganizationInterface $organization,
        LeaveTypeInterface $leaveTypeObj,
        LeaveYearSetupInterface $leaveYearSetup,
        EmployeeInterface $employee,
        LeaveTypeInterface $leaveType
    ) {
        $this->leave = $leave;
        $this->organization = $organization;
        $this->leaveTypeObj = $leaveTypeObj;
        $this->leaveYearSetup = $leaveYearSetup;
        $this->employee = $employee;
        $this->leaveType = $leaveType;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function createImportFile()
    {
        $data['organizationList'] = $this->organization->getList();
        $data['leaveTypeList'] = $this->leaveTypeObj->getList();
        return view('leave::leave-overview.create-importer', $data);
    }

    public function postImportFile(Request $request)
    {
        $inputData = $request->all();
        $query = Employee::query();
        $query->where('status', '=', 1);
        $query->where('organization_id', $inputData['organization_id']);
        $data['employees'] = $query->get();

        $data['leaveTypeArray'] = [];
        foreach ($inputData['leave_type_id'] as $key => $leaveType) {
            $data['leaveTypeArray'][] = LeaveType::find($leaveType)->code;
        }
        return Excel::download(new LeaveOverViewReport($data), 'leave-overview-report.xlsx');
        // return redirect()->back();
    }

    public function leaveOverview(Request $request)
    {
        $filter = $request->all();
        $data['organizationList'] = $this->organization->getList();
        if (isset($filter['from_date']) && !empty($filter['from_date'])) {
            LeaveOverview::where('created_at', '>=', $filter['from_date']);
        }

        if (isset($filter['to_date']) && !empty($filter['to_date'])) {
            LeaveOverview::where('created_at', '<=', $filter['to_date']);
        }

        // $data['leaveTypeList'] = $this->leaveTypeObj->getList();

        if (auth()->user()->user_type == 'division_hr' || auth()->user()->user_type == 'employee') {
            $leaveTypeLists = LeaveType::where('status', 11)->where('organization_id', optional(auth()->user()->userEmployer)->organization_id)->where('leave_year_id', getCurrentLeaveYearId())->get();
        } else {
            $leaveTypeLists = LeaveType::where('status', 11)->where('leave_year_id', getCurrentLeaveYearId())->get();
        }
        $data['leaveTypeList'] = [];
        foreach ($leaveTypeLists as $key => $leaveTypeList) {
            $organizationName = $leaveTypeList->organization ? "(" . optional($leaveTypeList->organization)->name . ")" : '';
            $data['leaveTypeList'][$leaveTypeList->id] = $leaveTypeList->name . ' ' . $organizationName;
        }
        $data['empLeaveOverviewReports'] = [];
        if ($filter) {
            $leaveType = $this->leaveTypeObj->findOne($filter['leave_type_id']);
            $filter['organization_id'] = $leaveType->organization_id;
            $data['empLeaveOverviewReports'] = $this->leave->employeeRemainingLeaveDetails($filter, 30);
        } else {
            $filter['leave_type_id'] = $this->leaveTypeObj->findOne(array_key_first($data['leaveTypeList']));
            $filter['organization_id'] = $filter['leave_type_id']->organization_id ?? null;
            $data['empLeaveOverviewReports'] = $this->leave->employeeRemainingLeaveDetails($filter, 30);
        }
        return view('leave::leave-overview.index', $data);
    }


   public function monthlySummary(Request $request)
    {
        $filter = $request->all();

        $page_limit=$request->page_limit ?? 30;
         if($request->page_limit=='All'){
             $page_limit=1000;
        }
        $leaveTypeLists = LeaveType::where('status', 11)->where('leave_year_id', getCurrentLeaveYearId())->get();
        $data['leaveTypeList'] = [];
        foreach ($leaveTypeLists as $key => $leaveTypeList) {
            $organizationName = $leaveTypeList->organization ? "(" . optional($leaveTypeList->organization)->name . ")" : '';
            $data['leaveTypeList'][$leaveTypeList->id] = $leaveTypeList->name . ' ' . $organizationName;
        }
        $dateConverter = new DateConverter();
        $data['organizationList'] = $this->organization->getList();
        $data['leaveYearList'] = $this->leaveYearSetup->getLeaveYearList();
        $data['employee_list'] = $this->employee->getList();
        $data['showStatus'] = false;
        $leaveType = LeaveType::where([
            'id' => $filter['leave_type_id'] ?? null,
            'organization_id' => $filter['organization_id'] ?? null,
            'leave_year_id' => $filter['leave_year_id'] ?? null
        ])->first();
        if (isset($filter['organization_id']) && isset($leaveType) && $filter['organization_id'] != null) {
            $id = $filter['organization_id'];
            $filter['leave_year_id'] = $filter['leave_year_id'];


            if (isset($filter['leave_year_id']) || isset($filter['leave_type_id']) || isset($filter['employee_id'])) {
                $getEmployee = EmployeeLeave::where([
                    'leave_year_id' => $filter['leave_year_id'],
                    'leave_type_id' => $leaveType->id
                ])->pluck('employee_id');
                $data['leaveYearDetail'] = isset($filter['leave_year_id']) ? $this->leaveYearSetup->findOne($filter['leave_year_id']) : null;

                if(isset($data['leaveYearDetail']) && $data['leaveYearDetail']->calendar_type == 'nep'){
                    $startingLeaveDate = $data['leaveYearDetail']->start_date;
                    $endingLeaveDate = $data['leaveYearDetail']->end_date;
                    $startingLeaveDateArray = explode('-', $startingLeaveDate);
                    $endingLeaveDateArray = explode('-', $endingLeaveDate);

                    $startMonth = (int)$startingLeaveDateArray[1]; // e.g., 4
                    $endMonth = (int)$endingLeaveDateArray[1];     // e.g., 3
                    $nepaliMonths = $dateConverter->getNepMonths();
                    // dd($data['nepaliMonths']);
                    $monthRange = [];

                    $current = $startMonth;
                    while (true) {
                        $monthRange[$current] = $nepaliMonths[$current];

                        if ($current == $endMonth) {
                            break;
                        }

                        $current++;
                        if ($current > 12) {
                            $current = 1; // wrap around after Chaitra
                        }
                    }

                    // Output result
                   $data['monthLists'] = $monthRange;
                }else{
                    $startingLeaveDate = $data['leaveYearDetail']->start_date_english;
                    $endingLeaveDate = $data['leaveYearDetail']->end_date_english;
                    $startingLeaveDateArray = explode('-', $startingLeaveDate);
                    $endingLeaveDateArray = explode('-', $endingLeaveDate);

                    $startMonth = (int)$startingLeaveDateArray[1]; // e.g., 4
                    $endMonth = (int)$endingLeaveDateArray[1];     // e.g., 3
                    $nepaliMonths = $dateConverter->getEngMonths();
                    // dd($data['nepaliMonths']);
                    $monthRange = [];

                    $current = $startMonth;
                    while (true) {
                        $monthRange[$current] = $nepaliMonths[$current];

                        if ($current == $endMonth) {
                            break;
                        }

                        $current++;
                        if ($current > 12) {
                            $current = 1; // wrap around after Chaitra
                        }
                    }

                    // Output result
                   $data['monthLists'] = $monthRange;
                }

                $data['employeeLeaveSummaries'] = (new EmployeeRepository())->getLeaveSummariesMonthly($filter, $id, $page_limit ?? 0, $data['leaveYearList'][$filter['leave_year_id']], $data['monthLists'], $getEmployee);
            }
            $data['leaveYearList'] = $this->leaveYearSetup->getLeaveYearList();
            if (auth()->user()->user_type == 'division_hr' || auth()->user()->user_type == 'employee') {
                $leaveTypeLists = LeaveType::where('status', 11)->where('organization_id', optional(auth()->user()->userEmployer)->organization_id)->where('leave_year_id', getCurrentLeaveYearId())->get();
            } else {
                $leaveTypeLists = LeaveType::where('status', 11)->where('leave_year_id', getCurrentLeaveYearId())->get();
            }
            $data['id'] = $id;

            $data['showStatus'] = true;
        }

        $data['leaveCode'] = isset($filter['leave_type_id']) ? optional($this->leaveTypeObj->findOne($filter['leave_type_id']))->code : null;
        $data['leaveName'] = $this->leaveName;

        $data['leaveTypeProrataStatus']=$leaveType ? ($leaveType->prorata_status=='10' ? true : false) : false;
        // dd($data['employeeLeaveSummaries'][0]);
        return view('leave::leave-report.index', $data);
    }

    function getLeaveSummariesMonthly($filter, $id, $limit, $leaveYear, $nepMonths, $employeeIds)
    {
        $leave_year_id = $filter['leave_year_id'];
        // $currentLeaveYear=LeaveYearSetup::where('status',1)->where('id',getCurrentLeaveYearId())->first();
        // dd($currentLeaveYear->leave_year,$filter);
        $leaveTypeQuery = $this->leaveType->getAllLeaveTypes($id, $leave_year_id);
        if (!empty($filter['leave_type_id'])) {
            $leaveTypeQuery = $leaveTypeQuery->where('id', $filter['leave_type_id']);
        }

        $data['allLeaveTypes'] = $this->leaveType->getAllLeaveTypes($id, $leave_year_id);

        $query = Employee::query();
        $query->where('status', '=', 1);
        $query->where('organization_id', $id);
        // $query->where('employee_id',493);
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

        $employees = $query->whereIn('id', $employeeIds)->paginate($limit);
        $result = $employees->setCollection($employees->getCollection()->transform(function ($emp) use ($leave_year_id, $id, $filter, $leaveYear, $nepMonths) {
            $emp->opening_leave = 0;
            $leaveTypeQuery = LeaveType::query();
            $leaveType = $leaveTypeQuery->where([
                'id' => $filter['leave_type_id'],
                'organization_id' => $id,
                'leave_year_id' => $filter['leave_year_id']
            ])->first();
            if ($leaveType) {
                $thresholdLimit = $leaveType->max_encashable_days ?? 0;
                // dd([
                //     'leave_year_id', $leave_year_id,'organization_id',  $id,'employee_id', $emp->id,'leave_type_id', $leaveType->id
                // ]);
                $employeeLeaveOpening = EmployeeLeaveOpening::where('leave_year_id', $leave_year_id)->where('organization_id',  $id)->where('employee_id', $emp->id)->where('leave_type_id', $leaveType->id)->first();
                $openinigBalanceValue = $employeeLeaveOpening->opening_leave ?? 0;
                $emp->opening_leave = $openinigBalanceValue;
                $this->leaveCode = $leaveType->code ?? '';
                $employeeleavesData=EmployeeLeave::where([
                    'leave_year_id'=>$leave_year_id,
                    'leave_type_id'=>$leaveType->id,
                    'employee_id'=>$emp->id
                ])->first();
                $empJoiningDate = explode('-', $emp->nepali_join_date);
                $status = false;
                $empJoiningMonth = (int)"$empJoiningDate[1]";
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
                $leaveDays = ($totalDays * $leaveTotalDays) / 12;
                $countValue = $openinigBalanceValue ?? 0;
                $remainingEmployeeLeave=$employeeleavesData->initial_leave_remaining;
                foreach ($nepMonths as $key => $mnt) {
                    $date = $leaveYear . '-' . ($key < 10 ? '0' . $key : $key);
                    $employeTypeWiseLeave = Leave::where(
                        [
                            'employee_id' => $emp->id,
                            'leave_type_id' => $leaveType->id
                        ]
                    )
                        ->whereBetween('nepali_date', [$date . '-01', $date . '-32'])
                        ->where('status', 3)
                        ->count() ?? 0;
                    $restrictMonthStatus = false;
                    if ($filter['leave_year_id'] == getCurrentLeaveYearId()) {
                        $restrictMonthStatus = true;
                        $currentDateInNep = explode('-', date_converter()->eng_to_nep_convert(date('Y-m-d')));
                        $currentMonth = $currentDateInNep[1] ?? 12;
                        $leaveDays = ($leaveDays / date_converter()->getTotalDaysInMonth($currentDateInNep[0], $currentMonth)) * $currentDateInNep[2];
                    }

                    if($leaveType->prorata_status=='10'){
                        $leaveDays = ($leaveType->number_of_days / 12) ?? 0;
                    }else{
                        if($leaveType->advance_allocation=='11'){
                            $leaveDays = ($leaveType->number_of_days / 12) ?? 0;
                        }
                    }
                    $countValue += $leaveDays;
                    $remainingLeave = $countValue - $employeTypeWiseLeave;
                    $employeeLeaveDetails[$key] = [
                        // 'leave_earned' => $countValue,
                        'leave_earned' => bcdiv($leaveDays,1,2),
                        'leave_taken' => $employeTypeWiseLeave,
                        'leave_remaining' => bcdiv($remainingLeave,1,2),
                    ];
                    if($leaveType->prorata_status == 10){
                        $remainingEmployeeLeave=$remainingEmployeeLeave - $employeTypeWiseLeave;
                        $remainingLeave = $remainingEmployeeLeave;
                        $employeeLeaveDetails[$key] = [
                            'leave_taken' => $employeTypeWiseLeave,
                            'leave_remaining' => bcdiv($remainingLeave,1,2),
                        ];
                    }
                    $countValue = $remainingLeave;
                    if ($leaveType->prorata_status !='10' && $restrictMonthStatus && ($currentMonth + 1 > $key)) {
                        break;
                    }
                }
            }
            $encashable = 0;
            if ($countValue > $thresholdLimit && $leaveType->encashable_status != 10) {
                if(round($countValue - $thresholdLimit, 10) !=0){
                    $encashable = bcdiv($countValue - (float)$thresholdLimit, 1,2);
                }
            }
            $emp->totalRemainingLeave = bcdiv($countValue,1,2);
            $emp->encashedLeave = bcdiv($encashable,1,2);
            $emp->closingLeave = bcdiv($countValue - $encashable,1,2);
            $emp->prorataLeave=$employeeleavesData->initial_leave_remaining > 0 ? ($employeeleavesData->initial_leave_remaining-$emp->opening_leave) : 0;
            if($leaveType->carry_forward_status=='10'){
                $emp->closingLeave = 0;
            }
            $emp->employeeLeaveDetails = $employeeLeaveDetails;
            return $emp;
        }));
        $employees;
        return $employees;
    }

    public function annualSummary(Request $request)
    {
        $filter = $request->all();
         $page_limit=$request->page_limit ?? 30;
         if($request->page_limit=='All'){
             $page_limit=1000;
        }
        $leaveTypeLists = LeaveType::where('status', 11)->where('leave_year_id', getCurrentLeaveYearId())->get();
        $data['leaveTypeList'] = [];
        foreach ($leaveTypeLists as $key => $leaveTypeList) {
            $organizationName = $leaveTypeList->organization ? "(" . optional($leaveTypeList->organization)->name . ")" : '';
            $data['leaveTypeList'][$leaveTypeList->id] = $leaveTypeList->name . ' ' . $organizationName;
        }
        $dateConverter = new DateConverter();
        $data['organizationList'] = $this->organization->getList();
        $data['leaveYearList'] = $this->leaveYearSetup->getLeaveYearList();
        $data['employee_list'] = $this->employee->getList();
        $data['showStatus'] = false;
        $leaveType = LeaveType::where([
            'id' => $filter['leave_type_id'] ?? null,
            'organization_id' => $filter['organization_id'] ?? null,
            'leave_year_id' => $filter['leave_year_id'] ?? null
        ])->first();
        if (isset($filter['organization_id']) && isset($leaveType) && $filter['organization_id'] != null) {
            $id = $filter['organization_id'];
            // $filter['leave_year_id']=getCurrentLeaveYearId();
            $filter['leave_year_id'] = $filter['leave_year_id'];

            if (isset($filter['leave_year_id']) || isset($filter['leave_type_id']) || isset($filter['employee_id'])) {
                $getEmployee = EmployeeLeave::where([
                    'leave_year_id' => $filter['leave_year_id'],
                    'leave_type_id' => $leaveType->id
                ])->pluck('employee_id');
                $data['employeeLeaveSummaries'] = self::getLeaveSummaries($filter, $id, $page_limit ?? 30, $data['leaveYearList'][$filter['leave_year_id']], $getEmployee);
            }
            $data['leaveYearList'] = $this->leaveYearSetup->getLeaveYearList();
            if (auth()->user()->user_type == 'division_hr' || auth()->user()->user_type == 'employee') {
                $leaveTypeLists = LeaveType::where('status', 11)->where('organization_id', optional(auth()->user()->userEmployer)->organization_id)->where('leave_year_id', getCurrentLeaveYearId())->get();
            } else {
                $leaveTypeLists = LeaveType::where('status', 11)->where('leave_year_id', getCurrentLeaveYearId())->get();
            }
            $data['id'] = $id;
            $data['leaveYearDetail'] = LeaveYearSetup::find($filter['leave_year_id']);
            $data['showStatus'] = true;
        }
        $data['leaveCode'] = $this->leaveCode;
        $data['leaveName'] = $this->leaveName;
        return view('leave::leave-report-annuall.index', $data);
    }

    public function getLeaveYearTypeLeave(Request $request)
    {
        try {
            $leaveYearType = LeaveType::where('leave_year_id', $request->leaveyearId)->get();
            foreach ($leaveYearType as $key => $leaveTypeList) {
                $organizationName = $leaveTypeList->organization ? "(" . optional($leaveTypeList->organization)->name . ")" : '';
                $data['leaveTypeList'][$leaveTypeList->id] = $leaveTypeList->name . ' ' . $organizationName;
            }
            $response = [
                'error' => false,
                'data' => $data['leaveTypeList'],
                'msg' => 'Success !!'
            ];
            return response()->json($response, 200);
        } catch (\Throwable $th) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Error !!'
            ];
            return response()->json($response, 200);
        }
    }

   function getLeaveSummaries($filter, $id, $limit, $leaveYear, $getEmployee)
    {
        $leave_year_id = $filter['leave_year_id'];
        $leaveYearSetupDetail = LeaveYearSetup::find($leave_year_id);
        if(!is_null($leaveYearSetupDetail)){
            $calenderType = $leaveYearSetupDetail->calendar_type;
            if($leaveYearSetupDetail->calendar_type=='nep'){
                $leaveYearStartDate = date_converter()->nep_to_eng_convert($leaveYearSetupDetail->start_date);
                $leaveYearEndDate = date_converter()->nep_to_eng_convert($leaveYearSetupDetail->end_date);
            }else{
                $leaveYearStartDate = $leaveYearSetupDetail->start_date_english;
                $leaveYearEndDate = $leaveYearSetupDetail->end_date_english;
            }
            $totalDaysInYear = getTotalDaysInLeaveYear($leaveYearStartDate, $leaveYearEndDate, 'eng');
        }
        $leaveTypeQuery = $this->leaveType->getAllLeaveTypes($id, $leave_year_id);
        if (!empty($filter['leave_type_id'])) {
            $leaveTypeQuery = $leaveTypeQuery->where('id', $filter['leave_type_id']);
        }

        $data['allLeaveTypes'] = $this->leaveType->getAllLeaveTypes($id, $leave_year_id);

        $query = Employee::query();
        $query->where('status', '=', 1);
        $query->where('organization_id', $id);
        // $query->where('employee_id',493);
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

        $employees = $query->whereIn('id', $getEmployee)->paginate($limit);
        $result = $employees->setCollection($employees->getCollection()->transform(function ($emp) use ($leave_year_id, $id, $filter, $leaveYear,$totalDaysInYear,$leaveYearSetupDetail,$leaveYearStartDate,$leaveYearEndDate) {
            // dd($emp);
            $leaveTypeQuery = LeaveType::query();
            $employeeLeaveDetails = [
                'opening' => 0,
                'leave_type' => null,
                'leave_taken' => 0,
                'balance' => 0,
                'encashable_limit' => 0,
                'encashable' => 0,
                'closining_balance' => 0
            ];
            $emp->carry_forward_text = 'No Carry forward';
            $leaveType = $leaveTypeQuery->where([
                'id' => $filter['leave_type_id'],
                'organization_id' => $id,
                'leave_year_id' => $filter['leave_year_id']
            ])->first();
            if (isset($leaveType) && $leaveType->carry_forward_status != 10) {
                $emp->carry_forward_text = 'Carry forward Enabled';
            }
            if ($leaveType) {
                $this->leaveCode = $leaveType->code ?? '';
                $this->leaveName = $leaveType->name ?? '';
                if($leaveType->prorata_status == 10){
                    $totalDays=(new EmployeeRepository())->calculateLeaveEarnedTotalDays($emp, $leaveYear,$totalDaysInYear,$leaveYearSetupDetail);
                }else{
                    $totalDays = (new EmployeeRepository())->calculateLeaveEarnedTotalDaysProrata($emp, $leaveYear,$totalDaysInYear,$leaveYearSetupDetail,$leaveType->advance_allocation);
                }
                $leaveTotalDays = ($leaveType->number_of_days / $totalDaysInYear) ?? 0;
                $leaveTypeTotalLeaveDays = $totalDays * $leaveTotalDays;
                $thresholdLimit = $leaveType->max_encashable_days ?? 0;
                $employeeLeaveOpening = EmployeeLeaveOpening::where('leave_year_id', $leave_year_id)->where('organization_id',  $id)->where('employee_id', $emp->id)->where('leave_type_id', $leaveType->id)->first();
                $openiningLeave = $employeeLeaveOpening->opening_leave ?? 0;
                // $employeeLeaveRemaining = EmployeeLeave::where('leave_year_id', $leave_year_id)->where('employee_id', $emp->id)->where('leave_type_id', $leaveType->id)->first();
                $employeTypeWiseLeave = Leave::where(
                        [
                            'employee_id' => $emp->id,
                            'leave_type_id' => $leaveType->id
                        ]
                    )
                        ->whereBetween('date', [$leaveYearStartDate, $leaveYearEndDate])
                        ->where('status', 3)
                        ->count() ?? 0;

                $employeeLeaveDetails['opening'] = bcdiv($openiningLeave,1,2) ?? 0;
                $employeeLeaveDetails['leave_type'] = bcdiv($leaveTypeTotalLeaveDays,1,2);
                $employeeLeaveDetails['leave_taken'] = bcdiv($employeTypeWiseLeave,1,2);
                $employeeLeaveDetails['balance'] = bcdiv($openiningLeave + $leaveTypeTotalLeaveDays - $employeTypeWiseLeave,1,2);
                $employeeLeaveDetails['encashable_limit'] = $thresholdLimit;
                $employeeLeaveDetails['encashable'] = 0;
                if ($employeeLeaveDetails['balance'] > $thresholdLimit && $leaveType->encashable_status != 10) {
                    $employeeLeaveDetails['encashable'] = bcdiv($employeeLeaveDetails['balance'] - $thresholdLimit,1,2);
                }
                $employeeLeaveDetails['closining_balance'] = $leaveType->carry_forward_status == 10 ? 0 : bcdiv($employeeLeaveDetails['balance'] - $employeeLeaveDetails['encashable'],1,2);
            }

            $emp->employeeLeaveDetails = $employeeLeaveDetails;
            return $emp;
        }));
        $employees;
        return $employees;
    }


    public function employeeLeaveDetails($employee_id)
    {
        $filter = [];
        $employeeModel = Employee::find($employee_id);
        $leave_year = LeaveYearSetup::currentLeaveYear();
        $employee_leave_details = [];

        if (auth()->user()->user_type == 'employee') {
            $filter['showStatus'] = 11;
        }

        $leaveTypeModels = LeaveType::when(true, function ($query) use ($filter, $employeeModel) {
            $query->where('status', 11);
            $query->where('organization_id', $employeeModel->organization_id);
            $query->where('leave_year_id', getCurrentLeaveYearId());

            if (isset($filter['showStatus']) && !empty($filter['showStatus'])) {
                $query->where('show_on_employee', $filter['showStatus']);
            }

            $query->where(function ($qry) use ($employeeModel) {
                $qry->where('gender', $employeeModel->gender);
                $qry->orWhere('gender', null);
            });

            $query->where(function ($qry) use ($employeeModel) {
                $qry->where('marital_status', $employeeModel->marital_status);
                $qry->orWhere('marital_status', null);
            });
        })->get();
        if (count($leaveTypeModels) > 0) {
            foreach ($leaveTypeModels as $key => $leaveTypeModel) {
                $employeeLeaveModel = EmployeeLeave::where([
                    'leave_year_id' => getCurrentLeaveYearId(),
                    'employee_id' => $employeeModel->id,
                    'leave_type_id' => $leaveTypeModel->id,
                    'is_valid' => 11
                ])->first();
                if ($employeeLeaveModel) {
                    $employee_leave_details[$key]['id'] = $employeeLeaveModel->id;
                    $employee_leave_details[$key]['leave_id'] = $leaveTypeModel->id;
                    $employee_leave_details[$key]['leave_type'] = $leaveTypeModel->name;
                    $employee_leave_details[$key]['leave_remaining'] = $employeeLeaveModel->leave_remaining;

                    $leave_taken = Leave::where([
                        'organization_id' => $employeeModel->organization_id,
                        'employee_id' => $employeeModel->id,
                        'leave_type_id' => $leaveTypeModel->id
                    ])
                        ->where('date', '>=', $leave_year->start_date_english)
                        ->where('date', '<=', $leave_year->end_date_english)
                        ->whereNotIn('status', [4, 5])
                        ->selectRaw('SUM(CASE WHEN leave_kind = 1 THEN 0.5 ELSE 1 END) as total_leaves')
                        ->first()
                        ->total_leaves;
                    $employee_leave_details[$key]['leave_taken'] = $leave_taken;
                    $employee_leave_details[$key]['total_leave'] = $employeeLeaveModel->leave_remaining + $leave_taken;
                    $openinigLeave = 0;
                    $employeeLeaveOpening = EmployeeLeaveOpening::where([
                        'leave_year_id' => getCurrentLeaveYearId(),
                        'employee_id' => $employeeModel->id,
                        'leave_type_id' => $leaveTypeModel->id
                    ])->first();

                    if ($employeeLeaveOpening) {
                        $openinigLeave = $employeeLeaveOpening->opening_leave ?? 0;
                    }
                    $employee_leave_details[$key]['opening_leave'] = $openinigLeave;
                }
            }
        }
        return $employee_leave_details;
    }




    public function storePreviousLeaveDetails(Request $request)
    {
        // $files = $request->upload_previous_leave_details;
        // $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

        // $reader->setReadDataOnly(true);

        // $spreadsheet = $reader->load($files);
        // \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());

        // $sheetData = $spreadsheet->getActiveSheet()->toArray();
        // // dd($sheetData);
        // $leaveTypes = $sheetData[0];
        // // array_shift($sheetData);
        // $import_file = PreviousLeaveDetailImport::import($sheetData);

        // if ($import_file) {
        //     toastr()->success('Previous Leave Detail Imported Successfully');
        // }
        DB::transaction(function () use ($request) {
            Excel::import(new LeaveOverViewImport, $request->file('upload_previous_leave_details'));
        });


        return redirect()->route('leave.leaveOverview');
    }

    public function exportLeaveOverview(Request $request)
    {
        $filter = $request->all();
        $data['empLeaveOverviewReports'] = $this->leave->employeeRemainingLeaveDetails($filter, 50);

        if ($data['empLeaveOverviewReports']->isEmpty()) {
            toastr('Please Filter first to download Excel Report', 'warning');
            return back();
        }

        return Excel::download(new LeaveOverviewReportExport($data), 'leave-overview-report.xlsx');
    }
}
