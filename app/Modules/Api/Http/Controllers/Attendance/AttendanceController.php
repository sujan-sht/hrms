<?php

namespace App\Modules\Api\Http\Controllers\Attendance;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Helpers\DateTimeHelper;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Modules\Leave\Entities\Leave;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use App\Modules\Shift\Entities\ShiftGroup;
use App\Modules\GeoFence\Entities\GeoFence;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Shift\Entities\EmployeeShift;
use App\Modules\Attendance\Entities\Attendance;
use App\Modules\Holiday\Entities\HolidayDetail;
use App\Modules\Shift\Entities\ShiftGroupMember;
use App\Modules\Attendance\Entities\AttendanceLog;
use App\Modules\Api\Http\Controllers\ApiController;
use App\Modules\NewShift\Entities\NewShiftEmployee;
use App\Modules\Shift\Repositories\ShiftRepository;
use App\Modules\Api\Transformers\AttendanceResource;
use App\Modules\Attendance\Entities\AttendanceRequest;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Shift\Repositories\ShiftGroupRepository;
use App\Modules\Api\Service\Attendance\AttendanceService;
use App\Modules\Shift\Repositories\EmployeeShiftRepository;
use App\Modules\Attendance\Repositories\AttendanceRepository;
use App\Modules\Attendance\Repositories\AttendanceLogRepository;
use App\Modules\Attendance\Repositories\AttendanceReportInterface;
use App\Modules\Attendance\Repositories\AttendanceReportRepository;

class AttendanceController extends ApiController
{
    protected $employee;
    protected $attendanceReport;

    public function __construct(EmployeeInterface $employee,AttendanceReportInterface $attendanceReport){
        $this->employee = $employee;
        $this->attendanceReport = $attendanceReport;

    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function overview(Request $request)
    {
        $filter = $request->all();
        try {

            $calendar_type = $request->calendar_type;
            $data['field'] = 'nepali_date';

            if (!$calendar_type) {
                return $this->respondInvalidParameters('Calendar Type is required');
            }


            if(isset($request->employee_id)){
                $emp = $this->employee->find($request->employee_id);
            }else{
                $emp = auth()->user()->userEmployer;
            }
            if (isset($calendar_type)) {
                $year = $calendar_type == 'eng' ? $request->eng_year : $request->nep_year;
                $month = $calendar_type == 'eng' ? $request->eng_month : $request->nep_month;

                $data['field'] = $calendar_type == 'eng' ? 'date' : 'nepali_date';
                $data['year'] = $year;
                $data['month'] = $month;
                $data['calendarType'] = $calendar_type;

                if (!$year || !$month) {
                    return $this->respondInvalidParameters('Year and Month is required');
                }


                if ($calendar_type == 'nep') {
                    $data['days'] = (new DateConverter())->getTotalDaysInMonth($year, $month);
                } else {
                    $data['days'] = Carbon::parse($year . '-' . $month)->daysInMonth;
                }

                $days = [];
                $k = 0;
                for ($i = 1; $i <= $data['days']; $i++) {
                    $fulldate = $data['year'] . '-' . sprintf("%02d", $data['month']) . '-' . sprintf("%02d", $i);
                    $dayDate = $fulldate;
                    if ($data['calendarType'] == 'nep') {
                        $dayDate = date_converter()->nep_to_eng_convert($dayDate);
                    }
                    $status = (new AttendanceReportRepository())->checkStatus($emp, $data['field'], $fulldate);
                    $days[$k] = [
                        'id' => $i,
                        'date' => $fulldate,
                        'day' => date('D', strtotime($dayDate)),
                        'status' => $status,
                        'total_working_hr' => '',
                        'checkin' => '',
                        'checkout' => '',
                    ];

                    $atd =  $emp->getSingleAttendance($data['field'], $fulldate);

                    if ($atd) {
                        $days[$k]['checkin'] = !is_null($atd->checkin) ? date('h.i A', strtotime($atd->checkin)) : '';
                        $days[$k]['checkout'] = !is_null($atd->checkout) ? date('h.i A', strtotime($atd->checkout)) : '';
                        $days[$k]['total_working_hr'] = $atd->total_working_hr ? $atd->total_working_hr : '';
                    }
                    $k++;
                }

                return $this->respond([
                    'status' => true,
                    'data' => $days
                ]);
            }
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function getTodayAttendance()
    {
        try {
            // $now = Carbon::now()->toDateString();
            // $activeUserModel = Auth::user();

            // $inputParam = [
            //     'emp_id' => $activeUserModel->emp_id,
            //     'date' => $now
            // ];
            // $getTodayAtd['date']=
            // $getTodayAtd['checkin'] = AttendanceLog::where($inputParam)->min('time');
            //  $checkOut = AttendanceLog::where($inputParam)->max('time');

            // $shiftCheckPoint = '14:00';
            // $shiftInfo = (new EmployeeShiftRepository())->findOne(['employee_id' => $activeUserModel->emp_id, 'days' => date('D', strtotime($now))]);
            // $getTodayAtd['checkout'] = '';
            // if (!empty($shiftInfo)) {
            //     $shiftCheckPoint = optional($shiftInfo->getShift)->getCheckpoint();
            //     if ($shiftCheckPoint < $checkOut) {
            //         $getTodayAtd['checkout'] = $checkOut;
            //     }
            // } elseif ($shiftCheckPoint < $checkOut) {
            //     $getTodayAtd['checkout'] = $checkOut;
            // }



            // dd($getTodayAtd);
            // $now = Carbon::now()->toDateString();
            // $activeUserModel = Auth::user();

            // if ($activeUserModel->user_type == 'employee') {
            //     $getLatestAtdLog = AttendanceLog::where([
            //         'emp_id' => $activeUserModel->emp_id,
            //         'date' => $now
            //     ])->latest()->get();

            //     $atdArray = Attendance::where([
            //         'emp_id' => $activeUserModel->emp_id,
            //         'date' => $now
            //     ])->get()->map(function ($atd) use ($getLatestAtdLog) {
            //         if (isset($getLatestAtdLog) && !empty($getLatestAtdLog)) {
            //             $atd->inout_mode = $getLatestAtdLog[0]['inout_mode'];
            //         }
            //         // $atd->log = $getLatestAtdLog->toArray();
            //         return $atd;
            //     });
            //     $data = [];
            //     if ($atdArray->isNotEmpty()) {
            //         $data = $atdArray[0];
            //     }
            // }
            $data = (new AttendanceLogRepository())->getTodayCheckInOut();

            return $this->respond([
                'status' => true,
                'data' => $data
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    // App Attendance Hit
    public function store(Request $request)
    {

        try {
            $inputData = ($request->except(['type']));

            $validate = Validator::make(
                $request->all(),
                [
                    'long' => 'required',
                    'lat' => 'required',
                    'type' => 'required',
                ]
            );

            if ($validate->fails()) {
                return $this->respondValidatorFailed($validate);
            }

            $geofenceStatus = false;
            $geofences = GeoFence::whereHas('geofenceAllocation', function ($query) {
                $query->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
                $query->where('department_id', optional(auth()->user()->userEmployer)->department_id);
                $query->whereJsonContains('employee_id', (string)auth()->user()->emp_id);
                $query->orWhereNull('employee_id');
            })->distance($request->lat, $request->long)->get();

            if (!(count($geofences) > 0)) {
                $geofenceStatus = true;
            }

            foreach ($geofences as $key => $geofence) {
                $distanceInMetre = $geofence->distance * 1000;
                if ((float)$geofence->radius > $distanceInMetre) {
                    $geofenceStatus = true;
                }
            }

            if (!$geofenceStatus) {
                return $this->respond([
                    'status' => false,
                    'message' => 'You need to be within a Geofence location to ' . $request->type . '.Please get near the location and try again.',
                    'statusCode' => '400',
                ]);
            }
            $userModel = Auth::user();
            $employeeModel = $userModel->userEmployer;
            $attendanceDate = Carbon::now()->toDateString();
            $currentTime = Carbon::now()->toTimeString();

            // $employeeShift = optional(optional(ShiftGroupMember::where('group_member', $employeeModel->id)->orderBy('id', 'DESC')->first())->group)->shift;

            // $dayWiseShift = (new AttendanceRepository())->getDayWiseShift($employeeModel->id, $attendanceDate);
            // $newShiftEmp = NewShiftEmployee::getShiftEmployee($employeeModel->id, $attendanceDate);
            // $shiftSeason = $employeeShift->getShiftSeasonForDate($attendanceDate);
            // $seasonalShiftId = null;
            // if($shiftSeason){
            //     $seasonalShiftId = $shiftSeason->id;
            //     $day = date('D', strtotime($attendanceDate));
            //     $dayWiseShift = $employeeShift->getShiftDayWise($day,$seasonalShiftId);
            // }
            // if (isset($newShiftEmp)) {
            //     $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
            //     if (isset($rosterShift) && isset($rosterShift->shift_id) && ($rosterShift->shift_id != null)) {
            //         $employeeShift = (new ShiftRepository())->find($rosterShift->shift_id);
            //         if($employeeShift){
            //             $day = date('D', strtotime($attendanceDate));
            //             $dayWiseShift = $employeeShift->getShiftDayWise($day);
            //         }
            //     }
            // }
            // if($dayWiseShift){
            //     $inputData['is_next_day'] = 10;
            //     if ($currentTime < $dayWiseShift->checkin_start_time) {
            //         $convertedDate = Carbon::parse($attendanceDate);
            //         $attendanceDate = $convertedDate->subDay()->toDateString();
            //         $inputData['is_next_day'] = 11;
            //     }
            // }

            $shiftDetail=$this->attendanceReport->getActualEmployeeShift($employeeModel,$attendanceDate);

            $employeeShift = $shiftDetail['empActualShift'];
            $seasonalShiftId = $shiftDetail['seasonalShiftId'];
            if (isset($employeeShift)) {
                $day = date('D', strtotime($attendanceDate));
                $daywiseShift = $employeeShift->getShiftDayWise($day, $seasonalShiftId);
            }

            if($daywiseShift){
                // if(!is_null(optional($daywiseShift->shift)->is_multi_day_shift) &&  optional($daywiseShift->shift)->is_multi_day_shift == 1){     //for multi day shift
                    $dateChanged = false;
                    $value['is_next_day'] = 10;
                    $newShiftEmp = NewShiftEmployee::getShiftEmployee($employeeModel->id, $attendanceDate);
                    if (isset($newShiftEmp)) {
                        $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
                        // dd($rosterShift);
                        if (isset($rosterShift) && isset($rosterShift->shift_group_id) && ($rosterShift->shift_group_id != null)) {
                            $employeeShift = ShiftGroup::find($rosterShift->shift_group_id)->shift;
                            if($employeeShift){
                                $day = date('D', strtotime($attendanceDate));
                                $daywiseShift = $employeeShift->getShiftDayWise($day);
                            }
                        }elseif($rosterShift->type == 'D'){
                            $date = Carbon::parse($attendanceDate);
                            $previousDate = $date->subDay()->toDateString();
                            $newShiftEmp = NewShiftEmployee::getShiftEmployee($employeeModel->id, $previousDate);
                            if (isset($newShiftEmp)) {
                                $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
                                // dd($rosterShift);
                                if (isset($rosterShift) && isset($rosterShift->shift_group_id) && ($rosterShift->shift_group_id != null)) {
                                    $employeeShift = ShiftGroup::find($rosterShift->shift_group_id)->shift;
                                    if($employeeShift){
                                        $day = date('D', strtotime($attendanceDate));
                                        $daywiseShift = $employeeShift->getShiftDayWise($day);
                                    }
                                }
                            }

                        }
                    }
                    $is_multi_day_shift = optional($daywiseShift->shiftSeason)->is_multi_day_shift ?? 0;
                    if (strtotime($value['time']) < strtotime($daywiseShift->checkin_start_time)) {
                        if($is_multi_day_shift==1){
                            $convertedDate = Carbon::parse($attendanceDate);
                            $attendanceDate = $convertedDate->subDay()->toDateString();
                            $value['is_next_day'] = 11;
                        }else{
                            // do nothing
                        }
                    }
                   $inputData['date'] = $attendanceDate;

                    $inputData['inout_mode'] = 1;
                    if ($request->type == 'checkin') {
                        $inputData['inout_mode'] = 0;
                    }
            }

            // $inputData['date'] = $attendanceDate;

            // $inputData['inout_mode'] = 1;
            // if ($request->type == 'checkin') {
            //     $inputData['inout_mode'] = 0;

            //     // if ($start_time >= $currentTime) {
            //     //     return $this->respondUnauthorized('Check-in only from ' . $start_time);
            //     // }
            // }

            // // if ($request->type == 'checkout') {
            // //     $inputData['inout_mode'] = 1;
            // // }

            $inputData['emp_id'] = $userModel->emp_id;
            $inputData['biometric_emp_id'] = $employeeModel->biometric_id;
            $inputData['org_id'] = $employeeModel->organization_id;
            $inputData['time'] = $currentTime;

            $inputData['punch_from'] = 'app';
            // $inputData['verify_mode'] = 0;

            $atdLog = AttendanceLog::create($inputData);
            if (setting('real_time_app_atd') && setting('real_time_app_atd') == 11) {
                if(!empty($atdLog)){
                    (new AttendanceRepository())->saveAttendance($employeeModel, $inputData);
                }
            }

            $data = (new AttendanceLogRepository())->getTodayCheckInOut();
            return $this->respond([
                'status' => true,
                'message' => $request->type . ' Succesfully',
                'data' => $data
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function getDropdown()
    {
        try {
            // $atdService = new AttendanceService();
            $data['typeList'] = setObjectIdAndName(AttendanceRequest::Types);
            $data['statusList'] = setObjectIdAndName(AttendanceRequest::PRE_STATUS);
            $data['kindList'] = setObjectIdAndName(AttendanceRequest::Kinds);
            $data['employeeList'] = setObjectIdAndName($this->employee->getList());

            return  $this->respond(['data' => $data]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }
    public function summary(Request $request)
    {
        $filter = $request->all();
        try {
            $calendar_type = $request->calendar_type;
            $input['field'] = 'nepali_date';

            if (!$calendar_type) {
                return $this->respondInvalidParameters('Calendar Type is required');
            }

            $userModel = auth()->user();
            $emp = auth()->user()->userEmployer;
            if (isset($calendar_type)) {
                $year = $calendar_type == 'eng' ? $request->eng_year : $request->nep_year;
                $month = $calendar_type == 'eng' ? $request->eng_month : $request->nep_month;

                $input['field'] = $calendar_type == 'eng' ? 'date' : 'nepali_date';
                $input['year'] = $year;
                $input['month'] = $month;
                $input['calendarType'] = $calendar_type;

                if (!$year || !$month) {
                    return $this->respondInvalidParameters('Year and Month is required');
                }
                if ($calendar_type == 'nep') {
                    $input['days'] = (new DateConverter())->getTotalDaysInMonth($year, $month);
                } else {
                    $input['days'] = Carbon::parse($year . '-' . $month)->daysInMonth;
                }


                $data['attendanceSummary'] = $this->getEmployeeAttendance($input);
                return $this->respond([
                    'status' => true,
                    'data' => $data
                ]);
            }
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    public function getCalenderFilter()
    {
        try {
            // $atdService = new AttendanceService();
            $data['calendar_type'] = setObjectIdAndName(['eng' => 'English', 'nep' => 'Nepali']);
            $data['eng_years'] = setObjectIdAndName(date_converter()->getEngYears());
            $data['nep_years'] = setObjectIdAndName(date_converter()->getNepYears());
            $data['eng_months'] = setObjectIdAndName(date_converter()->getEngMonths());
            $data['nep_months'] = setObjectIdAndName(date_converter()->getNepMonths());

            return $this->respond([
                'status' => true,
                'data' => $data
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    public function viewCalendar(Request $request)
    {
        try {
            $year = Carbon::now()->format('Y');
            $month = Carbon::now()->format('m');
            $filter['emp_id'] = [auth()->user()->emp_id];
            $data['filter'] = $filter;
            $data['show'] = true;
            $data['field'] = 'date';
            $data['year'] = $year;
            $data['month'] = $month;
            $data['calendarType'] = 'eng';
            $data['days'] = Carbon::parse($data['year'] . '-' . $data['month'])->daysInMonth;
            if($request->year && $request->month){
                $data['year'] = $request->year;
                $data['month'] = $request->month;
            }
            // dd( $data['year'] .  $data['month']);

            $emps = (new AttendanceReportRepository())->employeeAttendance($data, $filter, 20, $type = null);
            foreach ($emps as $emp) {
                foreach ($emp->date as $key => $value) {

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
                    $attendanceArray[] = [
                        'title' => $value['status'],
                        'start' => $key,
                        'color' => $color,
                    ];
                }
            }

            return $this->respond([
                'status' => true,
                'data' => $attendanceArray
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
        // dd($request)

        // dd($attendanceArray);
        // dd($year);

    }

    public function getEmployeeAttendance($data)
    {
        $userModel = auth()->user();
        $emp = auth()->user()->userEmployer;
        $year = $data['year'];
        $month = $data['month'];
        $dayoff = 0;
        $totalWorkingHours = 0;
        $dayOffs = $emp->employeeDayOff->pluck('day_off')->toArray();

        for ($i = 1; $i <= $data['days']; $i++) {
            $fulldate = $year . '-' . $month . '-' . sprintf("%02d", $i);
            $status = (new AttendanceReportRepository())->checkStatus($emp, $data['field'], $fulldate);

            if ($data['field'] == 'nepali_date') {
                $fulldate = date_converter()->nep_to_eng_convert($fulldate);
            }
            // $dayoff += $emp->dayoff == date('l', strtotime($fulldate)) ? 1 : 0;
            $dayoff += in_array(date('l', strtotime($fulldate)), $dayOffs) ? 1 : 0;

            //calc for total working hrs
            if($status != 'D' && $status != 'H'){
                $day = date('D', strtotime($fulldate));
                $empShift = optional(optional(ShiftGroupMember::where('group_member', $emp->id)->orderBy('id', 'DESC')->first())->group)->shift;
                $newShiftEmp = NewShiftEmployee::getShiftEmployee($emp->id, $fulldate);
                if (isset($newShiftEmp)) {
                    $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
                    if (isset($rosterShift) && isset($rosterShift->shift_group_id)) {
                        $empShift = optional((new ShiftGroupRepository())->find($rosterShift->shift_group_id)->shift);
                    }
                }
                $shiftSeason = $empShift->getShiftSeasonForDate($fulldate);
                $seasonalShiftId = null;
                if($shiftSeason){
                    $seasonalShiftId = $shiftSeason->id;
                }
                // $dailyWorkingHours = isset($empShift) ? (strtotime(optional($empShift->getShiftDayWise($day, $seasonalShiftId))->end_time) - strtotime(optional($empShift->getShiftDayWise($day, $seasonalShiftId))->start_time)) / 3600 : 8;
                $dailyWorkingHours = isset($empShift) ? DateTimeHelper::getTimeDiff(optional($empShift->getShiftDayWise($day, $seasonalShiftId))->start_time, optional($empShift->getShiftDayWise($day, $seasonalShiftId))->end_time) : 8;

                $totalWorkingHours += $dailyWorkingHours;
            }
        }

        $leaveModel =  Leave::where('employee_id', $emp->id)->whereYear($data['field'], $year)->whereMonth($data['field'], $month)->where('status', '!=', 4);
        $empData['worked_days'] = Attendance::where('emp_id', $emp->id)->whereYear($data['field'], $year)->whereMonth($data['field'], $month)->count() - $leaveModel->where('leave_kind', 1)->get()->sum('day');
        $empData['leave_taken'] =  Leave::where('employee_id', $emp->id)->whereYear($data['field'], $year)->whereMonth($data['field'], $month)->where('status', '!=', 4)->count();
        $empData['week_offs'] = $dayoff;

        if ($data['field'] == 'date') {
            $field = 'eng_date';
        } elseif ($data['field'] == 'nepali_date') {
            $field = 'nep_date';
        }
        $empData['public_holiday'] = HolidayDetail::whereYear($field, $year)
            ->whereMonth($field, $month)
            ->whereHas('holiday', function ($query) use ($emp) {
                $query->where('status', 11);
                $query->GetEmployeeWiseHoliday($emp, true, true);
            })
            ->count();
        $empData['total_days'] = $data['days'];
        $empData['working_days'] = $data['days'] - $dayoff - $emp->public_holiday;
        $empData['working_hour'] = $totalWorkingHours;
        $empData['worked_hour'] = Attendance::where('emp_id', $emp->id)->whereYear($data['field'], $year)->whereMonth($data['field'], $month)->sum('total_working_hr');
        $empData['worked_hour'] = round($empData['worked_hour'], 2);
        // $empData['worked_hour'] = round((float)$empData['worked_hour'], 2);

        $empData['paid_leave_taken'] = Leave::where('employee_id', $emp->id)->whereYear($data['field'], $year)->whereMonth($data['field'], $month)->where('status', '!=', 4)->whereHas('leaveTypeModel', function ($query) {
            $query->where('leave_type', 10);
        })->get()->sum('day');
        $empData['unpaid_leave_taken'] = Leave::where('employee_id', $emp->id)->whereYear($data['field'], $year)->whereMonth($data['field'], $month)->where('status', '!=', 4)->whereHas('leaveTypeModel', function ($query) {
            $query->where('leave_type', 11);
        })->get()->sum('day');

        return $empData;
    }
}
