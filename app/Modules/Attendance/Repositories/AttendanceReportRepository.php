<?php

namespace App\Modules\Attendance\Repositories;

use Carbon\Carbon;
use App\Helpers\DateTimeHelper;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Modules\Leave\Entities\Leave;
use Illuminate\Support\Facades\Config;
use App\Modules\Labour\Entities\Labour;
use App\Modules\Shift\Entities\ShiftGroup;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Attendance\Entities\Attendance;
use App\Modules\Holiday\Entities\HolidayDetail;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Modules\Shift\Entities\ShiftGroupMember;
use App\Modules\NewShift\Entities\NewShiftEmployee;
use App\Modules\Shift\Repositories\ShiftRepository;
use App\Modules\Attendance\Entities\AttendanceRequest;
use App\Modules\Employee\Entities\EmployeeApprovalFlow;
use App\Modules\Shift\Repositories\ShiftGroupRepository;
use App\Modules\Employee\Repositories\EmployeeRepository;
use App\Modules\OvertimeRequest\Entities\OvertimeRequest;
use App\Modules\Attendance\Entities\LabourAttendanceMonthly;
use App\Modules\Attendance\Entities\DivisionAttendanceMonthly;
use App\Modules\Attendance\Entities\AttendanceOrganizationLock;
use App\Modules\Organization\Repositories\OrganizationRepository;

class AttendanceReportRepository implements AttendanceReportInterface
{

    // public function attendanceRequestCheckInType($emp_id, $field, $date)
    // {
    //     $checkInType = [1, 4];
    //     return AttendanceRequest::where('employee_id', $emp_id)->where($field, $date)->whereIn('type', $checkInType)->get()->groupBy('type');
    // }

    // public function attendanceRequestCheckOutType($emp_id, $field, $date)
    // {
    //     $checkOutType = [2, 3];
    //     return AttendanceRequest::where('employee_id', $emp_id)->where($field, $date)->whereIn('type', $checkOutType)->get()->groupBy('type');
    // }

    // public function attRequestedCheckInType($emp_id, $field, $date)
    // {
    //     $checkIntype = [1, 4];
    //     return AttendanceRequest::where('employee_id', $emp_id)->where($field, $date)->whereIn('type', $checkIntype)->first();
    // }

    // public function attRequestedCheckOutType($emp_id, $field, $date)
    // {
    //     $checkIntype = [2, 3];
    //     return AttendanceRequest::where('employee_id', $emp_id)->where($field, $date)->whereIn('type', $checkIntype)->first();
    // }

    public function isHalfLeave($emp_id, $field, $date)
    {
        return Leave::where('employee_id', $emp_id)->where($field, $date)->where('leave_kind', '1')->whereStatus(3)->exists(); //for approved half leave
    }

    public function isPresent($emp_id, $field, $date)
    {
        return Attendance::where('emp_id', $emp_id)->where($field, $date)->whereNotNull(['checkin', 'checkout'])->exists() ?? "P";
    }

    public function isPartial($emp_id, $field, $date)
    {
        return Attendance::where('emp_id', $emp_id)->where($field, $date)->where(function ($query) {
            $query->orWhereNotNull('checkin')->orWhereNotNull('checkout');
        })->exists();
    }

    public function isLeave($emp_id, $field, $date)
    {
        return Leave::where('employee_id', $emp_id)->where($field, $date)->where('leave_kind', '2')->whereStatus(3)->exists(); //for approved full leave
    }

    public function isHoliday($emp, $field, $date)
    {
        if ($field == 'date') {
            $field = 'eng_date';
        } elseif ($field == 'nepali_date') {
            $field = 'nep_date';
        }
        return HolidayDetail::where($field, $date)
            ->whereHas('holiday', function ($query) use ($emp) {
                $query->where('status', 11);
                $query->GetEmployeeWiseHoliday($emp, true, true);
            })
            ->exists();
    }

    public function getHolidayName($field, $date)
    {
        if ($field == 'date') {
            $field = 'eng_date';
        } elseif ($field == 'nepali_date') {
            $field = 'nep_date';
        }
        return HolidayDetail::where($field, $date)->first()['sub_title'];
    }

  public function employeeAttendance($data, $filter, $limit = '', $type)
    {
        if (isset($filter['authUser'])) {
            if ($filter['authUser']['user_type'] == 'division_hr') {
                $filter['org_id'] = optional($filter['authUser']->userEmployer)->organization_id;
            }
        } else {
            $authUser = auth()->user();
            if ($authUser->user_type == 'division_hr') {
                $filter['org_id'] = optional($authUser->userEmployer)->organization_id;
            }
        }
        $query = Employee::query();
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

      if (!empty($filter['emp_id'])) {
    if (is_array($filter['emp_id'])) {
        $query->whereIn('id', array_filter($filter['emp_id'])); // remove empty/nulls
    } else {
        $query->where('id', $filter['emp_id']);
    }
}

        $employees = $query->paginate($limit ? $limit : Config::get('attendance.export-length'));
        return $employees->setCollection($employees->getCollection()->transform(function ($emp) use ($data, $filter, $type) {
            $emp->calendarType = $data['calendarType'] ?? 'eng';
            $lateArrivalArray = $earlyDepatureArray = [];
            for ($i = 1; $i <= $data['days']; $i++) {
                $fulldate = $data['year'] . '-' . sprintf("%02d", $data['month']) . '-' . sprintf("%02d", $i);
                $days[$fulldate] = [];
                $status = $this->checkStatus($emp, $data['field'], $fulldate);
                $holidayName = null;
                if ($status == 'H') {
                    $holidayName = $this->getHolidayName($data['field'], $fulldate);
                }

                $leave_status = null;
                if ($status == 'HL') {
                    $status = $this->isPartial($emp->id, $data['field'], $fulldate) ? 'P*' : 'HL';
                    $leave_status = $status == 'HL' ? null : 'HL';
                }

                $overStayValue = 0;

                $days[$fulldate] = [
                    'status' => $status,
                    'leave_status' => $leave_status,
                    'holidayName' => $holidayName,
                    'total_working_hr' => '',
                    'checkin' => '',
                    'checkout' => '',
                    'checkinStatus' => '',
                    'checkoutStatus' => '',
                    'checkin_original' => '',
                    'checkout_original' => '',
                    'checkin_from' => '',
                    'checkout_from' => '',
                    'late_arrival' => '',
                    'early_departure' => '',
                    'over_stay' => $overStayValue,
                    'actual_shift' => '',
                    'updated_shift' => '',
                    'ot_value' => 0
                    // 'atdRequest' => $atdRequest,
                ];

                // $employeeShift = $empActualShift = optional(optional(ShiftGroupMember::where('group_member', $emp->id)->orderBy('id', 'DESC')->first())->group)->shift;
                if ($data['field'] == 'nepali_date') {
                    $engFulldate = date_converter()->nep_to_eng_convert($fulldate);
                } else {
                    $engFulldate = $fulldate;
                }
                $day = date('D', strtotime($engFulldate));

                $shiftDetail=$this->getActualEmployeeShift($emp,$engFulldate);
                // dd($shiftDetail);
                $employeeShift = $empActualShift = $shiftDetail['empActualShift'];
                $seasonalShiftId = $shiftDetail['seasonalShiftId'];



                if (isset($employeeShift)) {
                    $newShiftEmp = NewShiftEmployee::getShiftEmployee($emp->id, $engFulldate);
                    if (isset($newShiftEmp)) {
                        $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();

                        if (isset($rosterShift) && isset($rosterShift->shift_group_id) && ($rosterShift->shift_group_id != null)) {

                            $employeeShift = $empUpdatedShift = optional((new ShiftGroupRepository())->find($rosterShift->shift_group_id))->shift;
                            // dd($newShiftEmp, $rosterShift,$empUpdatedShift,$engFulldate);
                            $seasonalShiftId = null;
                        } elseif($rosterShift->type == 'D'){
                            $empUpdatedShift = 'Day Off';
                        }else {
                            $empUpdatedShift = '';
                        }
                    } else {
                        $empUpdatedShift = '';
                    }
                   $shiftStartTime = optional(optional($employeeShift)->getShiftDayWise($day, $seasonalShiftId))->start_time;
                    $shiftEndTime = optional(optional($employeeShift)->getShiftDayWise($day, $seasonalShiftId))->end_time;

                    $days[$fulldate]['actual_working_hr'] = DateTimeHelper::getTimeDiff($shiftStartTime, $shiftEndTime);
                } else {
                    $days[$fulldate]['actual_working_hr'] = 8;
                }
                $days[$fulldate]['actual_shift'] = $this->getActualShiftNameWithDateRange($emp,$engFulldate);
                $days[$fulldate]['updated_shift'] = $this->getUpdatedShiftNameWithDateRange($emp,$engFulldate);

                //For export only
                if (isset($type) && $type == 'export') {
                    if ($status == 'L' || $status == 'HL') {
                        $leaveData = $this->getLeaveData($emp->id, $data['field'], $fulldate);
                        if ($leaveData) {
                            $days[$fulldate]['leave_apply_date'] = $leaveData['created_at']->toDateString();
                            $days[$fulldate]['leave_apply_by'] = optional(optional($leaveData->userModel)->userEmployer)->full_name;
                            $days[$fulldate]['leave_reason'] = $leaveData['reason'];
                            $days[$fulldate]['leave_approved_by'] = optional(optional($leaveData->acceptModel)->userEmployer)->full_name;
                        }
                    }

                    $latestCheckInTypeRequestData = $this->latestCheckInTypeRequestData($emp->id, $data['field'], $fulldate);
                    if (isset($latestCheckInTypeRequestData)) {
                        $days[$fulldate]['checkin_req_applied_by'] = optional(optional($latestCheckInTypeRequestData->userModel)->userEmployer)->full_name;
                        $days[$fulldate]['checkin_req_detail'] = $latestCheckInTypeRequestData->detail;
                        $days[$fulldate]['checkin_req_approved_by'] = optional(optional($latestCheckInTypeRequestData->approvedByModel)->userEmployer)->full_name;
                    }

                    $latestCheckOutTypeRequestData = $this->latestCheckOutTypeRequestData($emp->id, $data['field'], $fulldate);
                    if (isset($latestCheckOutTypeRequestData)) {
                        $days[$fulldate]['checkout_req_applied_by'] = optional(optional($latestCheckOutTypeRequestData->userModel)->userEmployer)->full_name;
                        $days[$fulldate]['checkout_req_detail'] = $latestCheckOutTypeRequestData->detail;
                        $days[$fulldate]['checkout_req_approved_by'] = optional(optional($latestCheckOutTypeRequestData->approvedByModel)->userEmployer)->full_name;
                    }
                }
                //

                $atd =  $emp->getSingleAttendance($data['field'], $fulldate);

                if ($atd) {
                    // $perDayShift = isset($employeeShift) ? (strtotime(optional($employeeShift->getShiftDayWise($day))->end_time) - strtotime(optional($employeeShift->getShiftDayWise($day))->start_time)) / 3600 : 8;
                    if(isset($seasonalShiftId) && $seasonalShiftId != null){
                        $shiftDayWise = $employeeShift->getShiftDayWise($day,$seasonalShiftId);
                    }
                    if(isset($shiftDayWise) && $shiftDayWise != null){
                        $shiftSeason = $shiftDayWise->shiftSeason;
                    }
                    if(isset($shiftDayWise)){
                        $perDayShift = isset($employeeShift) ? DateTimeHelper::getTimeDiff(optional($shiftDayWise)->start_time, optional( $shiftDayWise)->end_time) : 8;
                    }else{
                        $perDayShift = 0;
                    }

                    $days[$fulldate]['total_working_hr'] = DateTimeHelper::getTimeDiff($atd->checkin, $atd->checkout);
                    $days[$fulldate]['checkin'] = $atd->checkin;
                    $days[$fulldate]['checkout'] = $atd->checkout;

                    $shift = $this->getShift($emp, $atd, $data['field'], $fulldate);

                    $days[$fulldate]['checkinStatus'] = $shift['checkInShift'];
                    $days[$fulldate]['late_arrival'] = $lateArrivalArray[] = $shift['lateArrival'];

                    $days[$fulldate]['checkoutStatus'] = $shift['checkOutShift'];
                    $days[$fulldate]['early_departure'] = $earlyDepatureArray[] = $shift['earlyDeparture'];

                    $days[$fulldate]['checkin_original'] = $atd->checkin_original;
                    $days[$fulldate]['checkout_original'] = $atd->checkout_original;

                    $days[$fulldate]['checkin_from'] = $atd->checkin_from;
                    $days[$fulldate]['checkout_from'] = $atd->checkout_from;
                    $days[$fulldate]['over_stay'] = $this->overViewOverStay($atd->checkin, $atd->checkout, $perDayShift,$shiftSeason ?? null);

                }
            }
            $emp->date = $days;
            $emp->total_late_arrival = array_sum($lateArrivalArray);
            $emp->total_early_departure = array_sum($earlyDepatureArray);
            // dd($emp);
            return $emp;
        }));

    }

    public function employeeRangeAttendance($data, $filter, $limit = '', $type)
    {
        if (isset($filter['authUser'])) {
            if ($filter['authUser']['user_type'] == 'division_hr') {
                $filter['org_id'] = optional($filter['authUser']->userEmployer)->organization_id;
            }
        } else {
            $authUser = auth()->user();
            if ($authUser->user_type == 'division_hr') {
                $filter['org_id'] = optional($authUser->userEmployer)->organization_id;
            }
        }

        $query = Employee::query();
        $query->select('id', 'first_name', 'middle_name', 'last_name', 'organization_id', 'branch_id', 'department_id')
            ->where('status', 1);

        if (!empty($filter['org_id'])) {
            $query->where('organization_id', $filter['org_id']);
        }

        if (!empty($filter['branch_id'])) {
            $query->where('branch_id', $filter['branch_id']);
        }

        if (!empty($filter['department_id'])) {
            $query->where('department_id', $filter['department_id']);
        }

        if (!empty($filter['emp_id']['empId'])) {
            $empIds = array_filter((array) $filter['emp_id']['empId'], fn($val) => !is_null($val) && $val !== '');
            if (!empty($empIds)) {
                $query->whereIn('id', $empIds);
            }
        }

        if (!empty($filter['shift_id'])) {
            $groupIds = ShiftGroup::where('shift_id', $filter['shift_id'])->pluck('id');
            $employeeIds = ShiftGroupMember::whereIn('group_id', $groupIds)->pluck('group_member')->filter()->unique()->values()->all();
            if (!empty($employeeIds)) {
                $query->whereIn('id', $employeeIds);
            }
        }

        $employees = $query->paginate($limit ?: Config::get('attendance.export-length'));

        return $employees->setCollection($employees->getCollection()->transform(function ($emp) use ($data, $filter, $type) {
            $emp->calendarType = $data['calendarType'] ?? 'eng';
            $lateArrivalArray = $earlyDepatureArray = [];
            $days = [];

            // Setup loop dates
            if (!empty($data['days']) && !empty($data['year']) && !empty($data['month'])) {
                $startDates = [];
                for ($i = 1; $i <= $data['days']; $i++) {
                    $fulldate = $data['year'] . '-' . sprintf('%02d', $data['month']) . '-' . sprintf('%02d', $i);
                    $startDates[] = $fulldate;
                }
            } elseif (!empty($data['from_date']) && !empty($data['to_date'])) {
                $start = Carbon::parse($data['from_date']);
                $end = Carbon::parse($data['to_date']);
                $startDates = [];
                while ($start->lte($end)) {
                    $startDates[] = $start->toDateString();
                    $start->addDay();
                }
            } else {
                return $emp; // No valid dates provided
            }

            foreach ($startDates as $fulldate) {
                $status = $this->checkStatus($emp, $data['field'], $fulldate);
                $holidayName = $status === 'H' ? $this->getHolidayName($data['field'], $fulldate) : null;

                $leave_status = null;
                if ($status === 'HL') {
                    $status = $this->isPartial($emp->id, $data['field'], $fulldate) ? 'P*' : 'HL';
                    $leave_status = $status === 'HL' ? null : 'HL';
                }

                $days[$fulldate] = [
                    'status' => $status,
                    'leave_status' => $leave_status,
                    'holidayName' => $holidayName,
                    'total_working_hr' => '',
                    'checkin' => '',
                    'checkout' => '',
                    'checkinStatus' => '',
                    'checkoutStatus' => '',
                    'checkin_original' => '',
                    'checkout_original' => '',
                    'checkin_from' => '',
                    'checkout_from' => '',
                    'late_arrival' => '',
                    'early_departure' => '',
                    'over_stay' => 0,
                    'actual_shift' => '',
                    'updated_shift' => '',
                    'ot_value' => 0
                ];

                $engFulldate = $data['field'] === 'nepali_date'
                    ? date_converter()->nep_to_eng_convert($fulldate)
                    : $fulldate;

                $day = date('D', strtotime($engFulldate));
                $shiftDetail = $this->getActualEmployeeShift($emp, $engFulldate);
                $employeeShift = $shiftDetail['empActualShift'];
                $seasonalShiftId = $shiftDetail['seasonalShiftId'];

                if ($employeeShift) {
                    $newShiftEmp = NewShiftEmployee::getShiftEmployee($emp->id, $engFulldate);
                    if ($newShiftEmp) {
                        $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
                        if ($rosterShift && $rosterShift->shift_group_id) {
                            $employeeShift = $empUpdatedShift = optional((new ShiftGroupRepository())->find($rosterShift->shift_group_id))->shift;
                            $seasonalShiftId = null;
                        } elseif ($rosterShift->type === 'D') {
                            $empUpdatedShift = 'Day Off';
                        } else {
                            $empUpdatedShift = '';
                        }
                    } else {
                        $empUpdatedShift = '';
                    }

                    if ($employeeShift) {
                        $shiftStartTime = optional($employeeShift->getShiftDayWise($day, $seasonalShiftId))->start_time;
                        $shiftEndTime = optional($employeeShift->getShiftDayWise($day, $seasonalShiftId))->end_time;
                        $days[$fulldate]['actual_working_hr'] = DateTimeHelper::getTimeDiff($shiftStartTime, $shiftEndTime);
                    }
                } else {
                    $days[$fulldate]['actual_working_hr'] = 8;
                }

                $days[$fulldate]['actual_shift'] = $this->getActualShiftNameWithDateRange($emp, $engFulldate);
                $days[$fulldate]['updated_shift'] = $this->getUpdatedShiftNameWithDateRange($emp, $engFulldate);

                if ($type === 'export') {
                    if (in_array($status, ['L', 'HL'])) {
                        $leaveData = $this->getLeaveData($emp->id, $data['field'], $fulldate);
                        if ($leaveData) {
                            $days[$fulldate]['leave_apply_date'] = $leaveData['created_at']->toDateString();
                            $days[$fulldate]['leave_apply_by'] = optional(optional($leaveData->userModel)->userEmployer)->full_name;
                            $days[$fulldate]['leave_reason'] = $leaveData['reason'];
                            $days[$fulldate]['leave_approved_by'] = optional(optional($leaveData->acceptModel)->userEmployer)->full_name;
                        }
                    }

                    $checkinReq = $this->latestCheckInTypeRequestData($emp->id, $data['field'], $fulldate);
                    if ($checkinReq) {
                        $days[$fulldate]['checkin_req_applied_by'] = optional(optional($checkinReq->userModel)->userEmployer)->full_name;
                        $days[$fulldate]['checkin_req_detail'] = $checkinReq->detail;
                        $days[$fulldate]['checkin_req_approved_by'] = optional(optional($checkinReq->approvedByModel)->userEmployer)->full_name;
                    }

                    $checkoutReq = $this->latestCheckOutTypeRequestData($emp->id, $data['field'], $fulldate);
                    if ($checkoutReq) {
                        $days[$fulldate]['checkout_req_applied_by'] = optional(optional($checkoutReq->userModel)->userEmployer)->full_name;
                        $days[$fulldate]['checkout_req_detail'] = $checkoutReq->detail;
                        $days[$fulldate]['checkout_req_approved_by'] = optional(optional($checkoutReq->approvedByModel)->userEmployer)->full_name;
                    }
                }

                $atd = $emp->getSingleAttendance($data['field'], $fulldate);
                if ($atd) {
                    $days[$fulldate]['ot_value'] = $atd->actual_ot ?? 0;

                    $perDayShift = 0;
                    $shiftSeason = null;
                    if (isset($seasonalShiftId)) {
                        $shiftDayWise = $employeeShift->getShiftDayWise($day, $seasonalShiftId);
                        if ($shiftDayWise) {
                            $shiftSeason = $shiftDayWise->shiftSeason;
                            $perDayShift = DateTimeHelper::getTimeDiff($shiftDayWise->start_time, $shiftDayWise->end_time);
                        }
                    }

                    $days[$fulldate]['total_working_hr'] = DateTimeHelper::getTimeDiff($atd->checkin, $atd->checkout);
                    $days[$fulldate]['checkin'] = $atd->checkin;
                    $days[$fulldate]['checkout'] = $atd->checkout;

                    $shift = $this->getShift($emp, $atd, $data['field'], $fulldate);
                    $days[$fulldate]['checkinStatus'] = $shift['checkInShift'];
                    $days[$fulldate]['late_arrival'] = $lateArrivalArray[] = $shift['lateArrival'];

                    $days[$fulldate]['checkoutStatus'] = $shift['checkOutShift'];
                    $days[$fulldate]['early_departure'] = $earlyDepatureArray[] = $shift['earlyDeparture'];

                    $days[$fulldate]['checkin_original'] = $atd->checkin_original;
                    $days[$fulldate]['checkout_original'] = $atd->checkout_original;
                    $days[$fulldate]['checkin_from'] = $atd->checkin_from;
                    $days[$fulldate]['checkout_from'] = $atd->checkout_from;
                    $days[$fulldate]['over_stay'] = $this->overViewOverStay($atd->checkin, $atd->checkout, $perDayShift, $shiftSeason);
                }
            }

            $emp->date = $days;
            $emp->total_late_arrival = array_sum($lateArrivalArray);
            $emp->total_early_departure = array_sum($earlyDepatureArray);
            return $emp;
        }));
    }


    public function labourAttendance($data, $filter, $limit = '', $type)
    {
        if (isset($filter['authUser'])) {
            if ($filter['authUser']['user_type'] == 'division_hr') {
                $filter['org_id'] = optional($filter['authUser']->userEmployer)->organization_id;
            }
        } else {
            $authUser = auth()->user();
            if ($authUser->user_type == 'division_hr') {
                $filter['org_id'] = optional($authUser->userEmployer)->organization_id;
            }
        }

        $query = Labour::query();


        // $query->where('status', 1);
        if (isset($filter['org_id']) && $filter['org_id'] != '') {
            $query = $query->where('organization', $filter['org_id']);
        }


        if (isset($filter['emp_id']) && $filter['emp_id'] != '') {
            $query = $query->whereIn('id', $filter['emp_id']);
        }
        $employees = $query->paginate($limit ? $limit : Config::get('attendance.export-length'));
        return $employees->setCollection($employees->getCollection()->transform(function ($emp) use ($data, $filter, $type) {
            $emp->calendarType = $data['calendarType'];
            $total_worked_days = 0;
            for ($i = 1; $i <= $data['days']; $i++) {
                $fulldate = $data['year'] . '-' . sprintf("%02d", $data['month']) . '-' . sprintf("%02d", $i);
                // $atdRequest = $emp->getAtttendanceRequestByDate($data['field'], $fulldate)->get(['id', 'type', 'status', 'date', 'nepali_date']);
                $days[$fulldate] = [];


                $atd =  $emp->getLabourSingleAttendance($data['field'], $fulldate);
                if ($atd) {
                    if ($atd->is_present == 11) {
                        $days[$fulldate]['status'] = 'P';
                        $total_worked_days++;
                    } else {
                        $days[$fulldate]['status'] = 'A';
                    }
                }
            }
            $emp->date = $days;
            $emp->total_working_days = $data['days'];
            $emp->total_worked_days = $total_worked_days;

            return $emp;
        }));
    }

    public function getCalendarAttendanceDetails($data, $filter, $limit = '')
    {
        $query = Employee::query();
        $query->where('status', 1);

        if (isset($filter['emp_id']) && $filter['emp_id'] != '') {
            $query = $query->whereIn('id', $filter['emp_id']);
        }
        $employees = $query->paginate($limit ? $limit : Config::get('attendance.export-length'));
        return $employees->setCollection($employees->getCollection()->transform(function ($emp) use ($data) {
            $emp->calendarType = 'eng';

            for ($i = 1; $i <= $data['days']; $i++) {
                $fulldate = $data['year'] . '-' . sprintf("%02d", $data['month']) . '-' . sprintf("%02d", $i);
                $days[$fulldate] = [];
                $status = $this->checkStatus($emp, $data['field'], $fulldate);

                if ($status == 'HL' || $status == 'L') {
                    $leave = $this->getLeaveData($emp->id, $data['field'], $fulldate);
                    $title = optional($leave->leaveTypeModel)->name;
                } elseif ($status == 'P' || $status == 'P*') {
                    $attendance = Attendance::where([
                        'emp_id' => $emp->id,
                        $data['field'] => $fulldate
                    ])->first();

                    $checkinFrom = $attendance->checkin_from && $attendance->checkin_from == 'request' ? ' (R)' : '';
                    $checkoutFrom = $attendance->checkout_from && $attendance->checkout_from == 'request' ? ' (R)' : '';

                    $checkin = $attendance->checkin ? 'In: ' . date('h:i A', strtotime($attendance->checkin)) . $checkinFrom : '-';
                    $checkout = $attendance->checkout ? 'Out: ' . date('h:i A', strtotime($attendance->checkout)) . $checkoutFrom : '-';
                    $title = $checkin . "\n" . $checkout;
                } elseif ($status == 'H') {
                    $title = $this->getHolidayName($data['field'], $fulldate);
                } else {
                    $title = '';
                }

                $days[$fulldate] = [
                    'status' => $status,
                    'title' => $title,
                ];
            }
            $emp->date = $days;
            return $emp;
        }));
    }
    public function checkStatus($emp, $field, $fulldate)
    {
        $isHalfLeave = $this->isHalfLeave($emp->id, $field, $fulldate);
        $isPresent = $this->isPresent($emp->id, $field, $fulldate);
        $isPartial = $this->isPartial($emp->id, $field, $fulldate);
        $isLeave = $this->isLeave($emp->id, $field, $fulldate);
        $isHoliday = $this->isHoliday($emp, $field, $fulldate);

        if ($field == 'nepali_date') {
            $fulldate = date_converter()->nep_to_eng_convert($fulldate);
        }



        $dayOffs = $emp->employeeDayOff->pluck('day_off')->toArray();
        $isDayOff = in_array(date('l', strtotime($fulldate)), $dayOffs) ? true : false;
        if ($emp) {
            $newShiftEmp = NewShiftEmployee::getShiftEmployee($emp->id, $fulldate);
            if (isset($newShiftEmp)) {
                $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
                if ($rosterShift->type == 'D') {
                    $isDayOff = true;
                } else {
                    $isDayOff = false;
                }
            }
        }


        if ($isHalfLeave) {
            $status = 'HL';
        } elseif ($isPresent) {
            $status = 'P';
        } elseif ($isPartial) {
            $status = 'P*';
        } elseif ($isLeave) {
            $status = 'L';
        } elseif ($isHoliday) {
            $status = 'H';
        } elseif ($isDayOff) {
            $status = 'D';
        } else {
            $status = 'A';
        }

        return $status;
    }

    // public function getShift($emp, $att, $field, $fulldate)
    // {
    //     if ($field == 'nepali_date') {
    //         $fulldate = date_converter()->nep_to_eng_convert($fulldate);
    //     }
    //     $day = date('D', strtotime($fulldate));
    //     // $checkinStatus = '';
    //     // $checkoutStatus = '';
    //     // $lateArrival = '';
    //     // $earlyDeparture = '';

    //     // $shiftGroup = optional(ShiftGroupMember::where('group_member', $emp->id)->orderBy('id', 'DESC')->first())->group;
    //     $actualShiftGroupMember = ShiftGroupMember::where('group_member', $emp->id)->orderBy('id', 'DESC')->first();
    //     $empShift = optional(optional($actualShiftGroupMember)->group)->shift;
    //     // $empShift = optional($shiftGroup->shift);

    //     $newShiftEmp = NewShiftEmployee::getShiftEmployee($emp->id, $att->date);
    //     if (isset($newShiftEmp)) {
    //         $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
    //         if (isset($rosterShift) && isset($rosterShift->shift_id)) {
    //             $empShift = (new ShiftRepository())->find($rosterShift->shift_id);
    //         }
    //     }
    //     // if ($shiftGroupMember) {
    //     if (isset($empShift)) {
    //         $shiftSeason = $empShift->getShiftSeasonForDate($fulldate);
    //         $seasonalShiftId = null;
    //         if ($shiftSeason) {
    //             $seasonalShiftId = $shiftSeason->id;
    //         }
    //         if ($actualShiftGroupMember) {
    //             $checkinTimeWithGrace = (date('H:i', strtotime(intval('+' . optional($actualShiftGroupMember->group)->ot_grace_period ?? 0) . 'minutes', strtotime(optional($empShift->getShiftDayWise($day,$seasonalShiftId))->start_time))));

    //             // $checkoutTimeWithGrace = (date('H:i', strtotime(intval('-' . optional($actualShiftGroupMember->group)->grace_period_checkout ?? 0) . 'minutes', strtotime(optional($empShift->getShiftDayWise($day))->end_time))));
    //         }else{
    //             $checkinTimeWithGrace = (date('H:i', strtotime(intval('+' . 0) . 'minutes', strtotime(optional($empShift->getShiftDayWise($day,$seasonalShiftId))->start_time))));
    //             // $checkoutTimeWithGrace = (date('H:i', strtotime(intval('-' . 0) . 'minutes', strtotime(optional($empShift->getShiftDayWise($day))->end_time))));
    //         }
    //     }
    //     //Checkin Status
    //     // if (isset($att->checkin) && isset($checkinTimeWithGrace) && $checkinTimeWithGrace < date('H:i', strtotime($att->checkin))) {
    //     //     $checkinStatus = 'Late Arrival';
    //     //     $check_in = Carbon::parse($att->checkin);
    //     //     $checkinGrace = Carbon::parse($checkinTimeWithGrace);
    //     //     $lateArrival = $check_in->diffInMinutes($checkinGrace);
    //     // }

    //     // //Checkout Status
    //     // if (isset($att->checkout) && isset($checkoutTimeWithGrace) && $checkoutTimeWithGrace > date('H:i', strtotime($att->checkout))) {
    //     //     $checkoutStatus = 'Early Departure';
    //     //     $check_out = Carbon::parse($att->checkout);
    //     //     $checkOutGrace = Carbon::parse($checkoutTimeWithGrace);
    //     //     $earlyDeparture = $checkOutGrace->diffInMinutes($check_out);
    //     // }
    //     // }
    //     $late_arrival_in_minutes = (isset($att->late_arrival_in_minutes) && $att->late_arrival_in_minutes > 0) ? $att->late_arrival_in_minutes : '';
    //     $early_departure_in_minutes = (isset($att->early_departure_in_minutes) && $att->early_departure_in_minutes > 0) ? $att->early_departure_in_minutes : '';
    //     return [
    //         'checkInShift' => $late_arrival_in_minutes != '' ? 'Late Arrival' : '',
    //         'lateArrival' => $late_arrival_in_minutes,
    //         'checkOutShift' => $early_departure_in_minutes != '' ? 'Early Departure' : '',
    //         'earlyDeparture' => $early_departure_in_minutes,
    //         'startTime' => $empShift ? $empShift->start_time : '',
    //         'checkinTimeWithGrace' => $checkinTimeWithGrace ?? ''
    //     ];
    // }

    public function getShift($emp, $att, $field, $fulldate)
    {
        if ($field == 'nepali_date') {
            $fulldate = date_converter()->nep_to_eng_convert($fulldate);
        }
        $day = date('D', strtotime($fulldate));
        $checkinStatus = '';
        $checkoutStatus = '';
        $lateArrival = '';
        $earlyDeparture = '';

        $shiftDetail = $this->getActualEmployeeShift($emp, $fulldate);
        $empShift =  $shiftDetail['empActualShift'];
        $seasonalShiftId = $shiftDetail['seasonalShiftId'];
        $shiftGroup = $shiftDetail['shiftGroup'];


        if ($empShift) {
            $newShiftEmp = NewShiftEmployee::getShiftEmployee($emp->id, $att->date);
            if (isset($newShiftEmp)) {
                $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
                if (isset($rosterShift) && isset($rosterShift->shift_group_id)  && ($rosterShift->shift_group_id != null)) {
                    $shiftGroup = (new ShiftGroupRepository())->find($rosterShift->shift_group_id);
                    $empShift = $shiftGroup->shift;
                    $seasonalShiftId = $shiftGroup->shift_season_id;
                }
            }
            // if ($shiftGroupMember) {
            if (isset($empShift)) {
                if ($shiftGroup) {
                    $checkinTimeWithGrace = (date('H:i', strtotime(intval('+' . $shiftGroup->ot_grace_period ?? 0) . 'minutes', strtotime(optional($empShift->getShiftDayWise($day, $seasonalShiftId))->start_time))));

                    $checkoutTimeWithGrace = (date('H:i', strtotime(intval('-' . $shiftGroup->grace_period_checkout ?? 0) . 'minutes', strtotime(optional($empShift->getShiftDayWise($day, $seasonalShiftId))->end_time))));
                } else {
                    $checkinTimeWithGrace = (date('H:i', strtotime(intval('+' . 0) . 'minutes', strtotime(optional($empShift->getShiftDayWise($day, $seasonalShiftId))->start_time))));
                    $checkoutTimeWithGrace = (date('H:i', strtotime(intval('-' . 0) . 'minutes', strtotime(optional($empShift->getShiftDayWise($day, $seasonalShiftId))->end_time))));
                }
            }
        }
        //Checkin Status
        if (isset($att->checkin) && isset($checkinTimeWithGrace) && $checkinTimeWithGrace < date('H:i', strtotime($att->checkin))) {
            $checkinStatus = 'Late Arrival';
            $check_in = Carbon::parse($att->checkin);
            $checkinGrace = Carbon::parse($checkinTimeWithGrace);
            $lateArrival = $check_in->diffInMinutes($checkinGrace);
        }

        //Checkout Status
        if (isset($att->checkout) && isset($checkoutTimeWithGrace) && $checkoutTimeWithGrace > date('H:i', strtotime($att->checkout))) {
            $checkoutStatus = 'Early Departure';
            $check_out = Carbon::parse($att->checkout);
            $checkOutGrace = Carbon::parse($checkoutTimeWithGrace);
            $earlyDeparture = $checkOutGrace->diffInMinutes($check_out);
        }
        // }
        return [
            'checkInShift' => $checkinStatus,
            'lateArrival' => $lateArrival,
            'checkOutShift' => $checkoutStatus,
            'earlyDeparture' => $earlyDeparture,
            'startTime' => $empShift ? optional($empShift->getShiftDayWise($day, $seasonalShiftId))->start_time : '',
            'checkinTimeWithGrace' => $checkinTimeWithGrace ?? ''
        ];
    }

    public function monthlyAttendanceSummary($data, $filter, $limit = '')
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            $filter['org_id'] = optional($authUser->userEmployer)->organization_id;
        }
        $dateType = 'date';
        $year = $data['year'];
        $month = sprintf("%02d", $data['month']);
        if ($data['field'] == 'nepali_date') {
            $total_days = date_converter()->getTotalDaysInMonth($year, $month);
        } else {
            $total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        }
        $start_date = $year . '-' . $month . '-01';
        $end_date = $year . '-' . $month . '-' . $total_days;

        $query = Employee::query();

        //only show if visibility for specific user is checked
        // $query->whereHas('visibilitySetup', function($query) {
        //     $query->where('attendance', 1);
        // });

        if (isset($filter['org_id']) && $filter['org_id'] != '') {
            $query = $query->where('organization_id', $filter['org_id']);
        }

        if (isset($filter['branch_id']) && $filter['branch_id'] != '') {
            $query = $query->where('branch_id', $filter['branch_id']);
        }

        if (isset($filter['emp_id']) && $filter['emp_id'] != '') {
            $query = $query->where('id', $filter['emp_id']);
        }
        if (auth()->user()->user_type == 'employee') {
            $query = $query->where('id', auth()->user()->emp_id);
        } else if (auth()->user()->user_type == 'supervisor') {
            $authEmpId = array(intval(auth()->user()->emp_id));
            $employeeIds = Employee::getSubordinates(auth()->user()->id);
            $allEmployeeIds = array_merge($authEmpId, $employeeIds);
            $query = $query->whereIn('id', $allEmployeeIds);
        }
        //for export function
        $employees = $query->where('status', 1)->paginate($limit ? $limit : Config::get('attendance.export-length'));

        $collection = $employees->getCollection();
        $filteredCollection = $collection->transform(function ($emp) use ($data, $year, $month, $start_date, $end_date) {
            $dayoff = 0;
            $absentDays = 0;
            $dayOffs = $emp->employeeDayOff->pluck('day_off')->toArray();
            $dayOffDateList = [];
            $extra_work_days = 0;
            $half_leave_absent = 0;
            $totalWorkingHours = 0;

            for ($i = 1; $i <= $data['days']; $i++) {
                $fulldate = $year . '-' . $month . '-' . sprintf("%02d", $i);

                $status = $this->checkStatus($emp, $data['field'], $fulldate);
                // if($status && $status == 'A'){
                //     $absentDays += 1;
                // }
                if ($data['field'] == 'nepali_date') {
                    $fulldate = date_converter()->nep_to_eng_convert($fulldate);
                }
                // $dayoff += $emp->dayoff == date('l', strtotime($fulldate)) ? 1 : 0;
                $dayoff += in_array(date('l', strtotime($fulldate)), $dayOffs) ? 1 : 0;

                if (in_array(date('l', strtotime($fulldate)), $dayOffs)) {
                    $nepFullDate = date_converter()->eng_to_nep_convert($fulldate);
                    array_push($dayOffDateList, $nepFullDate);
                }

                //calc for total working hrs
                if ($status != 'D' && $status != 'H') {
                    $day = date('D', strtotime($fulldate));
                    $shiftGroupMember = ShiftGroupMember::where('group_member', $emp->id)->orderBy('id', 'DESC')->first();

                    $shiftGroup = optional(optional(optional($shiftGroupMember)->group));
                    $empShift = $shiftGroup->shift ?? null;


                    $newShiftEmp = NewShiftEmployee::getShiftEmployee($emp->id, $fulldate);
                    if (isset($newShiftEmp)) {
                        $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
                        if (isset($rosterShift) && isset($rosterShift->shift_group_id)) {
                            $empShift = optional((new ShiftGroupRepository())->find($rosterShift->shift_group_id))->shift;
                        }
                    }
                    if ($empShift) {
                        $shiftSeason = $empShift->getShiftSeasonForDate($fulldate);
                        $seasonalShiftId = null;
                        if (!is_null($shiftSeason)) {
                            $seasonalShiftId = $shiftSeason->id;
                        }
                        // $dailyWorkingHours = isset($empShift) ? (strtotime(optional($empShift->getShiftDayWise($day, $seasonalShiftId))->end_time) - strtotime(optional($empShift->getShiftDayWise($day, $seasonalShiftId))->start_time)) / 3600 : 8;

                        $dailyWorkingHours = isset($empShift) ? DateTimeHelper::getTimeDiff(optional($empShift->getShiftDayWise($day, $seasonalShiftId))->start_time, optional($empShift->getShiftDayWise($day, $seasonalShiftId))->end_time) : 8;
                        $totalWorkingHours += $dailyWorkingHours;
                    }
                }
            }
            for ($i = 1; $i <= $data['currentDay']; $i++) {
                $fulldate = $year . '-' . $month . '-' . sprintf("%02d", $i);
                $status = $this->checkStatus($emp, $data['field'], $fulldate);
                // $status = $this->checkStatus($emp, $data['field'], '2081-06-19');
                if (in_array($fulldate, $dayOffDateList)) {
                    if ($status == 'P' || $status == 'P*') {
                        $extra_work_days += 1;
                        $dayoff -= 1;
                    }
                }

                if ($status && $status == 'A') {
                    $absentDays += 1;
                }

                $attendanceExist = Attendance::where('emp_id', $emp->id)->where($data['field'], $fulldate)->exists();
                if ($attendanceExist) {
                    // do nothing
                } else {
                    if ($status && $status == 'HL') {
                        $half_leave_absent += 1;
                    }
                }
            }

            $leaveModel =  Leave::where('employee_id', $emp->id)->whereBetween($data['field'], [$start_date, $end_date])->where('status', '!=', 4)->where('status', '!=', 5);
            // $emp->worked_days = Attendance::where('emp_id', $emp->id)->whereYear($data['field'], $year)->whereMonth($data['field'], $month)->count() - $leaveModel->where('leave_kind', 1)->get()->sum('day');
            if ($half_leave_absent > 0) {
                $emp->worked_days = Attendance::where('emp_id', $emp->id)->whereBetween($data['field'], [$start_date, $end_date])->count();
            } else {
                $emp->worked_days = Attendance::where('emp_id', $emp->id)->whereBetween($data['field'], [$start_date, $end_date])->count() - $leaveModel->where('leave_kind', 1)->get()->sum('day');
            }
            // $emp->leave_taken =  Leave::where('employee_id', $emp->id)->whereYear($data['field'], $year)->whereMonth($data['field'], $month)->where('status', '!=', 4)->count();
            $total_leaves =  Leave::where('employee_id', $emp->id)->whereBetween($data['field'], [$start_date, $end_date])->where('status', '!=', 4)->Where('status', '!=', 5)->count();
            $half_leaves = Leave::where('employee_id', $emp->id)->whereBetween($data['field'], [$start_date, $end_date])->where('leave_kind', 1)->where('status', '!=', 4)->Where('status', '!=', 5)->count();
            $emp->leave_taken = $total_leaves - ($half_leaves / 2);

            if ($data['field'] == 'date') {
                $field = 'eng_date';
            } elseif ($data['field'] == 'nepali_date') {
                $field = 'nep_date';
            }

            $public_holidays = HolidayDetail::whereYear($field, $year)
                ->whereMonth($field, $month)
                ->whereHas('holiday', function ($query) use ($emp) {
                    $query->where('status', 11);
                    $query->GetEmployeeWiseHoliday($emp, true, true);
                })
                ->get();
            $emp->public_holiday = HolidayDetail::whereYear($field, $year)
                ->whereMonth($field, $month)
                ->whereHas('holiday', function ($query) use ($emp) {
                    $query->where('status', 11);
                    $query->GetEmployeeWiseHoliday($emp, true, true);
                })
                ->count();
            $atdExistCount = 0;
            if ($public_holidays->count() > 0) {
                foreach ($public_holidays as $public_holiday) {
                    if (in_array((date('l', strtotime($public_holiday->eng_date))), $dayOffs)) {
                        $dayoff -= 1;
                    }

                    $atdExists = Attendance::where('emp_id', $emp->id)->where('date', $public_holiday->eng_date)->exists();
                    if ($atdExists) {
                        $atdExistCount += 1;
                    }
                }
            }
            $emp->dayoffs = $dayoff;
            $emp->absent_days = $absentDays;
            $emp->total_days = $data['days'];
            $emp->working_days = $data['days'] - $dayoff - $emp->public_holiday - $extra_work_days;
            $emp->working_hour = $totalWorkingHours;
            // dd($start_date, $end_date);
            $overStayValue = 0;
            $overTimeValue = 0;
            Attendance::where('emp_id', $emp->id)->whereBetween($data['field'], [$start_date, $end_date])->get()->map(function ($item) use (&$overStayValue, &$overTimeValue) {
                $shift = optional(optional(ShiftGroupMember::where('group_member', $item->emp_id)->orderBy('id', 'DESC')->first())->group)->shift;
                $newShiftEmp = NewShiftEmployee::getShiftEmployee($item->emp_id, $item->date);
                if (isset($newShiftEmp)) {
                    $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
                    if (isset($rosterShift) && isset($rosterShift->shift_group_id)) {
                        $shift = optional(optional((new ShiftGroupRepository())->find($rosterShift->shift_group_id))->shift);
                    }
                }

                $shift_hr = isset($shift) ? DateTimeHelper::getTimeDiff($shift->start_time, $shift->end_time) : 8;
                if ($item->total_working_hr > $shift_hr && $item->total_working_hr > 0) {
                    $overStayValue += ($item->total_working_hr - $shift_hr);
                }
                $overTime = OvertimeRequest::where([
                    ['employee_id', $item->emp_id],
                    ['nepali_date', $item->nepali_date],
                    ['status', 3],
                ])->first();
                if ($overTime) {
                    if ($item->checkin && $item->checkout) {
                        $overTimeValue = $overTimeValue + $this->monthlyOtValue($item->checkin, $item->checkout, $overTime);
                    }
                }
            });
            $overStayValue = $overStayValue ? $this->convertTimeToMinutes($overStayValue) : 0;
            $remainingMinutes = $overStayValue - $overTimeValue;
            $overStayValue = $this->convertMinutesToTime($remainingMinutes);
            $emp->over_stay = $overStayValue ?? 0;
            $emp->ot_value = $overTimeValue ? $this->convertMinutesToTime($overTimeValue) : '00:00';
            // $emp->worked_hour = Attendance::where('emp_id', $emp->id)->whereYear($data['field'], $year)->whereMonth($data['field'], $month)->sum('total_working_hr');
            $emp->worked_hour = Attendance::where('emp_id', $emp->id)->whereBetween($data['field'], [$start_date, $end_date])->sum('total_working_hr');
            $emp->unworked_hour =  $emp->working_hour - $emp->worked_hour;
            // $emp->paid_leave_taken = Leave::where('employee_id', $emp->id)->whereYear($data['field'], $year)->whereMonth($data['field'], $month)->where('status', '!=', 4)->whereHas('leaveTypeModel', function ($query) {
            //     $query->where('leave_type', 10);
            // })->get()->sum('day');
            $emp->paid_leave_taken = Leave::where('employee_id', $emp->id)->whereBetween($data['field'], [$start_date, $end_date])->where('status', '!=', 4)->where('status', '!=', 5)->whereHas('leaveTypeModel', function ($query) {
                $query->where('leave_type', 10);
            })->get()->sum('day');
            // $emp->unpaid_leave_taken = Leave::where('employee_id', $emp->id)->whereYear($data['field'], $year)->whereMonth($data['field'], $month)->where('status', '!=', 4)->whereHas('leaveTypeModel', function ($query) {
            //     $query->where('leave_type', 11);
            // })->get()->sum('day');
            $emp->unpaid_leave_taken = Leave::where('employee_id', $emp->id)->whereBetween($data['field'], [$start_date, $end_date])->where('status', '!=', 4)->where('status', '!=', 5)->whereHas('leaveTypeModel', function ($query) {
                $query->where('leave_type', 11);
            })->get()->sum('day');

            // dd($emp);

            return $emp;
        });
        return $employees->setCollection($filteredCollection);
    }

    public function monthlyOtValue($checkin, $checkout, $overTimeRequest)
    {
        $startTime = new \DateTime($checkin);
        $endTime = new \DateTime($checkout);
        $checkinTimeRequest = new \DateTime($overTimeRequest->start_time);
        $checkoutTimeRequest = new \DateTime($overTimeRequest->end_time);

        $isCheckinInRange = ($checkinTimeRequest >= $startTime && $checkinTimeRequest <= $endTime);
        $isCheckoutInRange = ($checkoutTimeRequest >= $startTime && $checkoutTimeRequest <= $endTime);
        $totalTime = null;
        if ($isCheckinInRange || $isCheckoutInRange) {
            $start = max($checkinTimeRequest, $startTime);
            $end = min($checkoutTimeRequest, $endTime);
            if ($start < $end) {
                $interval = $start->diff($end);
                $totalTime = $interval->h * 60 + $interval->i;
            }
        }
        return $totalTime;
    }

    public function monthlyLabourAttendanceSummary($data, $filter, $limit = '')
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            $filter['org_id'] = optional($authUser->userEmployer)->organization_id;
        }

        $year = $data['year'];
        $month = sprintf("%02d", $data['month']);
        if ($data['field'] == 'nepali_date') {
            $total_days = date_converter()->getTotalDaysInMonth($year, $month);
        } else {
            $total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        }
        $start_date = $year . '-' . $month . '-01';
        $end_date = $year . '-' . $month . '-' . $total_days;

        $query = Labour::query();

        if (isset($filter['org_id']) && $filter['org_id'] != '') {
            $query = $query->where('organization', $filter['org_id']);
        }


        if (isset($filter['emp_id']) && $filter['emp_id'] != '') {
            $query = $query->where('id', $filter['emp_id']);
        }
        if (auth()->user()->user_type == 'employee') {
            $query = $query->where('id', auth()->user()->emp_id);
        }
        //for export function
        $employees = $query->where('is_archived', 0)->paginate($limit ? $limit : Config::get('attendance.export-length'));
        $filteredCollection = $employees->map(function ($emp) use ($data, $year, $month, $start_date, $end_date) {
            $absentDays = 0;
            $half_leave_absent = 0;


            for ($i = 1; $i <= $data['currentDay']; $i++) {
                $fulldate = $year . '-' . $month . '-' . sprintf("%02d", $i);
                $status = $this->checkStatus($emp, $data['field'], $fulldate);

                if ($status && $status == 'A') {
                    $absentDays += 1;
                }

                $attendanceExist = Attendance::where('emp_id', $emp->id)->where($data['field'], $fulldate)->exists();
                if ($attendanceExist) {
                    // do nothing
                } else {
                    if ($status && $status == 'HL') {
                        $half_leave_absent += 1;
                    }
                }
            }
        });
        return $employees->setCollection($filteredCollection);
    }

    public function employeeRegularAttendanceData($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        // dd($filter);
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            $filter['org_id'] = optional($authUser->userEmployer)->organization_id;
        }

        $query = Employee::query();

        //only show if visibility for specific user is checked
        //  $query->whereHas('visibilitySetup', function($query) {
        //     $query->where('attendance', 1);
        // });
        // $query->select('id');
        $query->where('status', 1);
        // $query->with('leave');
        if (isset($filter['org_id']) && $filter['org_id'] != '') {
            $query = $query->where('organization_id', $filter['org_id']);
        }

        if (isset($filter['branch_id']) && $filter['branch_id'] != '') {
            $query = $query->where('branch_id', $filter['branch_id']);
        }

        if (isset($filter['function_id']) && $filter['function_id'] != '') {
            $query = $query->where('function_id', $filter['function_id']);
        }
        if (isset($filter['department_id']) && $filter['department_id'] != '') {
            $query = $query->where('department_id', $filter['department_id']);
        }

        if (isset($filter['emp_id']) && $filter['emp_id'] != '') {
            $query = $query->where('id', $filter['emp_id']);
        }
        if (isset($filter['status']) && $filter['status'] != '') {
            if ($filter['status'] == 'P' || $filter['status'] == 'P*') {
                $query->whereHas('attendance', function ($q) use ($filter) {
                    $q->whereDate('date', '=', $filter['date_range']);

                    if ($filter['status'] == 'P') {
                        $q->whereNotNull(['checkin', 'checkout']);
                    } elseif ($filter['status'] == 'P*') {
                        $q->where(function ($k) {
                            $k->where(function ($v) {
                                $v->whereNull('checkin')->whereNotNull('checkout');
                            });

                            $k->orWhere(function ($v) {
                                $v->whereNotNull('checkin')->whereNull('checkout');
                            });
                        });
                    }

                    if (isset($filter['type']) && !empty($filter['type']) && isset($filter['medium']) && !empty($filter['medium'])) {
                        if ($filter['type'] == 'checkin') {
                            $q->where('checkin_from', $filter['medium']);
                        } else {
                            $q->where('checkout_from', $filter['medium']);
                        }
                    }

                    // if(isset($filter['medium']) && !empty($filter['medium'])){
                    //     $q->where('checkin_from', $filter['medium']);
                    //     $q->orWhere('checkout_from', $filter['medium']);
                    // }
                });
            } elseif ($filter['status'] == 'L') {
                $query->whereHas('leave', function ($q) {
                    $q->where('leave_kind', '2')->whereStatus(3);
                });
            } elseif ($filter['status'] == 'HL') {
                $query->whereHas('leave', function ($q) {
                    $q->where('leave_kind', '1')->whereStatus(3);
                });
            } elseif ($filter['status'] == 'A') {
                $query->doesntHave('attendance');
            }
        }
        $employees = $query->paginate($limit);
        // $employees = $query->get();
        // return $employees;
        $dateType = 'date';
        $fullDate = $filter['date_range'];
        $dayDate = $fullDate;
        if (isset($filter['calendar_type']) && $filter['calendar_type'] != '') {
            if ($filter['calendar_type'] == 'nep') {
                $dateType = 'nepali_date';
                $fullDate = $filter['nep_date_range'];
                $dayDate = date_converter()->nep_to_eng_convert($fullDate);
            }
        }
        // dd($filter,$fullDate);
        $collection = $employees->getCollection();

        $filteredCollection = $collection->transform(function ($emp) use ($filter, $fullDate, $dateType, $dayDate) {
            $shiftDetail = $this->getActualEmployeeShift($emp, $dayDate);
            $empShift = $empActualShift = $shiftDetail['empActualShift'];
            $seasonalShiftId = $shiftDetail['seasonalShiftId'];

            $day = date('D', strtotime($dayDate));
            // $empUpdatedShift = '';
            // if (isset($empShift)) {

            //     $newShiftEmp = NewShiftEmployee::getShiftEmployee($emp->id, $dayDate);
            //     if (isset($newShiftEmp)) {
            //         $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
            //         if (isset($rosterShift) && isset($rosterShift->shift_group_id) && ($rosterShift->shift_group_id != null)) {
            //             $empShift = $empUpdatedShift = optional((new ShiftGroupRepository())->find($rosterShift->shift_group_id))->shift;
            //             $seasonalShiftId = null;
            //         } elseif($rosterShift->type == 'D'){
            //             $empUpdatedShift = 'Day Off';
            //         }else {
            //             $empUpdatedShift = '';
            //         }
            //     } else {
            //         $empUpdatedShift = '';
            //     }

            // }
            // $perDayShift =  isset($empShift) ? DateTimeHelper::getTimeDiff($empShift->start_time, $empShift->end_time): 8;
            if (isset($empShift) && $empShift != null) {

                $shiftDayWise = $empShift->getShiftDayWise($day, $seasonalShiftId);
            }
            if (isset($shiftDayWise) && $shiftDayWise != null) {
                $shiftSeason = $shiftDayWise->shiftSeason;
            } else {
                $shiftSeason = null;
            }
            $perDayShift = isset($empShift) ? DateTimeHelper::getTimeDiff(optional($shiftDayWise)->start_time, optional($shiftDayWise)->end_time) : 8;
            // dd($perDayShift);
            $total_working_hr = $checkin = $checkout = $checkinStatus = $checkoutStatus = $coordinates = '';
            $checkin_from = '';
            $checkout_from = '';
            $atd = $emp->getSingleAttendance($dateType, $fullDate);
            if ($atd) {
                $shift = $this->getShift($emp, $atd, $dateType, $fullDate);
                // dd($shift);
                $checkinStatus = $shift['checkInShift'];
                $checkoutStatus = $shift['checkOutShift'];
                $startTime = $shift['startTime'];
                $total_working_hr = $atd->total_working_hr;
                $checkin = $atd->checkin;
                $checkout = $atd->checkout;
                $checkin_from = $atd->checkin_from;
                $checkout_from = $atd->checkout_from;
                $coordinates = $atd->getCoordinatesAttributes();
                $checkinTimeWithGrace = $shift['checkinTimeWithGrace'];
            }
            // Calculate early check-in time
            $earlyTime = $this->calculateEarlyCheckIn(@$checkin, @$startTime);
            $lateIn = $this->calculateLateIn(@$checkinTimeWithGrace, @$checkin);
            $overStay = $this->calculateOverStay($emp, $dateType, $fullDate, $checkin, $checkout, $perDayShift, $shiftSeason);
            // dd($overStay);
            //end


            $status = $this->checkStatus($emp, $dateType, $fullDate);
            $holidayName = null;
            if ($status == 'H') {
                $holidayName = $this->getHolidayName($dateType, $fullDate);
            }

            $emp->date = $fullDate;
            $emp->day = date('l', strtotime($dayDate));
            $emp->holidayName = $holidayName;
            $emp->checkinStatus = $checkinStatus;
            $emp->checkoutStatus = $checkoutStatus;
            $emp->coordinates = $coordinates;
            $emp->checkin = $checkin;
            $emp->checkout = $checkout;
            $emp->checkin_from = $checkin_from;
            $emp->checkout_from = $checkout_from;
            $emp->status = $status;
            $emp->total_working_hr = $total_working_hr;
            $emp->early_time = $earlyTime;
            $emp->lateIn = $lateIn;
            $emp->overStay = $overStay['overStayValue'] ?? 0;
            $emp->otValue = $overStay['otValue'] ?? 0;
            // $emp->actual_shift_name = isset($empActualShift->title) ? $empActualShift->title : '';
            // $emp->updated_shift_name = (isset($empUpdatedShift->title) ? $empUpdatedShift->title : $empUpdatedShift) ?? '';
            $emp->actual_shift_name = $this->getActualShiftNameWithDateRange($emp, $fullDate);
            $emp->updated_shift_name = $this->getUpdatedShiftNameWithDateRange($emp, $fullDate);

            return $emp;
        });

        // ->filter(function ($emp) use ($filter) {
        //     if (isset($filter['status']) && $filter['status'] != '') {
        //         return $emp->status == $filter['status'];
        //     }
        //     return $emp;
        // });
        return $employees->setCollection($filteredCollection);

        // return $employees->map(function ($emp) use ($filter) {
        //     if (isset($filter['date_range'])) {
        //         $filterDates = explode(' - ', $filter['date_range']);
        //         $startDate = $filterDates[0];
        //         $endDate = $filterDates[1];

        //         while ($startDate <= $endDate) {
        //             $fullDate = $startDate;

        //             $days[$fullDate] = [];
        //             $status = $this->checkStatus($emp, 'date', $fullDate);
        //             $holidayName = null;
        //             if ($status == 'H') {
        //                 $holidayName = $this->getHolidayName('date', $fullDate);
        //             }
        //             $days[$fullDate] = [
        //                 'status' => $this->checkStatus($emp, 'date', $fullDate),
        //                 'holidayName' => $holidayName,
        //                 'total_working_hr' => '',
        //                 'checkin' => '',
        //                 'checkout' => '',
        //                 'checkinStatus' => '',
        //                 'checkoutStatus' => '',
        //             ];

        //             $atd =  $emp->getSingleAttendance('date', $fullDate);
        //             if ($atd) {
        //                 $days[$fullDate]['total_working_hr'] = $atd->total_working_hr;
        //                 $days[$fullDate]['checkin'] = $atd->checkin;
        //                 $days[$fullDate]['checkout'] = $atd->checkout;

        //                 $shift = $this->getShift($emp, $atd);
        //                 $days[$fullDate]['checkinStatus'] = $shift['checkInShift'];
        //                 $days[$fullDate]['checkoutStatus'] = $shift['checkOutShift'];
        //             }

        //             $startDate = date('Y-m-d', strtotime('+1 day', strtotime($startDate)));     //increase startDate by 1 day
        //         }
        //         $emp->date = $days;
        //         return $emp;
        //     }
        // });


    }

    public function dateRangeAttendanceData($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            $filter['org_id'] = optional($authUser->userEmployer)->organization_id;
        }
        $query = Employee::query();
        $query->where('status', 1);
        $query->with(['department', 'designation'])->whereHas('department')->whereHas('designation');

        if (isset($filter['org_id']) && $filter['org_id'] != '') {
            $query = $query->where('organization_id', $filter['org_id']);
        }
        if (!empty($filter['emp_id'])) {
            $empIds = is_array($filter['emp_id']) ? $filter['emp_id'] : [$filter['emp_id']];
            $query = $query->whereIn('id', $empIds);
        }


        if (isset($filter['branch_id']) && $filter['branch_id'] != '') {
            $query = $query->where('branch_id', $filter['branch_id']);
        }
        if (isset($filter['department_id']) && $filter['department_id'] != '') {
            $query = $query->where('department_id', $filter['department_id']);
        }

        if (isset($filter['status']) && $filter['status'] != '') {
            if ($filter['status'] == 'P' || $filter['status'] == 'P*') {
                $query->whereHas('attendance', function ($q) use ($filter) {
                    $q->whereDate('date', '=', $filter['date_range']);

                    if ($filter['status'] == 'P') {
                        $q->whereNotNull(['checkin', 'checkout']);
                    } elseif ($filter['status'] == 'P*') {
                        $q->where(function ($k) {
                            $k->where(function ($v) {
                                $v->whereNull('checkin')->whereNotNull('checkout');
                            });

                            $k->orWhere(function ($v) {
                                $v->whereNotNull('checkin')->whereNull('checkout');
                            });
                        });
                    }

                    if (isset($filter['type']) && !empty($filter['type']) && isset($filter['medium']) && !empty($filter['medium'])) {
                        if ($filter['type'] == 'checkin') {
                            $q->where('checkin_from', $filter['medium']);
                        } else {
                            $q->where('checkout_from', $filter['medium']);
                        }
                    }
                });
            } elseif ($filter['status'] == 'L') {
                $query->whereHas('leave', function ($q) {
                    $q->where('leave_kind', '2')->whereStatus(3);
                });
            } elseif ($filter['status'] == 'HL') {
                $query->whereHas('leave', function ($q) {
                    $q->where('leave_kind', '1')->whereStatus(3);
                });
            } elseif ($filter['status'] == 'A') {
                $query->doesntHave('attendance');
            }
        }
        $limit = isset($limit) && is_numeric($limit) ? (int) $limit : 10;

        $employees = $query->paginate($limit);

        // dd($employees);
        $dateType = 'date';

        $startDate = Carbon::parse($filter['from_date']);
        $endDate = Carbon::parse($filter['to_date']);
        $rangeDates = new \DatePeriod($startDate, new \DateInterval('P1D'), (clone $endDate)->addDay());
        // dd($startDate, $daysCount = iterator_count($rangeDates));
        if ($filter['from_date']) {
            $finalData = [];

            foreach ($rangeDates as $date) {
                $fullDate = $date->format('Y-m-d');
                $dayDate = $fullDate;

                $collection = $employees->getCollection();

                foreach ($collection as $emp) {
                    $shiftDetail = $this->getActualEmployeeShift($emp, $dayDate);
                    $empShift = $shiftDetail['empActualShift'] ?? null;

                    if ($empShift === null) {
                        continue;
                    }

                    $seasonalShiftId = $shiftDetail['seasonalShiftId'];
                    $day = date('D', strtotime($dayDate));
                    $shiftDayWise = $empShift->getShiftDayWise($day, $seasonalShiftId);

                    if ($shiftDayWise === null) {
                        continue;
                    }

                    $shiftSeason = $shiftDayWise->shiftSeason ?? null;
                    $perDayShift = DateTimeHelper::getTimeDiff($shiftDayWise->start_time, $shiftDayWise->end_time);

                    $atd = $emp->getSingleAttendance($dateType, $fullDate);
                    $total_working_hr = $checkin = $checkout = $checkinStatus = $checkoutStatus = $coordinates = '';
                    $checkin_from = $checkout_from = '';
                    $checkinTimeWithGrace = null;

                    if ($atd) {
                        $shift = $this->getShift($emp, $atd, $dateType, $fullDate);
                        $checkinStatus = $shift['checkInShift'];
                        $checkoutStatus = $shift['checkOutShift'];
                        $startTime = $shift['startTime'];
                        $total_working_hr = $atd->total_working_hr;
                        $checkin = $atd->checkin;
                        $checkout = $atd->checkout;
                        $checkin_from = $atd->checkin_from;
                        $checkout_from = $atd->checkout_from;
                        $coordinates = $atd->getCoordinatesAttributes();
                        $checkinTimeWithGrace = $shift['checkinTimeWithGrace'];
                    }

                    $earlyTime = $this->calculateEarlyCheckIn($checkin, $startTime ?? null);
                    $lateIn = $this->calculateLateIn($checkinTimeWithGrace, $checkin);
                    $overStay = $this->calculateOverStay($emp, $dateType, $fullDate, $checkin, $checkout, $perDayShift, $shiftSeason);
                    $status = $this->checkStatus($emp, $dateType, $fullDate);
                    $holidayName = $status == 'H' ? $this->getHolidayName($dateType, $fullDate) : null;

                    $empId = $emp->id;


                    if (!isset($finalData[$empId])) {
                        $finalData[$empId] = [
                            'employee_id' => $empId,
                            'employee' => $emp,
                            'attendance' => [],
                        ];
                    }

                    $finalData[$empId]['attendance'][] = [
                        'date' => $fullDate,
                        'day' => date('l', strtotime($dayDate)),
                        'holidayName' => $holidayName,
                        'checkinStatus' => $checkinStatus,
                        'checkoutStatus' => $checkoutStatus,
                        'coordinates' => $coordinates,
                        'checkin' => $checkin,
                        'checkout' => $checkout,
                        'checkin_from' => $checkin_from,
                        'checkout_from' => $checkout_from,
                        'status' => $status,
                        'total_working_hr' => $total_working_hr,
                        'early_time' => $earlyTime,
                        'lateIn' => $lateIn,
                        'overStay' => $overStay['overStayValue'] ?? 0,
                        'otValue' => $overStay['otValue'] ?? 0,
                        'actual_shift_name' => $this->getActualShiftNameWithDateRange($emp, $fullDate),
                        'updated_shift_name' => $this->getUpdatedShiftNameWithDateRange($emp, $fullDate),
                    ];
                }
            }

            $finalCollection = collect($finalData);

            $page = request()->get('page', 1);
            $total = $finalCollection->count();

            $results = $finalCollection->slice(($page - 1) * $limit, $limit)->values();

            $paginatedResults = new LengthAwarePaginator(
                $results,
                $total,
                $limit,
                $page,
                [
                    'path' => request()->url(),
                    'query' => request()->query(),
                ]
            );

            return $paginatedResults;
        }

        // fallback if no from_date filter
        return $employees;
    }

    public function getActualEmployeeShift($emp, $date)
    {
        $shiftGroupMembers = ShiftGroupMember::where('group_member', $emp->id)->orderBy('id', 'DESC')->get();
        foreach ($shiftGroupMembers as $key => $groupMember) {

            if ($groupMember->group != null) {
                $shift = optional($groupMember->group)->shift;
                $shiftSeason = $shift->getShiftSeasonForDate($date);
                if ($shiftSeason != null) {
                    if ($shiftSeason) {
                        $seasonalShiftId = $shiftSeason->id;
                        $empActualShift = $shiftSeason->shift;
                        $shiftGroup = ShiftGroup::where('shift_season_id', $seasonalShiftId)->first();
                    } else {
                        $seasonalShiftId = null;
                    }
                }
            }
        }
        return [
            'empActualShift' => $empActualShift ?? null,
            'seasonalShiftId' => $seasonalShiftId ?? null,
            'shiftGroup' => $shiftGroup ?? null
        ];
    }

    public function getActualShiftNameWithDateRange($emp, $date)
    {
        $dayOffList = $emp->getEmployeeDayList();
        $day = date('l', strtotime($date));

        if (in_array($day, $dayOffList)) {
            return 'Day Off';
        }
        $shiftGroupMembers = ShiftGroupMember::where('group_member', $emp->id)->orderBy('id', 'DESC')->get();
        foreach ($shiftGroupMembers as $key => $groupMember) {

            if ($groupMember->group != null) {
                $shift = optional($groupMember->group)->shift;
                $shiftSeason = $shift->getShiftSeasonForDate($date);
                if ($shiftSeason != null) {
                    if ($shiftSeason) {
                        if ($shiftSeason) {
                            $shift = $shiftSeason->shift;
                            return ($shift->title ?? $shift->custom_title) . ' (' . $shiftSeason->date_from . ' - ' . $shiftSeason->date_to . ')';
                        } else {
                            return '';
                        }
                    } else {
                        return '';
                    }
                }
            }
        }
    }

    public function getUpdatedShiftNameWithDateRange($emp, $date)
    {
        $newShiftEmp = NewShiftEmployee::getShiftEmployee($emp->id, $date);
        if (isset($newShiftEmp)) {
            $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
            if ($rosterShift->type == 'D') {
                return 'Day Off';
            } else {
                $shiftSeason = optional($rosterShift->getShiftGroup)->shiftSeason_info;
                if ($shiftSeason) {
                    $shift = $shiftSeason->shift;
                    return ($shift->title ?? $shift->custom_title) . ' (' . $shiftSeason->date_from . ' - ' . $shiftSeason->date_to . ')';
                } else {
                    return '';
                }
            }
        } else {
            return '';
        }
    }

    // This function calculates overstay value and overtime
    public function calculateOverStay($emp, $dateType, $fullDate, $checkin, $checkout, $shiftTime, $shiftSeason)
    {
        // dd($emp, $dateType, $fullDate, $checkin, $checkout, $shiftTime);
        $overStayValue = 0;
        $otValue = 0;

        $otStatus = false;
        $checkinTime = new \DateTime($checkin);
        $checkoutTime = new \DateTime($checkout);
        if ($shiftSeason != null && $shiftSeason->is_multi_day_shift == 1) {
            $checkoutTime->modify('+1 day');
        }

        $interval = $checkinTime->diff($checkoutTime);
        $totalHours = $interval->h + ($interval->d * 24);
        $totalMinutes = $interval->i;
        if ($totalHours > $shiftTime) {
            $overStayValue = ($totalHours - $shiftTime) . (($totalMinutes && $totalMinutes > 0) ? (':' . $totalMinutes) : ':00');
        }

        $otStatus = $this->checkOtValue($emp, $dateType, $fullDate, $checkin, $checkout, $overStayValue);

        if ($otStatus['status']) {
            $data['overStayValue'] = $overStayValue;
            $data['otValue'] = $otStatus['totalOverTimeFormatted'];
        } else {
            $data['overStayValue'] = $overStayValue;
            $data['otValue'] = 0;
        }
        if ($otStatus['status']) {
            $totalTime = $otStatus['overLapTimeFormatted'];
            $totalTimeInMinutes = $this->convertTimeToMinutes($totalTime);
            $overStayValueInMinutes = $this->convertTimeToMinutes($overStayValue);

            $remainingMinutes = $overStayValueInMinutes - $totalTimeInMinutes;

            $remainingTime = $this->convertMinutesToTime($remainingMinutes);

            $data['overStayValue'] = $remainingTime;
        }

        return $data;
    }

    public function overViewOverStay($checkin, $checkout, $shiftTime, $shiftSeason)
    {
        $overStayValue = 0;
        $otValue = 0;

        $otStatus = false;
        $checkinTime = new \DateTime($checkin);
        $checkoutTime = new \DateTime($checkout);
        if ($shiftSeason != null && $shiftSeason->is_multi_day_shift == 1) {
            $checkoutTime->modify('+1 day');
        }
        $interval = $checkinTime->diff($checkoutTime);
        $totalHours = $interval->h + ($interval->d * 24);
        $totalMinutes = $interval->i;

        if ($totalHours > $shiftTime) {
            $overStayValue = ($totalHours - $shiftTime) . (($totalMinutes && $totalMinutes > 0) ? (':' . $totalMinutes) : ':00');
        }
        return $overStayValue;
    }

    public function checkOtValue($emp, $dateType, $fullDate, $checkin, $checkout, $overStayValue)
    {
        $totalOverTime = 0;
        $overLapTime = 0;
        $totalOverTimeFormatted = 0;
        $overLapTimeFormatted = 0;

        $overTimeRequest = OvertimeRequest::where('employee_id', $emp->id)
            ->where($dateType, $fullDate)
            ->where('status', 3)
            ->first();

        if (!$overTimeRequest) {
            return [
                'status' => false,
                'totalOverTimeFormatted' => $totalOverTimeFormatted,
                'overLapTimeFormatted' => $overLapTimeFormatted,
                'overStayValue' => $overStayValue
            ];
        }

        $startTime = new \DateTime($checkin);
        $endTime = new \DateTime($checkout);
        $checkinTimeRequest = new \DateTime($overTimeRequest->start_time);
        $checkoutTimeRequest = new \DateTime($overTimeRequest->end_time);

        $isCheckinInRange = ($checkinTimeRequest >= $startTime && $checkinTimeRequest <= $endTime);
        $isCheckoutInRange = ($checkoutTimeRequest >= $startTime && $checkoutTimeRequest <= $endTime);
        $totalTime = null;

        if ($isCheckinInRange || $isCheckoutInRange) {
            $start = max($checkinTimeRequest, $startTime);
            $end = min($checkoutTimeRequest, $endTime);
            if ($start < $end) {
                $interval = $start->diff($end);
                $totalTime = $interval->format('%H:%I');
            } else {
                $totalTime = "00:00";
            }
        } else {
            $overStayValue = $overStayValue;
        }
        $overtimeInterval = $checkinTimeRequest->diff($checkoutTimeRequest);
        $totalOverTime = ($overtimeInterval->h * 60) + $overtimeInterval->i;
        $totalOverTimeFormatted = $this->convertMinutesToTime($totalOverTime);
        if ($totalTime != "00:00") {
            $overLapTime = $this->convertTimeToMinutes($totalTime);
            $overLapTimeFormatted = $this->convertMinutesToTime($overLapTime);
        }

        return [
            'status' => true,
            'totalOverTimeFormatted' => $totalOverTimeFormatted,
            'overLapTimeFormatted' => $overLapTimeFormatted,
            'overStayValue' => $overStayValue
        ];
    }
    private function convertTimeToMinutes($time)
    {
        $timeParts = explode(":", $time);
        $hours = (int)$timeParts[0];
        $minutes = isset($timeParts[1]) ? (int)$timeParts[1] : 0;
        return ($hours * 60) + $minutes;
    }

    private function convertMinutesToTime($minutes)
    {
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        return sprintf("%02d:%02d", $hours, $remainingMinutes);
    }





    function calculateLateIn($startTime, $checkin)
    {
        if (empty($checkin) || empty($startTime)) {
            return '00:00';
        }

        try {
            $checkinTime = Carbon::createFromFormat('H:i:s', $checkin);
            $startOfficeTime = Carbon::createFromFormat('H:i:s', $startTime);
        } catch (\Exception $e) {
            // Handle invalid time format or missing data gracefully
            return '00:00';
        }

        if ($checkinTime > $startOfficeTime) {
            $lateSeconds = $checkinTime->diffInSeconds($startOfficeTime);
            $lateHours = floor($lateSeconds / 3600);
            $lateMinutes = floor(($lateSeconds % 3600) / 60);
            $remainingSeconds = $lateSeconds % 60;

            if ($lateHours > 0) {
                return sprintf('%02d:%02d:%02d', $lateHours, $lateMinutes, $remainingSeconds) . ' Hrs';
            } else {
                return sprintf('%02d:%02d', $lateMinutes, $remainingSeconds) . ' Min';
            }
        }

        return '00:00';
    }



    function calculateEarlyCheckIn($checkin, $startTime)
    {
        // Check if checkin or startTime is empty
        if (empty($checkin) || empty($startTime)) {
            return '00:00';
        }

        $checkinTime = Carbon::createFromFormat('H:i:s', $checkin);
        $startOfficeTime = Carbon::createFromFormat('H:i', $startTime);

        // Check if check-in time is earlier than start time
        if ($checkinTime < $startOfficeTime) {
            $earlySeconds = $startOfficeTime->diffInSeconds($checkinTime);

            $earlyHours = floor($earlySeconds / 3600);
            $earlyMinutes = floor(($earlySeconds % 3600) / 60);
            $remainingSeconds = $earlySeconds % 60;

            if ($earlyHours > 0) {
                return sprintf('%02d:%02d:%02d', $earlyHours, $earlyMinutes, $remainingSeconds) . ' ' . 'Hrs';
            } else {
                return sprintf('%02d:%02d', $earlyMinutes, $remainingSeconds) . ' ' . 'Min';
            }
        }

        return '00:00'; // No early check-in
    }




    public function getEmployeeOrganizationListBasedOnRole()
    {
        $userId = auth()->user()->id;
        $userType = auth()->user()->user_type;
        $data['employeeData'] = $data['employees'] = $data['organizationList'] = [];

        if ($userType == 'supervisor') {
            $firstApprovalEmps = EmployeeApprovalFlow::where('first_approval_user_id', $userId)->pluck('employee_id')->toArray();
            $lastApprovalEmps = EmployeeApprovalFlow::where('last_approval_user_id', $userId)->pluck('employee_id')->toArray();
            $authEmp = ['0' => intval(auth()->user()->emp_id)];
            $mergeArray = array_unique(array_merge($authEmp, $firstApprovalEmps, $lastApprovalEmps));
            $employeeData = [];
            foreach ($mergeArray as $employeeId) {
                // $employee = Employee::select('first_name', 'middle_name', 'last_name')->where('organization_id', optional(auth()->user()->userEmployer)->organization_id)->where('id', $employeeId)->whereStatus(1)->first();
                $employee = Employee::select('first_name', 'middle_name', 'last_name')->where('id', $employeeId)->whereStatus(1)->first();

                if (isset($employee) && !empty($employee)) {
                    $fullName = isset($employee->middle_name) ? $employee->first_name . ' ' . $employee->middle_name . ' ' . $employee->last_name : $employee->first_name . ' ' . $employee->last_name;

                    $employeeData[$employeeId] = $fullName;
                }
            }
            $data['employeeData'] = $employeeData;
        } elseif ($userType == 'super_admin' || $userType == 'admin' || $userType == 'hr') {
            $data['employees'] = (new EmployeeRepository())->getList();
            $data['organizationList'] = (new OrganizationRepository())->getList();
        } elseif ($userType == 'division_hr') {
            $data['employees'] = Employee::getOrganizationwiseEmployees(['organization_id' => optional(auth()->user()->userEmployer)->organization_id]);
            $data['organizationList'] = (new OrganizationRepository())->getList();
        }
        return $data;
    }

    public function getLeaveData($emp_id, $field, $date)
    {
        return Leave::where('employee_id', $emp_id)->where($field, $date)->whereStatus(3)->first(); //if leave is accepted only then
    }

    public function latestCheckInTypeRequestData($emp_id, $field, $date)
    {
        $checkInType = [1, 4];
        return AttendanceRequest::where('employee_id', $emp_id)->where($field, $date)->where('status', '3')->whereIn('type', $checkInType)->latest()->first();
    }

    public function latestCheckOutTypeRequestData($emp_id, $field, $date)
    {
        $checkOutType = [2, 3];
        return AttendanceRequest::where('employee_id', $emp_id)->where($field, $date)->where('status', '3')->whereIn('type', $checkOutType)->latest()->first();
    }

    public function checkODDRequestExist($date, $employee_id)
    {
        return AttendanceRequest::where('date', $date)->where('employee_id', $employee_id)->where('type', 6)->where('status', 3)->exists();
    }

    public function checkWFHRequestExist($date, $employee_id)
    {
        return AttendanceRequest::where('date', $date)->where('employee_id', $employee_id)->where('type', 7)->where('status', 3)->exists();
    }

    public function checkForceAtdRequestExist($date, $employee_id)
    {
        return AttendanceRequest::where('date', $date)->where('employee_id', $employee_id)->where('type', 5)->where('status', 3)->exists();
    }

    public function divisionAttendanceReport($limit = null, $filter = [])
    {
        $day = date('D', strtotime($filter['date']));
        $query = Employee::query();
        $query->where('status', 1)->where('organization_id', optional(auth()->user()->userEmployer)->organization_id)->where('id', '!=', auth()->user()->emp_id);
        $query->whereHas('getUser', function ($q) {
            $q->where('user_type', 'employee');
        });
        $employees = $query->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        $collection = $employees->getCollection();

        $filteredCollection = $collection->transform(function ($emp) use ($filter, $day) {
            $checkin = $checkout = $remarks = '';
            $shift_hr = $worked_hr = $ot_hr = 0;
            $status = $is_absent = null;
            $empShift = optional(optional(ShiftGroupMember::where('group_member', $emp->id)->orderBy('id', 'DESC')->first())->group)->shift;
            $newShiftEmp = NewShiftEmployee::getShiftEmployee($emp->id, $filter['date']);
            if (isset($newShiftEmp)) {
                $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
                if (isset($rosterShift) && isset($rosterShift->shift_group_id)) {
                    $empShift = optional((new ShiftGroupRepository())->find($rosterShift->shift_group_id)->shift);
                }
            }
            if (isset($empShift)) {
                $shiftSeason = $empShift->getShiftSeasonForDate($filter['date']);
                $seasonalShiftId = null;
                if ($shiftSeason) {
                    $seasonalShiftId = $shiftSeason->id;
                }
                $checkin = optional($empShift->getShiftDayWise($day, $seasonalShiftId))->start_time;
                $checkout = optional($empShift->getShiftDayWise($day, $seasonalShiftId))->end_time;
                $shift_hr = $worked_hr = DateTimeHelper::getTimeDiff($checkin, $checkout);
            }

            $atd = $emp->getSingleSiteAttendance($filter['date']);
            if ($atd) {
                $checkin = $atd->checkin;
                $checkout = $atd->checkout;
                $worked_hr = $atd->worked_hr;
                $ot_hr = $atd->ot_hr;
                $remarks = $atd->remarks;
                $status = $atd->status;
                $is_absent = $atd->is_absent;
            }
            $emp->date = $filter['date'];
            $emp->checkin = $checkin;
            $emp->checkout = $checkout;
            $emp->status = $status;
            $emp->shift_hr = $shift_hr;
            $emp->worked_hr = $worked_hr;
            $emp->ot_hr = $ot_hr;
            $emp->remarks = $remarks;
            $emp->is_absent = $is_absent;

            return $emp;
        });
        return $employees->setCollection($filteredCollection);
    }
    // old site attendance monthly
    public function siteAttendanceMonthly($data, $filter, $limit = null)
    {
        if (auth()->user()->user_type == 'division_hr' || auth()->user()->user_type == 'supervisor') {
            $filter['org_id'] = optional(auth()->user()->userEmployer)->organization_id;
        } elseif (auth()->user()->user_type == 'employee') {
            $filter['emp_id'] = auth()->user()->emp_id;
        }

        $select = ['id', 'employee_code', 'first_name', 'middle_name', 'last_name'];
        $query = Employee::query();

        //only show if visibility for specific user is checked
        // $query->whereHas('visibilitySetup', function($query) {
        //     $query->where('attendance', 1);
        // });

        $query->select($select);
        $query->where('status', 1);

        if (isset($filter['org_id']) && $filter['org_id'] != '') {
            $query = $query->where('organization_id', $filter['org_id']);
        }

        if (isset($filter['emp_id']) && $filter['emp_id'] != '') {
            $query = $query->where('id', $filter['emp_id']);
        }
        $employees = $query->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));

        return $employees->setCollection($employees->getCollection()->transform(function ($emp) use ($data) {
            $emp->calendarType = $data['calendarType'];

            for ($i = 1; $i <= $data['days']; $i++) {
                $fulldate = $data['year'] . '-' . sprintf("%02d", $data['month']) . '-' . sprintf("%02d", $i);
                $days[$fulldate] = [];
                $siteAtdMonthly = $this->findSiteAtdMonthly($emp->id, $data['field'], $fulldate);
                $days[$fulldate]['is_present'] = isset($siteAtdMonthly) ? $siteAtdMonthly->is_present : 11;
            }
            $emp->date = $days;
            return $emp;
        }));
    }

    public function labourSiteAttendanceMonthly($data, $filter, $limit = null)
    {

        $query = Labour::query();



        if (isset($filter['org_id']) && $filter['org_id'] != '') {
            $query = $query->where('organization', $filter['org_id']);
        }

        if (isset($filter['emp_id']) && $filter['emp_id'] != '') {
            $query = $query->where('id', $filter['emp_id']);
        }
        // $query->where('is_archived',0);
        $employees = $query->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $employees->setCollection($employees->getCollection()->transform(function ($emp) use ($data) {

            $emp->calendarType = $data['calendarType'];

            for ($i = 1; $i <= $data['days']; $i++) {
                $fulldate = $data['year'] . '-' . sprintf("%02d", $data['month']) . '-' . sprintf("%02d", $i);
                $days[$fulldate] = [];
                $siteAtdMonthly = $this->findSiteLabourAtdMonthly($emp->id, $data['field'], $fulldate);
                $days[$fulldate]['is_present'] = isset($siteAtdMonthly) ? $siteAtdMonthly->is_present : 11;
            }
            $emp->date = $days;
            return $emp;
        }));
    }

    public function findSiteAtdMonthly($employeeId, $field, $date)
    {
        return DivisionAttendanceMonthly::where('employee_id', $employeeId)->where($field, $date)->first();
    }

    public function saveSiteAtdMonthly($data)
    {
        return DivisionAttendanceMonthly::create($data);
    }

    public function findSiteLabourAtdMonthly($employeeId, $field, $date)
    {
        return LabourAttendanceMonthly::where('employee_id', $employeeId)->where($field, $date)->first();
    }

    public function saveSiteLabourAtdMonthly($data)
    {
        return LabourAttendanceMonthly::create($data);
    }

    public function checkLockedStatus($request)
    {
        $data = AttendanceOrganizationLock::where('organization_id', $request->organization_id ?? $request->org_id)->where('calender_type', $request->calendar_type)->where('year', $request->nep_year)->where('month', $request->nep_month)->where('lock_type', 2)->first();
        if ($data) {
            return [
                'status' => true,
                'data' => $data
            ];
        }
        return [
            'status' => false,
            'data' => null
        ];
    }

    public function setLockData($data, $status)
    {
        $request = $data->request_data;
        $attendanceOrgLk = $this->attendanceOrgLk($request, $status);
        return $attendanceOrgLk;
    }

    public function attendanceOrgLk($request, $status)
    {
        $calendar_type = $request->calendar_type;
        $currentTime = Carbon::now()->format('H:i:s');
        $currentEngDate = date('Y-m-d');
        $currentNepDateArray = date_converter()->eng_to_nep(date('Y'), date('m'), date('d'));
        $data = [
            'organization_id' => $request->organization_id ?? $request->org_id,
            'calender_type'   => $calendar_type,
            'year'            => $request->{$calendar_type . '_year'},
            'month'           =>  $request->{$calendar_type . '_month'},
            'created_np_datetime' => $currentNepDateArray['year'] . '-' . $currentNepDateArray['month'] . '-' . $currentNepDateArray['date'] . ' ' . $currentTime,
            'created_eng_datetime' => $currentEngDate . ' ' . $currentTime,
            'lock_type'       => $status
        ];
        return $data;
    }

    public function setEmpData($orgData, $emps)
    {
        $temp = [];
        foreach ($emps as $emp) {
            $temp[] = [
                'attendance_organization_lock_id' => $orgData->id,
                'employee_id' => $emp->id,
                'organization_id' => $orgData->organization_id,
                'calender_type' => $orgData->calender_type,
                'total_days' => $emp->total_days,
                'working_days' => $emp->working_days,
                'dayoffs' => $emp->dayoffs,
                'public_holiday' => $emp->public_holiday,
                'working_hour' => $emp->working_hour,
                'worked_days' => $emp->worked_days,
                'worked_hour' => $emp->worked_hour,
                'unworked_hour' => $emp->unworked_hour,
                'leave_taken' => $emp->leave_taken,
                'paid_leave_taken' => $emp->paid_leave_taken,
                'unpaid_leave_taken' => $emp->unpaid_leave_taken,
                'absent_days' => $emp->absent_days,
                'over_stay' => $emp->over_stay,
                'ot_value' => $emp->ot_value,
                'lock_type' => $orgData->lock_type
            ];
        }
        return $temp;
    }

    public function attendanceVerificationSummary($data, $filter, $limit = '')
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            $filter['org_id'] = optional($authUser->userEmployer)->organization_id;
        }
        $dateType = 'date';
        $year = $data['year'];
        $month = sprintf("%02d", $data['month']);
        if ($data['field'] == 'nepali_date') {
            $total_days = date_converter()->getTotalDaysInMonth($year, $month);
        } else {
            $total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        }
        $start_date = $year . '-' . $month . '-01';
        $end_date = $year . '-' . $month . '-' . $total_days;

        $query = Employee::query();

        //only show if visibility for specific user is checked
        // $query->whereHas('visibilitySetup', function($query) {
        //     $query->where('attendance', 1);
        // });

        if (isset($filter['org_id']) && $filter['org_id'] != '') {
            $query = $query->where('organization_id', $filter['org_id']);
        }

        if (isset($filter['branch_id']) && $filter['branch_id'] != '') {
            $query = $query->where('branch_id', $filter['branch_id']);
        }

        if (isset($filter['emp_id']) && $filter['emp_id'] != '') {
            $query = $query->where('id', $filter['emp_id']);
        }
        if (auth()->user()->user_type == 'employee') {
            $query = $query->where('id', auth()->user()->emp_id);
        } else if (auth()->user()->user_type == 'supervisor') {
            $authEmpId = array(intval(auth()->user()->emp_id));
            $employeeIds = Employee::getSubordinates(auth()->user()->id);
            $allEmployeeIds = array_merge($authEmpId, $employeeIds);
            $query = $query->whereIn('id', $allEmployeeIds);
        }
        //for export function
        $employees = $query->where('status', 1)->paginate($limit ? $limit : Config::get('attendance.export-length'));

        $collection = $employees->getCollection();
        $filteredCollection = $collection->transform(function ($emp) use ($data, $year, $month, $start_date, $end_date) {
            $dayoff = 0;
            $absentDays = 0;
            $dayOffs = $emp->employeeDayOff->pluck('day_off')->toArray();
            $dayOffDateList = [];
            $extra_work_days = 0;
            $half_leave_absent = 0;
            $totalWorkingHours = 0;

            for ($i = 1; $i <= $data['days']; $i++) {
                $fulldate = $year . '-' . $month . '-' . sprintf("%02d", $i);

                $status = $this->checkStatus($emp, $data['field'], $fulldate);
                // if($status && $status == 'A'){
                //     $absentDays += 1;
                // }
                if ($data['field'] == 'nepali_date') {
                    $fulldate = date_converter()->nep_to_eng_convert($fulldate);
                }
                // $dayoff += $emp->dayoff == date('l', strtotime($fulldate)) ? 1 : 0;
                $dayoff += in_array(date('l', strtotime($fulldate)), $dayOffs) ? 1 : 0;

                if (in_array(date('l', strtotime($fulldate)), $dayOffs)) {
                    $nepFullDate = date_converter()->eng_to_nep_convert($fulldate);
                    array_push($dayOffDateList, $nepFullDate);
                }

                //calc for total working hrs
                if ($status != 'D' && $status != 'H') {
                    $empShift = optional(optional(ShiftGroupMember::where('group_member', $emp->id)->orderBy('id', 'DESC')->first())->group)->shift;
                    $newShiftEmp = NewShiftEmployee::getShiftEmployee($emp->id, $fulldate);
                    if (isset($newShiftEmp)) {
                        $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
                        if (isset($rosterShift) && isset($rosterShift->shift_group_id)) {
                            $empShift = optional((new ShiftGroupRepository())->find($rosterShift->shift_group_id)->shift);
                        }
                    }
                    $dailyWorkingHours = isset($empShift) ? DateTimeHelper::getTimeDiff($empShift->start_time, $empShift->end_time) : 8;
                    $totalWorkingHours += $dailyWorkingHours;
                }
            }
            for ($i = 1; $i <= $data['currentDay']; $i++) {
                $fulldate = $year . '-' . $month . '-' . sprintf("%02d", $i);
                $status = $this->checkStatus($emp, $data['field'], $fulldate);
                // $status = $this->checkStatus($emp, $data['field'], '2081-06-19');
                if (in_array($fulldate, $dayOffDateList)) {
                    if ($status == 'P' || $status == 'P*') {
                        $extra_work_days += 1;
                        $dayoff -= 1;
                    }
                }

                if ($status && $status == 'A') {
                    $absentDays += 1;
                }

                $attendanceExist = Attendance::where('emp_id', $emp->id)->where($data['field'], $fulldate)->exists();
                if ($attendanceExist) {
                    // do nothing
                } else {
                    if ($status && $status == 'HL') {
                        $half_leave_absent += 1;
                    }
                }
            }

            $leaveModel =  Leave::where('employee_id', $emp->id)->whereBetween($data['field'], [$start_date, $end_date])->where('status', '!=', 4)->where('status', '!=', 5);
            // $emp->worked_days = Attendance::where('emp_id', $emp->id)->whereYear($data['field'], $year)->whereMonth($data['field'], $month)->count() - $leaveModel->where('leave_kind', 1)->get()->sum('day');
            if ($half_leave_absent > 0) {
                $emp->worked_days = Attendance::where('emp_id', $emp->id)->whereBetween($data['field'], [$start_date, $end_date])->count();
            } else {
                $emp->worked_days = Attendance::where('emp_id', $emp->id)->whereBetween($data['field'], [$start_date, $end_date])->count() - $leaveModel->where('leave_kind', 1)->get()->sum('day');
            }
            // $emp->leave_taken =  Leave::where('employee_id', $emp->id)->whereYear($data['field'], $year)->whereMonth($data['field'], $month)->where('status', '!=', 4)->count();
            $total_leaves =  Leave::where('employee_id', $emp->id)->whereBetween($data['field'], [$start_date, $end_date])->where('status', '!=', 4)->Where('status', '!=', 5)->count();
            $half_leaves = Leave::where('employee_id', $emp->id)->whereBetween($data['field'], [$start_date, $end_date])->where('leave_kind', 1)->where('status', '!=', 4)->Where('status', '!=', 5)->count();
            $emp->leave_taken = $total_leaves - ($half_leaves / 2);

            if ($data['field'] == 'date') {
                $field = 'eng_date';
            } elseif ($data['field'] == 'nepali_date') {
                $field = 'nep_date';
            }

            $public_holidays = HolidayDetail::whereYear($field, $year)
                ->whereMonth($field, $month)
                ->whereHas('holiday', function ($query) use ($emp) {
                    $query->where('status', 11);
                    $query->GetEmployeeWiseHoliday($emp, true, true);
                })
                ->get();
            $emp->public_holiday = HolidayDetail::whereYear($field, $year)
                ->whereMonth($field, $month)
                ->whereHas('holiday', function ($query) use ($emp) {
                    $query->where('status', 11);
                    $query->GetEmployeeWiseHoliday($emp, true, true);
                })
                ->count();
            $atdExistCount = 0;
            if ($public_holidays->count() > 0) {
                foreach ($public_holidays as $public_holiday) {
                    if (in_array((date('l', strtotime($public_holiday->eng_date))), $dayOffs)) {
                        $dayoff -= 1;
                    }

                    $atdExists = Attendance::where('emp_id', $emp->id)->where('date', $public_holiday->eng_date)->exists();
                    if ($atdExists) {
                        $atdExistCount += 1;
                    }
                }
            }
            $emp->dayoffs = $dayoff;
            $emp->absent_days = $absentDays;
            $emp->total_days = $data['days'];
            $emp->working_days = $data['days'] - $dayoff - $emp->public_holiday - $extra_work_days;
            $emp->working_hour = $totalWorkingHours;
            // dd($start_date, $end_date);
            $overStayValue = 0;
            $overTimeValue = 0;
            Attendance::where('emp_id', $emp->id)->whereBetween($data['field'], [$start_date, $end_date])->get()->map(function ($item) use (&$overStayValue, &$overTimeValue) {
                $shift = optional(optional(ShiftGroupMember::where('group_member', $item->emp_id)->orderBy('id', 'DESC')->first())->group)->shift;
                $newShiftEmp = NewShiftEmployee::getShiftEmployee($item->emp_id, $item->date);
                if (isset($newShiftEmp)) {
                    $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
                    if (isset($rosterShift) && isset($rosterShift->shift_group_id)) {
                        $shift = optional((new ShiftGroupRepository())->find($rosterShift->shift_group_id)->shift);
                    }
                }
                $shift_hr = isset($shift) ? DateTimeHelper::getTimeDiff($shift->start_time, $shift->end_time) : 8;
                if ($item->total_working_hr > $shift_hr && $item->total_working_hr > 0) {
                    $overStayValue += ($item->total_working_hr - $shift_hr);
                }
                $overTime = OvertimeRequest::where([
                    ['employee_id', $item->emp_id],
                    ['nepali_date', $item->nepali_date],
                    ['status', 3],
                ])->first();
                if ($overTime) {
                    if ($item->checkin && $item->checkout) {
                        $overTimeValue = $overTimeValue + $this->monthlyOtValue($item->checkin, $item->checkout, $overTime);
                    }
                }
            });
            $overStayValue = $overStayValue ? $this->convertTimeToMinutes($overStayValue) : 0;
            $remainingMinutes = $overStayValue - $overTimeValue;
            $overStayValue = $this->convertMinutesToTime($remainingMinutes);
            $emp->over_stay = $overStayValue ?? 0;
            $emp->ot_value = $overTimeValue ? $this->convertMinutesToTime($overTimeValue) : '00:00';
            // $emp->worked_hour = Attendance::where('emp_id', $emp->id)->whereYear($data['field'], $year)->whereMonth($data['field'], $month)->sum('total_working_hr');
            $emp->worked_hour = Attendance::where('emp_id', $emp->id)->whereBetween($data['field'], [$start_date, $end_date])->sum('total_working_hr');
            $emp->unworked_hour =  $emp->working_hour - $emp->worked_hour;
            // $emp->paid_leave_taken = Leave::where('employee_id', $emp->id)->whereYear($data['field'], $year)->whereMonth($data['field'], $month)->where('status', '!=', 4)->whereHas('leaveTypeModel', function ($query) {
            //     $query->where('leave_type', 10);
            // })->get()->sum('day');
            $emp->paid_leave_taken = Leave::where('employee_id', $emp->id)->whereBetween($data['field'], [$start_date, $end_date])->where('status', '!=', 4)->where('status', '!=', 5)->whereHas('leaveTypeModel', function ($query) {
                $query->where('leave_type', 10);
            })->get()->sum('day');
            // $emp->unpaid_leave_taken = Leave::where('employee_id', $emp->id)->whereYear($data['field'], $year)->whereMonth($data['field'], $month)->where('status', '!=', 4)->whereHas('leaveTypeModel', function ($query) {
            //     $query->where('leave_type', 11);
            // })->get()->sum('day');
            $emp->unpaid_leave_taken = Leave::where('employee_id', $emp->id)->whereBetween($data['field'], [$start_date, $end_date])->where('status', '!=', 4)->where('status', '!=', 5)->whereHas('leaveTypeModel', function ($query) {
                $query->where('leave_type', 11);
            })->get()->sum('day');

            // dd($emp);

            return $emp;
        });
        return $employees->setCollection($filteredCollection);
    }

    public function getEmployeeData($formDataValue, $filterData)
    {
        $data = [
            "show" => $formDataValue->show,
            "field" => $formDataValue->field,
            "days" => $formDataValue->days,
            "currentDay" => $formDataValue->currentDay,
            "year" => $formDataValue->year,
            "month" => $formDataValue->month
        ];
        $filter = [
            "organization_id" => $filterData->organization_id ?? null,
            "calendar_type" => $filterData->calendar_type ?? null,
            "nep_year" => $filterData->nep_year ?? null,
            "nep_month" => $filterData->nep_month ?? null,
            "org_id" => $filterData->org_id ?? null,
        ];
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            $filter['org_id'] = optional($authUser->userEmployer)->organization_id;
        }
        $dateType = 'date';
        $year = $data['year'];
        $month = sprintf("%02d", $data['month']);
        if ($data['field'] == 'nepali_date') {
            $total_days = date_converter()->getTotalDaysInMonth($year, $month);
        } else {
            $total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        }
        $start_date = $year . '-' . $month . '-01';
        $end_date = $year . '-' . $month . '-' . $total_days;

        $query = Employee::query();

        //only show if visibility for specific user is checked
        // $query->whereHas('visibilitySetup', function($query) {
        //     $query->where('attendance', 1);
        // });

        if (isset($filter['org_id']) && $filter['org_id'] != '') {
            $query = $query->where('organization_id', $filter['org_id']);
        }

        if (isset($filter['branch_id']) && $filter['branch_id'] != '') {
            $query = $query->where('branch_id', $filter['branch_id']);
        }

        if (isset($filter['emp_id']) && $filter['emp_id'] != '') {
            $query = $query->where('id', $filter['emp_id']);
        }
        if (auth()->user()->user_type == 'employee') {
            $query = $query->where('id', auth()->user()->emp_id);
        } else if (auth()->user()->user_type == 'supervisor') {
            $authEmpId = array(intval(auth()->user()->emp_id));
            $employeeIds = Employee::getSubordinates(auth()->user()->id);
            $allEmployeeIds = array_merge($authEmpId, $employeeIds);
            $query = $query->whereIn('id', $allEmployeeIds);
        }
        //for export function
        $employees = $query->where('status', 1)->get();
        $collection = $employees;
        $filteredCollection = $collection->transform(function ($emp) use ($data, $year, $month, $start_date, $end_date) {
            $dayoff = 0;
            $absentDays = 0;
            $dayOffs = $emp->employeeDayOff->pluck('day_off')->toArray();
            $dayOffDateList = [];
            $extra_work_days = 0;
            $half_leave_absent = 0;
            $totalWorkingHours = 0;

            for ($i = 1; $i <= $data['days']; $i++) {
                $fulldate = $year . '-' . $month . '-' . sprintf("%02d", $i);

                $status = $this->checkStatus($emp, $data['field'], $fulldate);
                // if($status && $status == 'A'){
                //     $absentDays += 1;
                // }
                if ($data['field'] == 'nepali_date') {
                    $fulldate = date_converter()->nep_to_eng_convert($fulldate);
                }
                // $dayoff += $emp->dayoff == date('l', strtotime($fulldate)) ? 1 : 0;
                $dayoff += in_array(date('l', strtotime($fulldate)), $dayOffs) ? 1 : 0;

                if (in_array(date('l', strtotime($fulldate)), $dayOffs)) {
                    $nepFullDate = date_converter()->eng_to_nep_convert($fulldate);
                    array_push($dayOffDateList, $nepFullDate);
                }

                //calc for total working hrs
                if ($status != 'D' && $status != 'H') {
                    $empShift = optional(optional(ShiftGroupMember::where('group_member', $emp->id)->orderBy('id', 'DESC')->first())->group)->shift;
                    $newShiftEmp = NewShiftEmployee::getShiftEmployee($emp->id, $fulldate);
                    if (isset($newShiftEmp)) {
                        $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
                        if (isset($rosterShift) && isset($rosterShift->shift_group_id)) {
                            $empShift = optional((new ShiftGroupRepository())->find($rosterShift->shift_group_id)->shift);
                        }
                    }
                    $dailyWorkingHours = isset($empShift) ?  DateTimeHelper::getTimeDiff($empShift->start_time, $empShift->end_time) : 8;
                    $totalWorkingHours += $dailyWorkingHours;
                }
            }
            for ($i = 1; $i <= $data['currentDay']; $i++) {
                $fulldate = $year . '-' . $month . '-' . sprintf("%02d", $i);
                $status = $this->checkStatus($emp, $data['field'], $fulldate);
                // $status = $this->checkStatus($emp, $data['field'], '2081-06-19');
                if (in_array($fulldate, $dayOffDateList)) {
                    if ($status == 'P' || $status == 'P*') {
                        $extra_work_days += 1;
                        $dayoff -= 1;
                    }
                }

                if ($status && $status == 'A') {
                    $absentDays += 1;
                }

                $attendanceExist = Attendance::where('emp_id', $emp->id)->where($data['field'], $fulldate)->exists();
                if ($attendanceExist) {
                    // do nothing
                } else {
                    if ($status && $status == 'HL') {
                        $half_leave_absent += 1;
                    }
                }
            }

            $leaveModel =  Leave::where('employee_id', $emp->id)->whereBetween($data['field'], [$start_date, $end_date])->where('status', '!=', 4)->where('status', '!=', 5);
            // $emp->worked_days = Attendance::where('emp_id', $emp->id)->whereYear($data['field'], $year)->whereMonth($data['field'], $month)->count() - $leaveModel->where('leave_kind', 1)->get()->sum('day');
            if ($half_leave_absent > 0) {
                $emp->worked_days = Attendance::where('emp_id', $emp->id)->whereBetween($data['field'], [$start_date, $end_date])->count();
            } else {
                $emp->worked_days = Attendance::where('emp_id', $emp->id)->whereBetween($data['field'], [$start_date, $end_date])->count() - $leaveModel->where('leave_kind', 1)->get()->sum('day');
            }
            // $emp->leave_taken =  Leave::where('employee_id', $emp->id)->whereYear($data['field'], $year)->whereMonth($data['field'], $month)->where('status', '!=', 4)->count();
            $total_leaves =  Leave::where('employee_id', $emp->id)->whereBetween($data['field'], [$start_date, $end_date])->where('status', '!=', 4)->Where('status', '!=', 5)->count();
            $half_leaves = Leave::where('employee_id', $emp->id)->whereBetween($data['field'], [$start_date, $end_date])->where('leave_kind', 1)->where('status', '!=', 4)->Where('status', '!=', 5)->count();
            $emp->leave_taken = $total_leaves - ($half_leaves / 2);

            if ($data['field'] == 'date') {
                $field = 'eng_date';
            } elseif ($data['field'] == 'nepali_date') {
                $field = 'nep_date';
            }

            $public_holidays = HolidayDetail::whereYear($field, $year)
                ->whereMonth($field, $month)
                ->whereHas('holiday', function ($query) use ($emp) {
                    $query->where('status', 11);
                    $query->GetEmployeeWiseHoliday($emp, true, true);
                })
                ->get();
            $emp->public_holiday = HolidayDetail::whereYear($field, $year)
                ->whereMonth($field, $month)
                ->whereHas('holiday', function ($query) use ($emp) {
                    $query->where('status', 11);
                    $query->GetEmployeeWiseHoliday($emp, true, true);
                })
                ->count();
            $atdExistCount = 0;
            if ($public_holidays->count() > 0) {
                foreach ($public_holidays as $public_holiday) {
                    if (in_array((date('l', strtotime($public_holiday->eng_date))), $dayOffs)) {
                        $dayoff -= 1;
                    }

                    $atdExists = Attendance::where('emp_id', $emp->id)->where('date', $public_holiday->eng_date)->exists();
                    if ($atdExists) {
                        $atdExistCount += 1;
                    }
                }
            }
            $emp->dayoffs = $dayoff;
            $emp->absent_days = $absentDays;
            $emp->total_days = $data['days'];
            $emp->working_days = $data['days'] - $dayoff - $emp->public_holiday - $extra_work_days;
            $emp->working_hour = $totalWorkingHours;
            // dd($start_date, $end_date);
            $overStayValue = 0;
            $overTimeValue = 0;
            Attendance::where('emp_id', $emp->id)->whereBetween($data['field'], [$start_date, $end_date])->get()->map(function ($item) use (&$overStayValue, &$overTimeValue) {
                $shift = optional(optional(ShiftGroupMember::where('group_member', $item->emp_id)->orderBy('id', 'DESC')->first())->group)->shift;
                $newShiftEmp = NewShiftEmployee::getShiftEmployee($item->emp_id, $item->date);
                if (isset($newShiftEmp)) {
                    $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
                    if (isset($rosterShift) && isset($rosterShift->shift_group_id)) {
                        $shift = optional((new ShiftGroupRepository())->find($rosterShift->shift_group_id)->shift);
                    }
                }
                $shift_hr = isset($shift) ? DateTimeHelper::getTimeDiff($shift->start_time, $shift->end_time) : 8;
                if ($item->total_working_hr > $shift_hr && $item->total_working_hr > 0) {
                    $overStayValue += ($item->total_working_hr - $shift_hr);
                }
                $overTime = OvertimeRequest::where([
                    ['employee_id', $item->emp_id],
                    ['nepali_date', $item->nepali_date],
                    ['status', 3],
                ])->first();
                if ($overTime) {
                    if ($item->checkin && $item->checkout) {
                        $overTimeValue = $overTimeValue + $this->monthlyOtValue($item->checkin, $item->checkout, $overTime);
                    }
                }
            });
            $overStayValue = $overStayValue ? $this->convertTimeToMinutes($overStayValue) : 0;
            $remainingMinutes = $overStayValue - $overTimeValue;
            $overStayValue = $this->convertMinutesToTime($remainingMinutes);
            $emp->over_stay = $overStayValue ?? 0;
            $emp->ot_value = $overTimeValue ? $this->convertMinutesToTime($overTimeValue) : '00:00';
            // $emp->worked_hour = Attendance::where('emp_id', $emp->id)->whereYear($data['field'], $year)->whereMonth($data['field'], $month)->sum('total_working_hr');
            $emp->worked_hour = Attendance::where('emp_id', $emp->id)->whereBetween($data['field'], [$start_date, $end_date])->sum('total_working_hr');
            $emp->unworked_hour =  $emp->working_hour - $emp->worked_hour;
            // $emp->paid_leave_taken = Leave::where('employee_id', $emp->id)->whereYear($data['field'], $year)->whereMonth($data['field'], $month)->where('status', '!=', 4)->whereHas('leaveTypeModel', function ($query) {
            //     $query->where('leave_type', 10);
            // })->get()->sum('day');
            $emp->paid_leave_taken = Leave::where('employee_id', $emp->id)->whereBetween($data['field'], [$start_date, $end_date])->where('status', '!=', 4)->where('status', '!=', 5)->whereHas('leaveTypeModel', function ($query) {
                $query->where('leave_type', 10);
            })->get()->sum('day');
            // $emp->unpaid_leave_taken = Leave::where('employee_id', $emp->id)->whereYear($data['field'], $year)->whereMonth($data['field'], $month)->where('status', '!=', 4)->whereHas('leaveTypeModel', function ($query) {
            //     $query->where('leave_type', 11);
            // })->get()->sum('day');
            $emp->unpaid_leave_taken = Leave::where('employee_id', $emp->id)->whereBetween($data['field'], [$start_date, $end_date])->where('status', '!=', 4)->where('status', '!=', 5)->whereHas('leaveTypeModel', function ($query) {
                $query->where('leave_type', 11);
            })->get()->sum('day');

            // dd($emp);

            return $emp;
        });
        return $filteredCollection;
    }
}
