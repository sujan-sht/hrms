<?php

namespace App\Modules\Attendance\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Traits\LogTrait;
use Illuminate\Http\Request;
use App\Helpers\DateTimeHelper;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Modules\Leave\Entities\Leave;
use Illuminate\Support\Facades\Cache;
use App\Modules\Setting\Entities\Setting;
use App\Modules\Employee\Entities\Employee;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Shift\Entities\EmployeeShift;
use App\Modules\Attendance\Entities\Attendance;
use App\Modules\Shift\Entities\ShiftGroupMember;
use App\Modules\Attendance\Entities\AttendanceLog;
use App\Modules\NewShift\Entities\NewShiftEmployee;
use App\Modules\Shift\Repositories\ShiftRepository;
use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Attendance\Jobs\AttendanceRequestJob;
use App\Modules\Attendance\Entities\AttendanceRequest;
use App\Modules\Employee\Entities\EmployeeApprovalFlow;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Shift\Repositories\ShiftGroupRepository;
use App\Modules\Shift\Repositories\EmployeeShiftInterface;
use App\Modules\Attendance\Entities\AttendanceLockAttribute;
use App\Modules\Attendance\Repositories\AttendanceInterface;
use App\Modules\Attendance\Entities\DivisionAttendanceReport;
use App\Modules\Attendance\Entities\AttendanceOrganizationLock;
use App\Modules\Organization\Repositories\OrganizationInterface;

use App\Modules\Attendance\Entities\AttendanceSummaryVerification;
use App\Modules\Attendance\Repositories\AttendanceReportInterface;
use App\Modules\Attendance\Repositories\AttendanceRequestInterface;
use App\Modules\Attendance\Repositories\AttendanceRequestLinkInterface;

class AttendanceRequestController extends Controller
{
    use LogTrait;
    protected $attendanceRequest;
    protected $attendance;
    protected $employees;
    protected $organization;
    protected $attendanceRequestLink;
    protected $employeeShift;
    protected $attendanceLockAttribute;
    protected $branchObj;
    protected $attendanceReport;

    public function __construct(
        AttendanceRequestInterface $attendanceRequest,
        AttendanceLockAttribute $attendanceLockAttribute,
        EmployeeInterface $employees,
        AttendanceInterface $attendance,
        OrganizationInterface $organization,
        AttendanceRequestLinkInterface $attendanceRequestLink,
        EmployeeShiftInterface $employeeShift,
        BranchInterface $branchObj,
        AttendanceReportInterface $attendanceReport
    ) {
        $this->attendanceRequest = $attendanceRequest;
        $this->employees = $employees;
        $this->attendance = $attendance;
        $this->organization = $organization;
        $this->attendanceRequestLink = $attendanceRequestLink;
        $this->employeeShift = $employeeShift;
        $this->branchObj = $branchObj;
        $this->attendanceReport = $attendanceReport;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $data['filter'] = $filter = $request->all();
        // $filter['isParent'] = true;
        $filter['authUser'] = auth()->user();

        $sort = [
            'by' => 'id',
            'sort' => 'DESC'
        ];

        $data['employeeList'] = $this->employees->getList();
        $data['statusList'] =  $status = $this->attendanceRequest->getStatus();
        unset($data['statusList'][5]);
        if (in_array(auth()->user()->user_type, ['hr', 'division_hr'])) {
            unset($data['statusList'][2]);
        }
        if (auth()->user()->user_type == 'super_admin') {
            $data['statusList'] = Leave::statusList();
        }
        $data['allStatus'] = $status;
        $data['organizationList'] = $this->organization->getList();
        $data['branchList'] = $this->branchObj->getList();
        $data['requests'] = $this->attendanceRequest->findAll(25, $filter, $sort);
        $data['type'] = $this->attendanceRequest->getTypes();
        $data['kind'] = $this->attendanceRequest->getKinds();
        if (in_array(auth()->user()->user_type, ['super_admin', 'admin', 'hr'])) {
            array_push($data['type'], 'Mark As Absent');
        }
        return view('attendance::attendance-request.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        // $data['employees'] = $this->employees->findAll()->pluck('full_name', 'id');
        $data['employees'] = $this->employees->getList();
        $data['type'] = $this->attendanceRequest->getTypes();
        if (in_array(auth()->user()->user_type, ['supervisor', 'employee'])) {
            unset($data['type'][5]);
        }
        $data['kind'] = $this->attendanceRequest->getKinds();
        if (in_array(auth()->user()->user_type, ['super_admin', 'admin', 'hr'])) {
            array_push($data['type'], 'Mark As Absent');
        }
        return view('attendance::attendance-request.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            if (auth()->user()->user_type === 'employee') {
                $data['employee_id'] = [$data['employee_id']];
            }

            $data['start_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['start_date']) : $data['start_date'];
            $setting = Setting::first();
            $employeeModel = Employee::find($data['employee_id']);

            if ($data['type'] == '5' || $data['type'] == '6' || $data['type'] == '7') {

                $data['end_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['end_date']) : $data['end_date'];

                $inputData = $data;
                $parentId = null;

                $existingCount = 0;

                $days = DateTimeHelper::DateDiffInDay($data['start_date'], $data['end_date']);
                $days += 1;
                if ($days > 0) {
                    foreach ($data['employee_id'] as $employeeId) {
                        $tempDate = $data['start_date'];
                        $employeeModel = Employee::find($employeeId);

                        for ($i = 1; $i <= $days; $i++) {
                            $inputData['date'] = $tempDate;
                            $inputData['nepali_date'] = date_converter()->eng_to_nep_convert($tempDate);
                            $inputData['created_by'] = auth()->user()->id;
                            $inputData['employee_id'] = $employeeId;

                            $checkData = [
                                'date' => $tempDate,
                                'empId' => $employeeId,
                                'requestType' => $data['type'],
                            ];

                            $testSumit[] = [
                                'date' => $tempDate,
                                'empId' => $employeeId,
                                'requestType' => $data['type'],
                            ];
                            $alreadyExist = $this->attendanceRequest->checkRequestExists($checkData);
                            if ($alreadyExist) {
                                $existingCount++;
                            } else {
                                $inputData['parent_id'] = $parentId;
                                $attendance = $this->attendanceRequest->save($inputData);
                                // if ($parentId == null) {
                                //     $parentId = $attendance->id;
                                // };

                            }
                            $tempDate = date('Y-m-d', strtotime('+1 day', strtotime($tempDate)));
                        }
                        if (isset($attendance)) {
                            $attendance['enable_mail'] = $setting->enable_mail;
                            AttendanceRequestJob::dispatch($attendance, auth()->user());

                            $this->attendanceRequest->sendMailNotification($attendance);
                            toastr('Attendance Request Added Successfully', 'success');

                            if (!optional($employeeModel->getUser)->id) {
                                toastr('We could not send Attendance Request Notification and Email to ' . $employeeModel->full_name . '. So please create user access for the employee.', 'warning');
                            }
                        } else {
                            toastr('Attendance Request for the date has been created already.', 'warning');
                            // return back();
                        }
                    }
                }
            } else {
                $data['date'] = $data['start_date'];
                $data['nepali_date'] = date_converter()->eng_to_nep_convert($data['start_date']);
                $data['created_by'] = auth()->user()->id;

                foreach ($data['employee_id'] as $employeeId) {
                    $data['employee_id'] = $employeeId;
                    $employeeModel = Employee::find($employeeId);
                    $checkData = [
                        'date' => $data['start_date'],
                        'empId' => $employeeId,
                        'requestType' => $data['type'],
                    ];
                    $alreadyExist = $this->attendanceRequest->checkRequestExists($checkData);

                    if ($alreadyExist) {
                        toastr('Attendance Request for the date has been created already.', 'warning');
                        return back();
                    }
                    $attendance = $this->attendanceRequest->save($data);
                    if (isset($attendance)) {

                        $attendance['enable_mail'] = $setting->enable_mail;
                        AttendanceRequestJob::dispatch($attendance, auth()->user());
                        // $this->attendanceRequest->sendMailNotification($attendance);
                        toastr('Attendance Request Added Successfully', 'success');

                        if (!optional($employeeModel->getUser)->id) {
                            toastr('We could not send Attendance Request Notification and Email to ' . $employeeModel->full_name . '. So please create user access for the employee.', 'warning');
                        }
                    }
                }
            }
            // dd($testSumit);
            if (isset($data['fromAttOverview']) && $data['fromAttOverview'] == 1) {
                toastr('Attendance Request Added Successfully', 'success');
                return redirect()->back();
            } else {
                $logData = [
                    'title' => 'New attendance request Created',
                    'action_id' => $attendance->id,
                    'action_model' => get_class($attendance),
                    'route' => route('attendanceRequest.show', $attendance->id)
                ];
                $this->setActivityLog($logData);
                toastr('Attendance Request Added Successfully', 'success');
                return redirect()->route('attendanceRequest.index');
            }
        } catch (Exception $e) {
            toastr('Error While Adding Attendance Request', 'error');
            return redirect()->route('attendanceRequest.index');
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $data['lastRequestDate'] = AttendanceRequest::select('date')->where('parent_id', $id)->orderBy('date', 'desc')->first();
        $user_id = auth()->user()->id;
        $data['attendanceRequest'] = $attendanceModel = $this->attendanceRequest->find($id);

        $statusList = $this->attendanceRequest->getStatus();
        unset($statusList[5]);
        $emp_approval_flow = EmployeeApprovalFlow::where('employee_id', $attendanceModel->employee_id)->first();

        if (auth()->user()->emp_id == $attendanceModel->employee_id) {
            $statusList = [
                '1' => 'Pending'
            ];
        } else {
            if (!empty($emp_approval_flow)) {
                if (!empty($emp_approval_flow->first_approval_user_id) && $emp_approval_flow->first_approval_user_id > 0) {
                    if ($attendanceModel->status == 1 && $emp_approval_flow->first_approval_user_id == $user_id) {
                        $statusList = [
                            '1' => 'Pending',
                            '2' => 'Recommended',
                            '4' => 'Rejected'
                        ];
                    } elseif ($attendanceModel->status == 2 && $emp_approval_flow->first_approval_user_id == $user_id) {
                        $statusList = [
                            '2' => 'Recommended',
                        ];
                    } elseif ($attendanceModel->status == 4 && $emp_approval_flow->first_approval_user_id == $user_id) {
                        $statusList = [
                            '4' => 'Rejected'
                        ];
                    } elseif ($attendanceModel->status == 2 && $emp_approval_flow->last_approval_user_id == $user_id) {
                        $statusList = [
                            '2' => 'Recommended',
                            '3' => 'Approved',
                            '4' => 'Rejected'
                        ];
                    } elseif ($attendanceModel->status == 1 && $emp_approval_flow->last_approval_user_id == $user_id) {
                        $statusList = [
                            '1' => 'Pending'
                        ];
                    } elseif ($attendanceModel->status == 3 && $emp_approval_flow->first_approval_user_id == $user_id) {
                        $statusList = [];
                    } elseif ($attendanceModel->status != 1 && $attendanceModel->status != 2 && $emp_approval_flow->last_approval_user_id == $user_id) {
                        $statusList = [];
                    }
                } else {
                    if ($emp_approval_flow->last_approval_user_id == $user_id) {
                        $statusList = [
                            '1' => 'Pending',
                            '3' => 'Approved',
                            '4' => 'Rejected'
                        ];
                    }
                }
            }
        }
        if (auth()->user()->user_type == 'super_admin') {
            $statusList = Leave::statusList();
        }
        $data['statusList'] = $statusList;
        $logData = [
            'title' => 'Attendance request viwed',
            'action_id' => $attendanceModel->id,
            'action_model' => get_class($attendanceModel),
            'route' => route('attendanceRequest.show', $attendanceModel->id)
        ];
        $this->setActivityLog($logData);
        return view('attendance::attendance-request.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['type'] = $this->attendanceRequest->getTypes();
        if (in_array(auth()->user()->user_type, ['supervisor', 'employee'])) {
            unset($data['type'][5]);
        }
        $data['kind'] = $this->attendanceRequest->getKinds();
        $data['employees'] = $this->employees->findAll()->pluck('full_name', 'id');
        $data['request'] = $this->attendanceRequest->find($id);
        if (in_array(auth()->user()->user_type, ['super_admin', 'admin', 'hr'])) {
            array_push($data['type'], 'Mark As Absent');
        }
        return view('attendance::attendance-request.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        try {
            $data = $request->all();
            $this->attendanceRequest->update($id, $data);
            $attendanceRequest = $this->attendanceRequest->find($id);
            $logData = [
                'title' => 'Attendance request updated',
                'action_id' => $attendanceRequest->id,
                'action_model' => get_class($attendanceRequest),
                'route' => route('attendanceRequest.show', $attendanceRequest->id)
            ];
            $this->setActivityLog($logData);
            toastr('Attendance Request Updated Successfully', 'success');
            return redirect()->route('attendanceRequest.index');
        } catch (Exception $e) {
            toastr('Error While Updating Attendance Request', 'error');
            return redirect()->route('attendanceRequest.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->attendanceRequest->delete($id);
            $logData = [
                'title' => 'Attendance request deleted',
                'action_id' => null,
                'action_model' => null,
                'route' => route('attendanceRequest.index')
            ];
            $this->setActivityLog($logData);
            toastr('Attendance Request Deleted Successfully', 'success');
        } catch (Exception $e) {
            toastr('Error While Deleting Attendance Request', 'error');
        }
        return redirect()->back();
    }

    public function updateStatus(Request $request)
    {


        try {
            $checkinType = ['Missed Check In', 'Late Arrival Request'];
            $checkoutType = ['Missed Check Out', 'Early Departure Request'];
            $extraType = ['Force Attendance Request', 'Out Door Duty Request', 'Work From Home Request'];
            $approvedStatus = 3;

            $attendanceRequest = $this->attendanceRequest->find($request->id);
            $this->attendanceLockAttribute($attendanceRequest);
            $attendanceRequest['status'] = $data['status'] = $request->status;
            $attendanceRequest['rejected_remarks'] = $data['rejected_remarks'] = $request->rejected_remarks;
            $attendanceRequest['forwarded_remarks'] = $data['forwarded_remarks'] = $request->forwaded_remarks;
            if ($request->status == 3) {
                $attendanceRequest['approved_by'] = $data['approved_by'] = Auth::user()->id;
                $attendanceRequest['approved_date'] = $data['approved_date'] = Carbon::now();
            } elseif ($request->status == 2) {
                $attendanceRequest['forwarded_by'] = $data['forwarded_by'] = Auth::user()->id;
                $attendanceRequest['forwarded_date'] = $data['forwarded_date'] = Carbon::now();
            } elseif ($request->status == 4) {
                $attendanceRequest['rejected_by'] = $data['rejected_by'] = Auth::user()->id;
                $attendanceRequest['rejected_date'] = $data['rejected_date'] = Carbon::now();
            } elseif ($request->status == 5) {
                $attendanceRequest['cancelled_by'] = $data['cancelled_by'] = Auth::user()->id;
                $attendanceRequest['cancelled_date'] = $data['cancelled_date'] = Carbon::now();
            }
            $employee = $this->employees->find($attendanceRequest->employee_id);

            $attendanceExist = $this->attendance->employeeAttendanceExists($attendanceRequest->employee_id, $attendanceRequest->date);
            //checkin and Approved
            if (in_array($attendanceRequest->getType(), $checkinType) && $request->status == $approvedStatus) {
                $late_arrival_in_minutes = $this->attendance->getLateArrivalEarlyDepartureData($employee, $attendanceRequest->date, $attendanceRequest->time, 'checkin')['lateArrival'];

                if ($attendanceExist) {
                    //Update Checkin Time
                    // $attendanceExist->fill(['checkin' => $attendanceRequest->time, 'checkin_from' => 'request']);
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

                //get return back deducted leave
                // $ldata = [
                //     'org_id' => optional($employee->organizationModel)->id,
                //     'emp_id' => $attendanceRequest->employee_id,
                //     'date' => $attendanceRequest->date,
                //     'generated_leave_type' => $attendanceRequest->type
                // ];
                // $this->attendanceRequest->returnBackDeductedLeave($ldata);
                //

                // $leave = $this->employees->getLeaveFromSubsituteDate($employee->id, $saveAtd->date);
                // if ($leave) {
                //     $this->employees->employeeLeaveIncrement($leave);
                // }
            }

            //checkout and Approved
            if (in_array($attendanceRequest->getType(), $checkoutType) && $request->status == $approvedStatus) {

                // $employeeShift = optional(optional(ShiftGroupMember::where('group_member', $attendanceRequest->employee_id)->orderBy('id', 'DESC')->first())->group)->shift;
                // if (isset($employeeShift)) {
                //     $shiftSeason = $employeeShift->getShiftSeasonForDate($attendanceRequest->date);
                //     $seasonalShiftId = null;
                //     if($shiftSeason){
                //         $seasonalShiftId = $shiftSeason->id;
                //     }
                //     $day = date('D', strtotime($attendanceRequest->date));
                //     $daywiseShift = $employeeShift->getShiftDayWise($day, $seasonalShiftId);
                // }else{
                //     $daywiseShift = $this->attendance->getDayWiseShift($attendanceRequest->employee_id, $attendanceRequest->date);
                // }
                $employeeModel = $this->employees->find($attendanceRequest->employee_id);

                $shiftDetail = $this->attendanceReport->getActualEmployeeShift($employeeModel, $attendanceRequest->date);
                $employeeShift = $shiftDetail['empActualShift'];
                $seasonalShiftId = $shiftDetail['seasonalShiftId'];

                if (isset($employeeShift)) {
                    $day = date('D', strtotime($attendanceRequest->date));
                    $daywiseShift = $employeeShift->getShiftDayWise($day, $seasonalShiftId);
                } else {
                    $daywiseShift = $this->attendance->getDayWiseShift($employeeModel->id, $attendanceRequest->date);
                }
                // $is_multi_day_shift = optional($daywiseShift->shiftSeason)->is_multi_day_shift ?? 0;
                // if($is_multi_day_shift == 1){
                //     $attendanceRequest->date = Carbon::parse($attendanceRequest->date)->subDay()->format('Y-m-d');
                // }
                $attendanceExist = $this->attendance->employeeAttendanceExists($attendanceRequest->employee_id, $attendanceRequest->date);
                $early_departure_in_minutes = $this->attendance->getLateArrivalEarlyDepartureData($employee, $attendanceRequest->date, $attendanceRequest->time, 'checkout')['earlyDeparture'];



                if ($attendanceExist) {
                    //Update Checkout Time
                    // $attendanceExist->fill(['checkout' => $attendanceRequest->time, 'checkout_from' => 'request', 'total_working_hr' => DateTimeHelper::getTimeDiff(date('H:i', strtotime($attendanceExist->checkin)), date('H:i', strtotime($attendanceRequest->time)))]);
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
                //get return back deducted leave
                // $ldata = [
                //     'org_id' => optional($employee->organizationModel)->id,
                //     'emp_id' => $attendanceRequest->employee_id,
                //     'date' => $attendanceRequest->date,
                //     'generated_leave_type' => $attendanceRequest->type
                // ];
                // $this->attendanceRequest->returnBackDeductedLeave($ldata);
                //

                // $leave = $this->employees->getLeaveFromSubsituteDate($employee->id, $saveAtd->date);
                // if ($leave) {
                //     $this->employees->employeeLeaveIncrement($leave);
                // }
            }

            //extraType and Approved
            if (in_array($attendanceRequest->getType(), $extraType) && $request->status == $approvedStatus) {
                $day = date('D', strtotime($attendanceRequest->date));

                $emp = $this->employees->find($request->employee_id);
                $shiftDetail = $this->attendanceReport->getActualEmployeeShift($emp, $attendanceRequest->date);
                $shiftInfo =  $shiftDetail['empActualShift'];
                $seasonalShiftId = $shiftDetail['seasonalShiftId'];

                $newShiftEmp = NewShiftEmployee::getShiftEmployee($emp->id, $attendanceRequest->date);
                if (isset($newShiftEmp)) {
                    $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
                    if (isset($rosterShift) && isset($rosterShift->shift_group_id) && ($rosterShift->shift_group_id != null)) {
                        $shiftInfo = optional((new ShiftGroupRepository())->find($rosterShift->shift_group_id))->shift;
                        $seasonalShiftId = null;
                    }
                }


                // $employeeShift = $this->employeeShift->findOne(['employee_id' => $request->employee_id, 'days' => date('D', strtotime($attendanceRequest->date))]);
                // $shiftInfo = $employeeShift->getShift;

                // $newShiftEmp = NewShiftEmployee::getShiftEmployee($request->employee_id, $attendanceRequest->date);
                // if (isset($newShiftEmp)) {
                //     $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
                //     if (isset($rosterShift) && isset($rosterShift->shift_id)) {
                //         $shiftInfo = (new ShiftRepository())->find($rosterShift->shift_id);
                //     }
                // }

                if ($shiftInfo) {
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
                    // $saveAtd = $attendanceExist;
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

                // $saveAtd = $this->attendance->save([
                //     'org_id' => optional($employee->organizationModel)->id,
                //     'emp_id' => $attendanceRequest->employee_id,
                //     'date' => $attendanceRequest->date,
                //     'nepali_date' => date_converter()->eng_to_nep_convert($attendanceRequest->date),
                //     'checkin' => $checkin,
                //     'checkout' => $checkout,
                //     'total_working_hr' => DateTimeHelper::getTimeDiff(date('H:i', strtotime($checkin)), date('H:i', strtotime($checkout))),
                // ]);

                // if ($attendanceRequest['id']) {
                //     $childModels = AttendanceRequest::select('date')->where('parent_id', $attendanceRequest['id'])->get();
                //     if (isset($childModels) && !empty($childModels)) {
                //         foreach ($childModels as $childModel) {
                //             $saveAtd = $this->attendance->save([
                //                 'org_id' => optional($employee->organizationModel)->id,
                //                 'emp_id' => $attendanceRequest->employee_id,
                //                 'date' => $childModel->date,
                //                 'nepali_date' => date_converter()->eng_to_nep_convert($childModel->date),
                //                 'checkin' => $checkin,
                //                 'checkout' => $checkout,
                //                 'checkin_from' => 'request',
                //                 'checkout_from' => 'request',
                //                 'total_working_hr' => DateTimeHelper::getTimeDiff(date('H:i', strtotime($checkin)), date('H:i', strtotime($checkout))),
                //             ]);
                //         }
                //     }
                // }
            }
            if ($attendanceRequest->type == 8 && $request->status == $approvedStatus) {
                // $startDate = [$attendanceRequest->date];
                // $childDates = AttendanceRequest::where('parent_id', $request->id)->pluck('date')->toArray();
                // $finalRequestedDates = array_merge($startDate, $childDates);

                // foreach ($finalRequestedDates as $reqDate) {
                //     Attendance::where('emp_id',$attendanceRequest->employee_id)->where('date',$reqDate)->delete();
                //     AttendanceLog::where('biometric_emp_id',$employee->biometric_id)->where('date',$reqDate)->delete();
                // }

                Attendance::where('emp_id', $attendanceRequest->employee_id)->where('date', $attendanceRequest->date)->delete();
                AttendanceLog::where('biometric_emp_id', $employee->biometric_id)->where('date', $attendanceRequest->date)->delete();

                // $divisionAtdMonthly = DivisionAttendanceMonthly::where('employee_id', $attendanceRequest->employee_id)->where('date', $attendanceRequest->date)->first();
                // if(isset($divisionAtdMonthly) && !empty($divisionAtdMonthly)){
                //     $divisionAtdMonthly->update(['is_present'=>10]);
                // }

                $divisionAtdReport = DivisionAttendanceReport::where('employee_id', $attendanceRequest->employee_id)->where('date', $attendanceRequest->date)->first();
                if (isset($divisionAtdReport) && !empty($divisionAtdReport)) {
                    $divisionAtdReport->update(['is_absent' => 11, 'checkin' => null, 'checkout' => null, 'worked_hr' => 0, 'ot_hr' => 0]);
                }
            }
            $this->attendanceRequest->update($request->id, $data);

            // if ($request->id) {
            //     $childModels = AttendanceRequest::where('parent_id', $request->id)->get();
            //     if (isset($childModels) && !empty($childModels)) {
            //         foreach ($childModels as $childModel) {
            //             $this->attendanceRequest->update($childModel->id, $data);
            //         }
            //     }
            // }
            // send notification
            // $attendanceRequest = $this->attendanceRequest->find($request->id);
            $attendanceRequest['enable_mail'] = setting('enable_mail');
            $this->attendanceLockAttribute($attendanceRequest);
            // $this->attendanceRequest->sendMailNotification($attendanceRequest);
            AttendanceRequestJob::dispatch($attendanceRequest, auth()->user());
            $logData = [
                'title' => 'Attendance request status updated',
                'action_id' => $attendanceRequest->id,
                'action_model' => get_class($attendanceRequest),
                'route' => route('attendanceRequest.show', $attendanceRequest->id)
            ];
            $this->setActivityLog($logData);
            // $cacheKey = 'pending_approvals_' . auth()->user()->emp_id;
            // Cache::forget($cacheKey);
            toastr('Attendance Request Status Updated Successfully', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            dd($e->getMessage() . $e->getLine());
            toastr('Error While Updating Attendance Request Status', 'error');
            return redirect()->back();
        }
    }

    public function attendanceLockAttribute($data)
    {
        $this->perfomeAttendanceAttribute($data);
    }

    public function perfomeAttendanceAttribute($data)
    {
        $calenderType = $data->employee->organizationModel->payrollCalender->calendar_type ?? null;
        $year = '';
        $month = '';
        if ($calenderType == 'nep') {
            $date = explode('-', $data->nepali_date);
        } else {
            $date = explode('-', $data->date);
        }
        $year = ltrim($date[0], '0');
        $month = ltrim($date['1'], '0');
        $lockAttendance = AttendanceOrganizationLock::where([
            ['organization_id', '=', $data->employee->organization_id],
            ['calender_type', '=', $calenderType],
            ['year', '=', $year],
            ['month', '=', $month]
        ])->first();
        if ($lockAttendance) {
            $lockSummaryData = AttendanceSummaryVerification::where([
                ['attendance_organization_lock_id', $lockAttendance->id],
                ['employee_id', $data->employee_id],
                ['organization_id', $data->employee->organization_id],
                ['calender_type', $calenderType],
            ])->first();
            if ($lockSummaryData) {
                $status = $data->status == '3' ? 1 : 0;
                $this->setLockAttribute($data, $lockAttendance->id, $lockSummaryData->id, $status, 2);
            }
        }
    }

    public function setLockAttribute($data1, $lockAttendance, $lockSummaryData, $status, $type)
    {
        $data = [
            'attendance_organization_lock_id' => $lockAttendance,
            'attendance_summary_verification_id' => $lockSummaryData,
            'emp_id' => $data1->employee_id,
            'type' => $type,
            'value' => $data1->id,
            'item_value' => 1,
            'status' => $status
        ];
        $this->attendanceLockAttribute = AttendanceLockAttribute::where('type', 2)->where('value', $data1->id)->first();
        if (!$this->attendanceLockAttribute) {
            $this->attendanceLockAttribute = new AttendanceLockAttribute();
        }
        $this->attendanceLockAttribute->fill($data);
        $this->attendanceLockAttribute->save();
    }

    public function updateStatusBulk(Request $request)
    {
        $inputData = $request->except('_token');
        $requestIds = json_decode($inputData['request_multiple_id'][0], true);
        // try {
        $checkinType = ['Missed Check In', 'Late Arrival Request'];
        $checkoutType = ['Missed Check Out', 'Early Departure Request'];
        $extraType = ['Force Attendance Request', 'Out Door Duty Request', 'Work From Home Request'];
        if (!empty($requestIds)) {
            foreach ($requestIds as $requestId) {

                $attendanceRequest = $this->attendanceRequest->find($requestId);
                $this->attendanceLockAttribute($attendanceRequest);
                if ($attendanceRequest['status'] != $inputData['status']) {

                    $attendanceRequest['status'] = $data['status'] = $inputData['status'];
                    // $attendanceRequest['rejected_remarks'] = $data['rejected_remarks'] = $request->rejected_remarks;
                    // $attendanceRequest['forwarded_remarks'] = $data['forwarded_remarks'] = $request->forwaded_remarks;
                    if ($inputData['status'] == 3) {
                        $attendanceRequest['approved_by'] = $data['approved_by'] = Auth::user()->id;
                    }
                    $employee = $this->employees->find($attendanceRequest->employee_id);
                    $late_arrival_in_minutes = $this->attendance->getLateArrivalEarlyDepartureData($employee, $attendanceRequest->date, $attendanceRequest->time, 'checkin')['lateArrival'];

                    $attendanceExist = $this->attendance->employeeAttendanceExists($attendanceRequest->employee_id, $attendanceRequest->date);
                    //checkin and Approved
                    if (in_array($attendanceRequest->getType(), $checkinType) && $inputData['status'] == 3) {
                        if ($attendanceExist) {
                            //Update Checkin Time
                            // $attendanceExist->fill(['checkin' => $attendanceRequest->time, 'checkin_from' => 'request']);
                            $attendanceExist->fill(['checkin' => $attendanceRequest->time, 'checkin_from' => 'request', 'late_arrival_in_minutes' => $late_arrival_in_minutes]);

                            if ($attendanceExist->checkout) {
                                $attendanceExist->fill(['total_working_hr' => DateTimeHelper::getTimeDiff(date('H:i', strtotime($attendanceRequest->time)), date('H:i', strtotime($attendanceExist->checkout)))]);
                            }
                            $attendanceExist->update();
                        } else {
                            //Create Attendance with Checkin Type
                            $this->attendance->save([
                                'org_id' => optional($employee->organizationModel)->id,
                                'emp_id' => $attendanceRequest->employee_id,
                                'date' => $attendanceRequest->date,
                                'nepali_date' => date_converter()->eng_to_nep_convert($attendanceRequest->date),
                                'checkin' => $attendanceRequest->time,
                                'checkin_from' => 'request',
                                'total_working_hr' => null,
                                'late_arrival_in_minutes' => $late_arrival_in_minutes
                            ]);
                        }
                    }

                    //checkout and Approved
                    if (in_array($attendanceRequest->getType(), $checkoutType) && $inputData['status'] == 3) {
                        $early_departure_in_minutes = $this->attendance->getLateArrivalEarlyDepartureData($employee, $attendanceRequest->date, $attendanceRequest->time, 'checkout')['earlyDeparture'];
                        if ($attendanceExist) {
                            //Update Checkout Time
                            // $attendanceExist->fill(['checkout' => $attendanceRequest->time, 'checkout_from' => 'request', 'total_working_hr' => DateTimeHelper::getTimeDiff(date('H:i', strtotime($attendanceExist->checkin)), date('H:i', strtotime($attendanceRequest->time)))]);
                            $attendanceExist->fill(['checkout' => $attendanceRequest->time, 'checkout_from' => 'request', 'total_working_hr' => DateTimeHelper::getTimeDiff(date('H:i', strtotime($attendanceExist->checkin)), date('H:i', strtotime($attendanceRequest->time))), 'early_departure_in_minutes' => $early_departure_in_minutes]);
                            $attendanceExist->update();
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
                    if (in_array($attendanceRequest->getType(), $extraType) && $inputData['status'] == 3) {
                        $day = date('D', strtotime($attendanceRequest->date));

                        $emp = $this->employees->find($attendanceRequest->employee_id);
                        $shiftDetail = $this->attendanceReport->getActualEmployeeShift($emp, $attendanceRequest->date);
                        $shiftInfo =  $shiftDetail['empActualShift'];
                        $seasonalShiftId = $shiftDetail['seasonalShiftId'];

                        $newShiftEmp = NewShiftEmployee::getShiftEmployee($emp->id, $attendanceRequest->date);
                        if (isset($newShiftEmp)) {
                            $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
                            if (isset($rosterShift) && isset($rosterShift->shift_group_id) && ($rosterShift->shift_group_id != null)) {
                                $shiftInfo = optional((new ShiftGroupRepository())->find($rosterShift->shift_group_id))->shift;
                                $seasonalShiftId = null;
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

                    if ($attendanceRequest->type == 8 && $inputData['status'] == 3) {
                        // $startDate = [$attendanceRequest->date];
                        // $childDates = AttendanceRequest::where('parent_id', $requestId)->pluck('date')->toArray();
                        // $finalRequestedDates = array_merge($startDate, $childDates);

                        // foreach ($finalRequestedDates as $reqDate) {
                        //     Attendance::where('emp_id',$attendanceRequest->employee_id)->where('date',$reqDate)->delete();
                        //     AttendanceLog::where('biometric_emp_id',$employee->biometric_id)->where('date',$reqDate)->delete();
                        // }

                        Attendance::where('emp_id', $attendanceRequest->employee_id)->where('date', $attendanceRequest->date)->delete();
                        AttendanceLog::where('biometric_emp_id', $employee->biometric_id)->where('date', $attendanceRequest->date)->delete();

                        // $divisionAtdMonthly = DivisionAttendanceMonthly::where('employee_id', $attendanceRequest->employee_id)->where('date', $attendanceRequest->date)->first();
                        // if(isset($divisionAtdMonthly) && !empty($divisionAtdMonthly)){
                        //     $divisionAtdMonthly->update(['is_present'=>10]);
                        // }

                        $divisionAtdReport = DivisionAttendanceReport::where('employee_id', $attendanceRequest->employee_id)->where('date', $attendanceRequest->date)->first();
                        if (isset($divisionAtdReport) && !empty($divisionAtdReport)) {
                            $divisionAtdReport->update(['is_absent' => 11, 'checkin' => null, 'checkout' => null, 'worked_hr' => 0, 'ot_hr' => 0]);
                        }
                    }
                    $this->attendanceRequest->update($requestId, $data);

                    $attendanceRequest['enable_mail'] = setting('enable_mail');
                    // $this->attendanceRequest->sendMailNotification($attendanceRequest);
                    AttendanceRequestJob::dispatch($attendanceRequest, auth()->user());
                }
            }
        }
        $logData = [
            'title' => 'Attendance request status updated',
            'action_id' => $attendanceRequest->id,
            'action_model' => get_class($attendanceRequest),
            'route' => route('attendanceRequest.show', $attendanceRequest->id)
        ];
        $this->setActivityLog($logData);
        toastr('Attendance Request Status Updated Successfully', 'success');
        // } catch (Exception $e) {
        //     toastr('Error While Updating Attendance Request Status', 'error');
        // }
        return redirect()->back();
    }


    public function showTeamAttendance(Request $request)
    {
        if (auth()->user()->user_type != 'supervisor') {
            toastr('User have to be supervisor', 'error');
            return redirect()->back();
        }
        $filter = $request->all();
        // $filter['isParent'] = true;

        $sort = [
            'by' => 'id',
            'sort' => 'DESC'
        ];
        $data['title'] = 'Team Attendance Request';
        $data['organizationList'] = $this->organization->getList();
        $data['employeeList'] = $this->employees->getList();

        $data['statusList'] = $status = $this->attendanceRequest->getStatus();
        $data['allStatus'] = $status;

        $data['requests'] = $this->attendanceRequest->findTeamAttendance(20, $filter, $sort);
        $data['type'] = $this->attendanceRequest->getTypes();
        if (in_array(auth()->user()->user_type, ['super_admin', 'admin', 'hr'])) {
            array_push($data['type'], 'Mark As Absent');
        }
        return view('attendance::attendance-request-team.index', $data);
    }

    public function teamRequestCreate()
    {
        $data['employees'] = $this->employees->getList();
        $data['type'] = $this->attendanceRequest->getTypes();
        $data['kind'] = $this->attendanceRequest->getKinds();
        if (in_array(auth()->user()->user_type, ['super_admin', 'admin', 'hr'])) {
            array_push($data['type'], 'Mark As Absent');
        }
        return view('attendance::attendance-request-team.create', $data);
    }

    public function teamRequestStore(Request $request)
    {
        try {
            $this->store($request);
            // toastr('Attendance Request Added Successfully', 'success');
            if (isset($data['fromAttOverview']) && $data['fromAttOverview'] == 1) {
                return redirect()->back();
            } else {
                return redirect()->route('attendanceRequest.showTeamAttendance');
            }
        } catch (Exception $e) {
            // toastr('Error While Adding Attendance Request', 'error');
            return redirect()->route('attendanceRequest.showTeamAttendance');
        }
    }

    public function checkRequestExist(Request $request)
    {
        $data = $request->all();
        try {
            $check =  $this->attendanceRequest->checkRequestExists($data);
            if ($check) {
                return response()->json(['exist' => true]);
            } else {
                return response()->json(['exist' => false]);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function cancelAttendanceRequest(Request $request)
    {
        try {
            $data['status'] = $request->status;
            AttendanceRequest::with('childs')->find($request->id)->update($data);
            AttendanceRequest::where('parent_id', $request->id)->update($data);
            // $this->attendanceRequest->update($request->id, $data);

            toastr('Attendance Request Status Cancelled Successfully', 'success');
            return redirect()->to($request->url);
        } catch (Exception $e) {
            toastr('Error While Updating Attendance Request Status', 'error');
            return redirect()->route('attendanceRequest.index');
        }
    }

    public function postProcessData(Request $request)
    {
        $inputData = $request->all();
        $employeeId = $inputData['params']['employeeId'];
        $type = $inputData['params']['type'];
        $startDate = $inputData['params']['startDate'];
        $endDate = $inputData['params']['endDate'];

        $numberOfDays = 0;
        $numberOfDays = DateTimeHelper::DateDiffInDay($startDate, $endDate);
        $numberOfDays += 1; // adjust data from proper calculation
        $response['numberOfDays'] = $numberOfDays;
        $response['restrictSave'] = 'false';


        $atdRequestDays = [];
        $status = [1, 2, 3]; //except rejected and cancelled
        $attendanceRequestModels = AttendanceRequest::where('employee_id', $employeeId)
            ->where('date', '>=', $startDate)
            ->where('date', '<=', $endDate)
            ->where('type', $type)
            ->whereIn('status', $status)
            ->orderBy('date', 'ASC')->get();

        // dd($inputData,$attendanceRequestModels->toArray());
        if (count($attendanceRequestModels) > 0) {
            foreach ($attendanceRequestModels as $attendanceRequestModel) {
                $atdRequestDays[] = $attendanceRequestModel->date;
            }
            $data['previousAttendanceRequests'] = implode(', ', $atdRequestDays);
            $response['restrictSave'] = "true";
        }

        if ($response['restrictSave'] == 'false') {
            $data['finalMessage'] = "The total number of days you are applying is " . $numberOfDays;
        }

        $response['noticeList'] = view('attendance::attendance-request.partial.notice-list', $data)->render();
        return  json_encode($response);
    }


    public function getCheckInCheckOutTime(Request $request)
    {
        $data = $request->all();
        $request_type = $data['requestType'];
        $employeeId = $data['empId'];
        $date = $data['date'];
        if (setting('calendar_type') == 'BS') {
            $date = date_converter()->nep_to_eng_convert($date);
        }
        $day = Carbon::parse($date)->format('D');

        $employeeShift = $this->employeeShift->employeeShift($employeeId, $day);
        $shift = optional($employeeShift->getShift);

        $newShiftEmp = NewShiftEmployee::getShiftEmployee($employeeId, $date);
        if (isset($newShiftEmp)) {
            $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
            if (isset($rosterShift) && isset($rosterShift->shift_id)) {
                $shift = (new ShiftRepository())->find($rosterShift->shift_id);
            }
        }
        $shiftSeason = $shift->getShiftSeasonForDate($date);
        $seasonalShiftId = null;
        if ($shiftSeason) {
            $seasonalShiftId = $shiftSeason->id;
        }
        if ($request_type == '1') {
            return optional($shift->getShiftDayWise($day, $seasonalShiftId))->start_time;
        } else {
            return optional($shift->getShiftDayWise($day, $seasonalShiftId))->end_time;
        }
    }
}