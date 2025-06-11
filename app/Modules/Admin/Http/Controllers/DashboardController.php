<?php

namespace App\Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\DateTimeHelper;
use Illuminate\Routing\Controller;
use App\Modules\User\Entities\User;
use Illuminate\Support\Facades\Auth;
use App\Modules\Leave\Entities\Leave;
use App\Modules\Leave\Entities\LeaveType;
use App\Modules\Worklog\Entities\Worklog;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Training\Entities\Training;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Onboarding\Entities\Applicant;
use App\Modules\Onboarding\Entities\Interview;
use App\Modules\Attendance\Entities\Attendance;
use App\Modules\Employee\Entities\EmployeeLeave;
use App\Modules\Leave\Repositories\LeaveInterface;
use App\Modules\Organization\Entities\Organization;
use App\Modules\Leave\Entities\EmployeeLeaveOpening;
use App\Modules\Notice\Repositories\NoticeInterface;
use App\Modules\Onboarding\Repositories\MrfInterface;
use App\Modules\Attendance\Entities\AttendanceRequest;
use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Admin\Repositories\SystemReminderInterface;
use App\Modules\Attendance\Entities\AttendanceLog;
use App\Modules\Attendance\Entities\WebAttendanceAllocation;
use App\Modules\Attendance\Repositories\AttendanceInterface;
use App\Modules\Attendance\Repositories\AttendanceRequestInterface;
use App\Modules\BusinessTrip\Repositories\BusinessTripInterface;
use App\Modules\Event\Entities\Event;
use App\Modules\Event\Repositories\EventInterface;
use App\Modules\FiscalYearSetup\Entities\FiscalYearSetup;
use App\Modules\Holiday\Entities\Holiday;
use App\Modules\Holiday\Entities\HolidayDetail;
use App\Modules\Holiday\Repositories\HolidayInterface;
use App\Modules\LeaveYearSetup\Entities\LeaveYearSetup;
use App\Modules\Onboarding\Repositories\MrfRepository;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Poll\Repositories\PollInterface;
use App\Modules\Poll\Repositories\PollRepository;
use App\Modules\Setting\Repositories\DepartmentInterface;
use App\Modules\Setting\Repositories\LevelInterface;
use App\Modules\Shift\Entities\EmployeeShift;
use App\Modules\Shift\Repositories\EmployeeShiftRepository;
use App\Modules\Survey\Repositories\SurveyInterface;
use App\Modules\Survey\Repositories\SurveyRepository;
use App\Modules\Tada\Entities\Tada;
use App\Modules\Tada\Entities\TadaRequest;
use App\Modules\Tada\Repositories\TadaInterface;
use App\Modules\Tada\Repositories\TadaRequestInterface;
use App\Modules\User\Repositories\UserInterface;
use Carbon\Carbon;
use Nwidart\Modules\Facades\Module;

class DashboardController extends Controller
{
    private $mrfObj;
    private $organizationObj;
    private $employeeObj;
    private $dropdownObj;
    public $noticeObj;
    public $leaveObj;
    public $reminderObj;
    public $holiday;
    public $event;
    private $attendanceRequest;
    private $tadaClaim;
    private $tadaRequest;
    private $user;
    private $attendance;
    private $businessTrip;
    private $pollObj;
    private $surveyObj;
    private $department;
    private $level;



    /**
     *
     */
    public function __construct(
        // MrfInterface $mrfObj,
        OrganizationInterface $organizationObj,
        EmployeeInterface $employeeObj,
        DropdownInterface $dropdownObj,
        NoticeInterface $noticeObj,
        LeaveInterface $leaveObj,
        SystemReminderInterface $reminderObj,
        HolidayInterface $holiday,
        EventInterface $event,
        AttendanceInterface $attendance,
        AttendanceRequestInterface $attendanceRequest,
        TadaInterface $tadaClaim,
        TadaRequestInterface $tadaRequest,
        UserInterface $user,
        BusinessTripInterface $businessTrip,
        DepartmentInterface $department,
        LevelInterface $level
        // PollInterface $pollObj,
        // SurveyInterface $surveyObj

    ) {
        // $this->mrfObj = $mrfObj;
        $this->organizationObj = $organizationObj;
        $this->employeeObj = $employeeObj;
        $this->dropdownObj = $dropdownObj;
        $this->noticeObj = $noticeObj;
        $this->leaveObj = $leaveObj;
        $this->reminderObj = $reminderObj;
        $this->holiday = $holiday;
        $this->event = $event;
        $this->attendanceRequest = $attendanceRequest;
        $this->tadaClaim = $tadaClaim;
        $this->tadaRequest = $tadaRequest;
        $this->user = $user;
        $this->attendance = $attendance;
        $this->businessTrip = $businessTrip;
        $this->department = $department;
        $this->level = $level;

        // $this->pollObj = $pollObj;
        // $this->surveyObj = $surveyObj;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        if (empty(getCurrentLeaveYearId())) {
            toastr()->error('Please Setup Leave Year');
            return redirect(route('leaveYearSetup.index'));
        }

        $employeeView = false;
        $requestData = $request->all();
        if (isset($requestData['view'])) {
            if ($requestData['view'] == 'employee') {
                $employeeView = true;
            }
        }

        $activeUserModel = Auth::user();
        if ($activeUserModel->user_type == 'employee' || $activeUserModel->user_type == 'supervisor' || $employeeView) {
            $data = $this->employeeDashboardContent();
            $data['systemReminders'] = $this->reminderObj->getSystemReminder(10);
            $now = Carbon::now()->toDateString();
            $inputParam = [
                'emp_id' => $activeUserModel->emp_id,
                'date' => $now
            ];
            $latestCheck = AttendanceLog::where($inputParam)->latest()->first();

            $inout_mode = $latestCheck ? $latestCheck->inout_mode : 1;
            $todayAttendance = Attendance::where($inputParam)->first();
            if (isset($todayAttendance)) {
                $getTodayAtd['checkin'] = $todayAttendance->checkin;
                $getTodayAtd['checkout'] = $todayAttendance->checkout;
                $getTodayAtd['inout_mode'] = $inout_mode;
            } else {
                $getTodayAtd['checkin'] = null;
                $getTodayAtd['checkout'] = null;
                $getTodayAtd['inout_mode'] = 1;
            }
            $data['getTodayAtd'] = $getTodayAtd;


            return view('admin::employee.dashboard', $data);
        }

        $data = $this->employeeDashboardContent();
        $data['currentDatetime'] = date('Y-m-d H:i:s');
        $data['totalOrganization'] = Organization::getCount();
        $data['totalEmployee'] = Employee::getCount();
        $data['totalLeave'] = Leave::getCount();
        $data['totalAttendance'] = Attendance::getCount();
        $data['totalInterview'] = Interview::count();
        $data['workReportCount'] = Worklog::getCount();
        $data['trainingCount'] = Training::getCount();

        if (Module::isModuleEnabled('Onboarding')) {
            $mrfFilter['status'] = 3;
            $mrfSort = ['by' => 'end_date', 'sort' => 'ASC'];
            $data['mrfModels'] = (new MrfRepository())->findAll(20, $mrfFilter, $mrfSort);
        }
        $data['statusList'] = Leave::statusList();

        $filter['isParent'] = true;
        $data['leaveModels'] = $this->leaveObj->findAll(6, $filter);

        $userInfo = Auth::user();
        $data['user_type'] = $user_type = $userInfo->user_type;
        $id = (($user_type == 'super_admin' || $user_type == 'hr')) ? '' : $userInfo->emp_id;
        $data['emp_id'] = $id;
        $data['user_id'] = $userInfo->id;

        $data['organizationList'] = $this->organizationObj->getList();
        $organizationModels = $this->organizationObj->findAll(null, []);
        $orgArray = [];
        foreach ($organizationModels as $organizationModel) {
            $orgArray[] = [
                'name' => $organizationModel->name,
                'value' => $organizationModel->employees()->where('status', 1)->count()
            ];
        }
        $data['organizationwiseEmployees'] = $orgArray;

        $levelData = [];
        $levels = $this->level->getList();
        foreach ($levels as $levelId => $level) {
            $levelData[] = [
                'name' => $level,
                'value' => Employee::getLevelCount($levelId)
            ];
        }
        $data['levelData'] = $levelData;

        $maleCount = 0;
        $femaleCount = 0;
        $total = 0;
        $male = $this->dropdownObj->getByDropvalue('Male');
        $female = $this->dropdownObj->getByDropvalue('Female');
        $data['maleCount'] = '';
        $data['femaleCount'] = '';

        if (isset($male) && !empty($male)) {
            $maleCount = $data['maleCount'] = Employee::getMaleCount($male->id);
        }
        if (isset($female) && !empty($female)) {
            $femaleCount = $data['femaleCount'] = Employee::getFemaleCount($female->id);
        }

        $total = $maleCount + $femaleCount;
        $data['malePercentage'] = $maleCount ? round(($maleCount / $total) * 100, 0) : 0;
        $data['femalePercentage'] = $femaleCount ? round(($femaleCount / $total) * 100, 0) : 0;

        $singleCount = 0;
        $marriedCount = 0;
        $single = $this->dropdownObj->getByDropvalue('Single');
        $married = $this->dropdownObj->getByDropvalue('Married');
        if (isset($single)) {
            $singleCount = Employee::getSingleCount($single->id);
        }
        if (isset($married)) {
            $marriedCount = Employee::getMarriedCount($married->id);
        }
        $data['maritalStatusData'] = [
            ['name' => 'Single', 'value' => $singleCount],
            ['name' => 'Married', 'value' => $marriedCount]
        ];

        $sources = Applicant::sourceList();
        foreach ($sources as $key => $source) {
            $sourceList[] = $source;
            $sourceData[] = Applicant::getSourceCount($key);
            // $sourceData[] = Applicant::where('source', $key)->count();
        }
        $data['sourceLabel'] = $sourceList;
        $data['sourceData'] = $sourceData;

        $data['systemReminders'] = $this->reminderObj->getSystemReminder(20);
        // $data['todayLeaves'] = $this->leaveObj->getApprovedTodayLeave();
        $data['mobileAttendances'] = $this->attendance->getMobileAttendance();
        return view('admin::admin.dashboard', $data);
    }

    public function graphicalIndex(Request $request)
    {

        if (empty(getCurrentLeaveYearId())) {
            toastr()->error('Please Setup Leave Year');
            return redirect(route('leaveyearsetup.index'));
        }

        $employeeView = false;
        $requestData = $request->all();
        if (isset($requestData['view'])) {
            if ($requestData['view'] == 'employee') {
                $employeeView = true;
            }
        }

        $activeUserModel = Auth::user();
        if ($activeUserModel->user_type == 'employee' || $activeUserModel->user_type == 'supervisor' || $employeeView) {
            $data = $this->employeeDashboardContent();
            $data['systemReminders'] = $this->reminderObj->getSystemReminder(10);


            $now = Carbon::now()->toDateString();
            $inputParam = [
                'emp_id' => $activeUserModel->emp_id,
                'date' => $now
            ];

            $latestCheck = AttendanceLog::where($inputParam)->latest()->first();

            $inout_mode = $latestCheck ? $latestCheck->inout_mode : 1;


            $todayAttendance = Attendance::where($inputParam)->first();
            if (isset($todayAttendance)) {
                $getTodayAtd['checkin'] = $todayAttendance->checkin;
                $getTodayAtd['checkout'] = $todayAttendance->checkout;
                $getTodayAtd['inout_mode'] = $inout_mode;
            } else {
                $getTodayAtd['checkin'] = null;
                $getTodayAtd['checkout'] = null;
                $getTodayAtd['inout_mode'] = 1;
            }
            $data['getTodayAtd'] = $getTodayAtd;

            return view('admin::employee.dashboard', $data);
        }

        $data = $this->employeeDashboardContent();
        $data['currentDatetime'] = date('Y-m-d H:i:s');
        $data['totalOrganization'] = Organization::getCount();
        $data['totalEmployee'] = Employee::getCount();
        $data['totalLeave'] = Leave::getCount();
        $data['totalAttendance'] = Attendance::getCount();
        $data['totalInterview'] = Interview::count();
        $data['workReportCount'] = Worklog::getCount();
        $data['trainingCount'] = Training::getCount();

        if (Module::isModuleEnabled('Onboarding')) {
            $mrfFilter['status'] = 3;
            $mrfSort = ['by' => 'end_date', 'sort' => 'ASC'];
            $data['mrfModels'] = (new MrfRepository())->findAll(20, $mrfFilter, $mrfSort);
        }
        $data['statusList'] = Leave::statusList();

        $filter['isParent'] = true;
        $data['leaveModels'] = $this->leaveObj->findAll(6, $filter);

        $userInfo = Auth::user();
        $data['user_type'] = $user_type = $userInfo->user_type;
        $id = (($user_type == 'super_admin' || $user_type == 'hr')) ? '' : $userInfo->emp_id;
        $data['emp_id'] = $id;
        $data['user_id'] = $userInfo->id;

        $data['organizationList'] = $this->organizationObj->getList();
        $organizationModels = $this->organizationObj->findAll(null, []);
        $orgArray = [];
        foreach ($organizationModels as $organizationModel) {
            $orgArray[] = [
                'name' => $organizationModel->name,
                'value' => $organizationModel->employees()->where('status', 1)->count()
            ];
        }
        $data['organizationwiseEmployees'] = $orgArray;

        $levelData = [];
        $levels = $this->level->getList();
        foreach ($levels as $levelId => $level) {
            $levelData[] = [
                'name' => $level,
                'value' => Employee::getLevelCount($levelId)
            ];
        }
        $data['levelData'] = $levelData;

        $maleCount = 0;
        $femaleCount = 0;
        $total = 0;
        $male = $this->dropdownObj->getByDropvalue('Male');
        $female = $this->dropdownObj->getByDropvalue('Female');
        $data['maleCount'] = '';
        $data['femaleCount'] = '';

        if (isset($male) && !empty($male)) {
            $maleCount = $data['maleCount'] = Employee::getMaleCount($male->id);
        }
        if (isset($female) && !empty($female)) {
            $femaleCount = $data['femaleCount'] = Employee::getFemaleCount($female->id);
        }

        $total = $maleCount + $femaleCount;
        $data['malePercentage'] = $maleCount ? round(($maleCount / $total) * 100, 0) : 0;
        $data['femalePercentage'] = $femaleCount ? round(($femaleCount / $total) * 100, 0) : 0;

        $singleCount = 0;
        $marriedCount = 0;
        $single = $this->dropdownObj->getByDropvalue('Single');
        $married = $this->dropdownObj->getByDropvalue('Married');
        if (isset($single)) {
            $singleCount = Employee::getSingleCount($single->id);
        }
        if (isset($married)) {
            $marriedCount = Employee::getMarriedCount($married->id);
        }
        $data['maritalStatusData'] = [
            ['name' => 'Single', 'value' => $singleCount],
            ['name' => 'Married', 'value' => $marriedCount]
        ];

        $sources = Applicant::sourceList();
        foreach ($sources as $key => $source) {
            $sourceList[] = $source;
            $sourceData[] = Applicant::getSourceCount($key);
            // $sourceData[] = Applicant::where('source', $key)->count();
        }
        $data['sourceLabel'] = $sourceList;
        $data['sourceData'] = $sourceData;

        $data['systemReminders'] = $this->reminderObj->getSystemReminder(20);
        // $data['todayLeaves'] = $this->leaveObj->getApprovedTodayLeave();
        $data['mobileAttendances'] = $this->attendance->getMobileAttendance();
        return view('admin::admin.graphical-dashboard', $data);
    }

    public function analyticalIndex(Request $request)
    {
        $data['statusList'] = Leave::statusList();
        $filter['isParent'] = true;
        $data['leaveModels'] = $this->leaveObj->findAll(6, $filter);
        $userInfo = Auth::user();
        $data['user_type'] = $user_type = $userInfo->user_type;
        $id = (($user_type == 'super_admin' || $user_type == 'hr')) ? '' : $userInfo->emp_id;
        $data['emp_id'] = $id;
        $data['user_id'] = $userInfo->id;
        return view('admin::admin.analyticaldashboard', $data);
    }

    /**
     *
     */
    public function employeeDashboardContent()
    {
        $userModel = User::where('id', Auth::user()->id)->first();
        $departmentId = optional($userModel->userEmployer)->department_id;

        $currentLeaveYear = LeaveYearSetup::currentLeaveYear();
        $remainingLeave = EmployeeLeave::where(function ($query) use ($userModel, $currentLeaveYear) {
            $query->where('employee_id', $userModel->emp_id);
            // $query->where('leave_year_id', $currentLeaveYear->id);
            $query->whereHas('leaveTypeModel', function ($q) use ($currentLeaveYear) {
                $q->where('show_on_employee', '11')->where('leave_year_id', $currentLeaveYear->id);
            });
        })->sum('leave_remaining');

        $totalLeave = EmployeeLeaveOpening::where(function ($query) use ($userModel, $currentLeaveYear) {
            $query->where('employee_id', $userModel->emp_id);
            // $query->where('leave_year_id', $currentLeaveYear->id);
            $query->whereHas('leaveTypeModel', function ($q) use ($currentLeaveYear) {
                $q->where('show_on_employee', '11')->where('leave_year_id', $currentLeaveYear->id);
            });
        })->sum('opening_leave');
        // $totalLeave = LeaveType::whereIn('id', [2, 4])->sum('number_of_days');

        $data['todayLeaves'] = $this->leaveObj->getApprovedTodayLeave()->sortBy('emp_name');
        $data['todayFullLeaves'] = $data['todayLeaves']->where('leave_kind', 2);
        $data['todayFirstHalfLeaves'] = $data['todayLeaves']->where('leave_kind', 1)->where('half_type', 1);
        $data['todaySecondHalfLeaves'] = $data['todayLeaves']->where('leave_kind', 1)->where('half_type', 2);

        $data['currentDatetime'] = date('Y-m-d H:i:s');
        $data['departmentId'] = $departmentId;
        $data['leaveRequestCount'] = Leave::where([
            'employee_id' => $userModel->emp_id,
            'status' => 3,
            'parent_id' => null,
        ])
            ->whereHas('leaveTypeModel', function ($query) {
                $query->where('leave_year_id', getCurrentLeaveYearId());
            })
            ->count();
        $data['leaveRequestCountThisMonth'] = Leave::where([
            'employee_id' => $userModel->emp_id,
            'status' => 3,
            'parent_id' => null,
        ])
            ->whereHas('leaveTypeModel', function ($query) {
                $query->where('leave_year_id', getCurrentLeaveYearId());
            })->whereMonth('date', date('m'))
            ->count();
        $data['attendanceRequestCount'] = AttendanceRequest::where('employee_id', $userModel->emp_id)->whereMonth('date', date('m'))->count();
        $claim = Tada::where('employee_id', $userModel->emp_id)->whereMonth('eng_from_date', date('m'))->count();
        $tadaRequest = TadaRequest::where('employee_id', $userModel->emp_id)->whereMonth('eng_request_date', date('m'))->count();
        $data['claimRequest'] = $claim + $tadaRequest;
        // $data['employeeDepartments'] = Employee::where('id', '!=', $userModel->emp_id)->where('department_id', $departmentId)->get();

        $data['employeeDepartments'] = Employee::when(true, function ($query) use ($userModel, $departmentId) {
            $empModel = $userModel->userEmployer;
            if (auth()->user()->user_type == 'division_hr' || auth()->user()->user_type == 'supervisor' || auth()->user()->user_type == 'hr') {
                $query->where('organization_id', $empModel->organization_id);
                $query->where('department_id', $departmentId);
            }

            if (auth()->user()->user_type == 'employee') {
                $query->where('department_id', $departmentId);
                $query->where('organization_id', $empModel->organization_id);
            }
        })
            ->where('id', '!=', $userModel->emp_id)
            ->where('status', 1)
            ->get();

        $data['subordinates'] = Employee::getSupervisorSubordinates(auth()->user()->id);

        // $data['myTeamCount'] = Employee::where('status', 1)->where('department_id', $departmentId)->count();
        $data['totalClaimRequest'] = 0;
        // $data['notice'] = $this->noticeObj->getLatestNotices();
        $data['notice'] = $this->noticeObj->getNotices();


        $data['remaining_leave'] = $remainingLeave;
        $data['total_leave'] = $totalLeave;
        $data['avg_hrs'] = 0;
        $data['ontime_per'] = 0;
        $data['myteam_avg_hrs'] = 0;
        $data['team_ontime_per'] = 0;

        // $data['my_pending_leaves'] = Leave::paginate(100);

        // $data['holiday'] = Leave::where('status', 0)->paginate(100);
        // $data['events'] = Leave::where('status', 0)->paginate(100);
        $data['event_users'] = [];

        // $data['dept_employees'] = Leave::where('status', 0)->paginate(100);
        // $data['reminderNotify'] = Leave::where('status', 0)->paginate(100);

        if (Module::isModuleEnabled('Onboarding')) {
            $mrfFilter['status'] = 3;
            $mrfSort = ['by' => 'end_date', 'sort' => 'ASC'];
            $data['mrfModels'] = (new MrfRepository())->findAll(20, $mrfFilter, $mrfSort);
        }

        // $data['pollModel'] = $this->pollObj->getLatestPoll();
        // $data['surveyModels'] = $this->surveyObj->findAll(null, $surveyFilter, $surveySort);
        if (Module::isModuleEnabled('Survey')) {
            $surveyFilter = [
                'organization_id' => optional(auth()->user()->userEmployer)->organization_id,
                'department_id' => optional(auth()->user()->userEmployer)->department_id,
                'level_id' => optional(auth()->user()->userEmployer)->level_id,
                'checkSurveyParticipant' => true,
            ];
            $surveySort = ['by' => 'id', 'sort' => 'DESC'];
            $data['surveyModels'] = (new SurveyRepository())->findAll(null, $surveyFilter, $surveySort);
        }
        if (Module::isModuleEnabled('Poll')) {
            $data['pollModel'] = (new PollRepository())->getLatestPoll();
        }
        // $data['annoucements'] = $this->noticeObj->getLatestNotices();
        $data['annoucements'] = $this->noticeObj->getNotices();

        $data['newEmployeeData'] = $this->employeeObj->getNewEmployeeList();
        $data['lateEarlyAndMissedData'] = $this->attendance->getlateEarlyAndMissed();
        // dd($data['lateEarlyAndMissedData']);
        $mergedData = [];
        $birthdayData = $this->employeeObj->getBirthdayList()
            ->map(function ($name, $key) use (&$mergedData) {
                $mergedData[] = $name;
            });
        $anniversaryData = $this->employeeObj->getAnniversaryList()
            ->map(function ($name, $key) use (&$mergedData) {
                $mergedData[] = $name;
            });
        // $newEmployeeData = $this->employeeObj->getNewEmployeeList()
        //     ->map(function ($name, $key) use (&$mergedData) {
        //         $mergedData[] = $name;
        //     });
        $collection = collect($mergedData);
        $finalMergedData =  $collection->sortBy('sort_date');

        // $data['birthdayAnniversaryAndNewEmployeeData'] = $finalMergedData;
        $data['birthdayAnniversary'] = $finalMergedData;
        $data['jobEnds'] = $this->employeeObj->getJobEndAndContractEndList();
        $data['todayLeaves'] = $this->leaveObj->getApprovedTodayLeave()->sortBy('emp_name');
        $data['todayFullLeaves'] = $data['todayLeaves']->where('leave_kind', 2);
        $data['todayFirstHalfLeaves'] = $data['todayLeaves']->where('leave_kind', 1)->where('half_type', 1);
        $data['todaySecondHalfLeaves'] = $data['todayLeaves']->where('leave_kind', 1)->where('half_type', 2);

        // $data['todayCustomLeaves'] = $data['todayLeaves']->where('leave_kind',3);
        $data['event_holidays'] = collect($this->getEventHolidays());
        $data['requestApprovals'] = collect($this->getRequestForApproval());

        if ($userModel->emp_id) {
            $data['empLeaveOverviewReports'] = $this->leaveObj->empRemainingLeaveDetailsLeaveTypewise($userModel->emp_id);
            $data['tadas'] = $this->tadaClaim->findAll(null, null);
        }
        $data['allowWebAttendance'] = true;
        $webAtdAllocation = WebAttendanceAllocation::where('organization_id', optional(auth()->user()->userEmployer)->organization_id)->where('department_id', optional(auth()->user()->userEmployer)->department_id)->first();
        if (isset($webAtdAllocation) && !empty($webAtdAllocation)) {
            $empData = json_decode($webAtdAllocation->employee_id);
            if (!empty($empData) && in_array(auth()->user()->emp_id, $empData)) {
                $data['allowWebAttendance'] = false;
            }
        }
        return $data;
    }

    public function getRequestForApproval()
    {
        $activeUserModel = Auth::user();
        $leaves =  $this->leaveObj->getEmployeeLeaves()->where('status', 1)->toArray();
        $attendanceRequests = $this->attendanceRequest->getEmployeeAttendanceRequest()->toArray();
        $claims = $this->tadaClaim->getEmployeeClaim()->toArray();
        $requests = $this->tadaRequest->getEmployeeTadaRequest()->toArray();
        $businessTrips = $this->businessTrip->getEmployeeBusinessTrips($activeUserModel->emp_id)->toArray();
        $mergeArray = array_merge($leaves, $attendanceRequests, $claims, $requests, $businessTrips);
        usort($mergeArray, function ($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });

        return $mergeArray;
    }

    public function getEventHolidays()
    {
        $mergeArray = [];
        $filter = [
            'start' => Carbon::now()->toDateString(),
            'end' => date('Y-m-d', strtotime(Carbon::now() . '+ 7 days'))
        ];
        $holidays = $this->holiday->findAll('', $filter);

        $holidayArray = [];
        foreach ($holidays as $key => $holiday) {
            foreach ($holiday->holidayDetail as $key => $value) {
                $holidayArray[] = [
                    'id' => $value['id'],
                    'title' => $value['sub_title'],
                    'date' => $value['eng_date'],
                    'type' => 'holiday'

                ];
            }
        }

        $events = $this->event->findAll('', $filter);

        $returnEventArray = [];
        foreach ($events as $key => $value) {
            $returnEventArray[] = [
                'id' => $value['id'],
                'title' => $value['title'],
                'date' => $value['event_start_date'],
                'type' => 'event'
            ];
        }


        $mergeArray = array_merge($holidayArray, $returnEventArray);
        usort($mergeArray, function ($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });
        return $mergeArray;
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('admin::create');
    }


    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('admin::show');
    }

    public function viewCalendar()
    {
        $data['holidays'] = $this->holiday->findAll();
        $data['events'] = $this->event->findAll();
        $data['is_edit'] = false;
        $data['organizationList'] = $this->organizationObj->getList();
        $data['departmentList'] = $this->department->getList();
        $filterArray = ['user_type' => ['employee'], 'modelmm' => 'user'];
        if (auth()->user()->user_type == 'division_hr') {
            $filterArray['organization_id'] = optional(auth()->user()->userEmployer)->organization_id;
        }
        // $data['users'] = employee_helper()->getUserListsByType($filterArray);
        $data['users'] = $this->user->getEmployeeUserList();

        return view('admin::calendar', $data);
    }

    public function getCalendarEventHolidayByAjax(Request $request)
    {

        // if ($request->ajax()) {
        $filter = $request->all();
        $data = $eventArray = $holidayArray = [];
        $eventResult = $this->event->findAll('', $filter);
        // dd($eventResult);
        foreach ($eventResult as $key => $event) {
            $eventArray[] = [
                'id' => $event->id,
                'title' => $event->title,
                'start' => date('Y-m-d H:i:s', strtotime("$event->event_start_date $event->event_time")),
                // 'start' => $event->event_start_date,
                'end' => $event->event_end_date,
                'type' => 'event',
                'color' => auth()->user()->id == $event->created_by ? '#f58646' : '#3a87ad',
                'created_by' => $event->created_by,
                'description' => $event->description,
                // 'taggable_users'=>$event->users->pluck('id')
            ];
        }

        $holidays = $this->holiday->findAll('', $filter);

        foreach ($holidays as $key => $holiday) {
            foreach ($holiday->holidayDetail as $key => $value) {
                $holidayArray[] = [
                    'id' => $value['id'],
                    'title' => $value['sub_title'],
                    'start' => $value['eng_date'],
                    'end' => $value['eng_date'],
                    'type' => 'holiday',
                    'color' => '#eb1e4e',
                    'description' => '',

                ];
            }
        }
        $data = array_merge($eventArray, $holidayArray);
        return response()->json($data);
        // }
    }

    public function calendarEvents(Request $request)
    {
        $data = $request->all();
        // dd($data);
        switch ($request->type) {
            case 'create':
                $event = [];
                try {
                    $data['created_by'] = auth()->user()->id;
                    $eventSave = $this->event->save($data);
                    $this->event->saveTaggedUser($eventSave, $data);

                    $event = [
                        'id' => $eventSave->id,
                        'title' => $eventSave->title,
                        'start' => $eventSave->event_start_date,
                        'end' => $eventSave->event_end_date,
                        'type' => 'event',
                        'color' => '#f58646',
                        'created_by' => $eventSave->created_by

                    ];
                    $msg = "Event Created Successfully";
                } catch (\Throwable $e) {
                    $msg = $e->getMessage();
                }
                return response()->json(['msg' => $msg, 'event' => $event]);
                break;

            case 'edit':
                $event = Event::find($request->id)->update([
                    // 'title' => $request->event_name,
                    'event_start_date' => $request->start,
                    'event_end_date' => $request->end,
                ]);

                return response()->json($event);
                break;

            case 'delete':
                $event = Event::find($request->id)->delete();
                return response()->json($event);
                break;
            case 'view':
                if ($data['category'] == 'event') {
                    // select('id','title','description','"event_start_date" as event_start_date','"end_date" as event_end_date')->
                    $result = Event::select('*')->selectRaw('event_start_date as start_date  , event_end_date as end_date')->find($request->id);
                } elseif ($data['category'] == 'holiday') {
                    $result = HolidayDetail::selectRaw('id , sub_title as title ," " as description , eng_date as start_date , "" as end_date')->find($request->id);
                }

                return response()->json([
                    'event' => view('event::event.partial.view-modal', compact('result'))->render()
                ]);
                break;

            default:
                break;
        }
    }

    //Web Attendance hit
    public function storeAttendance(Request $request)
    {
        try {
            $inputData = ($request->except(['type']));

            $userModel = Auth::user();
            // if ($userModel->ip_address == $request->ip()) {
            //     toastr()->error('IP Address not matched');
            //     return redirect()->back();
            // }
            $employeeModel = $userModel->userEmployer;

            $currentTime = Carbon::now()->toTimeString();
            $currentDate = Carbon::now()->toDateString();

            // $inputParams = [
            //     'employee_id' => $userModel->emp_id,
            //     'days' => date('D')
            // ];
            // $start_time = "09:00";
            // $empShift = EmployeeShift::where($inputParams)->orderBy('id', 'desc')->first();
            // if ($empShift) {
            //     $start_time = optional($empShift->getShift)->start_time;
            // }

            $inputData['inout_mode'] = 1;
            if ($request->type == 'checkin') {
                $inputData['inout_mode'] = 0;

                // if ($start_time >= $currentTime) {
                //     toastr()->error('Check-in only from ' . $start_time);
                //     return redirect()->back();
                // }
            }


            $inputData['emp_id'] = $userModel->emp_id;
            $inputData['biometric_emp_id'] = $employeeModel->biometric_id;
            $inputData['org_id'] = $employeeModel->organization_id;
            $inputData['date'] = $currentDate;
            $inputData['time'] = $currentTime;

            $inputData['punch_from'] = 'web';
            // $inputData['verify_mode'] = 0;
            $atdLog = AttendanceLog::create($inputData);
            if (!empty($atdLog)) {
                $this->attendance->saveAttendance($employeeModel, $inputData);
            }

            if ($request->type == 'checkin') {
                // $inputData['inout_mode'] = 0;
                toastr()->success('Check In Succesful');
            } elseif ($request->type == 'checkout') {
                // $inputData['inout_mode'] = 1;
                toastr()->success('Check Out Succesful');
            }


            // $inputParams = [
            //     'date' => $currentDate,
            //     'biometric_emp_id' => $inputData['biometric_emp_id'],
            // ];

            // $minLogTime = AttendanceLog::where($inputParams + ['inout_mode' => 0])->min('time');
            // $maxLogTime = AttendanceLog::where($inputParams + ['inout_mode' => 1])->max('time');
            // $inputParams = [
            //     'date' => $currentDate,
            //     'biometric_emp_id' => $inputData['biometric_emp_id'],
            // ];

            // $minLogTime = AttendanceLog::where($inputParams + ['inout_mode' => 0])->min('time');
            // $maxLogTime = AttendanceLog::where($inputParams + ['inout_mode' => 1])->max('time');

            // if ($request->type == 'checkin') {
            //     $inputData['checkin'] = $minLogTime;
            //     $inputData['checkin_form'] = 'web';
            //     toastr()->success('Check In Succesful');
            // } elseif ($request->type == 'checkout') {
            //     $inputData['checkout'] = $maxLogTime; //inputData['time']
            //     $inputData['checkout_form'] = 'web';
            //     $inputData['total_working_hr'] = DateTimeHelper::getTimeDiff($minLogTime, $maxLogTime);
            //     toastr()->success('Check Out Succesful');
            // }

            // Attendance::updateOrCreate([
            //     'emp_id' => $inputData['emp_id'],
            //     'org_id' => $inputData['org_id'],
            //     'date' => $currentDate,
            // ], $inputData);
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }
        return redirect()->back();
    }
}
