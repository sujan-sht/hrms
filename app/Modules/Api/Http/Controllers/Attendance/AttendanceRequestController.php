<?php

namespace App\Modules\Api\Http\Controllers\Attendance;

use Illuminate\Http\Request;
use App\Helpers\DateTimeHelper;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use App\Modules\Setting\Entities\Setting;
use Illuminate\Support\Facades\Validator;
use App\Modules\Employee\Entities\Employee;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Shift\Entities\EmployeeShift;
use Illuminate\Validation\ValidationException;
use App\Modules\Attendance\Entities\Attendance;
use App\Modules\Shift\Entities\ShiftGroupMember;
use App\Modules\Attendance\Entities\AttendanceLog;
use App\Modules\Api\Http\Controllers\ApiController;
use App\Modules\NewShift\Entities\NewShiftEmployee;
use App\Modules\Shift\Repositories\ShiftRepository;
use App\Modules\Attendance\Jobs\AttendanceRequestJob;
use App\Modules\Attendance\Entities\AttendanceRequest;
use App\Modules\Employee\Entities\EmployeeApprovalFlow;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Shift\Repositories\ShiftGroupRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\Shift\Repositories\EmployeeShiftInterface;
use App\Modules\Api\Transformers\AttendanceRequestResource;
use App\Modules\Attendance\Repositories\AttendanceInterface;
use App\Modules\Attendance\Entities\DivisionAttendanceReport;
use App\Modules\Attendance\Repositories\AttendanceRequestInterface;
use App\Modules\Attendance\Repositories\AttendanceRequestRepository;

class AttendanceRequestController extends ApiController
{
    protected $attendanceRequest;
    protected $attendance;
    protected $employees;
    protected $employeeShift;


    public function __construct(
        AttendanceRequestInterface $attendanceRequest,
        EmployeeInterface $employees,
        AttendanceInterface $attendance,
        EmployeeShiftInterface $employeeShift
    ) {
        $this->attendanceRequest = $attendanceRequest;
        $this->employees = $employees;
        $this->attendance = $attendance;
        $this->employeeShift = $employeeShift;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        // $filter = $request->all();
        // $user = auth()->user();

        // try {
        //     $atdRequests = AttendanceRequest::when(true, function ($query) use ($filter,$user) {
        //         if (isset($filter['from_date']) && !empty($filter['from_date'])) {
        //             $query->where('date', '>=', $filter['from_date']);
        //         }

        //         if (isset($filter['to_date']) && !empty($filter['to_date'])) {
        //             $query->where('date', '<=', $filter['to_date']);
        //         }

        //         if (isset($filter['type']) && $filter['type'] != '') {
        //             $query = $query->where('type', $filter['type']);
        //         }

        //         if (isset($filter['status']) && $filter['status'] != '') {
        //             $query = $query->where('status', $filter['status']);
        //         }

        //         if (isset($filter['organization_id']) && $filter['organization_id'] != '') {
        //             $query->whereHas('employee', function ($q) use ($filter) {
        //                 $q->where('organization_id', $filter['organization_id']);
        //             });
        //         }

        //         if (isset($filter['branch_id']) && $filter['branch_id'] != '') {
        //             $query->whereHas('employee', function ($q) use ($filter) {
        //                 $q->where('branch_id', $filter['branch_id']);
        //             });
        //         }

        //         if (isset($filter['employee_id']) && $filter['employee_id'] != '') {
        //             $query->where('employee_id', $filter['employee_id']);
        //         }

        //         if($user->user_type == 'hr') {
        //             if (isset($filter['is_self']) && $filter['is_self'] == '1') {
        //                 $query->where('employee_id', $user->emp_id);
        //             }else{
        //                 $query->where('employee_id','!=' ,$user->emp_id);
        //             }
        //         }elseif ($user->user_type == 'division_hr') {
        //             if (isset($filter['is_self']) && $filter['is_self'] == '1') {
        //                 $query->where('employee_id', $user->emp_id);
        //             }else{
        //                 $query->whereHas('employee', function ($q) use($user){
        //                     $q->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
        //                 })->where('employee_id','!=' ,$user->emp_id);
        //             }
        //         }elseif ($user->user_type == 'supervisor') {
        //             if (isset($filter['is_self']) && $filter['is_self'] == '1') {
        //                 $query->where('employee_id', $user->emp_id);
        //             }else{
        //                 $firstApprovalEmps = EmployeeApprovalFlow::where('first_approval_user_id', $user->id)->pluck('last_approval_user_id', 'employee_id')->toArray();
        //                 $lastApprovalEmps = EmployeeApprovalFlow::where('last_approval_user_id', $user->id)->pluck('last_approval_user_id', 'employee_id')->toArray();

        //                 $query->where(function ($q)use($firstApprovalEmps){
        //                     $q->whereIn('employee_id', array_keys($firstApprovalEmps))->where('status', [1,2]);
        //                 });

        //                 $query->orWhere(function ($q)use($lastApprovalEmps){
        //                     $q->whereIn('employee_id', array_keys($lastApprovalEmps))->whereIn('status', [2,3]);
        //                 });
        //             }
        //         }else {
        //             $query->where(['employee_id' => $user->emp_id]);
        //         }
        //     })
        //         ->get();

        //     return  $this->respond([
        //         'status' => true,
        //         'data' => AttendanceRequestResource::collection($atdRequests)
        //     ]);
        // } catch (QueryException $e) {
        //     return $this->respondInvalidQuery($e->getMessage());
        // }

        $filter = $request->all();
        try {
            if (isset($filter['is_self']) && !empty($filter['is_self'])) {
                $atdRequests = $this->getAttendanceRequestList($filter);
            } else {
                $authUser = auth()->user();

                if ($authUser->user_type == 'division_hr') {
                    $filter['organization_id'] = optional($authUser->userEmployer)->organization_id;
                    $atdRequests = $this->getAttendanceRequestList($filter);
                } elseif ($authUser->user_type == 'supervisor') {
                    $atdRequests = $this->teamRequests($filter);
                } elseif ($authUser->user_type == 'employee') {
                    $filter['employee_id'] = $authUser->emp_id;
                    $atdRequests = $this->getAttendanceRequestList($filter);
                } else {
                    $atdRequests = $this->getAttendanceRequestList($filter);
                }
            }
            $data = AttendanceRequestResource::collection($atdRequests);
            return  $this->respondSuccess($data);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    public function teamRequests($filter)
    {
        try {
            $statusList = AttendanceRequest::STATUS;
            $user = auth()->user();
            $userId = $user->id;
            $usertype = $user->user_type;

            $firstApprovalEmps = EmployeeApprovalFlow::where('first_approval_user_id', $userId)->pluck('last_approval_user_id', 'employee_id')->toArray();
            $firstApproval = AttendanceRequest::with('employee.employeeApprovalFlowRelatedDetailModel')->when(true, function ($query) use ($firstApprovalEmps, $user) {
                $query->whereHas('employee', function ($q) use ($user) {
                    $q->where('organization_id', optional($user->userEmployer)->organization_id);
                });

                $query->whereIn('employee_id', array_keys($firstApprovalEmps));
            })->get()->map(function ($approvals) use ($statusList, $usertype, $userId) {
                $approvalFlow = optional($approvals->employee)->employeeApprovalFlowRelatedDetailModel;
                if ($usertype == 'supervisor') {
                    if (!$approvalFlow->last_approval_user_id || $approvals->status == 1) {
                        unset($statusList[3]);
                    }

                    if ($approvalFlow->first_approval_user_id == $userId && $approvals->status == 2) {
                        unset($statusList[1], $statusList[3], $statusList[4]);
                    }
                    unset($statusList[5]);
                }
                $approvals->status_list = setObjectIdAndName($statusList);
                return $approvals;
            });
            $lastApprovalEmps = EmployeeApprovalFlow::where('last_approval_user_id', $userId)->select('first_approval_user_id', 'last_approval_user_id', 'employee_id')->get()->toArray();
            $lastApproval = [];
            if (count($lastApprovalEmps) > 0) {
                $lastApproval = AttendanceRequest::when(true, function ($query) use ($lastApprovalEmps, $user) {
                    $query->whereHas('employee', function ($q) use ($user) {
                        $q->where('organization_id', optional($user->userEmployer)->organization_id);
                    });
                    $where = 'where';
                    foreach ($lastApprovalEmps as $value) {

                        $query->$where(function ($query) use ($value, $where) {
                            $query->where('employee_id', $value['employee_id']);
                            if (is_null($value['first_approval_user_id'])) {
                                $query->whereIn('status', [1, 2, 3, 4, 5]);
                            } else {
                                $query->whereIn('status', [2, 3, 4, 5]);
                            }
                        });
                        $where = 'orWhere';
                    }
                })->get()->map(function ($approvals) use ($statusList, $usertype, $userId) {
                    $approvalFlow = optional($approvals->employee)->employeeApprovalFlowRelatedDetailModel;
                    if ($usertype == 'supervisor') {
                        if ($approvals->status == 1) {
                            if (isset($approvalFlow->first_approval_user_id) && $approvalFlow->first_approval_user_id == $userId) {
                                unset($statusList[3]);
                            } elseif (isset($approvalFlow->last_approval_user_id) && $approvalFlow->last_approval_user_id == $userId) {
                                unset($statusList[2]);
                            }
                        } elseif ($approvals->status == 2) {
                            if (isset($approvalFlow->first_approval_user_id) && $approvalFlow->first_approval_user_id == $userId) {
                                unset($statusList[1], $statusList[3], $statusList[4]);
                            } elseif (isset($approvalFlow->last_approval_user_id) && $approvalFlow->last_approval_user_id == $userId) {
                                unset($statusList[1]);
                            }
                        }
                        unset($statusList[5]);
                    }
                    $approvals->status_list = setObjectIdAndName($statusList);
                    return $approvals;
                });
            }

            $mergeApproval = $firstApproval->merge($lastApproval)->sortByDesc('id');
            $myCollectionObj = collect($mergeApproval);
            $result = $myCollectionObj;

            if (isset($filter['date_range'])) {
                $filterDates = explode(' - ', $filter['date_range']);
                $result = $result->where('date', '>=', $filterDates[0]);
                $result = $result->where('date', '<=', $filterDates[1]);
            }
            if (isset($filter['type']) && !empty($filter['type'])) {
                $result = $result->where('type', $filter['type']);
            }
            if (isset($filter['status']) && !empty($filter['status'])) {
                $result = $result->where('status', $filter['status']);
            }
            return $result;
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = ($request->all());
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    // 'date' => 'required',
                    // 'time' => 'required',
                    'type' => 'required',
                    'detail' => 'required',
                ]
            );

            if ($validateUser->fails()) {
                return $this->respondValidatorFailed($validateUser);
            }

            // $data['start_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['start_date']) : $data['start_date'];
            $setting = Setting::first();

            $userModel = auth()->user();
            $data['employee_id'] = $userModel->emp_id;
            $employeeModel = Employee::find($data['employee_id']);

            if ($data['type'] == '5' || $data['type'] == '6' || $data['type'] == '7') {
                // $data['end_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['end_date']) : $data['end_date'];

                $inputData = $data;
                $inputData['time'] = null;
                $parentId = null;
                $tempDate = $data['start_date'];
                $existingCount = 0;

                $days = DateTimeHelper::DateDiffInDay($data['start_date'], $data['end_date']);
                $days += 1;
                if ($days > 0) {
                    for ($i = 1; $i <= $days; $i++) {
                        $inputData['date'] = $tempDate;
                        $inputData['nepali_date'] = date_converter()->eng_to_nep_convert($tempDate);
                        $inputData['created_by'] = auth()->user()->id;

                        $checkData = [
                            'date' => $tempDate,
                            'empId' => $data['employee_id'],
                            'requestType' => $data['type'],
                        ];
                        $alreadyExist = $this->attendanceRequest->checkRequestExists($checkData);
                        if ($alreadyExist) {
                            $existingCount++;
                        } else {
                            $inputData['parent_id'] = $parentId;
                            $attendance = $this->attendanceRequest->save($inputData);
                            if ($parentId == null) {
                                $parentId = $attendance->id;
                            }
                            $tempDate = date('Y-m-d', strtotime('+1 day', strtotime($tempDate)));
                        }
                    }
                    if (isset($attendance)) {
                        $attendance['enable_mail'] = $setting->enable_mail;
                        // $this->attendanceRequest->sendMailNotification($attendance);
                        AttendanceRequestJob::dispatch($attendance, auth()->user());


                        if (!optional($employeeModel->getUser)->id) {
                            return $this->respondUnauthorized('We could not send Attendance Request Notification and Email to ' . $employeeModel->full_name . '. So please create user access for the employee.', 400);
                        }
                    } else {
                        return $this->respondUnauthorized('Attendance Request for this date already exists.', 400);
                    }
                }
            } else {
                $data['date'] = $data['start_date'];
                $data['nepali_date'] = date_converter()->eng_to_nep_convert($data['start_date']);
                $data['created_by'] = auth()->user()->id;
                $data['kind'] = null;
                $data['end_date'] = null;

                $userModel = auth()->user();
                $data['employee_id'] = $userModel->emp_id;

                $checkData = [
                    'date' => $data['start_date'],
                    'empId' => $data['employee_id'],
                    'requestType' => $data['type'],
                ];
                $alreadyExist = $this->attendanceRequest->checkRequestExists($checkData);

                if ($alreadyExist) {
                    return $this->respondUnauthorized('Attendance Request for this date already exists.', 400);
                }

                $attendance = $this->attendanceRequest->save($data);
                if (isset($attendance)) {
                    $attendance['enable_mail'] = $setting->enable_mail;
                    // $this->attendanceRequest->sendMailNotification($attendance);
                    AttendanceRequestJob::dispatch($attendance, auth()->user());

                    if (!optional($employeeModel->getUser)->id) {
                        return $this->respondUnauthorized('We could not send Attendance Request Notification and Email to ' . $employeeModel->full_name . '. So please create user access for the employee.', 400);
                    }
                }
            }
            return  $this->respond([
                'status' => true,
                'data' => new AttendanceRequestResource($attendance)
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound($e->getMessage());
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function view($id)
    {
        try {
            $atdRequest = AttendanceRequest::findOrFail($id);
            return  $this->respond([
                'status' => true,
                'data' => new AttendanceRequestResource($atdRequest)
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    public function edit(Request $request, $id)
    {
        $inputData = ($request->all());
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'date' => 'required',
                    // 'time' => 'required',
                    'type' => 'required',
                    'detail' => 'required',
                ]
            );

            if ($validateUser->fails()) {
                return $this->respondValidatorFailed($validateUser);
            }
            $setting = Setting::first();
            $userModel = auth()->user();
            $inputData['employee_id'] = $userModel->emp_id;
            $employeeModel = Employee::find($inputData['employee_id']);

            $inputData['nepali_date'] = date_converter()->eng_to_nep_convert($inputData['date']);
            $inputData['updated_by'] = auth()->user()->id;
            $inputData['status'] = 1;


            if ($inputData['type'] == '5' || $inputData['type'] == '6' || $inputData['type'] == '7') {
                $inputData['time'] = null;
            } else {
                $inputData['kind'] = null;
            }
            $atdRequest = AttendanceRequest::find($id);


            $atdRequest->update($inputData);

            return  $this->respond([
                'status' => true,
                'message' => 'Attendance Request Updated Succesfully',
                'data' => new AttendanceRequestResource($atdRequest)
            ]);

            if (isset($atdRequest)) {
                $atdRequest['enable_mail'] = $setting->enable_mail;
                // $this->attendanceRequest->sendMailNotification($atdRequest);
                AttendanceRequestJob::dispatch($atdRequest, auth()->user());

                if (!optional($employeeModel->getUser)->id) {
                    return $this->respondUnauthorized('We could not send Attendance Request Notification and Email to ' . $employeeModel->full_name . '. So please create user access for the employee.', 400);
                }
            }
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound($e->getMessage());
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $checkinType = ['Missed Check In', 'Late Arrival Request'];
            $checkoutType = ['Missed Check Out', 'Early Departure Request'];
            $extraType = ['Out Door Duty Request', 'Work From Home Request'];
            $approvedStatus = 3;
            $data['status'] = $request->status;
            $data['rejected_remarks'] = $request->rejected_remarks;
            if ($request->status == 3) {
                $data['approved_by'] = Auth::user()->id;
            }
            // $employeeShift = EmployeeShift::where('employee_id',$request->employee_id)->orderBy('id', 'DESC')->first();

            $status = [1, 2, 3]; //except rejected and cancelled
            $alreadyExist = AttendanceRequest::where('date', $request['date'])->where('employee_id', $request['employee_id'])->where('type', $request['type'])->whereIn('status', $status)->where('id', '!=', $request->id)->exists();

            if ($alreadyExist) {
                return $this->respondUnauthorized('Attendance Request for this date already exists.', 400);
            }

            $attendanceRequest = $this->attendanceRequest->find($request->id);
            $attendanceRequest['status'] = $request->status;
            $attendanceExist = $this->attendance->employeeAttendanceExists($attendanceRequest->employee_id, $attendanceRequest->date);
            $employee = $this->employees->find($attendanceRequest->employee_id);
             //checkin and Approved
             if (in_array($attendanceRequest->getType(), $checkinType) && $request->status == $approvedStatus) {
                $late_arrival_in_minutes = $this->attendance->getLateArrivalEarlyDepartureData($employee, $attendanceRequest->date, $attendanceRequest->time, 'checkin')['lateArrival'];

                if ($attendanceExist) {
                    //Update Checkin Time
                    $attendanceExist->fill(['checkin' => $attendanceRequest->time, 'checkin_from' => 'request', 'late_arrival_in_minutes' => $late_arrival_in_minutes]);

                    if ($attendanceExist->checkout) {
                        $attendanceExist->fill(['total_working_hr' => DateTimeHelper::getTimeDiff(date('H:i', strtotime($attendanceRequest->time)), date('H:i', strtotime($attendanceExist->checkout)))]);
                    }
                    $attendanceExist->update();
                    // $saveAtd = $attendanceExist;
                } else {
                    //Create Attendance with Checkin Type
                    $this->attendance->save([
                        'org_id' => optional($employee->organizationModel)->id,
                        'emp_id' => $attendanceRequest->employee_id,
                        'date' => $attendanceRequest->date,
                        'nepali_date' => date_converter()->eng_to_nep_convert($attendanceRequest->date),
                        'checkin' => $attendanceRequest->time,
                        'checkin_from' => 'request',
                        // 'total_working_hr' => DateTimeHelper::getTimeDiff($attendanceRequest->checkin, $attendanceRequest->checkout)
                        'total_working_hr' => null,
                        'late_arrival_in_minutes' => $late_arrival_in_minutes
                    ]);
                }


            }

            //checkout and Approved
            if (in_array($attendanceRequest->getType(), $checkoutType) && $request->status == $approvedStatus) {

                $employeeShift = optional(optional(ShiftGroupMember::where('group_member', $attendanceRequest->employee_id)->orderBy('id', 'DESC')->first())->group)->shift;
                if (isset($employeeShift)) {
                    $shiftSeason = $employeeShift->getShiftSeasonForDate($attendanceRequest->date);
                    $seasonalShiftId = null;
                    if($shiftSeason){
                        $seasonalShiftId = $shiftSeason->id;
                    }
                    $day = date('D', strtotime($attendanceRequest->date));
                    $daywiseShift = $employeeShift->getShiftDayWise($day, $seasonalShiftId);
                }else{
                    $daywiseShift = $this->attendance->getDayWiseShift($attendanceRequest->employee_id, $attendanceRequest->date);
                }

                if($daywiseShift->start_time > $daywiseShift->end_time){
                    $attendanceRequest->date = Carbon::parse($attendanceRequest->date)->subDay()->format('Y-m-d');
                    $attendanceExist = $this->attendance->employeeAttendanceExists($attendanceRequest->employee_id, $attendanceRequest->date);
                }

                $early_departure_in_minutes = $this->attendance->getLateArrivalEarlyDepartureData($employee, $attendanceRequest->date, $attendanceRequest->time, 'checkout')['earlyDeparture'];



                if ($attendanceExist) {
                    //Update Checkout Time
                    $attendanceExist->fill(['checkout' => $attendanceRequest->time, 'checkout_from' => 'request', 'total_working_hr' => DateTimeHelper::getTimeDiff(date('H:i', strtotime($attendanceExist->checkin)), date('H:i', strtotime($attendanceRequest->time))), 'early_departure_in_minutes' => $early_departure_in_minutes]);

                    $attendanceExist->update();
                    // $saveAtd = $attendanceExist;
                } else {
                    //Create Attendance with Checkout Type
                    $this->attendance->save([
                        'org_id' => optional($employee->organizationModel)->id,
                        'emp_id' => $attendanceRequest->employee_id,
                        'date' => $attendanceRequest->date,
                        'nepali_date' => date_converter()->eng_to_nep_convert($attendanceRequest->date),
                        'checkout' => $attendanceRequest->time,
                        'checkout_from' => 'request',
                        'total_working_hr' => null,
                        'early_departure_in_minutes' => $early_departure_in_minutes
                    ]);
                }
            }

            //extraType and Approved
            if (in_array($attendanceRequest->getType(), $extraType) && $request->status == $approvedStatus) {
                $day = date('D', strtotime($attendanceRequest->date));

                $employeeShift = $this->employeeShift->findOne(['employee_id' => $request->employee_id, 'days' => date('D', strtotime($attendanceRequest->date))]);
                $shiftInfo = optional($employeeShift->getShift);

                $newShiftEmp = NewShiftEmployee::getShiftEmployee($request->employee_id, $attendanceRequest->date);
                if (isset($newShiftEmp)) {
                    $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
                    if (isset($rosterShift) && isset($rosterShift->shift_id)) {
                        $shiftInfo = (new ShiftRepository())->find($rosterShift->shift_id);
                    }
                }

                if ($shiftInfo) {
                    $shiftSeason = $shiftInfo->getShiftSeasonForDate($attendanceRequest->date);
                    $seasonalShiftId = null;
                    if ($shiftSeason) {
                        $seasonalShiftId = $shiftSeason->id;
                    }
                    $checkinTime = optional($shiftInfo->getShiftDayWise($day, $seasonalShiftId))->start_time;
                    $firstHalfEnd = optional($shiftInfo->getShiftDayWise($day, $seasonalShiftId))->getCheckpoint();
                    $secondHalfStart = (date('H:i', strtotime(intval('+' . 1) . 'minutes', strtotime(optional($shiftInfo->getShiftDayWise($day, $seasonalShiftId))->getCheckpoint()))));
                    $checkoutTime = optional($shiftInfo->getShiftDayWise($day, $seasonalShiftId))->end_time;
                } else {
                    $checkinTime = '09:00';
                    $firstHalfEnd = '14:00';
                    $secondHalfStart = '14:01';
                    $checkoutTime = '18:00';
                }

                if (isset($attendanceRequest['kind'])) {
                    if ($attendanceRequest['kind'] == 1) {
                        $checkin = $checkinTime;
                        $checkout = $firstHalfEnd;
                    } elseif ($attendanceRequest['kind'] == 2) {
                        $checkin = $secondHalfStart;
                        $checkout = $checkoutTime;
                    } elseif ($attendanceRequest['kind'] == 3) {
                        $checkin = $checkinTime;
                        $checkout = $checkoutTime;
                    }
                }
                $late_arrival_in_minutes = $this->attendance->getLateArrivalEarlyDepartureData($employee, $attendanceRequest->date, $checkin, 'checkin')['lateArrival'];
                $early_departure_in_minutes = $this->attendance->getLateArrivalEarlyDepartureData($employee, $attendanceRequest->date, $checkout, 'checkout')['earlyDeparture'];

                if ($attendanceExist) {
                    //Update Checkin Time
                    $attendanceExist->fill([
                        'checkin' => $checkin,
                        'checkout' => $checkout,
                        'checkin_from' => 'request',
                        'checkout_from' => 'request',
                        'total_working_hr' => DateTimeHelper::getTimeDiff(date('H:i', strtotime($checkin)), date('H:i', strtotime($checkout))),
                        'late_arrival_in_minutes' => $late_arrival_in_minutes,
                        'early_departure_in_minutes' => $early_departure_in_minutes
                    ]);

                    $attendanceExist->update();
                } else {
                    $this->attendance->save([
                        'org_id' => optional($employee->organizationModel)->id,
                        'emp_id' => $attendanceRequest->employee_id,
                        'date' => $attendanceRequest->date,
                        'nepali_date' => date_converter()->eng_to_nep_convert($attendanceRequest->date),
                        'checkin' => $checkin,
                        'checkout' => $checkout,
                        'checkin_from' => 'request',
                        'checkout_from' => 'request',
                        'total_working_hr' => DateTimeHelper::getTimeDiff(date('H:i', strtotime($checkin)), date('H:i', strtotime($checkout))),
                        'late_arrival_in_minutes' => $late_arrival_in_minutes,
                        'early_departure_in_minutes' => $early_departure_in_minutes
                    ]);
                }
            }
            if ($attendanceRequest->type == 8 && $request->status == $approvedStatus) {

                Attendance::where('emp_id', $attendanceRequest->employee_id)->where('date', $attendanceRequest->date)->delete();
                AttendanceLog::where('biometric_emp_id', $employee->biometric_id)->where('date', $attendanceRequest->date)->delete();

                $divisionAtdReport = DivisionAttendanceReport::where('employee_id', $attendanceRequest->employee_id)->where('date', $attendanceRequest->date)->first();
                if (isset($divisionAtdReport) && !empty($divisionAtdReport)) {
                    $divisionAtdReport->update(['is_absent' => 11, 'checkin' => null, 'checkout' => null, 'worked_hr' => 0, 'ot_hr' => 0]);
                }
            }
            $this->attendanceRequest->update($request->id, $data);
            // send notification
            // $attendanceRequest = $this->attendanceRequest->find($request->id);
            $attendanceRequest['enable_mail'] = setting('enable_mail');
            // $this->attendanceRequest->sendMailNotification($attendanceRequest);
            AttendanceRequestJob::dispatch($attendanceRequest, auth()->user());

            return  $this->respond([
                'status' => true,
                'message' => 'Attendance Request Updated Succesfully'
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound($e->getMessage());
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    public function getAttendanceRequestList($filter)
    {
        return AttendanceRequest::when(true, function ($query) use ($filter) {
            if (isset($filter['employee_id']) && !empty($filter['employee_id'])) {
                $query->where('employee_id', $filter['employee_id']);
            }
            if (isset($filter['from_date']) && !empty($filter['from_date'])) {
                $query->where('date', '>=', $filter['from_date']);
            }
            if (isset($filter['to_date']) && !empty($filter['to_date'])) {
                $query->where('date', '<=', $filter['to_date']);
            }
            if (isset($filter['status']) && !empty($filter['status'])) {
                $query->where('status', $filter['status']);
            }

            if (isset($filter['type']) && $filter['type'] != '') {
                $query->where('type', $filter['type']);
            }
            if (isset($filter['is_self']) && !empty($filter['is_self'])) {
                $query->where('employee_id', auth()->user()->emp_id);
            }

            if (isset($filter['organization_id']) && !empty($filter['organization_id'])) {
                $query->whereHas('employee', function ($q) use ($filter) {
                    $q->where('organization_id', $filter['organization_id']);
                });
            }
            if (isset($filter['branch_id']) && !empty($filter['branch_id'])) {
                $query->whereHas('employee', function ($q) use ($filter) {
                    $q->where('branch_id', $filter['branch_id']);
                });
            }
        })
            // ->where('parent_id', null)
            ->orderBy('id', 'desc')->get()->map(function ($approvals) {
                $approvals->status_list = setObjectIdAndName(AttendanceRequest::STATUS);
                return $approvals;
            });
    }
}
