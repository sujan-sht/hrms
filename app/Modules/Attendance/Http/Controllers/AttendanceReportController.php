<?php

namespace App\Modules\Attendance\Http\Controllers;

use DateTime;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Helpers\DateTimeHelper;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Modules\Leave\Entities\Leave;
use App\Modules\Shift\Entities\Shift;
use Illuminate\Support\Facades\Config;
use App\Modules\Leave\Entities\LeaveType;
use App\Modules\Payroll\Entities\Payroll;
use App\Modules\Shift\Entities\ShiftGroup;
use App\Modules\Employee\Entities\Employee;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Attendance\Entities\Attendance;
use App\Modules\Holiday\Entities\HolidayDetail;
use App\Modules\Shift\Entities\ShiftGroupMember;
use App\Modules\Attendance\Entities\AttendanceLog;
use App\Modules\NewShift\Entities\NewShiftEmployee;
use App\Modules\Shift\Repositories\ShiftRepository;
use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Leave\Repositories\LeaveTypeInterface;
use App\Modules\Employee\Entities\EmployeeApprovalFlow;
use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Shift\Repositories\ShiftGroupRepository;
use App\Modules\Setting\Repositories\DepartmentInterface;
use App\Modules\Attendance\Repositories\AttendanceInterface;
use App\Modules\Attendance\Entities\AttendanceOrganizationLock;
use App\Modules\Attendance\Repositories\AttendanceLogInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Attendance\Entities\AttendanceSummaryVerification;
use App\Modules\Attendance\Repositories\AttendanceReportInterface;
use App\Modules\Attendance\Repositories\AttendanceRequestInterface;

class AttendanceReportController extends Controller
{
    protected $attendance;
    protected $attendanceLog;
    protected $attendanceReport;
    protected $employees;
    protected $organization;
    protected $attendanceRequest;
    protected $branch;
    protected $dropdown;

    protected $attendanceOrgLock;
    protected $attendanceSummaryVerification;
    protected $department;
    protected $employeeObj;
    protected $leaveTypeObj;


    public function __construct(
        AttendanceInterface $attendance,
        AttendanceLogInterface $attendanceLog,
        EmployeeInterface $employees,
        AttendanceReportInterface $attendanceReport,
        OrganizationInterface $organization,
        AttendanceRequestInterface $attendanceRequest,
        BranchInterface $branch,
        EmployeeInterface $employeeObj,
        LeaveTypeInterface $leaveTypeObj,
        DropdownInterface $dropdown,
        AttendanceOrganizationLock $attendanceOrgLock,
        AttendanceSummaryVerification $attendanceSummaryVerification,
        DepartmentInterface $department
    ) {
        $this->attendance = $attendance;
        $this->attendanceLog = $attendanceLog;
        $this->employees = $employees;
        $this->organization = $organization;
        $this->attendanceReport = $attendanceReport;
        $this->attendanceRequest = $attendanceRequest;
        $this->branch = $branch;
        $this->leaveTypeObj = $leaveTypeObj;
        $this->dropdown = $dropdown;
        $this->employeeObj = $employeeObj;
        $this->attendanceOrgLock = $attendanceOrgLock;
        $this->attendanceSummaryVerification = $attendanceSummaryVerification;
        $this->department = $department;
    }

    //Attendance Overview
    public function monthlyAttendance(Request $request)
    {
        $filter = $request->all();
        // dd($filter);
        if (isset($filter['emp_id'])) {
            $filter['emp_id'] = ['empId' => $filter['emp_id']];
        }
        $data['show'] = false;
        $calendarType = $request->calendar_type;
        $dateConverter = new DateConverter();
        // $data['field'] = 'nepali_date';

        if (isset($calendarType)) {
            $year = $calendarType == 'eng' ? $request->eng_year : $request->nep_year;
            $month = $calendarType == 'eng' ? $request->eng_month : $request->nep_month;

            $data['show'] = true;
            $data['field'] = $calendarType == 'eng' ? 'date' : 'nepali_date';
            $data['year'] = $year;
            $data['month'] = $month;
            $data['calendarType'] = $calendarType;
            if ($calendarType == 'nep') {
                $data['days'] = $dateConverter->getTotalDaysInMonth($year, $month);
            } else {
                $data['days'] = Carbon::parse($year . '-' . $month)->daysInMonth;
            }

            //Update no. of days of current month
            if ($data['calendarType'] == 'eng' && $data['year'] == date('Y') && $data['month'] == date('n')) {
                $data['days'] = date('d');
            } elseif ($data['calendarType'] == 'nep') {
                $nepDateArray = date_converter()->eng_to_nep(date('Y'), date('m'), date('d'));
                if ($data['year'] == $nepDateArray['year'] && $data['month'] == $nepDateArray['month']) {
                    $data['days'] = $nepDateArray['date'];
                }
            }
            //

            //Check valid year and month
            $checkDate = [
                'calendarType' => $calendarType,
                'year' => $year,
                'month' => $month,
            ];
            $getDate = $this->restrictFutureDate($checkDate);
            if ($getDate) {
                $data['emps'] = $this->attendanceReport->employeeAttendance($data, $filter, 1, $type = null);
                // dd($data['emps']->toArray());
            } else {
                $data['emps'] = [];
            }
            //
        }
        // dd($data['emps']);
        $list = $this->attendanceReport->getEmployeeOrganizationListBasedOnRole();
        $data['employeeData'] = $list['employeeData'];
        $data['employees'] = $list['employees'];
        $data['organizationList'] = $list['organizationList'];
        $data['branchList'] = $this->branch->getList();

        $data['eng_years'] = $dateConverter->getEngYears();
        $data['nep_years'] = $dateConverter->getNepYears();
        $data['eng_months'] = $dateConverter->getEngMonths();
        $data['nep_months'] = $dateConverter->getNepMonths();
        $data['type'] = $this->attendanceRequest->getTypes();
        $data['shiftLists'] = Shift::get()->mapWithKeys(function ($shift) {
            return [$shift->id => $shift->title];
        });
        // $data['shiftLists'] = ShiftGroup::pluck('group_name', 'id');

        // dd($data);
        // dd($data['type']);

        return view('attendance::monthly-attendance.index', $data);
    }

    public function DateRangeAttendance(Request $request)
    {
        $calendarType = $request['calendar_type'] ?? 'eng';
        $data = [
            'calendarType' => $calendarType,
            'field' => $calendarType === 'nep' ? 'nepali_date' : 'date',
        ];

        if (!empty($request['year']) && !empty($request['month']) && !empty($request['days'])) {
            $data['year'] = $request['year'];
            $data['month'] = $request['month'];
            $data['days'] = $request['days'];
        }
        if (!empty($request['from_date']) && !empty($request['to_date'])) {
            $data['from_date'] = $request['from_date'];
            $data['to_date'] = $request['to_date'];
        }
        $filter = $request;
        // dd($filter);
        if ($filter['from_date'] && $filter['to_date']) {
            $data['show'] = true;
        }
        $emps =  $this->attendanceReport->dateRangeAttendanceData($data, $filter, 1, $type = null);
        $data['emps'] = $emps;
        // dd($data['emps']);
        $list = $this->attendanceReport->getEmployeeOrganizationListBasedOnRole();
        $data['employeeData'] = $list['employeeData'];
        $data['employees'] = $list['employees'];
        $data['organizationList'] = $list['organizationList'];
        $data['branchList'] = $this->branch->getList();
        $data['type'] = $this->attendanceRequest->getTypes();
        $data['shiftLists'] = Shift::get()->mapWithKeys(function ($shift) {
            return [$shift->id => $shift->title];
        });
        // dd($data['emps']);
        $startDate = Carbon::parse($filter['from_date']);
        $endDate = Carbon::parse($filter['to_date']);
        $rangeDates = new \DatePeriod($startDate, new \DateInterval('P1D'), (clone $endDate)->addDay());
        $data['days'] = iterator_count($rangeDates);
        $data['date_range'] = $rangeDates;

        return view('attendance::monthly-attendance.date-range-report', $data);
    }





    // raw attendance report
    public function rawAttendance(Request $request)
    {
        $filter = $request->all();
        if (isset($filter['emp_id'])) {
            $filter['emp_id'] = ['empId' => $filter['emp_id']];
        }
        $list = $this->attendanceReport->getEmployeeOrganizationListBasedOnRole();
        $data['employeeData'] = $list['employeeData'];
        $data['employees'] = $list['employees'];
        $data['organizationList'] = $list['organizationList'];
        $data['branchList'] = $this->branch->getList();
        $dateConverter = new DateConverter();
        $data['eng_years'] = $dateConverter->getEngYears();
        $data['nep_years'] = $dateConverter->getNepYears();
        $data['eng_months'] = $dateConverter->getEngMonths();
        $data['nep_months'] = $dateConverter->getNepMonths();
        $data['type'] = $this->attendanceRequest->getTypes();
        $data['attendances'] = $this->attendance->findAll(20, $filter);
        return view('attendance::monthly-attendance.raw', $data);
    }

    //Attendance Summary
    public function monthlyAttendanceSummary(Request $request)
    {
        $filter = $request->all();
        if (Auth::user()->user_type == 'super_admin' || Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'hr') {
            if (!empty($filter)) {
                $filter['org_id'] = $filter['organization_id'] ?? $filter['org_id'];
            }
        } else {
            $filter['org_id'] = auth()->user()->organization_id;
        }
        $currentEngDate = date('Y-m-d');
        $currentNepDateArray = date_converter()->eng_to_nep(date('Y'), date('m'), date('d'));
        $data['show'] = false;
        $calendarType = $request->calendar_type;
        $dateConverter = new DateConverter();
        // $data['field'] = 'nepali_date';

        $data['columns'] = [
            'total_days' => "Total Days",
            'working_days' => 'Total Working Days',
            'dayoffs' => 'Week Off',
            'public_holiday' => 'Public Holidays',
            'working_hour' => 'Total Working Hours',
            'worked_days' => 'Total Worked Days',
            'worked_hour' => 'Total Worked Hours',
            'unworked_hour' => 'Total Unworked Hours',
            'leave_taken' => 'Total Leave Taken',
            'paid_leave_taken' => 'Total Paid Leave Taken',
            'unpaid_leave_taken' => 'Total Unpaid Leave Taken',
            'absent_days' => 'Absent Days',
            'over_stay' => 'System OverTime(hr)',
            'ot_value' => 'Actual OverTime(hr)'
        ];

        if (isset($calendarType)) {
            $year = $calendarType == 'eng' ? $request->eng_year : $request->nep_year;
            $month = $calendarType == 'eng' ? $request->eng_month : $request->nep_month;

            $data['show'] = true;
            $data['field'] = $calendarType == 'eng' ? 'date' : 'nepali_date';
            if ($calendarType == 'nep') {
                $data['days'] = $dateConverter->getTotalDaysInMonth($year, $month);
                if ($filter['nep_year'] == $currentNepDateArray['year'] && $filter['nep_month'] == $currentNepDateArray['month']) {
                    $data['currentDay'] = $currentNepDateArray['date'];
                } else {
                    $data['currentDay'] = $data['days'];
                }
            } else {
                $data['days'] = Carbon::parse($year . '-' . $month)->daysInMonth;
                if ($filter['eng_year'] == date('Y') && $filter['eng_month'] == (int) date('m')) {
                    $data['currentDay'] = (int)date('d');
                } else {
                    $data['currentDay'] = $data['days'];
                }
            }
            $data['year'] = $year;
            $data['month'] = $month;

            //Check valid year and month
            $checkDate = [
                'calendarType' => $calendarType,
                'year' => $year,
                'month' => $month,
            ];
            $getDate = $this->restrictFutureDate($checkDate);
            if ($getDate) {
                $data['emps'] = $this->attendanceReport->monthlyAttendanceSummary($data, $filter, 10);
            } else {
                $data['emps'] = [];
            }
            //
        }

        $list = $this->attendanceReport->getEmployeeOrganizationListBasedOnRole();
        $data['employeeData'] = $list['employeeData'];
        $data['employees'] = $list['employees'];
        $data['organizationList'] = $list['organizationList'];
        $data['branchList'] = $this->branch->getList();

        $data['eng_years'] = $dateConverter->getEngYears();
        $data['nep_years'] = $dateConverter->getNepYears();
        $data['eng_months'] = $dateConverter->getEngMonths();
        $data['nep_months'] = $dateConverter->getNepMonths();
        return view('attendance::monthly-attendance-summary.index', $data);
    }

    public function monthlyAttendanceSummaryVerification(Request $request)
    {
        $data['columns'] = [
            'total_days' => "Total Days",
            'working_days' => 'Total Working Days',
            'dayoffs' => 'Week Off',
            'public_holiday' => 'Public Holidays',
            'working_hour' => 'Total Working Hours',
            'worked_days' => 'Total Worked Days',
            'worked_hour' => 'Total Worked Hours',
            'unworked_hour' => 'Total Unworked Hours',
            'leave_taken' => 'Total Leave Taken',
            'paid_leave_taken' => 'Total Paid Leave Taken',
            'unpaid_leave_taken' => 'Total Unpaid Leave Taken',
            'absent_days' => 'Absent Days',
            'over_stay' => 'Over Stay(hr)',
            'ot_value' => 'Ot(hr)'
        ];
        $alreadyLockedStatus = [
            'status' => true,
            'data' => null
        ];
        $alreadyLockedStatus = $this->attendanceReport->checkLockedStatus($request);
        if ($alreadyLockedStatus['status']) {
            $calendarType = $request->calendar_type;
            $list = $this->attendanceReport->getEmployeeOrganizationListBasedOnRole();
            $data['show'] = true;
            $data['organizationList'] = $list['organizationList'];
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
            $data['calendarTypeValue'] = $calendarType;
            $data['fetchDatas'] = $alreadyLockedStatus['data']->getAttendanceSummaryVerification->map(function ($item) use (&$data) {
                $item->getLockAttribute->groupBy('type')->map(function ($value, $index) use (&$data, $item) {
                    if ($index == '1') {
                        $data['columns'][(string)$index] = 'Leave Request';
                        $item->setAttribute('1', $value->sum('item_value'));
                    } elseif ($index == '2') {
                        $data['columns'][(string)$index] = 'Attendance Request';
                        $item->setAttribute('2', $value->sum('item_value'));
                    }
                });
                return $item;
            });
            $payrollStatus = false;
            $data['payrollStatus'] = $this->checkPayrollStatus($alreadyLockedStatus['data']);
            $data['attendanceOrganizationLock'] = $alreadyLockedStatus['data']->id;
            return view('attendance::attendance-verification.attendancelock', $data);
        }
        $filter = $request->all();
        if (Auth::user()->user_type == 'super_admin' || Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'hr') {
            if (!empty($filter)) {
                $filter['org_id'] = $filter['organization_id'] ?? $filter['org_id'];
            }
        } else {
            $filter['org_id'] = auth()->user()->organization_id;
        }
        $currentEngDate = date('Y-m-d');
        $currentNepDateArray = date_converter()->eng_to_nep(date('Y'), date('m'), date('d'));
        $data['show'] = false;
        $calendarType = $request->calendar_type;
        $dateConverter = new DateConverter();
        // $data['field'] = 'nepali_date';

        $fiterData = [];
        if (isset($calendarType)) {
            $year = $calendarType == 'eng' ? $request->eng_year : $request->nep_year;
            $month = $calendarType == 'eng' ? $request->eng_month : $request->nep_month;

            $data['show'] = true;
            $data['field'] = $calendarType == 'eng' ? 'date' : 'nepali_date';
            if ($calendarType == 'nep') {
                $data['days'] = $dateConverter->getTotalDaysInMonth($year, $month);
                if ($filter['nep_year'] == $currentNepDateArray['year'] && $filter['nep_month'] == $currentNepDateArray['month']) {
                    $data['currentDay'] = $currentNepDateArray['date'];
                } else {
                    $data['currentDay'] = $data['days'];
                }
            } else {
                $data['days'] = Carbon::parse($year . '-' . $month)->daysInMonth;
                if ($filter['eng_year'] == date('Y') && $filter['eng_month'] == (int) date('m')) {
                    $data['currentDay'] = (int)date('d');
                } else {
                    $data['currentDay'] = $data['days'];
                }
            }
            $data['year'] = $year;
            $data['month'] = $month;

            //Check valid year and month
            $checkDate = [
                'calendarType' => $calendarType,
                'year' => $year,
                'month' => $month,
            ];
            $getDate = $this->restrictFutureDate($checkDate);
            if ($getDate) {
                $data['emps'] = $this->attendanceReport->attendanceVerificationSummary($data, $filter, 10);
            } else {
                $data['emps'] = [];
            }
            //
        }

        $list = $this->attendanceReport->getEmployeeOrganizationListBasedOnRole();

        $data['organizationList'] = $list['organizationList'];
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
        $data['calendarTypeValue'] = $calendarType;
        $fiterData = [
            "show" => $data['show'],
            "field" => $data["field"],
            "days" => $data['days'],
            "currentDay" => $data['currentDay'],
            "year" => $data['year'],
            "month" => $data['month']
        ];
        $data['arrangeData'] = base64_encode(json_encode([
            'request_data' => $request->all(),
            'formData' => $fiterData,
            'filterData' => $filter
        ]));
        return view('attendance::attendance-verification.attendanceverification', $data);
    }

    public function unlockedAttendance(Request $request)
    {
        DB::beginTransaction();
        try {
            $attendanceOrganizationLock = AttendanceOrganizationLock::where('id', $request->attendanceOrganizationLock)->first();
            if (!$attendanceOrganizationLock) {
                throw new Exception();
            }
            $attendanceOrganizationLock->getAttendanceSummaryVerification()->delete();
            $attendanceOrganizationLock->getLockAttribute()->delete();
            $attendanceOrganizationLock->delete();
            DB::commit();
            toastr()->success('Attendance Summary Unlocked Sucessfully !!');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            toastr()->error('Something Went Wrong !!');
            return redirect()->back();
        }
    }
    public function checkPayrollStatus($data)
    {
        $payrollData = Payroll::where([
            ['organization_id', $data->organization_id],
            ['calendar_type', $data->calender_type],
            ['year', $data->year],
            ['month', $data->month]
        ])->first();
        if ($payrollData && isset($payrollData->payrollEmployees)) {
            $status = optional(optional($payrollData->payrollEmployees)->first())->status;
        } else {
            $status = 1;
        }

        if ($payrollData && $status == 2) {
            return true;
        }

        return false;
    }

    public function monthlyAttendanceSummaryVerificationDraft(Request $request)
    {
        DB::beginTransaction();
        try {
            $requestData = $request->request_data;
            if (!$requestData || $requestData == null) {
                throw new Exception();
            }
            $status = $request->status;
            if (!$status || $status == null) {
                throw new Exception();
            }
            $requestData = json_decode(base64_decode($requestData));
            $requestData->emp = $this->attendanceReport->getEmployeeData($requestData->formData, $requestData->filterData, 10);
            if ($status != '1') {
                $orgData = $this->attendanceReport->setLockData($requestData, $status);
                if (!$orgData) {
                    throw new Exception();
                }
                $this->attendanceOrgLock = $this->attendanceOrgLock->create($orgData);
                if (!$this->attendanceOrgLock) {
                    throw new Exception();
                }
                $empData = $this->attendanceReport->setEmpData($this->attendanceOrgLock, $requestData->emp);
                if (!$empData) {
                    throw new Exception();
                }
                $this->attendanceSummaryVerification = $this->attendanceSummaryVerification->insert($empData);
                if (!$this->attendanceSummaryVerification) {
                    throw new Exception();
                }
            }

            $message = $status == '1' ? "Draft" : "Locked";
            DB::commit();
            toastr()->success('Attendance ' . $message . ' Successfully !!');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            toastr()->error('Somethimg Went Wrong !!');
            return redirect()->back();
        }
    }


    //

    //Monthly Attendance Report
    public function dailyAttendance(Request $request)
    {
        $filter = $request->all();
        if (isset($filter['emp_id'])) {
            $filter['emp_id'] = ['empId' => $filter['emp_id']];
        }
        $data['show'] = false;
        $calendar_type = $request->calendar_type;
        $dateConverter = new DateConverter();
        if (isset($calendar_type)) {
            $year = $calendar_type == 'eng' ? $request->eng_year : $request->nep_year;
            $month = $calendar_type == 'eng' ? $request->eng_month : $request->nep_month;
            $data['show'] = true;
            $data['field'] = $calendar_type == 'eng' ? 'date' : 'nepali_date';
            $data['year'] = $year;
            $data['month'] = $month;
            $data['calendarType'] = $calendar_type;

            if ($calendar_type == 'nep') {
                $data['days'] = $dateConverter->getTotalDaysInMonth($year, $month);
            } else {
                $data['days'] = Carbon::parse($year . '-' . $month)->daysInMonth;
            }

            if ($data['calendarType'] == 'eng' && $data['year'] == date('Y') && $data['month'] == date('n')) {
                $data['days'] = date('d');
            } elseif ($data['calendarType'] == 'nep') {
                $nepDateArray = date_converter()->eng_to_nep(date('Y'), date('m'), date('d'));
                if ($data['year'] == $nepDateArray['year'] && $data['month'] == $nepDateArray['month']) {
                    $data['days'] = $nepDateArray['date'];
                }
            }
            //

            $checkDate = [
                'calendarType' => $calendar_type,
                'year' => $year,
                'month' => $month,
            ];
            $getDate = $this->restrictFutureDate($checkDate);
            if ($getDate) {
                $data['emps'] = $this->attendanceReport->employeeAttendance($data, $filter, 10, $type = null);
            } else {
                $data['emps'] = [];
            }
        }

        if ($data['emps']->total() > 0) {
            session(['total_monthly_attendance_report' => $data['emps']->total()]);
        }
        if (auth()->user()->user_type == 'super_admin' || auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'hr') {
            $data['employees'] = $this->employees->getList();
            $data['organizationList'] = $this->organization->getList();
            $data['departmentList'] = $this->department->getList();
            $data['branchList'] = $this->branch->getList();
        }
        $data['eng_years'] = $dateConverter->getEngYears();
        $data['nep_years'] = $dateConverter->getNepYears();
        $data['eng_months'] = $dateConverter->getEngMonths();
        $data['nep_months'] = $dateConverter->getNepMonths();
        return view('attendance::daily-attendance.index', $data);
    }

    public function calendarMonthlyAttendance(Request $request)
    {
        $filter = $request->all();
        $data['organizationList'] = $this->organization->getList();
        $data['employeeList'] = $this->employeeObj->getList();

        if (isset($filter['emp_id'])) {
            $filter['emp_id'] = ['empId' => $filter['emp_id']];
        }
        $data['show'] = false;
        $calendar_type = $request->calendar_type;
        $dateConverter = new DateConverter();
        if (isset($calendar_type)) {
            $year = $calendar_type == 'eng' ? $request->eng_year : $request->nep_year;
            $month = $calendar_type == 'eng' ? $request->eng_month : $request->nep_month;
            $data['show'] = true;
            $data['field'] = $calendar_type == 'eng' ? 'date' : 'nepali_date';
            $data['year'] = $year;
            $data['month'] = $month;
            $data['calendarType'] = $calendar_type;

            if ($calendar_type == 'nep') {
                $data['days'] = $dateConverter->getTotalDaysInMonth($year, $month);
            } else {
                $data['days'] = Carbon::parse($year . '-' . $month)->daysInMonth;
            }

            if ($data['calendarType'] == 'eng' && $data['year'] == date('Y') && $data['month'] == date('n')) {
                $data['days'] = date('d');
            } elseif ($data['calendarType'] == 'nep') {
                $nepDateArray = date_converter()->eng_to_nep(date('Y'), date('m'), date('d'));
                if ($data['year'] == $nepDateArray['year'] && $data['month'] == $nepDateArray['month']) {
                    $data['days'] = $nepDateArray['date'];
                }
            }
            //

            $checkDate = [
                'calendarType' => $calendar_type,
                'year' => $year,
                'month' => $month,
            ];
            $getDate = $this->restrictFutureDate($checkDate);
            if ($getDate) {
                $data['emps'] = $this->attendanceReport->employeeAttendance($data, $filter, 10, $type = null);
            } else {
                $data['emps'] = [];
            }
        }
        return view('attendance::daily-attendance.calendar', $data);
    }




    //Daily attendance report
    public function regularAttendanceReport(Request $request)
    {
        $data['filter'] = $filter = $request->all();
        $sort = [
            'by' => 'id',
            'sort' => 'DESC'
        ];
        $data['emps'] = $this->attendanceReport->employeeRegularAttendanceData(20, $filter, $sort);
        $data['mediumList'] = ['biometric' => 'Biometric', 'web' => 'Web', 'app' => 'App'];
        $data['typeList'] = ['checkin' => 'Checkin', 'checkout' => 'Checkout'];

        if (auth()->user()->user_type == 'super_admin' || auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'hr') {
            $data['employees'] = $this->employees->getList();
            $data['organizationList'] = $this->organization->getList();
            $data['branchList'] = $this->branch->getList();
            $data['departmentList'] = $this->department->getList();
            // $data['jobTypeList'] = LeaveType::JOB_TYPE;

        }
        // dd($data);
        return view('attendance::regular-attendance.index', $data);
    }



    public function monthlyAttendanceRange(Request $request)
    {
        set_time_limit(-1);

        $filter = $request->all();
        // dd($filter);
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            $filter['org_id'] = optional($authUser->userEmployer)->organization_id;
        }
        $select = ['id', 'organization_id', 'branch_id', 'employee_id', 'employee_code', 'first_name', 'middle_name', 'last_name', 'dayoff', 'profile_pic', 'gender'];
        $query = Employee::query();
        $query->select($select);
        $query->where('status', 1);
        if (isset($filter['org_id']) && $filter['org_id'] != '') {
            $query = $query->where('organization_id', $filter['org_id']);
        }

        if (isset($filter['branch_id']) && $filter['branch_id'] != '') {
            $query = $query->where('branch_id', $filter['branch_id']);
        }

        if (isset($filter['department_id']) && $filter['department_id'] != '') {
            $query = $query->where('department_id', $filter['department_id']);
        }

        if (isset($filter['emp_id']) && $filter['emp_id'] != '') {
            $query = $query->whereIn('id', $filter['emp_id']);
        }
        $employees = $query->paginate(20);

        $data['emps'] = [];
        $data['show'] = false;

        if (isset($filter['calendar_type'])) {
            if ($filter['calendar_type'] == 'eng') {
                $filter['field'] = 'date';

                $from_date = $filter['from_eng_date'];
                $to_date = $filter['to_eng_date'];
                $date_range = Carbon::parse($from_date)->daysUntil($to_date);
                foreach ($date_range as $date) {
                    $data['dateRanges'][] = $date->format('Y-m-d');
                }
            } else {
                $filter['field'] = 'date';
                $from_date = date_converter()->nep_to_eng_convert($filter['from_nep_date']);
                $to_date = date_converter()->nep_to_eng_convert($filter['to_nep_date']);
                $date_range = Carbon::parse($from_date)->daysUntil($to_date);
                foreach ($date_range as $date) {
                    // $data['dateRanges'][] = date_converter()->eng_to_nep_convert($date->format('Y-m-d'));
                    $data['dateRanges'][] = $date->format('Y-m-d');
                }
            }
        }
        if (isset($from_date) && isset($to_date)) {
            $holidayDetail = HolidayDetail::select('*')
                ->with('holiday')
                ->whereIn('eng_date', $data['dateRanges'])
                ->whereHas('holiday', function ($query) use ($filter) {
                    $query->where('status', 11);
                    $query->where('organization_id', $filter['org_id']);
                    $query->orWhereNull('organization_id');
                })
                ->get()->map(function ($holidayDetail) {
                    $holidayDetail->organization_id = $holidayDetail->holiday->organization_id;
                    $holidayDetail->branch_id = $holidayDetail->holiday->branch_id;
                    $holidayDetail->gender_type = $holidayDetail->holiday->gender_type;
                    $holidayDetail->religion_type = $holidayDetail->holiday->religion_type;

                    unset($holidayDetail->holiday);
                    return $holidayDetail;
                });
            $data['show'] = true;
            if ($data['emps']) {

                $data['emps'] = $employees->setCollection($employees->getCollection()->transform(function ($emp) use ($filter, $data, $holidayDetail) {
                    $dates = collect($data['dateRanges'])->mapWithKeys(function ($key, $value) {
                        return [$key  => ['status' => 'A']];
                    });
                    dd($data['emps']);

                    $dayOffDates = collect();
                    $dayOffs = $emp->employeeDayOff->pluck('day_off')->toArray();
                    foreach ($data['dateRanges'] as $key => $value) {
                        if (in_array(date('l', strtotime($value)), $dayOffs)) {
                            $dayOffDates[$value] = ['status' => 'D'];
                        }
                    }

                    // $leaveDates = Leave::select('leave_kind', 'date')->where('employee_id', $emp->id)->whereIn($filter['field'], $data['dateRanges'])->get()->mapWithKeys(function ($leave) {
                    //     return [$leave->date  => ['status' => ($leave->leave_kind == 1) ? 'HL' : (($leave->status == '1') ? 'LP' : (($leave->status == '2') ? 'LF' : 'L'))]];
                    // });
                    $leaveDates = Leave::select('leave_kind', 'status', 'date')
                        ->where('employee_id', $emp->id)
                        ->whereIn($filter['field'], $data['dateRanges'])
                        ->get()
                        ->map(function ($leave) {
                            // Determine the leave status based on leave_kind and status
                            $status = 'L';  // Default value
                            if ($leave->leave_kind == 1) {
                                $status = 'HL';  // Holiday Leave
                            } elseif ($leave->status == '1') {
                                $status = 'LP';  // Leave Pending
                            } elseif ($leave->status == '2') {
                                $status = 'LF';  // Leave Approved (or whatever status '2' signifies)
                            }
                            return [$leave->date => ['status' => $status]];
                        })
                        ->flatten(1)
                        ->toArray();  // Convert the result back to an array for better usability


                    $approvedBy = $emp->attendanceRequest->first()->approved_by ?? false;
                    $leaveApprovedBy = $emp->leave->first()->accept_by ?? false;
                    $atdDates  = Attendance::select('attendances.date', 'attendances.checkin', 'attendances.checkout', 'attendance_requests.status as req_status')
                        ->leftJoin('attendance_requests', function ($join) use ($emp) {
                            $join->on('attendances.date', '=', 'attendance_requests.date')
                                ->where('attendance_requests.employee_id', '=', $emp->id)
                                ->where('attendance_requests.type', '=', 6)
                                ->where('attendance_requests.status', '=', 3);
                        })
                        ->where('attendances.emp_id', $emp->id)
                        ->whereIn('attendances.' . $filter['field'], $data['dateRanges'])
                        ->get()
                        ->mapWithKeys(function ($atd) use ($leaveDates, $approvedBy, $leaveApprovedBy) {
                            // $leaveDatesArr = $leaveDates->toArray();
                            $leaveDatesArr = $leaveDates;
                            $status = isset($atd->req_status)
                                ? 'OD'
                                : ((is_null($atd->checkin) || is_null($atd->checkout))
                                    ? ($approvedBy ? 'P' : 'P*')
                                    : 'P');

                            $leave_status = in_array($atd->date, array_keys($leaveDatesArr)) && $leaveApprovedBy ? 'HL' : '';

                            return [
                                $atd->date => [
                                    'status' => $status,
                                    'leave_status' => $leave_status,
                                    'checkin' => $atd->checkin,
                                    'checkout' => $atd->checkout,
                                ],
                            ];
                        });
                    $gender = optional($emp->getGender)->dropvalue == 'Female' ? 2 : 3;
                    $holiday = $holidayDetail
                        ->whereIn('organization_id', [$emp->organization_id, null])
                        ->whereIn('gender_type', [1, $gender])
                        ->whereIn('branch_id', [$emp->branch_id, null])
                        ->mapWithKeys(function ($holiday) use ($emp) {
                            if ($holiday->holiday->apply_for_all == 10) {
                                return [$holiday->eng_date  => ['status' => 'H']];
                            }
                        });
                    $days = $dates->merge($dayOffDates)->merge($holiday)->merge($leaveDates)->merge($atdDates)->sortKeys();
                    $days = $days->filter(function ($item, $key) {
                        return !($key === 0 && isset($item['status']) && $item['status'] === 'LP');
                    });

                    $emp->date = $days;
                    return $emp;
                }));
            }
        }

        $list = $this->attendanceReport->getEmployeeOrganizationListBasedOnRole();
        $data['employeeData'] = $list['employeeData'];
        $data['employees'] = $list['employees'];
        $data['organizationList'] = $list['organizationList'];
        $data['branchList'] = $this->branch->getList();
        $data['departmentList'] = $this->dropdown->getFieldBySlug('department');
        return view('attendance::monthly-attendance.date-range-report', $data);
    }


    //

    //Test Function
    // public function viewAtdLog(Request $request)
    // {
    //     $data['filter'] = $filter = $request->all();
    //     $sort = [
    //         'by' => 'id',
    //         'sort' => 'DESC'
    //     ];

    //     $data['logs']=AttendanceLog::when(true,function($query){
    //         if (isset($filter['org_id']) && $filter['org_id'] != '') {
    //             $query = $query->where('organization_id', $filter['org_id']);
    //         }

    //         if (isset($filter['date']) && $filter['date'] != '') {
    //             $query = $query->where('date', $filter['date']);
    //         }

    //         if (isset($filter['emp_id']) && $filter['emp_id'] != '') {
    //             $query = $query->where('id', $filter['emp_id']);
    //         }
    //     })->get();
    //     dd($data['logs']->toArray());
    // }
    //

    public function restrictFutureDate($checkDate)
    {
        if ($checkDate['calendarType'] == 'nep') {
            $nepDateArray = date_converter()->eng_to_nep(date('Y'), date('m'), date('d'));
            if ($checkDate['year'] > $nepDateArray['year']) {
                toastr()->error('Invalid Year');
                return false;
            } elseif ($checkDate['year'] == $nepDateArray['year'] && $checkDate['month'] > $nepDateArray['month']) {
                toastr()->error('Invalid Month');
                return false;
            }
        } elseif ($checkDate['calendarType'] == 'eng') {
            if ($checkDate['year'] > date('Y')) {
                toastr()->error('Invalid Year');
                return false;
            } elseif ($checkDate['year'] == date('Y') && $checkDate['month'] > date('n')) {
                toastr()->error('Invalid Month');
                return false;
            }
        }
        return true;
    }

    public function viewAttendanceCalendar(Request $request)
    {
        $filter = $request->all();
        $data['organizationList'] = $this->organization->getList();
        $data['branchList'] = $this->branch->getList();
        $data['employeeList'] = $this->employees->getList();
        $data['search_value'] = $filter;
        return view('attendance::calendar.view', $data);
    }
    public function viewMonthlyAttendanceCalendar(Request $request)
    {

        $filter = $request->all();
        $data['organizationList'] = $this->organization->getList();
        $data['employeeList'] = $this->employees->getList();
        $data['search_value'] = $filter;
        return view('attendance::daily-attendance.calendar', $data);
    }

    public function getCalendarAttendanceByAjax(Request $request)
    {
        if ($request->ajax()) {
            $filter['emp_id'] = [auth()->user()->emp_id];
            $avgDate = (strtotime($request->start) + strtotime($request->end)) / 2;

            $data['field'] = 'date';
            $data['year'] = date('Y', $avgDate);
            $data['month'] = date('m', $avgDate);
            $data['days'] = Carbon::parse($data['year'] . '-' . $data['month'])->daysInMonth;

            $emps = $this->attendanceReport->getCalendarAttendanceDetails($data, $filter, 20);

            foreach ($emps as $emp) {
                foreach ($emp->date as $key => $value) {
                    // Convert $key to a DateTime object to compare with today's date
                    $date = new DateTime($key);
                    $today = new DateTime(); // Today's date


                    switch ($value['status']) {
                        case 'HL':
                            $color = '#6A1B9A';
                            break;
                        case 'P':
                            $color = '#43A047';
                            break;
                        case 'P*':
                            $color = '#1E88E5';
                            break;
                        case 'L':
                            $color = '#3949AB';
                            break;
                        case 'H':
                            $color = '#00ACC1';
                            break;
                        case 'D':
                            $color = '#546E7A';
                            break;
                        default:
                            $color = '#E53935';
                            break;
                    }
                    if ($value['status'] === 'A' && $date > $today) {
                        $title = null;
                        $color = '#fff'; // Default color for future dates
                    } else {
                        $title = $value['status'] . "\n" . $value['title'];
                    }

                    // Add entry to $attendanceArray
                    $attendanceArray[] = [
                        'title' => $title,
                        'start' => $key,
                        'color' => $color,
                    ];
                }
            }

            return response()->json($attendanceArray);
        }
    }

    public function getMonthlyCalendarAttendanceByAjax(Request $request)
    {
        // if ($request->ajax()) {
            $filter = $request->all();
            if (auth()->user()->user_type == 'employee') {
                $filter['emp_id'] = auth()->user()->emp_id;
            }
            $attendanceArray = [];
            $avgDate = (strtotime($request->start) + strtotime($request->end)) / 2;
            $data['field'] = 'date';
            $data['year'] = date('Y', $avgDate);
            $data['month'] = date('m', $avgDate);
            $data['days'] = Carbon::parse($data['year'] . '-' . $data['month'])->daysInMonth;
            $emps = $this->attendanceReport->employeeAttendance($data, $filter, 20, null);

            foreach ($emps as $emp) {
                foreach ($emp->date as $key => $value) {
                    $date = new DateTime($key);
                    $today = new DateTime();

                    switch ($value['status']) {
                        case 'HL':
                            $color = '#6A1B9A';
                            break;
                        case 'P':
                            $color = '#43A047';
                            $checkIn = $value['checkin'];
                            $checkOut = $value['checkout'];
                            break;
                        case 'P*':
                            $color = '#1E88E5';
                            break;
                        case 'L':
                            $color = '#3949AB';
                            break;
                        case 'H':
                            $color = '#00ACC1';
                            break;
                        case 'D':
                            $color = '#546E7A';
                            break;
                        default:
                            $color = '#E53935';
                            break;
                    }
                    if ($value['status'] === 'A' && $date > $today) {
                        $title = null;
                        $color = '#fff';
                    } elseif ($value['status'] == 'H') {
                        $title = 'H';
                        $title .= "\n" . $value['holidayName'];
                    } else {
                        $title = $value['status'];
                        if ($value['status'] == 'P') {
                            $title .= "\nCheck-in: " . date('H:i', strtotime($checkIn));
                            $title .= "\nCheck-out: " . date('H:i', strtotime($checkOut));
                        }
                    }

                    // Add entry to $attendanceArray
                    $attendanceArray[] = [
                        'title' => $title,
                        'start' => $key,
                        'color' => $color,
                    ];
                }
            }


            return response()->json($attendanceArray);
        // }
    }

    public function getEmployeesFromOrgBranchDepartmentIdDesignationId(Request $request)
    {
        if ($request->ajax()) {
            $list = [];
            $employees = Employee::whereOrganizationId($request->organization_id)
                ->whereBranchId($request->branch_id)
                ->where('department_id', $request->department_id)
                ->where('designation_id', $request->designation_id)
                ->get();
            if ($employees->isNotEmpty()) {
                foreach ($employees as $model) {
                    $list[$model->id] = $model->full_name . ' :: ' . $model->employee_code;
                }
            }

            return response()->json($list);
        }
    }

    public function appAttendanceReport(Request $request)
    {
        ${'checkinTimeWithGrace' . $request->id} = '';

        $data['filter'] = $filter = $request->all();
        $data['filter']['medium'] = 'app';
        // $filter['authUser'] = auth()->user();

        if (isset($filter['calendar_type'])) {
            if ($filter['calendar_type'] == 'eng') {
                $data['field'] = 'date';
                $data['col_field'] = 'eng_date';
                $from_date = $filter['from_eng_date'];
                $to_date = $filter['to_eng_date'];
                $date_range =  Carbon::parse($from_date)->daysUntil($to_date);
                foreach ($date_range as $date) {
                    $data['dateRanges'][] = $date->format('Y-m-d');
                }
            } else {
                $data['field'] = 'nepali_date';
                $data['col_field'] = 'nep_date';
                $from_date = date_converter()->nep_to_eng_convert($filter['from_nep_date']);
                $to_date = date_converter()->nep_to_eng_convert($filter['to_nep_date']);
                $date_range =  Carbon::parse($from_date)->daysUntil($to_date);
                foreach ($date_range as $date) {
                    $data['dateRanges'][] = $date->format('Y-m-d');
                }
            }
        }
        // if (isset($filter['authUser'])) {
        //     if ($filter['authUser']['user_type'] == 'division_hr') {
        //         $filter['org_id'] = optional($filter['authUser']->userEmployer)->organization_id;
        //     }
        // } else {
        //     $authUser = auth()->user();
        //     if ($authUser->user_type == 'division_hr') {
        //         $filter['org_id'] = optional($authUser->userEmployer)->organization_id;
        //     }
        // }

        if (auth()->user()->user_type == 'supervisor' || auth()->user()->user_type == 'employee') {
            $filter['org_id'] = optional(auth()->user()->userEmployer)->organization_id;
            $filter['emp_id'] = [auth()->user()->emp_id];
        }

        $data['emps'] = [];
        $data['show'] = false;
        if (isset($from_date) && isset($to_date)) {
            $select = ['id', 'organization_id', 'employee_id', 'biometric_id', 'employee_code', 'first_name', 'middle_name', 'last_name', 'dayoff', 'profile_pic', 'gender'];

            $query = Employee::query();

            //only show if visibility for specific user is checked
            $query->whereHas('visibilitySetup', function ($query) {
                $query->where('attendance', 1);
            });

            $query->select($select);
            $query->where('status', 1);
            if (isset($filter['org_id']) && $filter['org_id'] != '') {
                $query = $query->where('organization_id', $filter['org_id']);
            }

            if (isset($filter['branch_id']) && $filter['branch_id'] != '') {
                $query = $query->where('branch_id', $filter['branch_id']);
            }

            if (isset($filter['department_id']) && $filter['department_id'] != '') {
                $query = $query->where('department_id', $filter['department_id']);
            }

            if (isset($filter['emp_id']) && $filter['emp_id'] != '') {
                $query = $query->whereIn('id', $filter['emp_id']);
            }

            $employees = $query->paginate(1);

            $holidayDetail = HolidayDetail::select('*')
                ->with('holiday')
                ->whereIn($data['col_field'], $data['dateRanges'])
                ->whereHas('holiday', function ($query) use ($filter) {
                    $query->where('status', 11);
                    $query->where('organization_id', $filter['org_id']);
                    $query->orWhereNull('organization_id');
                })
                ->get()->map(function ($holidayDetail) {
                    $holidayDetail->organization_id = $holidayDetail->holiday->organization_id;
                    $holidayDetail->gender_type = $holidayDetail->holiday->gender_type;
                    $holidayDetail->religion_type = $holidayDetail->holiday->religion_type;

                    unset($holidayDetail->holiday);
                    return $holidayDetail;
                });
            $data['show'] = true;
            $data['emps'] =  $employees->setCollection($employees->getCollection()->transform(function ($emp) use ($data, $holidayDetail) {

                $dates = collect($data['dateRanges'])->mapWithKeys(function ($key, $value) {
                    return [$key  => ['status' => 'A']];
                });

                $dayOffDates = collect();
                $dayOffs = $emp->employeeDayOff->pluck('day_off')->toArray();

                foreach ($data['dateRanges'] as $key => $value) {
                    if (in_array(date('l', strtotime($value)), $dayOffs)) {
                        $dayOffDates[$value] = ['status' => 'D'];
                    }
                }
                $leaveDates = Leave::select('leave_kind', 'date')->where('employee_id', $emp->id)->whereIn($data['field'], $data['dateRanges'])->whereStatus(3)->get()->mapWithKeys(function ($leave) {
                    return [$leave->date  => [
                        'status' => ($leave->leave_kind == 1) ? 'HL' : 'L',
                        'leave_kind' =>  $leave->leave_kind
                    ]];
                });

                $atdDates  = Attendance::select('date', 'checkin', 'checkout', 'checkin_from', 'checkout_from', 'checkin_coordinates', 'checkout_coordinates')->where('emp_id', $emp->id)->whereIn($data['field'], $data['dateRanges'])->get()
                    ->mapWithKeys(function ($atd) use ($leaveDates, $emp) {
                        $leaveDatesArr = $leaveDates->toArray();
                        $day = date('D', strtotime($atd->date));
                        // $shiftGroup = optional(ShiftGroupMember::where('group_member', $emp->id)->orderBy('id', 'DESC')->first())->group;
                        $actualShiftGroupMember = ShiftGroupMember::where('group_member', $emp->id)->orderBy('id', 'DESC')->first();
                        $empShift = $actualShift = optional(optional(ShiftGroupMember::where('group_member', $emp->id)->orderBy('id', 'DESC')->first())->group)->shift;

                        $newShiftEmp = NewShiftEmployee::getShiftEmployee($emp->id, $atd->date);
                        // if (isset($newShiftEmp)) {
                        //     $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
                        //     if (isset($rosterShift) && isset($rosterShift->shift_group_id)) {
                        //         $shiftGroup = (new ShiftGroupRepository())->find($rosterShift->shift_group_id);
                        //         $empShift = $updatedShift = optional($shiftGroup->shift);
                        //     } else {
                        //         $updatedShift = '';
                        //     }
                        // } else {
                        //     $updatedShift = '';
                        // }
                        // $shiftStartTime = '09:00';
                        // $shiftEndTime = '18:00';
                        // if (isset($empShift)) {
                        //     $shiftSeason = $empShift->getShiftSeasonForDate($atd->date);
                        //     $seasonalShiftId = null;
                        //     if ($shiftSeason) {
                        //         $seasonalShiftId = $shiftSeason->id;
                        //     }
                        //     // $empShift = optional($shiftGroupMember->group)->shift;
                        //     $shiftStartTime = optional($empShift->getShiftDayWise($day, $seasonalShiftId))->start_time;
                        //     $shiftEndTime = optional($empShift->getShiftDayWise($day, $seasonalShiftId))->end_time;
                        // }
                        // $perDayShift = (strtotime($shiftEndTime) - strtotime($shiftStartTime)) / 3600;

                        if (isset($newShiftEmp)) {
                            $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
                            if (isset($rosterShift) && isset($rosterShift->shift_id)) {
                                $empShift = $updatedShift = (new ShiftRepository())->find($rosterShift->shift_id);
                            } else {
                                $updatedShift = '';
                            }
                        } else {
                            $updatedShift = '';
                        }
                        $shiftStartTime = '09:00';
                        $shiftEndTime = '18:00';
                        if (isset($empShift)) {
                            // $empShift = optional($shiftGroupMember->group)->shift;
                            $shiftSeason = $empShift->getShiftSeasonForDate($atd->date);
                            $seasonalShiftId = null;
                            if ($shiftSeason) {
                                $seasonalShiftId = $shiftSeason->id;
                            }
                            if (isset($empShift)) {
                                $shiftStartTime = optional($empShift->getShiftDayWise($day, $seasonalShiftId))->start_time;
                                $shiftEndTime = optional($empShift->getShiftDayWise($day, $seasonalShiftId))->end_time;
                            }
                        }
                        $perDayShift = DateTimeHelper::getTimeDiff($shiftStartTime, $shiftEndTime);

                        $total_working_hr = DateTimeHelper::getTimeDiff($atd->checkin, $atd->checkout);
                        $over_time = ($total_working_hr && (float)$total_working_hr >= (float)$perDayShift) ? (float)$total_working_hr - (float)$perDayShift : 0;

                        $checkinStatus = $lateArrival = $checkoutStatus = $earlyDeparture = '';
                        // if ($actualShiftGroupMember) {
                        if ($actualShiftGroupMember) {
                            $checkinTimeWithGrace = (date('H:i', strtotime(intval('+' . optional($actualShiftGroupMember->group)->ot_grace_period ?? 0) . 'minutes', strtotime($shiftStartTime))));
                            $checkoutTimeWithGrace = (date('H:i', strtotime(intval('-' . optional($actualShiftGroupMember->group)->grace_period_checkout ?? 0) . 'minutes', strtotime($shiftEndTime))));
                        } else {
                            $checkinTimeWithGrace = (date('H:i', strtotime(intval('+' . 0) . 'minutes', strtotime($shiftStartTime))));
                            $checkoutTimeWithGrace = (date('H:i', strtotime(intval('-' . 0) . 'minutes', strtotime($shiftEndTime))));
                        }
                        //Checkin Status
                        if (isset($atd->checkin) && $checkinTimeWithGrace < date('H:i', strtotime($atd->checkin))) {
                            $checkinStatus = 'Late Arrival';
                            $check_in = Carbon::parse($atd->checkin);
                            $checkinGrace = Carbon::parse($checkinTimeWithGrace);
                            $lateArrival = $check_in->diffInMinutes($checkinGrace);
                        }

                        //Checkout Status
                        if (isset($atd->checkout) && $checkoutTimeWithGrace > date('H:i', strtotime($atd->checkout))) {
                            $checkoutStatus = 'Early Departure';
                            $check_out = Carbon::parse($atd->checkout);
                            $checkOutGrace = Carbon::parse($checkoutTimeWithGrace);
                            $earlyDeparture = $checkOutGrace->diffInMinutes($check_out);
                        }
                        // }

                        return [$atd->date  => [
                            'status' => (is_null($atd->checkin) || is_null($atd->checkout)) ? 'P*' : 'P',
                            'leave_status' => in_array($atd->date, array_keys($leaveDatesArr)) ? 'HL' : '',
                            'checkin' =>  $atd->checkin,
                            'checkout' =>  $atd->checkout,
                            'checkin_from' => $atd->checkin_from,
                            'checkout_from' => $atd->checkout_from,
                            'checkinStatus' => $checkinStatus,
                            'checkoutStatus' =>  $checkoutStatus,
                            'total_working_hr' => $total_working_hr,
                            'over_time' => (abs($over_time) > 0) ? $over_time : '',
                            'coordinates' => $atd->getCoordinatesAttributes(),
                            'late_arrival' => $lateArrival,
                            'early_departure' => $earlyDeparture,
                            'actual_shift' => isset($actualShift->title) ? $actualShift->title : '',
                            'updated_shift' => isset($updatedShift->title) ? $updatedShift->title : ''

                        ]];
                    });

                $gender = optional($emp->getGender)->dropvalue == 'Female' ? 2 : 3;
                $holiday = $holidayDetail
                    ->whereIn('organization_id', [$emp->organization_id, null])
                    ->whereIn('gender_type', [1, $gender])
                    ->mapWithKeys(function ($holiday) {
                        return [$holiday->eng_date  => ['status' => 'H', 'holidayName' => $holiday->sub_title]];
                    });

                $days = $dates->merge($dayOffDates)->merge($holiday)->merge($leaveDates)->merge($atdDates)->sortKeys();
                $filter = $data['filter'];
                // if (isset($filter['status']) && $filter['status'] != '') {
                //     $days =  $days->where('status', $filter['status']);

                if (isset($filter['type']) && !empty($filter['type']) && isset($filter['medium']) && !empty($filter['medium'])) {
                    if ($filter['type'] == 'checkin') {
                        $days =  $days->where('checkin_from', $filter['medium']);
                    } else {
                        $days =  $days->where('checkout_from', $filter['medium']);
                    }
                }
                // }

                $emp->date = $days;

                return $emp;
            }));
        }
        $data['mediumList'] = ['biometric' => 'Biometric', 'web' => 'Web', 'app' => 'App'];
        $data['typeList'] = ['checkin' => 'Checkin', 'checkout' => 'Checkout'];

        // if (auth()->user()->user_type == 'super_admin' || auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'hr') {
        // $data['employees'] = $this->employees->getList();
        $data['organizationList'] = $this->organization->getList();
        $data['branchList'] = $this->branch->getList();
        // $data['departmentList'] = $this->department->getList();
        // }
        return view('attendance::app-attendance.index', $data);
    }

    public function appViewLogs(Request $request)
    {
        if ($request->ajax()) {
            $filter = $request->all();
            $sort = [
                'by' => 'id',
                'sort' => 'ASC'
            ];
            $data['empAttendanceLogs'] = $this->attendanceLog->findAll(null, $filter, $sort, null);
            $empAttendanceLogs = view('attendance::app-attendance.partial.view-logs', $data)->render();
            return response()->json(['data' => $empAttendanceLogs]);
        }
    }
}
