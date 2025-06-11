<?php

namespace App\Modules\Leave\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Helpers\DateTimeHelper;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Modules\Leave\Entities\Leave;
use App\Modules\Leave\Entities\LeaveType;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Leave\Entities\LeaveOverview;
use App\Modules\Employee\Entities\EmployeeLeave;
use App\Modules\Leave\Entities\LeaveEncashmentLog;
use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Leave\Entities\EmployeeLeaveOpening;
use App\Modules\Leave\Http\Requests\LeaveTypeRequest;
use App\Modules\Admin\Http\Controllers\ToolController;
use App\Modules\Leave\Repositories\LeaveTypeInterface;
use App\Modules\LeaveYearSetup\Entities\LeaveYearSetup;
use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Employee\Entities\EmployeeCarrierMobility;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\LeaveYearSetup\Repositories\LeaveYearSetupInterface;
use App\Modules\FiscalYearSetup\Repositories\FiscalYearSetupInterface;
use App\Modules\Setting\Entities\Department;
use App\Modules\Setting\Entities\Level;

class LeaveTypeController extends Controller
{
    private $leaveType;
    private $organization;
    private $dropdown;
    private $fiscalYearSetup;
    private $leaveYearSetup;
    private $employee;
    private $branchObj;
    protected $requestData = null;
    private $employeeObj;



    /**
     * LeaveTypeController constructor.
     * @param LeaveTypeInterface $leaveType
     * @param DropdownInterface $dropdown
     */
    public function __construct(
        LeaveTypeInterface $leaveType,
        OrganizationInterface $organization,
        DropdownInterface $dropdown,
        FiscalYearSetupInterface $fiscalYearSetup,
        LeaveYearSetupInterface $leaveYearSetup,
        EmployeeInterface $employee,
        BranchInterface $branchObj,
        EmployeeInterface $employeeObj

    ) {
        $this->leaveType = $leaveType;
        $this->organization = $organization;
        $this->dropdown = $dropdown;
        $this->fiscalYearSetup = $fiscalYearSetup;
        $this->leaveYearSetup = $leaveYearSetup;
        $this->employee = $employee;
        $this->branchObj = $branchObj;
        $this->employeeObj = $employeeObj;
    }

    public function index(Request $request)
    {
        $filter = $request->all();

        $data['leaveTypeModels'] = $this->leaveType->findAll(20, $filter);
        $data['organizationList'] = $this->organization->getList();
        $data['branchList'] = $this->branchObj->getList();
        $data['statusList'] = LeaveType::statusList();
        $data['currentLeaveyear'] = $this->leaveYearSetup->getLeaveYear();
        $data['leaveYearList'] = $this->leaveYearSetup->getLeaveYearList();

        return view('leave::leave-type.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data['isEdit'] = false;
        $data['organizationList'] = $this->organization->getList();
        $data['leaveTypeList'] = LeaveType::leaveTypeList();
        $data['leaveYearList'] = $this->leaveYearSetup->getActiveLeaveYearList();
        $currentLeaveyear = $this->leaveYearSetup->getCurrentLeaveYear();
        if ($currentLeaveyear->isNotEmpty() && $currentLeaveyear->count() > 0) {
            $data['currentLeaveyear'] = $currentLeaveyear;
        } else {
            toastr()->error('Please set Active Leave Year first !!!');
            return redirect(route('leaveYearSetup.index'));
        }
        $data['currentLeaveyear'] = getCurrentLeaveYearId();

        // dd($data['currentLeaveyear']);
        $data['genderList'] = $this->dropdown->getFieldBySlug('gender');
        $data['maritalStatusList'] = $this->dropdown->getFieldBySlug('marital_status');
        $data['departmentList'] = Department::pluck('title', 'id')->toArray();
        $data['levelList'] = Level::pluck('title', 'id')->toArray();
        $data['yesNoList'] = array('11' => 'Yes', '10' => 'No');
        $data['noYesList'] = array('10' => 'No', '11' => 'Yes');
        $data['jobTypeList'] = LeaveType::JOB_TYPE;
        // $data['contractTypeList'] = LeaveType::CONTRACT;
        $data['halfLeaveList'] = Leave::halfTypeList();
        $data['statusList'] = LeaveType::statusList();
        $data['employeeList'] = $this->employeeObj->getList();

        return view('leave::leave-type.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(LeaveTypeRequest $request)
    {
        $data = $request->all();

        $data['gender'] = $data['gender'] ==  'all' ? null : $data['gender'];
        $data['marital_status'] = $data['marital_status'] ==  'all' ? null : $data['marital_status'];
        $data['advance_allocation'] = $data['prorata_status'] == 10 ? 10 : $data['advance_allocation'];
        $data['max_encashable_days'] = $data['encashable_status'] == 10 ? null : $data['max_encashable_days'];

        try {
            $leaveTypeData = $this->leaveType->create($data);
            // if (isset($leaveTypeData['fixed_remaining_leave']) && $leaveTypeData['fixed_remaining_leave'] > 0) {
            //     $this->updateFixedEmpRemainingLeave($leaveTypeData);
            // } else {
            // }
            $this->updateLeaveTypeDetails($leaveTypeData);

            if ($leaveTypeData->employeeLeave()->exists()) {
                toastr()->success('LeaveType Created Successfully');
            } else {
                toastr()->error('No Employee Found');
            }
        } catch (\Throwable $e) {
            dd($e);
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('leaveType.index', ['leave_year_id' => getCurrentLeaveYearId()]));
    }


    public function getEmployeeDepartmentWise(Request $request)
    {
        $employees = Employee::whereIn('department_id', $request->department_id)->get();

        // Transform the collection
        $formatted = $employees->map(function ($employee) {
            return [
                'id' => $employee->id,
                'first_name' => $employee->first_name,
                'middle_name' => $employee->middle_name,
                'last_name' => $employee->last_name,
                'full_name' => trim("{$employee->first_name} {$employee->middle_name} {$employee->last_name}"),
            ];
        });

        return response()->json($formatted);
    }
    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id, Request $request)
    {
        // abort('404');
        $filter = $request->all();
        $data['yesNoList'] = array('11' => 'Yes', '10' => 'No');
        $data['leaveType'] = $this->leaveType->findOne($id);
        $data['empList'] = $this->leaveType->getEmpListFromLeaveType($id, $filter);
        return view('leave::leave-type.view', $data);
        // return redirect(route('leaveType.index'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $data['isEdit'] = true;
        $data['leaveTypeModel'] = $this->leaveType->findOne($id);
        $data['organizationList'] = $this->organization->getList();
        $data['leaveTypeList'] = LeaveType::leaveTypeList();
        $data['leaveYearList'] = $this->leaveYearSetup->getActiveLeaveYearList();
        $data['currentLeaveyear'] = $this->leaveYearSetup->getCurrentLeaveYear();

        $data['currentLeaveyear'] = $data['leaveTypeModel']->leave_year_id;

        // $currentYear = LeaveYearSetup::where('status', 1)
        // ->where('id',$data['leaveTypeModel']->leave_year_id)
        // ->get()
        // ->mapWithKeys(function ($item) {
        //     return [
        //         $item->id => $item->leave_year ?? $item->leave_year_english
        //     ];
        // });

        //   $data['currentLeaveyear'] = $currentYear;




        // $data['currentLeaveyear'] = $this->leaveYearSetup->getCurrentLeaveYear();

        $data['genderList'] = $this->dropdown->getFieldBySlug('gender');
        $data['maritalStatusList'] = $this->dropdown->getFieldBySlug('marital_status');
        $data['departmentList'] = Department::pluck('title', 'id')->toArray();
        $data['levelList'] = Level::pluck('title', 'id')->toArray();
        $data['yesNoList'] = array('11' => 'Yes', '10' => 'No');
        $data['noYesList'] = array('10' => 'No', '11' => 'Yes');
        // $data['contractTypeList'] = $this->dropdown->getFieldBySlug('contract_type');

        $data['jobTypeList'] = LeaveType::JOB_TYPE;
        // $data['contractTypeList'] = LeaveType::CONTRACT;
        $data['halfLeaveList'] = Leave::halfTypeList();
        $data['statusList'] = LeaveType::statusList();
        $data['employeeList'] = $this->employeeObj->getList();


        return view('leave::leave-type.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(LeaveTypeRequest $request, $id)
    {
        $data = $request->all();
        $this->requestData = $data;
        $data['gender'] = $data['gender'] ==  'all' ? null : $data['gender'];
        $data['marital_status'] = $data['marital_status'] ==  'all' ? null : $data['marital_status'];
        $data['advance_allocation'] = $data['prorata_status'] == 10 ? 10 : $data['advance_allocation'];
        $data['max_encashable_days'] = $data['encashable_status'] == 10 ? null : $data['max_encashable_days'];

        try {
            $leaveType = $this->leaveType->findOne($id);
            $this->leaveType->update($id, $data);
            $leaveType->request_prorata = $data['prorata_status'];
            $leaveType->is_update = true;

            $this->updateLeaveTypeDetails($leaveType);

            // $this->leaveType->update($id, $data);
            // if (isset($data['fixed_remaining_leave']) && $data['fixed_remaining_leave'] > 0) {
            //     // $this->updateFixedEmpRemainingLeave($leaveType, $data);
            //     $this->updateLeaveTypeDetails($leaveType);
            // } else {
            //     if ($leaveType->prorata_status != $data['prorata_status']) {
            //         $this->UpdateLeaveProrataTypeDetails($leaveType, $data);
            //     }
            // }
            toastr()->success('LeaveType Updated Successfully');
        } catch (\Throwable $e) {
            dd($e);
            toastr()->error('Something Went Wrong !!!');
        }

        // return redirect(route('leaveType.index'));
        return redirect(route('leaveType.index', ['leave_year_id' => getCurrentLeaveYearId()]));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $this->leaveType->delete($id);

            toastr()->success('LeaveType Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }

    /**
     *
     */
    public function sync($leaveYearId)
    {
        try {
            $leaveYearModel = $this->leaveYearSetup->findOne($leaveYearId);
            // if ($leaveYearModel->start_date_english == date('Y-m-d')) {
            // $lastLeaveYearId = $leaveYearId - 1;
            $lastLeaveYearId = LeaveYearSetup::previousLeaveYear()->id;
            $leaveTypeFilter = ['leave_year_id' => $lastLeaveYearId];
            $leaveTypeModels = $this->leaveType->findAll(null, $leaveTypeFilter);
            foreach ($leaveTypeModels as $leaveTypeModel) {
                $newData = $leaveTypeModel->toArray();
                $newData['leave_year_id'] = $leaveYearId;
                unset($newData['id']);
                unset($newData['created_by']);
                unset($newData['updated_by']);
                unset($newData['created_at']);
                unset($newData['updated_at']);
                unset($newData['employee_leave']);
                $newData['departmentArray'] = $leaveTypeModel->departments->pluck('department_id')->toArray();
                $newData['levelArray'] = $leaveTypeModel->levels->pluck('level_id')->toArray();
                $leaveTypeData = $this->leaveType->create($newData);
                $this->updateLeaveTypeDetails($leaveTypeData, $lastLeaveYearId, $leaveTypeModel->id);
            }
            $leaveYearModel->is_sync = 11;
            $leaveYearModel->save();

            toastr()->success('Leave Type sync Successful.');
            // } else {
            //     toastr()->warning('Unable to sync Leave Type.');
            // }
        } catch (\Throwable $e) {
            toastr()->error('Oops! Something Went Wrong.');
        }

        return redirect()->back();
    }


    /**
     * For internal use
     */
    public function updateEmployeeData($params, $lastLeaveYearId = null, $lastLeaveTypeId = null, $closingYearKey)
    {

        $joinDate = $params['employee_join_date'];
        $leaveYearStartDate = $params['leaveYearStartDate'];
        $nepaliLeaveYearStartDate = $params['nepaliLeaveYearStartDate'];
        $leaveTypeData = $params['leaveTypeData'];
        $leaveYearId = $params['leaveYearId'];
        $employee_id = $params['employee_id'];
        $leaveTypeId = $params['leaveTypeId'];
        $currentDate = date('Y-m-d');
        $emp_job_type = $params['job_type'];
        $emp_job_type_changed_date_nep = $params['job_type_changed_date_nep'];
        $nepaliJoinDate = $params['employee_nepali_join_date'];
        // if(isset($leaveTypeData['job_type']) && isset($params['job_type_changed_date_nep'])){
        if (isset($leaveTypeData->jobTypes) && count($leaveTypeData->jobTypes) > 0 && isset($params['job_type_changed_date_nep'])) {
            $nepaliJoinDate = $emp_job_type_changed_date_nep;
        }
        if ($nepaliJoinDate < $nepaliLeaveYearStartDate) {
            $nepaliJoinDate = $nepaliLeaveYearStartDate;
        }
        $monthsDiff = DateTimeHelper::DateDiff(date_converter()->nep_to_eng_convert($nepaliLeaveYearStartDate), date_converter()->nep_to_eng_convert($nepaliJoinDate));
        $empRemainingMonthInCurrentLeave = 12 - $monthsDiff;
        $leaveTypeDaysPerMonth = ($leaveTypeData['number_of_days'] / 12);
        $openingLeave = (bcdiv($leaveTypeDaysPerMonth * $empRemainingMonthInCurrentLeave, 1, 2));
        $prorataStatus = false;
        $prorataValue = 0;
        $leaveAmountValue = 0;
        // if ($leaveTypeData['prorata_status'] == '11') {
        //     $calc_date = $joinDate;
        //     // if(isset($leaveTypeData['job_type']) && isset($params['job_type_changed_date_nep'])){
        //     if ((count($leaveTypeData->jobTypes) > 0) && isset($params['job_type_changed_date_nep'])) {
        //         $calc_date = date_converter()->nep_to_eng_convert($emp_job_type_changed_date_nep);
        //     }
        //     // ---------------------Old Code-------------
        //     $newMonthsDiff = DateTimeHelper::DateDiff($calc_date, $currentDate);
        //     if ($newMonthsDiff > 12) {
        //         $newMonthsDiff = DateTimeHelper::DateDiff($leaveYearStartDate, $currentDate);
        //     }
        //     $prorataStatus=true;
        //     $openingLeave = round($leaveTypeDaysPerMonth * $newMonthsDiff, 2);
        //     $prorataValue=round($leaveTypeDaysPerMonth * $newMonthsDiff, 2);
        //     // ---------------------Old Code-------------
        // }
        $closiningLeaveValue = $this->previousClosiningLeave($params, $closingYearKey, $leaveTypeData->organization_id, $leaveTypeData) ?? 0;
        // dd($closiningLeaveValue);
        $employee = Employee::where('employee_id', $employee_id)->first();
        $nepJoinDate = $employee->nepali_join_date;
        $joinDateCheckStatus = false;
        $joinYear = null;
        $explodeJoinDate = null;
        $restrictRetire = false;
        if ($nepJoinDate) {
            $explodeJoinDate = explode('-', $nepJoinDate);
            $joinYear = $explodeJoinDate[0];
            if ($joinYear == $params['currentLeaveYear']) {
                $joinDateCheckStatus = true;
            }
        }
        if ($params['prorata_status'] == '11') {
            $prorataStatus = true;
            $calc_date = explode('-', date_converter()->eng_to_nep_convert($currentDate));
            $openingLeave = $prorataValue = 0;
            $lastMonth = $calc_date[1];
            $lastDay = $calc_date[2];
            if ($joinDateCheckStatus) {
                if ($explodeJoinDate[1] == $calc_date[1]) {
                    $lastDay = ($calc_date[2] + 1) - $explodeJoinDate[2];
                } elseif ($explodeJoinDate[1]  > $calc_date[1]) {
                    $lastDay = 0;
                }
            }
            $totalDaysInMonth = date_converter()->getTotalDaysInMonth($calc_date[0], $lastMonth);
            $perDayValue = $leaveTypeDaysPerMonth / $totalDaysInMonth ?? 30;
            $openingLeave = $prorataValue = bcdiv((($lastMonth - 1) * $leaveTypeDaysPerMonth) + ($perDayValue * $lastDay), 1, 2);
            if ($this->requestData['advance_allocation'] == '11') {
                $calc_date = date_converter()->nep_to_eng_convert($params['currentLeaveYear'] . '-01-01');
                if ($joinDateCheckStatus) {
                    $calc_date = date_converter()->nep_to_eng_convert($nepJoinDate);
                }
                $openingLeave = $prorataValue = 0;
                $newMonthsDiff = 0;
                $prorataStatus = true;
                // if ((count($leaveTypeData->jobTypes) > 0) && isset($params['job_type_changed_date_nep'])) {
                //     $calc_date = date_converter()->nep_to_eng_convert($emp_job_type_changed_date_nep);
                // }
                // ---------------------Old Code-------------
                $newMonthsDiff = DateTimeHelper::DateDiff($calc_date, $currentDate);
                if ($newMonthsDiff <= 0) {
                    $newMonthsDiff = 0;
                }
                if ($newMonthsDiff > 12) {
                    $newMonthsDiff = DateTimeHelper::DateDiff($leaveYearStartDate, $currentDate);
                }
                $openingLeave = $prorataValue = bcdiv($leaveTypeDaysPerMonth * $newMonthsDiff, 1, 2);
            }

            // ---------------------------
            // $calc_date = $joinDate;
            // if ((count($leaveTypeData->jobTypes) > 0) && isset($params['job_type_changed_date_nep'])) {
            //     $calc_date = date_converter()->nep_to_eng_convert($emp_job_type_changed_date_nep);
            // }
            // $newMonthsDiff = DateTimeHelper::DateDiff($calc_date, $currentDate);
            // if ($newMonthsDiff > 12) {
            //     $newMonthsDiff = DateTimeHelper::DateDiff($leaveYearStartDate, $currentDate);
            // }
            // $prorataStatus=true;
            // $openingLeave = $prorataValue=round($leaveTypeDaysPerMonth * $newMonthsDiff, 2);

            // ---------------------------


        } else {
            $employeeRetirementAge = $employee->retirement_age ?? 0;
            $retirementStatus = false;
            $retirementDate = null;
            $archivedStatus = false;
            $archivedDate = null;
            $contractStatus = false;
            $contractEndDate = null;

            $fetchJobType = $employee->appendPayrollRetatedDetailAttributes($employee);
            if ($employee->nep_archived_date != null) {
                $archivedStatus = true;
                $archivedDate = $employee->nep_archived_date;
            }
            if ($fetchJobType->job_type != null && $fetchJobType->job_type == 12) {
                $contractStartDate = date_converter()->eng_to_nep_convert($fetchJobType->contract_start_date);
                $contractEndDate = date_converter()->eng_to_nep_convert($fetchJobType->contract_end_date);
                $contractExplodedStartDate = explode('-', $contractStartDate);
                if ($contractExplodedStartDate[0] == $params['currentLeaveYear']) {
                    $contractStatus = true;
                }
            }
            if ($employeeRetirementAge && $employeeRetirementAge > 0) {
                if ($employee->nep_dob && $employee->nep_dob != null) {
                    $employeeDobNep = explode('-', $employee->nep_dob);
                    $employeeBirthYear = $employeeDobNep[0] + $employeeRetirementAge;
                    $retirementDate = $employeeBirthYear . '-' . $employeeDobNep[1] . '-' . $employeeDobNep[2];
                    if ($employeeBirthYear < (int)$params['currentLeaveYear']) {
                        $restrictRetire = true;
                    }
                    if ($employeeBirthYear == (int)$params['currentLeaveYear']) {
                        $retirementStatus = true;
                    }
                }
            }
            $dateCheckerStatus = Self::dateChecker($archivedDate, $contractEndDate, $retirementDate);
            // dd($dateCheckerStatus);
            if (isset($dateCheckerStatus['validType']) && $dateCheckerStatus['validType'] === 'retirement' && $retirementStatus) {
                if ($employeeBirthYear == (int)$params['currentLeaveYear']) {
                    if ($joinDateCheckStatus) {
                        $employeeDobNep = $explodeJoinDate;
                    }
                    $lastMonth = $employeeDobNep[1];
                    $lastDay = $employeeDobNep[2];
                    $totalDaysInMonth = date_converter()->getTotalDaysInMonth($employeeDobNep[0], $lastMonth);
                    $perDayValue = $leaveTypeDaysPerMonth / $totalDaysInMonth ?? 30;
                    $leaveAmountValue += bcdiv((($lastMonth - 1) * $leaveTypeDaysPerMonth) + ($perDayValue * $lastDay), 1, 2);
                    $retirementStatus = true;
                }
            } elseif ($archivedDate || $contractEndDate) {
                $calculateDate = explode('-', $dateCheckerStatus['date']);
                if ($joinDateCheckStatus) {
                    $calculateDate = $explodeJoinDate;
                }
                $lastMonth = $calculateDate[1];
                if ($contractStatus) {
                    if ($joinDateCheckStatus) {
                        $contractExplodedStartDate = $explodeJoinDate;
                    }
                    if (isset($contractExplodedStartDate[1]) && $contractExplodedStartDate[1] < $lastMonth) {
                        $lastMonth = $lastMonth - $contractExplodedStartDate[1] + 1;
                    }
                }
                $lastDay = $calculateDate[2];
                $totalDaysInMonth = date_converter()->getTotalDaysInMonth((int)$calculateDate[0], (int)$lastMonth);
                $perDayValue = $leaveTypeDaysPerMonth / $totalDaysInMonth ?? 30;
                $leaveAmountValue += bcdiv((($lastMonth - 1) * $leaveTypeDaysPerMonth) + ($perDayValue * $lastDay), 1, 2);
            } else {
                $leaveAmountValue = $leaveTypeData['number_of_days'];
                if ($joinDateCheckStatus) {
                    $leaveAmountValue = ($leaveTypeData['number_of_days'] / (12)) * (12 - $explodeJoinDate[1] + 1);
                }
            }
        }
        if ($lastLeaveYearId && $lastLeaveTypeId) {
            $employeeLeaveModel = EmployeeLeave::where([
                'leave_year_id' => $lastLeaveYearId,
                'employee_id' => $employee_id,
                'leave_type_id' => $lastLeaveTypeId
            ])->first();

            if ($employeeLeaveModel) {
                if ($leaveTypeData->carry_forward_status == '11') {

                    $openingLeave += $employeeLeaveModel->leave_remaining;

                    //update previous leave year data in leave overview
                    $overviewData = [
                        'employee_id' => $employee_id,
                        'leave_type_id' => $leaveTypeData->id,
                        'previous_remaining_leave' => $employeeLeaveModel->leave_remaining
                    ];
                    LeaveOverview::updateOrCreate([
                        'employee_id' => $employee_id,
                        'leave_type_id' => $leaveTypeData->id
                    ], $overviewData);
                }

                if ($leaveTypeData->encashable_status == '11' && $leaveTypeData['prorata_status'] == '11') {
                    $openingLeave = 0;
                }
            }
        }
        // dd($prorataStatus)
        if ($prorataStatus) {
            $leaveRemaining = $prorataValue + $closiningLeaveValue;
        } else {
            // dd($leaveAmountValue,$closiningLeaveValue);
            $leaveRemaining = $leaveAmountValue + $closiningLeaveValue;
        }
        if ($joinYear > $params['currentLeaveYear']) {
            $leaveRemaining = 0;
            $closiningLeaveValue = 0;
        }

        if ($restrictRetire) {
            $leaveRemaining = 0;
        }
        $empLeaveData = [
            'leave_year_id' => $leaveYearId,
            'employee_id' => $employee_id,
            'leave_type_id' => $leaveTypeId
        ];
        // $employee_opening_leave = EmployeeLeaveOpening::updateOrCreate(
        //     $empLeaveData,
        //     $empLeaveData + [
        //         // 'opening_leave' => $openingLeave,
        //         'opening_leave' => $closiningLeaveValue,
        //         'organization_id' => $leaveTypeData['organization_id']
        //     ]
        // );
        // dd($leaveRemaining);
        $employeeLeaveModel = EmployeeLeave::updateOrCreate(
            $empLeaveData,
            $empLeaveData + [
                // 'leave_remaining' => $employee_opening_leave['opening_leave'],
                'leave_remaining' => $leaveRemaining,
                'initial_leave_remaining' => $leaveRemaining,
                'prorata_earned' => null
                // 'is_valid' => 11
            ]
        );

        // $employeeOpeningLeave = [
        //     'leave_year_id' => $leaveYearId,
        //     'opening_leave' => $openingLeave,
        // ];
        // EmployeeLeaveOpening::saveData($leaveTypeData['organization_id'], $employee_id, $leaveTypeId, $employeeOpeningLeave);

        // $employeeLeaveModel = EmployeeLeave::where([
        //     'leave_year_id' => $leaveYearId,
        //     'employee_id' => $employee_id,
        //     'leave_type_id' => $leaveTypeId
        // ])->first();


        // if (empty($employeeLeaveModel)) {
        //     $employeeLeaveModel = new EmployeeLeave();
        //     $employeeLeaveModel->leave_year_id = $leaveYearId;
        //     $employeeLeaveModel->employee_id = $employee_id;
        //     $employeeLeaveModel->leave_type_id = $leaveTypeId;
        // }

        // $employee_opening_leave = EmployeeLeaveOpening::getLeaveOpening($leaveYearId, $leaveTypeData['organization_id'], $employee_id, $leaveTypeId);
        // $employeeLeaveModel->leave_remaining = $employee_opening_leave;

        $employeeLeaveModel->is_valid = 11;

        // $leaveTaken = Leave::where([
        //     'organization_id' => $leaveTypeData['organization_id'],
        //     'employee_id' => $employee_id,
        //     'leave_type_id' => $leaveTypeId
        // ])
        //     ->where('status', '!=', '4')
        //     ->count();

        // if ($leaveTaken > 0) {
        //     $employeeLeaveModel->leave_remaining -= $leaveTaken;
        // }

        if ($employeeLeaveModel->leave_remaining < 0) {
            $employeeLeaveModel->leave_remaining = 0;
        }
        // dd($employeeLeaveModel);
        $employeeLeaveModel->save();

        //For Leave encashment Log
        if ($leaveTypeData->encashable_status == 11 && !is_null($leaveTypeData->max_encashable_days)) {
            $leaveEncashData = [
                'employee_id' => $employee_id,
                'leave_type_id' => $leaveTypeId
            ];
            if ($employeeLeaveModel['leave_remaining'] >= 0 && ($employeeLeaveModel['leave_remaining'] > $leaveTypeData['max_encashable_days'])) {
                $exceeded_balance = $employeeLeaveModel['leave_remaining'] - $leaveTypeData['max_encashable_days'];
            } else {
                $exceeded_balance = 0;
            }
            $leaveEncashmentModel = LeaveEncashmentLog::firstOrCreate(
                $leaveEncashData,
                $leaveEncashData + [
                    'encashment_threshold' => $leaveTypeData['max_encashable_days'],
                    'leave_remaining' => $employeeLeaveModel['leave_remaining'],
                    'exceeded_balance' => $exceeded_balance,
                    'total_balance' => $employeeLeaveModel['leave_remaining'],
                    'eligible_encashment' => $exceeded_balance,
                    'status' => 1,
                    'is_valid' => 11
                ]
            );
            $leaveEncashmentModel->save();
        }
        //
    }

    public function dateChecker($archivedDate, $contractEndDate, $retirementDate)
    {
        $inputDates = [
            'archived'    => $archivedDate,
            'contract' => $contractEndDate,
            'retirement'  => $retirementDate
        ];

        $dates = [];

        foreach ($inputDates as $key => $value) {
            if (!empty($value)) {
                $dates[$key] = new DateTime($value);
            }
        }

        if (empty($dates)) {
            return null;
        }

        uasort($dates, fn($a, $b) => $a <=> $b);

        $firstKey = array_key_first($dates);
        $firstDate = $dates[$firstKey]->format('Y-m-d');

        return [
            'validType' => $firstKey,
            'date' => $firstDate
        ];
    }




    public function calculateAge($dobNepdate)
    {
        $nepaliYear = date_converter()->nep_to_eng_convert($dobNepdate);
        $age = Carbon::parse($nepaliYear)->age;
        // return response()->json(['age' => $age]);

        $dob = Carbon::parse($nepaliYear);
        $currentDate = Carbon::now();
        $years = $dob->diffInYears($currentDate);
        $months = $dob->copy()->addYears($years)->diffInMonths($currentDate);
        $days = $dob->copy()->addYears($years)->addMonths($months)->diffInDays($currentDate);
        return $age;
    }

    public function previousClosiningLeave($params, $closingYearKey, $id, $leaveTypeData)
    {
        $closingDetails = 0;
        if ($closingYearKey && $closingYearKey != null) {
            $allLeaveYear = $this->leaveYearSetup->getLeaveYearList();
            $closingYear = $allLeaveYear[$closingYearKey];
            $previousLeaveType = LeaveType::where([
                'organization_id' => $id,
                'leave_year_id' => $closingYearKey,
                'code' => $leaveTypeData->code
            ])->first();
            if ($previousLeaveType) {
                if ($previousLeaveType->carry_forward_status == '11') {
                    $filter = [
                        "leave_year_id" => $closingYearKey,
                        "organization_id" => $id,
                        "leave_type_id" => $previousLeaveType->id,
                        "employee_id" => $params['employee_id']
                    ];
                    $closingDetails = self::getLeaveSummaries($filter, $id, 30, $closingYear, $params['employee_id']);
                }
            }
        }
        return $closingDetails;
    }
     function getLeaveSummaries($filter, $id, $limit, $leaveYear, $getEmployee)
    {
        $leave_year_id = $filter['leave_year_id'];

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
            $query->where('employee_id', $filter['employee_id']);
        }

        $employees = $query->where('id', $getEmployee)->paginate($limit);
        $result = $employees->setCollection($employees->getCollection()->transform(function ($emp) use ($leave_year_id, $id, $filter, $leaveYear) {
            $leaveTypeQuery = LeaveType::query();
            $closining_balance = 0;
            $emp->closining_balance = $closining_balance;
            $leaveType = $leaveTypeQuery->where([
                'id' => $filter['leave_type_id'],
                'organization_id' => $id,
                'leave_year_id' => $filter['leave_year_id']
            ])->first();
            if ($leaveType) {
                $empJoiningDate = explode('-', $emp->nepali_join_date);
                $status = false;
                $empJoiningMonth = (int)"$empJoiningDate[1]";
                if ($empJoiningDate[0] != $leaveYear) {
                    $empJoiningMonth = 1;
                    $status = true;
                }
                $totalDays = 0;
                $isCarrierUpdate = EmployeeCarrierMobility::where('employee_id', $emp->id)->latest()->first();
                if ($isCarrierUpdate) {
                    $carrierUpdateDate = explode('-', date_converter()->eng_to_nep_convert($isCarrierUpdate->date));
                    if ($carrierUpdateDate[0] == $leaveYear) {
                        $empJoiningDate = $carrierUpdateDate;
                        $empJoiningMonth = $carrierUpdateDate[1];
                    }
                }
                for ($i = $empJoiningMonth; $i <= 12; $i++) {
                    $totalDaysInMonth = date_converter()->getTotalDaysInMonth($leaveYear, $i);
                    if ($i == $empJoiningMonth && !$status) {
                        $totalDaysInMonth = $totalDaysInMonth - $empJoiningDate[2] + 1;
                    }
                    $totalDays += $totalDaysInMonth;
                }
                $leaveTotalDays = ($leaveType->number_of_days / 366) ?? 0;
                // dd($totalDays,$empJoiningDate,$totalDays * $leaveTotalDays);
                $leaveTypeTotalLeaveDays = bcdiv($totalDays * $leaveTotalDays, 1, 2);
                $thresholdLimit = $leaveType->max_encashable_days ?? 0;
                $employeeLeaveOpening = EmployeeLeaveOpening::where('leave_year_id', $leave_year_id)->where('organization_id',  $id)->where('employee_id', $emp->id)->where('leave_type_id', $leaveType->id)->first();
                $openiningLeave = $employeeLeaveOpening->opening_leave ?? 0;
                $employeeLeaveRemaining = EmployeeLeave::where('leave_year_id', $leave_year_id)->where('employee_id', $emp->id)->where('leave_type_id', $leaveType->id)->first();
                $employeTypeWiseLeave = Leave::where(
                    [
                        'employee_id' => $emp->id,
                        'leave_type_id' => $leaveType->id
                    ]
                )
                    ->where('status', 3)
                    ->count() ?? 0;
                $employeeLeaveDetails['opening'] = bcdiv($openiningLeave, 1, 2) ?? 0;
                $employeeLeaveDetails['leave_type'] = bcdiv($leaveTypeTotalLeaveDays, 1, 2);
                $employeeLeaveDetails['leave_taken'] = bcdiv($employeTypeWiseLeave, 1, 2);
                $employeeLeaveDetails['balance'] = bcdiv($openiningLeave + $leaveTypeTotalLeaveDays - $employeTypeWiseLeave, 1, 2);
                $employeeLeaveDetails['encashable_limit'] = $thresholdLimit;
                $employeeLeaveDetails['encashable'] = 0;
                if ($employeeLeaveDetails['balance'] > $thresholdLimit && $leaveType->encashable_status != 10) {
                    $employeeLeaveDetails['encashable'] = bcdiv($employeeLeaveDetails['balance'] - $thresholdLimit, 1, 2);
                }
                $closining_balance = $leaveType->carry_forward_status == 10 ? 0 : bcdiv($employeeLeaveDetails['balance'] - $employeeLeaveDetails['encashable'], 1, 2);
            }
            $emp->closining_balance = $closining_balance;
            return $emp->closining_balance;
        }));
        $employees;
        return $employees[0];
    }
    // public function updateFixedEmpRemainingLeave($leaveType)
    // {
    //     // $leaveType = ($this->leaveType->findOne($leaveTypeId));
    //     $departments = $leaveType->departments->pluck('department_id', 'department_id')->toArray();
    //     $params['department_ids'] = $departments;
    //     $levels = $leaveType->levels->pluck('level_id', 'level_id')->toArray();;
    //     $params['level_ids'] = $levels;
    //     $params['gender'] = $leaveType['gender'];
    //     $params['marital_status'] = $leaveType['marital_status'];
    //     $params['contract_type'] = $leaveType['contract_type'];
    //     $params['probation_status'] = $leaveType['job_type'];

    //     $employees = $this->employee->getEmployeeByOrganization($leaveType['organization_id'], $params);
    //     // dd($params,$employees->toArray());

    //     $currentLeaveYearData = LeaveYearSetup::currentLeaveYear();
    //     if (!is_null($currentLeaveYearData)) {
    //         $leaveYearId = $currentLeaveYearData['id'];
    //         foreach ($employees as $employee) {
    //             $employeeLeaveModel = EmployeeLeave::where([
    //                 'leave_year_id' => $leaveYearId,
    //                 'employee_id' => $employee->id,
    //                 'leave_type_id' => $leaveType->id
    //             ])->first();

    //             if (empty($employeeLeaveModel)) {
    //                 $employeeLeaveModel = new EmployeeLeave();
    //                 $employeeLeaveModel->leave_year_id = $leaveYearId;
    //                 $employeeLeaveModel->employee_id = $employee->id;
    //                 $employeeLeaveModel->leave_type_id = $leaveType->id;
    //             }

    //             $employeeLeaveModel->leave_remaining = $leaveType['fixed_remaining_leave'];
    //             $employeeLeaveModel->save();
    //         }
    //     }
    // }

    public function updateLeaveTypeDetails($leaveType, $lastLeaveYearId = null, $lastLeaveTypeId = null)
    {

        // $leaveType = ($this->leaveType->findOne($leaveTypeId));
        $params['department_ids'] = $leaveType->departments->pluck('department_id', 'department_id')->toArray();
        $params['level_ids']  = $leaveType->levels->pluck('level_id', 'level_id')->toArray();
        $params['job_type_ids']  = $leaveType->jobTypes->pluck('job_type_id', 'job_type_id')->toArray();
        $params['gender'] = $leaveType['gender'];
        $params['marital_status'] = $leaveType['marital_status'];
        // $params['contract_type'] = $leaveType['contract_type'];
        // $params['job_type'] = $leaveType['job_type'];

        $employees = $this->employee->getEmployeeByOrganization($leaveType['organization_id'], $params);
        $empLeave = $leaveType->employeeLeave->where('is_valid', 11);
        $leaveEncashmentLog = $leaveType->leaveEncashmentLogs->where('is_valid', 11);
        $unsetEmpLeaves = array_diff($empLeave->pluck('employee_id')->toArray(), $employees->pluck('id')->toArray());
        $unsetLeaveEncashmentLogs = array_diff($leaveEncashmentLog->pluck('employee_id')->toArray(), $employees->pluck('id')->toArray());
        foreach ($unsetEmpLeaves as $key => $value) {
            $leaveType = $empLeave->where('employee_id', $value)->first();
            $leaveType->is_valid = 10;
            $leaveType->save();
        }
        foreach ($unsetLeaveEncashmentLogs as $key => $value) {
            $leaveEncashLog = $leaveEncashmentLog->where('employee_id', $value)->first();
            $leaveEncashLog->is_valid = 10;
            $leaveEncashLog->save();
        }
        $currentLeaveYearData = LeaveYearSetup::currentLeaveYear();
        // dd($currentLeaveYearData);
        $allLeaveYear = $this->leaveYearSetup->getLeaveYearList();
        $currentLeaveYear = $allLeaveYear[$currentLeaveYearData['id']];
        $closingYearKey = '';
        // commented this code as it was throwing error. purpose not clear
        // foreach ($allLeaveYear as $key => $year) {
        //     if ($currentLeaveYear - 1 == $year) {
        //         $closingYearKey = $key;
        //         break;
        //     }
        // }
        if (!is_null($currentLeaveYearData)) {
            foreach ($employees as $employee) {

                if (isset($leaveType['fixed_remaining_leave']) && $leaveType['fixed_remaining_leave'] > 0) {
                    $empLeaveData = [
                        'leave_year_id' => $currentLeaveYearData['id'],
                        'employee_id' => $employee->id,
                        'leave_type_id' => $leaveType->id
                    ];

                    $leaveTaken = Leave::where([
                        'organization_id' => $leaveType['organization_id'],
                        'employee_id' => $employee->id,
                        'leave_type_id' => $leaveType['id']
                    ])
                        ->where('status', '!=', '4')
                        ->count();

                    $empLeave = EmployeeLeave::updateOrCreate(
                        $empLeaveData,
                        $empLeaveData + [
                            'leave_remaining' => $leaveType['fixed_remaining_leave'] - $leaveTaken,
                            'initial_leave_remaining' => $leaveType['fixed_remaining_leave'] - $leaveTaken,
                            'is_valid' => 11
                        ]
                    );

                    EmployeeLeaveOpening::firstOrCreate(
                        $empLeaveData,
                        $empLeaveData + [
                            'opening_leave' => $leaveType['fixed_remaining_leave'],
                            'organization_id' => $leaveType['organization_id']
                        ]
                    );
                    // EmployeeLeave::updateOrCreate(
                    //     $empLeaveData,
                    //     $empLeaveData + [
                    //         'leave_remaining' => $leaveType['fixed_remaining_leave'],
                    //         'is_valid' => 11
                    //     ]
                    // );

                    // EmployeeLeaveOpening::updateOrCreate(
                    //     $empLeaveData,
                    //     $empLeaveData + [
                    //         'opening_leave' => $leaveType['fixed_remaining_leave'],
                    //         'organization_id' => $leaveType['organization_id']
                    //     ]
                    // );
                } else {

                    // if ($leaveType->is_update) {sumit
                    //     if ($leaveType->prorata_status != $leaveType->request_prorata) {
                    //         continue;
                    //     }
                    // }
                    $dataParams = [
                        'employee_join_date' => $employee->join_date,
                        'employee_nepali_join_date' => $employee->nepali_join_date,
                        'employee_id' => $employee->id,
                        'leaveTypeId' => $leaveType->id,
                        'leaveTypeData' => $leaveType,
                        'leaveYearId' => $currentLeaveYearData['id'],
                        'leaveYearStartDate' => $currentLeaveYearData->start_date_english,
                        'nepaliLeaveYearStartDate' => $currentLeaveYearData['start_date'],

                        'job_type' => optional($employee->payrollRelatedDetailModel)->job_type,
                        'job_type_changed_date_nep' => optional($employee->payrollRelatedDetailModel)->job_type_changed_date_nep,
                        'prorata_status' => $this->requestData['prorata_status'] ?? $leaveType->request_prorata,
                        'currentLeaveYear' => $currentLeaveYearData['leave_year'],
                        'advance_aloocation' => $leaveType

                    ];
                    $this->updateEmployeeData($dataParams, $lastLeaveYearId, $lastLeaveTypeId, $closingYearKey);
                }
            }
        }
        // commented this code as it was throwing error. purpose not clear

    }

    // public function UpdateLeaveTypeDetails($leaveTypeId, $leaveTypeData)
    // {
    //     $leaveType = ($this->leaveType->findOne($leaveTypeId));
    //     $departments = $leaveType->departments->pluck('department_id', 'department_id')->toArray();
    //     $params['department_ids'] = $departments;
    //     $levels = $leaveType->levels->pluck('level_id', 'level_id')->toArray();;
    //     $params['level_ids'] = $levels;
    //     $params['gender'] = $leaveTypeData['gender'];
    //     $params['marital_status'] = $leaveTypeData['marital_status'];

    //     if (in_array($leaveTypeData['contract_type'], [2, 3])) {
    //         $params['contract_type'] = $leaveTypeData['contract_type'];
    //     }

    //     if ($leaveTypeData['job_type'] == 2) {
    //         $params['probation_status'] = 11;
    //     } else {
    //         $params['probation_status'] = 10;
    //     }


    //     $employees = $this->employee->getEmployeeByOrganization($leaveTypeData['organization_id'], $params);
    //     $currentLeaveYearData = LeaveYearSetup::currentLeaveYear();
    //     if (!is_null($currentLeaveYearData)) {
    //         $leaveYearId = $currentLeaveYearData['id'];
    //         $leaveYearStartDate = $currentLeaveYearData['start_date'];
    //         foreach ($employees as $employee) {
    //             $dataParams = [
    //                 'employee_join_date' => $employee->join_date,
    //                 'employee_nepali_join_date' => $employee->nepali_join_date,
    //                 'employee_id' => $employee->id,
    //                 'leaveTypeId' => $leaveTypeId,
    //                 'leaveTypeData' => $leaveTypeData,
    //                 'leaveYearId' => $leaveYearId,
    //                 'leaveYearStartDate' => $currentLeaveYearData->start_date_english,
    //                 'nepaliLeaveYearStartDate' => $leaveYearStartDate
    //             ];
    //             $this->UpdateEmployeeData($dataParams);
    //         }
    //     }
    // }

    //Function Not used
    public function UpdateLeaveProrataTypeDetails($leaveTypeId, $leaveTypeData)
    {
        $leaveType = ($this->leaveType->findOne($leaveTypeId));
        $departments = $leaveType->departments->pluck('department_id', 'department_id')->toArray();
        $params['department_ids'] = $departments;
        $levels = $leaveType->levels->pluck('level_id', 'level_id')->toArray();;
        $params['level_ids'] = $levels;
        $params['gender'] = $leaveTypeData['gender'];
        $params['marital_status'] = $leaveTypeData['marital_status'];
        // $params['probation_status'] = $leaveTypeData['job_type'];

        $jobTypes = $leaveType->jobTypes->pluck('job_type_id', 'job_type_id')->toArray();;
        $params['job_type_ids'] = $jobTypes;


        $employees = $this->employee->getEmployeeByOrganization($leaveTypeData['organization_id'], $params);
        $currentLeaveYearData = LeaveYearSetup::currentLeaveYear();
        $allLeaveYear = $this->leaveYearSetup->getLeaveYearList();
        $currentLeaveYear = $allLeaveYear[$currentLeaveYearData['id']];
        $closingYearKey = '';
        foreach ($allLeaveYear as $key => $year) {
            if ($currentLeaveYear - 1 == $year) {
                $closingYearKey = $key;
                break;
            }
        }
        if (!is_null($currentLeaveYearData)) {
            $leaveYearId = $currentLeaveYearData['id'];
            $leaveYearStartDate = $currentLeaveYearData['start_date'];
            foreach ($employees as $employee) {
                $dataParams = [
                    'employee_join_date' => $employee->join_date,
                    'employee_nepali_join_date' => $employee->nepali_join_date,
                    'employee_id' => $employee->id,
                    'leaveTypeId' => $leaveTypeId,
                    'leaveTypeData' => $leaveTypeData,
                    'leaveYearId' => $leaveYearId,
                    'leaveYearStartDate' => $currentLeaveYearData->start_date_english,
                    'nepaliLeaveYearStartDate' => $leaveYearStartDate,
                    'prorata_status' => $leaveType->request_prorata
                ];
                $this->updateEmployeeData($dataParams, $lastLeaveYearId = null, $lastLeaveTypeId = null, $closingYearKey);
            }
        }
    }
}
