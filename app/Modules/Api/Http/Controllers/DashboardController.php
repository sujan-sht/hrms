<?php

namespace App\Modules\Api\Http\Controllers;

use App\Helpers\DateTimeHelper;
use App\Modules\Admin\Entities\SystemReminder;
use App\Modules\Admin\Repositories\SystemReminderInterface;
use App\Modules\Admin\Repositories\SystemReminderRepository;
use App\Modules\Api\Transformers\AttendanceRequestResource;
use App\Modules\Api\Transformers\DepartmentResource;
use App\Modules\Api\Transformers\EmployeeResource;
use App\Modules\Api\Transformers\EventResource;
use App\Modules\Api\Transformers\LeaveResource;
use App\Modules\Api\Transformers\LeaveTodayResource;
use App\Modules\Api\Transformers\NewEmployeeListResource;
use App\Modules\Api\Transformers\NoticeResource;
use App\Modules\Api\Transformers\SurveyResource;
// use App\Modules\Api\Transformers\NoticeResource;
use App\Modules\Api\Transformers\SystemReminderResource;
use App\Modules\Attendance\Entities\Attendance;
use App\Modules\Attendance\Entities\AttendanceLog;
use App\Modules\Attendance\Entities\AttendanceRequest;
use App\Modules\Attendance\Repositories\AttendanceInterface;
use App\Modules\Attendance\Repositories\AttendanceRequestRepository;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\EmployeeLeave;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Event\Entities\Event;
use App\Modules\Event\Repositories\EventInterface;
use App\Modules\Event\Repositories\EventRepository;
use App\Modules\LeaveYearSetup\Entities\LeaveYearSetup;
use App\Modules\Holiday\Repositories\HolidayRepository;
use App\Modules\Leave\Entities\EmployeeLeaveOpening;
use App\Modules\Leave\Entities\Leave;
use App\Modules\Leave\Repositories\LeaveInterface;
use App\Modules\Leave\Repositories\LeaveRepository;
use App\Modules\Notice\Entities\Notice;
use App\Modules\Notice\Repositories\NoticeInterface;
use App\Modules\Notice\Repositories\NoticeRepository;
use App\Modules\Survey\Repositories\SurveyRepository;
use App\Modules\Tada\Entities\Tada;
use App\Modules\Tada\Entities\TadaRequest;
// use App\Modules\Notice\Transformers\NoticeResource;
use App\Modules\Tada\Repositories\TadaRepository;
use App\Modules\Tada\Repositories\TadaRequestRepository;
use App\Modules\User\Repositories\UserInterface;
use BadMethodCallException;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DashboardController extends ApiController
{
    protected $notice;
    protected $user;
    protected $event;
    protected $employeeObj;
    protected $leaveObj;
    protected $attendanceObj;
    protected $reminderObj;

    public function __construct(
        NoticeInterface $notice,
        EventInterface $event,
        UserInterface $user,
        LeaveInterface $leaveObj,
        EmployeeInterface $employeeObj,
        AttendanceInterface $attendanceObj,
        SystemReminderInterface $reminderObj
    ) {
        $this->notice = $notice;
        $this->event = $event;
        $this->user = $user;
        $this->employeeObj = $employeeObj;
        $this->leaveObj = $leaveObj;
        $this->attendanceObj = $attendanceObj;
        $this->reminderObj = $reminderObj;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function dashboard()
    {
        try {
            $userModel = auth()->user();
            $currentLeaveYear = LeaveYearSetup::currentLeaveYear();
            // $notices = Notice::orderBy('notice_date', 'desc')->take(2)->get();
            $filter = [
                'start' => Carbon::now()->toDateString(),
                'end' => date('Y-m-d', strtotime(Carbon::now() . '+7 days'))
            ];
            $events = (new EventRepository())->findAll(4, $filter);
            $notices = (new NoticeRepository())->getLatestNotices(2);
            // dd($events->toArray());
            // $events = Event::when(true, function ($query) {
            //     $now = Carbon::now();
            //     $compile_end_date = Carbon::now()->addDays(90);

            //     $query->whereDate('event_start_date', '>=', $now);
            //     $query->whereHas('users', function (Builder $qry) {
            //         $qry->where('users.id', auth()->user()->id);
            //     });
            //     $query->orDoesnthave('users');
            //     $query->where(function (Builder $qry) use ($compile_end_date) {
            //         $qry->whereDate('event_end_date', '<=', $compile_end_date);
            //         $qry->orWhereNull('event_end_date');
            //     });
            // })->orderBy('event_start_date', 'DESC')->take(2)->get();

            // $systemReminders = (new SystemReminderRepository())->getSystemReminder(10);

            $pending = $this->pendingApproval();
            $employees = $this->getDepartmentMembers($userModel)->take(10);
            $leaveRequestCount = Leave::where([
                'employee_id' => $userModel->emp_id,
                'status' => 3,
                'parent_id' => null,
            ])->whereHas('leaveTypeModel', function ($query) {
                $query->where('leave_year_id', getCurrentLeaveYearId());
            })->count();
            $attendanceRequestCount = AttendanceRequest::where('employee_id', $userModel->emp_id)->whereMonth('date', date('m'))->count();
            $claim = Tada::where('employee_id', $userModel->emp_id)->whereMonth('eng_from_date', date('m'))->count();
            $tadaRequest = TadaRequest::where('employee_id', $userModel->emp_id)->whereMonth('eng_request_date', date('m'))->count();
            $claimRequestCount = $claim + $tadaRequest;

            $data = [
                'first_name' => Str::ucfirst(optional($userModel->userEmployer)->first_name),
                'notices' =>  NoticeResource::collection($notices),
                'events' =>  EventResource::collection($events),
                // 'systemReminders' => new SystemReminderResource($systemReminders),
                // 'todayAtd'=>$this->getTodayAtd(),
                'pending' => $pending,
                'total_departments' => $this->getDepartmentMembers($userModel)->count(),
                'department' => DepartmentResource::collection($employees),
                'leaveRequestCount' => $leaveRequestCount,
                'attendanceRequestCount' => $attendanceRequestCount,
                'claimRequestCount' => $claimRequestCount,
            ];

            return $this->respond([
                'status' => true,
                'data' => $data
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    public function getStaffsOnLeave()
    {
        try {
            $currentLeaveYear = LeaveYearSetup::currentLeaveYear();
            $todayLeaves = $this->leaveObj->getApprovedTodayLeave()->sortBy('emp_name');
            $todayFullLeaves = $todayLeaves->where('leave_kind', 2);
            $todayFirstHalfLeaves = $todayLeaves->where('leave_kind', 1)->where('half_type', 1);
            $todaySecondHalfLeaves = $todayLeaves->where('leave_kind', 1)->where('half_type', 2);
            $data['staffsOnLeave'] = [
                'todayLeaves' => LeaveTodayResource::collection($todayLeaves),
                'todayFullLeaves' => LeaveTodayResource::collection($todayFullLeaves),
                'todayFirstHalfLeaves' => LeaveTodayResource::collection($todayFirstHalfLeaves),
                'todaySecondHalfLeaves' => LeaveTodayResource::collection($todaySecondHalfLeaves)
            ];
            return $this->respond([
                'status' => true,
                'data' => $data
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    public function getLeaveSummary(){
        try {
            $userModel = auth()->user();
            $currentLeaveYear = LeaveYearSetup::currentLeaveYear();
            $remainingLeave = EmployeeLeave::where(function ($query) use ($userModel, $currentLeaveYear) {
                $query->where('employee_id', $userModel->emp_id);
                $query->whereHas('leaveTypeModel', function ($q) use ($currentLeaveYear) {
                    $q->where('show_on_employee', '11')->where('leave_year_id', $currentLeaveYear->id);
                });
            })->sum('leave_remaining');

            $totalLeave = EmployeeLeaveOpening::where(function ($query) use ($userModel, $currentLeaveYear) {
                $query->where('employee_id', $userModel->emp_id);
                $query->whereHas('leaveTypeModel', function ($q) use ($currentLeaveYear) {
                    $q->where('show_on_employee', '11')->where('leave_year_id', $currentLeaveYear->id);
                });
            })->sum('opening_leave');
            $data['leaveSummary'] = [
                'remainingLeave' => $remainingLeave,
                'totalLeave' => $totalLeave,
            ];
            return $this->respond([
                'status' => true,
                'data' => $data
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    public function getSystemReminder(){
        try {
            $systemReminders = $this->reminderObj->getSystemReminder(10);
            $data['systemReminder'] = new SystemReminderResource($systemReminders);
            return $this->respond([
                'status' => true,
                'data' => $data
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    public function getBirthdayAniversary(){
        try {
            $data['birthdayAnniversary'] =  $this->getBirthdayAnniversary();
            return $this->respond([
                'status' => true,
                'data' => $data
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    public function getnewStarter(){
        try {
            $data['newStarter'] = NewEmployeeListResource::collection($this->employeeObj->getNewEmployeeList());
            return $this->respond([
                'status' => true,
                'data' => $data
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    public function getSurvey(){
        try {
            $userModel = auth()->user();
            $surveys = $this->getSurveys();
            $data['surveys'] = SurveyResource::collection($surveys);
            $data['totalSurveys'] = $surveys->count();
            return $this->respond([
                'status' => true,
                'data' => $data
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    public function getAttendanceOverview(){
        try {
            $data['attendanceOverview'] = $this->attendanceObj->getlateEarlyAndMissed();
            return $this->respond([
                'status' => true,
                'data' => $data
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }
    


    public function pendingApproval()
    {
        $activeUserModel = auth()->user();
        $leaves = ((new LeaveRepository())->getEmployeeLeaves($activeUserModel->emp_id, 2)->where('status', 1))->toArray();
        // $attendanceRequests = ((new AttendanceRequestRepository())->getEmployeeAttendanceRequest(2)->toArray());
        $attendanceRequests = AttendanceRequest::where('status', 1)
            ->where('employee_id', $activeUserModel->emp_id)
            ->orderBy('id', 'DESC')->take(2)->get()->map(function ($atd) {
                $atd->title = ($atd->getType());
                $atd->type = 'attendance';
                return $atd;
            })->toArray();
        // $claims = (new TadaRepository())->getEmployeeClaim(2)->toArray();
        // $requests = (new TadaRequestRepository())->getEmployeeTadaRequest(2)->toArray();
        // $mergeArray = $leaves->merge($attendanceRequests);
        // $mergeArray->sortBy('date');
        // return $mergeArray;

        $mergeArray = array_merge($leaves, $attendanceRequests);
        usort($mergeArray, function ($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });

        $myCollectionObj = collect($mergeArray);
        return $myCollectionObj->take(4);
    }

    public function getCalendarEventHoliday(Request $request)
    {
        try {
            $data = $eventArray = $holidayArray = [];
            $eventResult = (new EventRepository())->getEventByUserType();
            foreach ($eventResult as $key => $event) {
                $eventArray[] = [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => date('Y-m-d H:i:s', strtotime("$event->event_start_date $event->event_time")),
                    'end' => $event->event_end_date,
                    'type' => 'event',
                    'color' => auth()->user()->id == $event->created_by ? '#f58646' : '#3a87ad',
                    // 'created_by' => $event->created_by
                ];
            }

            $holidays = (new HolidayRepository())->getHolidayList();
            foreach ($holidays as $key => $holiday) {
                $holidayArray[] = [
                    'id' => $holiday['id'],
                    'title' => $holiday['title'],
                    'start' => $holiday['date'],
                    'end' => $holiday['date'],
                    'type' => 'holiday',
                    'color' => '#eb1e4e',
                ];
            }
            $data = array_merge($eventArray, $holidayArray);
            return $this->respond([
                'status' => true,
                'data' => $data
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    public function getDepartmentMembers($userModel)
    {
        $employees = Employee::when(true, function ($query) use ($userModel) {
            $departmentId = optional($userModel->userEmployer)->department_id;
            $empModel = $userModel->userEmployer;
            $query->where('department_id', $departmentId);
            $query->where('organization_id', $empModel->organization_id);
        })
            ->where('id', '!=', $userModel->emp_id)
            ->where('status', 1)
            ->get();
        return $employees;
    }
    public function getSurveys()
    {
        $surveyFilter = [
            'organization_id' => optional(auth()->user()->userEmployer)->organization_id,
            'department_id' => optional(auth()->user()->userEmployer)->department_id,
            'level_id' => optional(auth()->user()->userEmployer)->level_id,
            'checkSurveyParticipant' => true,
        ];
        $surveySort = ['by' => 'id', 'sort' => 'DESC'];
        $surveys = (new SurveyRepository())->findAll(10, $surveyFilter, $surveySort);
        return $surveys;
    }
    public function getBirthdayAnniversary()
    {
        $mergedData = [];
        $birthdayData = $this->employeeObj->getBirthdayList()
            ->map(function ($name, $key) use (&$mergedData) {
                $mergedData[] = $name;
            });
        $anniversaryData = $this->employeeObj->getAnniversaryList()
            ->map(function ($name, $key) use (&$mergedData) {
                $mergedData[] = $name;
            });
        $collection = collect($mergedData);
        $finalMergedData =  $collection->sortBy('sort_date');
        return $finalMergedData;
    }
}
