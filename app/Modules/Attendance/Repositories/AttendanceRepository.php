<?php

namespace App\Modules\Attendance\Repositories;

use Carbon\Carbon;
use App\Helpers\DateTimeHelper;
use Illuminate\Support\Facades\DB;

use App\Modules\User\Entities\User;
use App\Modules\Leave\Entities\Leave;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Attendance\Entities\Attendance;
use App\Modules\Employee\Entities\EmployeeLeave;
use App\Modules\Shift\Entities\ShiftGroupMember;
use App\Modules\Attendance\Entities\AttendanceLog;
use App\Modules\NewShift\Entities\NewShiftEmployee;
use App\Modules\Shift\Repositories\ShiftRepository;
use App\Modules\Setting\Entities\LeaveDeductionSetup;
use App\Modules\Shift\Repositories\ShiftGroupRepository;
use App\Modules\Attendance\Entities\IrregularAttendanceLog;
use App\Modules\Shift\Repositories\EmployeeShiftRepository;

class AttendanceRepository implements AttendanceInterface
{

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            $filter['organization_id'] = optional($authUser->userEmployer)->organization_id;
        }

        $result = Attendance::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['organization_id'])) {
                $query->where('org_id', $filter['organization_id']);
            }
            if (isset($filter['employee_id'])) {
                $query->where('emp_id', $filter['employee_id']);
            }
            if (isset($filter['date_range'])) {
                $filterDates = explode(' - ', $filter['date_range']);
                $query->where('date', '>=', $filterDates[0]);
                $query->where('date', '<=', $filterDates[1]);
            }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }

    public function findAllWithStatus($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $empId = optional(User::where('id', auth()->user()->id)->first()->userEmployer)->id;
        $filter['current_emp_id'] = $empId;

        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            $filter['organization_id'] = optional($authUser->userEmployer)->organization_id;
        }

        $result = tap(Attendance::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['organization_id'])) {
                $query->where('org_id', $filter['organization_id']);
            }
            if (isset($filter['employee_id'])) {
                $query->where('emp_id', $filter['employee_id']);
            }
            if (isset($filter['date_range'])) {
                $filterDates = explode(' - ', $filter['date_range']);
                $query->where('date', '>=', $filterDates[0]);
                $query->where('date', '<=', $filterDates[1]);
            }

            if (auth()->user()->user_type == 'employee' || auth()->user()->user_type == 'supervisor') { //supervisor logic changes
                $query->where('emp_id', $filter['current_emp_id']);
            } elseif (auth()->user()->user_type == 'hr' || auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'super_admin') {
                $query->where('emp_id', '!=', null);
            }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999)))->map(function ($att) {
            $checkinStatus = '-';
            $checkoutStatus = '-';

            $day = date('D', strtotime($att->date));
            $shiftGroup = optional(ShiftGroupMember::where('group_member', $att->emp_id)->orderBy('id', 'DESC')->first()->group);
            $shift = optional($shiftGroup->shift);
            $newShiftEmp = NewShiftEmployee::getShiftEmployee($att->emp_id, $att->date);
            if (isset($newShiftEmp)) {
                $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
                if (isset($rosterShift) && isset($rosterShift->shift_group_id)) {
                    $shiftGroup = (new ShiftGroupRepository())->find($rosterShift->shift_group_id);
                    $shift = optional($shiftGroup->shift);
                }
            }

            // if ($shiftGroupMember) {
            $shiftSeason = $shift->getShiftSeasonForDate($att->date);
            $seasonalShiftId = null;
            if ($shiftSeason) {
                $seasonalShiftId = $shiftSeason->id;
            }
            $normalCheckinTime = optional($shift->getShiftDayWise($day, $seasonalShiftId))->start_time;
            $checkoutTime = optional($shift->getShiftDayWise($day, $seasonalShiftId))->end_time;
            if ($shiftGroup) {
                $checkinTimeWithGrace = (date('h:i', strtotime(intval('+' . $shiftGroup->ot_grace_period ?? 0) . 'minutes', strtotime(optional($shift->getShiftDayWise($day, $seasonalShiftId))->start_time))));
            } else {
                $checkinTimeWithGrace = (date('h:i', strtotime(intval('+' . 0) . 'minutes', strtotime(optional($shift->getShiftDayWise($day, $seasonalShiftId))->start_time))));
            }

            //Checkin Status
            if ($checkinTimeWithGrace >= date('H:i', strtotime($att->checkin)) && $normalCheckinTime <= date('H:i', strtotime($att->checkin))) {
                $checkinStatus = 'On Time';
            }
            if ($checkinTimeWithGrace < date('H:i', strtotime($att->checkin))) {
                $checkinStatus = 'Late';
            }
            if ($normalCheckinTime  > date('H:i', strtotime($att->checkin))) {
                $checkinStatus = 'Early';
            }

            //CheckoutStatus
            if ($att->checkout == null) {
                $checkoutStatus = '-';
            } else if ($checkoutTime > date('H:i', strtotime($att->checkout))) {
                $checkoutStatus = 'Early';
            } else if ($checkoutTime < date('H:i', strtotime($att->checkout))) {
                $checkoutStatus = 'Late';
            } elseif ($checkoutTime == date('H:i', strtotime($att->checkout))) {
                $checkoutStatus = 'On Time';
            }
            // }

            $att->checkin_status = $checkinStatus;
            $att->checkout_status = $checkoutStatus;
            return $att;
        });

        return $result;
    }

    public function find($id)
    {
        return Attendance::find($id);
    }

    public function save($data)
    {
        return Attendance::create($data);
    }

    public function update($id, $data)
    {
        return Attendance::find($id)->update($data);
    }

    public function delete($id)
    {
        return Attendance::find($id)->delete();
    }

    public function findOne($filter)
    {
        $result = Attendance::where($filter)->first();
        return $result;
    }

    public function employeeAttendanceExists($emp_id, $date)
    {
        return Attendance::where('date', $date)->where('emp_id', $emp_id)->first();
    }

    public function getlateEarlyAndMissed()
    {
        $authUser = auth()->user()->userEmployer;
        $missedCheckin = '';
        $missedCheckout = '';
        $checkoutStatus = '';
        $lateArrival = 0;
        $earlyDeparture = 0;
        // $current_month = date('m');

        $current_year = Carbon::now()->year;
        $current_month = sprintf("%02d", Carbon::now()->month);
        $total_days = cal_days_in_month(CAL_GREGORIAN, $current_month, $current_year);

        if ($authUser) {
            $shiftGroup = optional(optional(ShiftGroupMember::where('group_member', auth()->user()->userEmployer->id)->orderBy('id', 'DESC')->first())->group);

            $missedCheckin = Attendance::where('emp_id', auth()->user()->userEmployer->id)->whereMonth('date', $current_month)->where('checkin', null)->count();
            $missedCheckout = Attendance::where('emp_id', auth()->user()->userEmployer->id)->whereMonth('date', $current_month)->where('checkout', null)->count();
        }
        for ($i = 1; $i <= $total_days; $i++) {
            $fulldate = $current_year . '-' . $current_month . '-' . sprintf("%02d", $i);
            $day = date('D', strtotime($fulldate));

            if (isset($shiftGroup)) {
                $empShift = optional($shiftGroup->shift);
            }
            if (auth()->user()->user_type != 'super_admin') {
                $newShiftEmp = NewShiftEmployee::getShiftEmployee(auth()->user()->userEmployer->id, $fulldate);
                if (isset($newShiftEmp)) {
                    $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
                    if (isset($rosterShift) && isset($rosterShift->shift_group_id)) {
                        $shiftGroup = (new ShiftGroupRepository())->find($rosterShift->shift_group_id);
                        $empShift = optional($shiftGroup->shift);
                    }
                }
            }

            // if (isset($shiftGroupMember)) {
            if (isset($empShift)) {
                $shiftSeason = $empShift->getShiftSeasonForDate($fulldate);
                $seasonalShiftId = null;
                if ($shiftSeason) {
                    $seasonalShiftId = $shiftSeason->id;
                }
                if (isset($shiftGroup)) {
                    $checkinTimeWithGrace = (date('H:i:s', strtotime(intval('+' . $shiftGroup->ot_grace_period ?? 0) . 'minutes', strtotime(optional($empShift->getShiftDayWise($day, $seasonalShiftId))->start_time))));
                    $checkoutTimeWithGrace = (date('H:i:s', strtotime(intval('-' . $shiftGroup->grace_period_checkout ?? 0) . 'minutes', strtotime(optional($empShift->getShiftDayWise($day, $seasonalShiftId))->end_time))));
                } else {
                    $checkinTimeWithGrace = (date('H:i:s', strtotime(intval('+' . 0) . 'minutes', strtotime(optional($empShift->getShiftDayWise($day, $seasonalShiftId))->start_time))));
                    $checkoutTimeWithGrace = (date('H:i:s', strtotime(intval('-' . 0) . 'minutes', strtotime(optional($empShift->getShiftDayWise($day, $seasonalShiftId))->end_time))));
                }

                // $lateArrival = Attendance::where('emp_id', auth()->user()->userEmployer->id)->whereMonth('date', $current_month)->where('checkin', '>', $checkinTimeWithGrace)->count();

                $lateArrivalExists = Attendance::where('emp_id', auth()->user()->userEmployer->id)->where('date', $fulldate)->where('checkin', '>', $checkinTimeWithGrace)->exists();
                if ($lateArrivalExists) {
                    $lateArrival += 1;
                }

                // $earlyDeparture = Attendance::where('emp_id', auth()->user()->userEmployer->id)->whereMonth('date', $current_month)->where('checkout', '<', $checkoutTimeWithGrace)->count();
                $isEarlyDepartureExists = Attendance::where('emp_id', auth()->user()->userEmployer->id)->where('date', $fulldate)->where('checkout', '<', $checkoutTimeWithGrace)->exists();
                if ($isEarlyDepartureExists) {
                    $earlyDeparture += 1;
                }
            }
            // }
        }

        return [
            'missedCheckin' => $missedCheckin,
            'missedCheckout' => $missedCheckout,
            'lateArrival' => $lateArrival,
            'earlyDeparture' => $earlyDeparture
        ];
    }

    public function getAttendance($filter = [], $limit = null, $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            $filter['org_id'] = optional($authUser->userEmployer)->organization_id;
        }

        $result = Attendance::when(array_keys($filter, true), function ($query) use ($filter) {

            if (isset($filter['date'])) {
                if (isset($filter['calendar_type']) && $filter['calendar_type'] == 1) {
                    $query->where('nepali_date', '=', $filter['date']);
                } else {
                    $query->where('date', '=', $filter['date']);
                }
            }

            if (isset($filter['day_log']) && $filter['day_log'] > 0) {
                $query->whereRaw('date >= DATE_SUB(CURDATE(), INTERVAL ' . $filter['day_log'] . ' DAY)');
            }

            if (isset($filter['org_ids']) && !empty($filter['org_ids'])) {
                $query->whereIn('org_id', $filter['org_ids']);
            }

            if (isset($filter['start_date']) && isset($filter['end_date'])) {
                if (isset($filter['calendar_type']) && $filter['calendar_type'] == 1) {
                    $query->where('nepali_date', '>=', $filter['start_date'])->where('nepali_date', '<=', $filter['end_date']);
                } else {
                    $query->whereBetween('date', [$filter['start_date'], $filter['end_date']]);
                }
            }

            if (isset($filter['from_date']) && isset($filter['to_date']) && !is_null($filter['from_date']) && !is_null($filter['to_date'])) {
                $query->whereBetween('date', [$filter['from_date'], $filter['to_date']]);
            } elseif (isset($filter['from_date']) && !is_null($filter['from_date'])) {
                $query->whereBetween('date', [$filter['from_date'], date('Y-m-d')]);
            } elseif (isset($filter['to_date']) && !is_null($filter['to_date'])) {
                $query->where('date', '<=', $filter['to_date']);
            }

            if (isset($filter['emp_id']) && !is_null($filter['emp_id'])) {
                $query->where('emp_id', '=', $filter['emp_id']);
            }

            if (isset($filter['org_id']) && !is_null($filter['org_id'])) {
                $query->where('org_id', '=', $filter['org_id']);
            }

            if (isset($filter['department_id']) && !is_null($filter['department_id'])) {
                $query->whereHas('getEmployee', function ($q) use ($filter) {
                    $q->where('department_id', '=', $filter['department_id']);
                });
            }

            if (isset($filter['designation_id']) && !is_null($filter['designation_id'])) {
                $query->whereHas('getEmployee', function ($q) use ($filter) {
                    $q->where('designation_id', '=', $filter['designation_id']);
                });
            }

            if (isset($filter['search_value']) && !is_null($filter['search_value'])) {

                $query->whereHas('getEmployee', function ($q) use ($filter) {
                    $q->where('first_name', 'like', '%' . $filter['search_value'] . '%');
                    $q->orWhere('last_name', 'like', '%' . $filter['search_value'] . '%');
                    $q->orWhere(DB::raw("concat_ws(' ', first_name, middle_name, last_name)"), 'like', '%' . $filter['search_value'] . '%');
                });
            }
        })->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));

        return $result;
    }

    // public function getEmployee($empId)
    // {
    //     return Leave::where('employee_id', $empId)->select('leave_type_id', 'date', 'reason', 'status')->orderBy('id', 'DESC')->get();
    // }


    public function getMonthlyAttendance($date, $emp_id, $calendar_type = 0)
    {
        $monthly_attendance_date = date('Y-m', strtotime($date));
        if ($calendar_type == 1) {
            $result = Attendance::where(DB::raw("DATE_FORMAT(`nepali_date`,'%Y-%m')"), $monthly_attendance_date)->where('emp_id', $emp_id)->get();
        } else {
            $result = Attendance::where(DB::raw("DATE_FORMAT(`date`,'%Y-%m')"), $monthly_attendance_date)->where('emp_id', $emp_id)->get();
        }
        return $result;
    }

    public function getMonthlyAttendanceList($date, $org_id)
    {
        $monthly_attendance_date = date('Y-m', strtotime($date));
        return Attendance::where(DB::raw("DATE_FORMAT(`date`,'%Y-%m')"), $monthly_attendance_date)->where('org_id', $org_id)->get();
    }

    public function getMonths()
    {
        return Attendance::MONTHS;
    }

    public function getYears()
    {
        $date = date('Y');
        $year = [];
        for ($i = $date + 1; $i > $date - 1; $i--) {
            $year[$i] = $i;
        }
        return $year;
    }

    public function getDistinctEmployee($org_id)
    {
        $emp_list = Attendance::distinct('emp_id')->select('emp_id')->where('org_id', $org_id)->get();
        return $emp_list;
    }

    public static function getEmpAttendanceByDate($date, $emp_id, $calendar_type = 0)
    {
        if ($calendar_type == 1) {
            $result = Attendance::where('nepali_date', $date)->where('emp_id', $emp_id)->first();
        } else {
            $result = Attendance::where('date', $date)->where('emp_id', $emp_id)->first();
        }
        return $result;
    }

    public static function getEngByNepDate($year, $month, $day)
    {
        $cal = new DateConverter();
        $nep_date_resp = $cal->nep_to_eng($year, $month, $day);
        return $nep_date_resp['year'] . '-' . $nep_date_resp['month'] . '-' . $nep_date_resp['date'];
    }

    public function getNepaliMonths()
    {
        return Attendance::NEPALI_MONTHS;
    }

    public static function getEmpAttendanceByDateList($date, $emp_id, $filter = [], $calendar_type = 0, $limit = null, $sort = ['by' => 'id', 'sort' => 'DESC'])
    {

        if (isset($filter['search_from']) && isset($filter['search_to'])) {

            $result = Attendance::when(array_keys($filter, true), function ($query) use ($filter) {

                $query->where('date', '>=', $filter['search_from'])->where('date', '<=', $filter['search_to']);
            })->where('emp_id', '=', $emp_id)->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));

            return $result;
        } else {
            return Attendance::where('emp_id', '=', $emp_id)->where('date', '=', $date)->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        }
    }

    public static function getNepByEngDate($year, $month, $day)
    {
        $cal = new DateConverter();
        $nep_date_resp = $cal->eng_to_nep($year, $month, $day);
        return $nep_date_resp['year'] . '-' . $nep_date_resp['month'] . '-' . $nep_date_resp['date'];
    }

    public function getAvgHrMonthlyAttendance($year_month, $emp_id, $calendar_type = 0)
    {
        if ($calendar_type == 1) {
            $result = Attendance::where(DB::raw("DATE_FORMAT(`nepali_date`,'%Y-%m')"), $year_month)->where('emp_id', $emp_id)->avg('total_working_hr');
        } else {
            $result = Attendance::where(DB::raw("DATE_FORMAT(`date`,'%Y-%m')"), $year_month)->where('emp_id', $emp_id)->avg('total_working_hr');
        }
        return $result;
    }

    public function getAvgHrMonthlyAttendanceByDepartment($year_month, $emp_id, $calendar_type = 0, $department_id)
    {
        if ($calendar_type == 1) {
            $result = Attendance::where(DB::raw("DATE_FORMAT(`nepali_date`,'%Y-%m')"), $year_month)
                ->whereHas('getEmployee', function ($q) use ($department_id) {
                    $q->where('department_id', $department_id);
                })->avg('total_working_hr');
        } else {
            $result = Attendance::where(DB::raw("DATE_FORMAT(`date`,'%Y-%m')"), $year_month)
                ->whereHas('getEmployee', function ($q) use ($department_id) {
                    $q->where('department_id', $department_id);
                })->avg('total_working_hr');
        }
        return $result;
    }

    public function getOnTimeAttCount($year_month, $emp_id, $calendar_type = 0, $start_time, $start_grace_time)
    {
        if ($calendar_type == 1) {
            $result = Attendance::where(DB::raw("DATE_FORMAT(`nepali_date`,'%Y-%m')"), $year_month)
                ->where('emp_id', $emp_id)
                ->whereBetween('checkin', [$start_time, $start_grace_time])
                ->count('id');
        } else {
            $result = Attendance::where(DB::raw("DATE_FORMAT(`date`,'%Y-%m')"), $year_month)
                ->where('emp_id', $emp_id)
                ->whereBetween('checkin', [$start_time, $start_grace_time])
                ->count('id');
        }
        return $result;
    }

    public function getOnTimeAttCountByDepartment($year_month, $emp_id, $calendar_type = 0, $department_id, $start_time, $start_grace_time)
    {
        if ($calendar_type == 1) {
            $result = Attendance::where(DB::raw("DATE_FORMAT(`nepali_date`,'%Y-%m')"), $year_month)
                ->whereBetween('checkin', [$start_time, $start_grace_time])
                ->whereHas('getEmployee', function ($q) use ($department_id) {
                    $q->where('department_id', $department_id);
                })->count('id');
        } else {
            $result = Attendance::where(DB::raw("DATE_FORMAT(`date`,'%Y-%m')"), $year_month)
                ->whereBetween('checkin', [$start_time, $start_grace_time])
                ->whereHas('getEmployee', function ($q) use ($department_id) {
                    $q->where('department_id', $department_id);
                })->count('id');
        }
        return $result;
    }

    public function dailyLeaveDeductBasedOnAttendance()
    {
        $query = Employee::query();
        $employees = $query->where('status', 1)->get();

        return $employees->map(function ($emp) {
            $todayDate = date('Y-m-d');
            // $todayDate = '2023-01-02';
            $atd =  $emp->getSingleAttendance('date', $todayDate);

            // if ($emp->id == 6) {
            $sendData = [
                'organizationId' => $emp->organization_id,
                'todayDate' => $todayDate,
                'employeeId' => $emp->id
            ];
            if ($atd) {
                $shift = ShiftGroupMember::where('group_member', $emp->id)->orderBy('id', 'DESC')->first();

                //checkin
                if (isset($atd->checkin)) {
                    if ($shift) {
                        $checkinTimeWithPenalty = (date('H:i', strtotime(intval('+' . optional($shift->group)->grace_period_checkin_for_penalty ?? 0) . 'minutes', strtotime(optional(optional($shift->group)->shift)->start_time))));

                        if ((isset(optional($shift->group)->grace_period_checkin_for_penalty) && optional($shift->group)->grace_period_checkin_for_penalty > 0) && ($checkinTimeWithPenalty < date('H:i', strtotime($atd->checkin)))) {
                            // For penalty exceeded
                            $sendData['type'] = 6;
                            $leaveDeductionSetup = $this->checkLeaveDeductionSetup($sendData);
                            if (isset($leaveDeductionSetup) && !empty($leaveDeductionSetup)) {
                                $sendData['leaveTypeId'] = $leaveDeductionSetup->leave_type_id;
                                $sendData['unpaidLeaveType'] = $leaveDeductionSetup->unpaid_leave_type;
                                $sendData['numberOfDays'] = $leaveDeductionSetup->deduct_leave_number;
                                $sendData['reason'] = 'System Generated Leave for Check In Grace with Penalty.';
                                $this->createLeave($sendData);
                            }
                            //
                        } else {
                            $checkinTimeWithGracePeriod = (date('H:i', strtotime(intval('+' . optional($shift->group)->ot_grace_period ?? 0) . 'minutes', strtotime(optional(optional($shift->group)->shift)->start_time))));

                            // For Late Arrival
                            if ((isset(optional($shift->group)->ot_grace_period) && optional($shift->group)->ot_grace_period > 0) && ($checkinTimeWithGracePeriod < date('H:i', strtotime($atd->checkin)))) {
                                $sendData['type'] = 4;
                                $leaveDeductionSetup = $this->checkLeaveDeductionSetup($sendData);

                                if (isset($leaveDeductionSetup) && !empty($leaveDeductionSetup)) {

                                    $sendData['leaveTypeId'] = $leaveDeductionSetup->leave_type_id;
                                    $sendData['unpaidLeaveType'] = $leaveDeductionSetup->unpaid_leave_type;
                                    $sendData['numberOfDays'] = $leaveDeductionSetup->deduct_leave_number;
                                    $sendData['maxNoOfLimit'] = $leaveDeductionSetup->max_late_days;
                                    Attendance::saveIrregularAttendanceLog($sendData);

                                    $model = Attendance::getIrregularAttendanceLog($sendData['employeeId']);
                                    if (!empty($model)) {
                                        if ($model->total_late_arrival_days == $leaveDeductionSetup->max_late_days) {
                                            $sendData['numberOfDays'] = $model->total_late_arrival_days / $leaveDeductionSetup->max_late_days;
                                            $sendData['reason'] = 'System Generated Leave for Late Arrival';
                                            $updateEmpLeave = $this->createLeave($sendData);
                                            if ($updateEmpLeave) {
                                                $model->total_late_arrival_days = 0;
                                                $model->save();
                                            }
                                        }
                                    }
                                }
                            }
                            //
                        }
                    }
                } else {
                    //missed check in
                    $sendData['type'] = 1;
                    $leaveDeductionSetup = $this->checkLeaveDeductionSetup($sendData);
                    if (isset($leaveDeductionSetup) && !empty($leaveDeductionSetup)) {
                        $sendData['leaveTypeId'] = $leaveDeductionSetup->leave_type_id;
                        $sendData['unpaidLeaveType'] = $leaveDeductionSetup->unpaid_leave_type;
                        $sendData['numberOfDays'] = $leaveDeductionSetup->deduct_leave_number;
                        $sendData['reason'] = 'System Generated Leave for Missed Check In.';
                        $this->createLeave($sendData);
                    }
                    //
                }
                //

                //checkout
                if (isset($atd->checkout)) {
                    if ($shift) {
                        $checkoutTimeWithPenalty = (date('H:i', strtotime(intval('-' . optional($shift->group)->grace_period_checkout_for_penalty ?? 0) . 'minutes', strtotime(optional(optional($shift->group)->shift)->end_time))));

                        if ((isset(optional($shift->group)->grace_period_checkout_for_penalty) && optional($shift->group)->grace_period_checkout_for_penalty > 0) && ($checkoutTimeWithPenalty > date('H:i', strtotime($atd->checkout)))) {

                            // For penalty exceeded
                            $sendData['type'] = 5;
                            $leaveDeductionSetup = $this->checkLeaveDeductionSetup($sendData);
                            if (isset($leaveDeductionSetup) && !empty($leaveDeductionSetup)) {
                                $sendData['leaveTypeId'] = $leaveDeductionSetup->leave_type_id;
                                $sendData['unpaidLeaveType'] = $leaveDeductionSetup->unpaid_leave_type;
                                $sendData['numberOfDays'] = $leaveDeductionSetup->deduct_leave_number;
                                $sendData['reason'] = 'System Generated Leave for Check Out Grace with Penalty.';
                                $this->createLeave($sendData);
                            }
                            //
                        } else {
                            $checkoutTimeWithGraceperiod = (date('H:i', strtotime(intval('-' . optional($shift->group)->grace_period_checkout ?? 0) . 'minutes', strtotime(optional(optional($shift->group)->shift)->end_time))));

                            // For Early Departure
                            if ((isset(optional($shift->group)->grace_period_checkout) && optional($shift->group)->grace_period_checkout > 0) && ($checkoutTimeWithGraceperiod > date('H:i', strtotime($atd->checkout)))) {
                                $sendData['type'] = 3;
                                $leaveDeductionSetup = $this->checkLeaveDeductionSetup($sendData);

                                if (isset($leaveDeductionSetup) && !empty($leaveDeductionSetup)) {

                                    $sendData['leaveTypeId'] = $leaveDeductionSetup->leave_type_id;
                                    $sendData['unpaidLeaveType'] = $leaveDeductionSetup->unpaid_leave_type;
                                    $sendData['numberOfDays'] = $leaveDeductionSetup->deduct_leave_number;
                                    $sendData['maxNoOfLimit'] = $leaveDeductionSetup->max_late_days;
                                    Attendance::saveIrregularAttendanceLog($sendData);

                                    $model = Attendance::getIrregularAttendanceLog($sendData['employeeId']);
                                    if (!empty($model)) {
                                        if ($model->total_early_departure_days == $leaveDeductionSetup->max_late_days) {
                                            $sendData['numberOfDays'] = $model->total_early_departure_days / $leaveDeductionSetup->max_late_days;
                                            $sendData['reason'] = 'System Generated Leave for Early Departure';

                                            $updateEmpLeave = $this->createLeave($sendData);
                                            if ($updateEmpLeave) {
                                                $model->total_early_departure_days = 0;
                                                $model->save();
                                            }
                                        }
                                    }
                                }
                            }
                            //
                        }
                    }
                } else {
                    //missed check out
                    $sendData['type'] = 2;
                    $leaveDeductionSetup = $this->checkLeaveDeductionSetup($sendData);
                    if (isset($leaveDeductionSetup) && !empty($leaveDeductionSetup)) {
                        $sendData['leaveTypeId'] = $leaveDeductionSetup->leave_type_id;
                        $sendData['unpaidLeaveType'] = $leaveDeductionSetup->unpaid_leave_type;
                        $sendData['numberOfDays'] = $leaveDeductionSetup->deduct_leave_number;
                        $sendData['reason'] = 'System Generated Leave for Missed Check Out.';
                        $this->createLeave($sendData);
                    }
                    //
                }
                //
            } else {
                // missed checkin
                $sendData['type'] = 1;
                $leaveDeductionSetup = $this->checkLeaveDeductionSetup($sendData);
                if (isset($leaveDeductionSetup) && !empty($leaveDeductionSetup)) {
                    $sendData['leaveTypeId'] = $leaveDeductionSetup->leave_type_id;
                    $sendData['unpaidLeaveType'] = $leaveDeductionSetup->unpaid_leave_type;
                    $sendData['numberOfDays'] = $leaveDeductionSetup->deduct_leave_number;
                    $sendData['reason'] = 'System Generated Leave for Missed Check In.';
                    $this->createLeave($sendData);
                }
                //

                // missed checkout
                $sendData['type'] = 2;
                $leaveDeductionSetup1 = $this->checkLeaveDeductionSetup($sendData);
                if (isset($leaveDeductionSetup1) && !empty($leaveDeductionSetup1)) {
                    $sendData['leaveTypeId'] = $leaveDeductionSetup1->leave_type_id;
                    $sendData['unpaidLeaveType'] = $leaveDeductionSetup1->unpaid_leave_type;
                    $sendData['numberOfDays'] = $leaveDeductionSetup1->deduct_leave_number;
                    $sendData['reason'] = 'System Generated Leave for Missed Check Out.';
                    $this->createLeave($sendData);
                }
                //
            }
            // }

            // return $emp;
        });
    }

    // public function monthlyLeaveDeductBasedOnAttendance()
    // {
    //     $query = Employee::query();
    //     $employees = $query->where('status', 1)->get();

    //     return $employees->map(function ($emp) {
    //         $todayDate = date('Y-m-d');

    //         $sendData = [
    //             'organizationId' => $emp->organization_id,
    //             'todayDate' => $todayDate,
    //             'employeeId' => $emp->id
    //         ];

    //         $sendData['type'] = 4;
    //         $sendData['reason'] = 'System Generated Leave for Late Arrival';
    //         $this->checkLeaveDeductionSetup($sendData);

    //         $sendData['type'] = 5;
    //         $sendData['reason'] = 'System Generated Leave for Early Departure';
    //         $this->checkLeaveDeductionSetup($sendData);
    //     });
    //     IrregularAttendanceLog::query()->delete();
    // }

    public function createLeave($data)
    {
        $empLeaveRemaining = EmployeeLeave::getLeaveRemaining(getCurrentLeaveYearId(), $data['employeeId'], $data['leaveTypeId']);
        if ($empLeaveRemaining && $empLeaveRemaining > 0) {
            $leaveTypeId = $data['leaveTypeId'];
        } else {
            $leaveTypeId = $data['unpaidLeaveType'];
        }
        if ($data['numberOfDays'] > 0) {
            $leaveData = Leave::create([
                'organization_id' => $data['organizationId'],
                'employee_id' => $data['employeeId'],
                // 'leave_kind' => 1,
                'leave_type_id' => $leaveTypeId,
                // 'half_type' => 1,
                'date' => $data['todayDate'],
                'nepali_date' => date_converter()->eng_to_nep_convert($data['todayDate']),
                'reason' => $data['reason'],
                'status' => 1,
                'created_by' => 1,
                'generated_by' => 11,
                'generated_leave_type' => $data['type'],
                'generated_no_of_days' => $data['numberOfDays']
            ]);
            if ($leaveData) {
                $inputData = [
                    'employee_id' => $data['employeeId'],
                    'leave_type_id' => $leaveTypeId,
                    'numberOfDays' => $data['numberOfDays']
                ];
                EmployeeLeave::updateRemainingLeave($inputData, 'SUB');

                return true;
            }
        } else {
            return false;
        }
    }

    public function checkLeaveDeductionSetup($data)
    {
        $leaveDeductionSetup = [];
        $leaveDeductionSetup = LeaveDeductionSetup::where('organization_id', $data['organizationId'])->where('type', $data['type'])->first();
        return $leaveDeductionSetup;
    }

    public function getMobileAttendance()
    {
        $query = Attendance::query();

        if (auth()->user()->user_type == 'division_hr') {
            $query->whereHas('employee', function ($q) {
                $q->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
            });
        }
        $result = $query->where(function ($query) {
            $query->where('checkin_from', 'app');
            $query->orWhere('checkout_from', 'app');
        })
            ->whereDate('date', Carbon::now()->toDateString())
            ->get();

        $result = $result->transform(function ($attendance) {
            $checkIn = $checkOut = [];
            if ($attendance->checkin_coordinates) {

                $checkInCoordinates = $attendance->checkin_coordinates;
                $checkInCoordinates += array('color' => 'green', 'type' => 'Check in');
                // getAddressFromLatLong($checkInCoordinates);
                $checkIn[0] = $checkInCoordinates;
            }
            if ($attendance->checkout_coordinates) {
                $checkOutCoordinates = $attendance->checkout_coordinates;
                $checkOutCoordinates += array('color' => 'red', 'type' => 'Check out');
                $checkOut[1] = $checkOutCoordinates;
            }
            $locations = array_merge($checkIn, $checkOut);
            $attendance->coordinates = json_encode($locations);
            return $attendance;
        });

        return $result;
    }

    //Data in attendance table store logic from all api/web/biometric
    public function saveAttendance($employee, $inputData)
    {
        $dataParams = array(
            'emp_id' => $employee->id,
            'org_id' => $employee->organization_id,
            'date' => $inputData['date'],
        );
        $attendanceModel = $this->findOne($dataParams);
        // dd($attendanceModel);
        if (isset($attendanceModel) && !empty($attendanceModel)) {
            if (isset($inputData['inout_mode']) || isset($inputData['check_in']) || isset($inputData['check_out'])) {
                //checkin
                if ($inputData['inout_mode'] == 0 || $inputData['check_in']) {
                    $inTime = $inputData['time'] ?? $inputData['check_in'];
                    if (is_null($attendanceModel->checkin)) {
                        $attendanceModel->checkin = $inTime;
                        $attendanceModel->checkin_original = $inTime;
                        $attendanceModel->checkin_from = $inputData['punch_from'] ?? $inputData['source'];
                        $attendanceModel->checkin_ip = $inputData['IpAddress'] ?? null;

                        if ($inputData['punch_from'] == 'app' || $inputData['source'] == 'app') {
                            if ($inputData['lat'] && $inputData['long']) {
                                $attendanceModel->checkin_coordinates = ['lat' => $inputData['lat'], 'long' => $inputData['long']];
                            }
                        }
                    } else {
                        if (strtotime($inTime) < strtotime($attendanceModel->checkin)) {
                            $attendanceModel->checkin = $inTime;
                            $attendanceModel->checkin_original = $inTime;
                            $attendanceModel->checkin_from = $inputData['punch_from'] ?? $inputData['source'];
                        }
                    }
                    $attendanceModel->late_arrival_in_minutes = $this->getLateArrivalEarlyDepartureData($employee, $inputData['date'], $inTime, 'checkin')['lateArrival'];
                    $attendanceModel->is_checkin_next_day = $inputData['is_checkin_next_day'] ?? null;
                }
                //checkout
                if ($inputData['inout_mode'] == 1  || $inputData['check_out'] != null) {
                    $outTime = $inputData['time'] ?? $inputData['check_out'];
                    if (is_null($attendanceModel->checkout)) {
                        $attendanceModel->checkout_original = $outTime;
                        $attendanceModel->checkout = $outTime;
                    }
                    $attendanceModel->checkout_original = $outTime;
                    $attendanceModel->checkout = $outTime;
                    $attendanceModel->checkout_from = $inputData['punch_from'] ?? $inputData['source'];
                    $attendanceModel->checkout_ip = $inputData['IpAddress'] ?? null;
                    $attendanceModel->early_departure_in_minutes = $this->getLateArrivalEarlyDepartureData($employee, $inputData['date'], $outTime, 'checkout')['earlyDeparture'];

                    if ($inputData['punch_from'] == 'app' || $inputData['source'] == 'app') {
                        if ($inputData['lat'] && $inputData['long']) {
                            $attendanceModel->checkout_coordinates = ['lat' => $inputData['lat'], 'long' => $inputData['long']];
                        }
                    }
                }
                if ($attendanceModel->checkin && $attendanceModel->checkout) {
                    $attendanceModel->total_working_hr = DateTimeHelper::getTimeDiff($attendanceModel->checkin, $attendanceModel->checkout);
                }
                $attendanceModel->save();
            }
        } else {
            $attendanceModel = new Attendance();
            $attendanceModel->emp_id = $employee->id;
            $attendanceModel->org_id = $employee->organization_id;
            $attendanceModel->date = $inputData['date'];
            $attendanceModel->nepali_date = date_converter()->eng_to_nep_convert($inputData['date']);

            //checkin
            if (isset($inputData['inout_mode']) || isset($inputData['check_in']) || isset($inputData['check_out'])) {
                if ($inputData['inout_mode'] == 0 || $inputData['check_in'] != null) {
                    $attendanceModel->checkin = $inputData['time'] ?? $inputData['check_in'];
                    $attendanceModel->checkin_original = $inputData['time'] ?? $inputData['check_in'];
                    $attendanceModel->checkin_from = $inputData['punch_from'] ?? $inputData['source'];
                    $attendanceModel->checkin_ip = $inputData['IpAddress'] ?? null;
                    $inTime = $inputData['time'] ??  $inputData['check_in'];
                    $attendanceModel->late_arrival_in_minutes = $this->getLateArrivalEarlyDepartureData($employee, $inputData['date'], $inTime, 'checkin')['lateArrival'];
                    $attendanceModel->is_checkin_next_day = $inputData['is_checkin_next_day'] ?? null;

                    if ($inputData['punch_from'] == 'app' || $inputData['source'] == 'app') {
                        if ($inputData['lat'] && $inputData['long']) {
                            $attendanceModel->checkin_coordinates = ['lat' => $inputData['lat'], 'long' => $inputData['long']];
                        }
                    }
                }
                //checkout
                if ($inputData['inout_mode'] == 1 || $inputData['check_out'] != null) {
                    $attendanceModel->checkout = $inputData['time'] ?? $inputData['check_out'];
                    $attendanceModel->checkout_original = $inputData['time'] ?? $inputData['check_out'];
                    $attendanceModel->checkout_from = $inputData['punch_from'] ?? $inputData['source'];
                    $attendanceModel->checkout_ip = $inputData['IpAddress'] ?? null;
                    $outTime = $inputData['time'] ?? $inputData['check_out'];
                    $attendanceModel->early_departure_in_minutes = $this->getLateArrivalEarlyDepartureData($employee, $inputData['date'], $outTime, 'checkout')['earlyDeparture'];

                    if ($inputData['punch_from'] == 'app' || $inputData['source'] == 'app') {
                        if ($inputData['lat'] && $inputData['long']) {
                            $attendanceModel->checkout_coordinates = ['lat' => $inputData['lat'], 'long' => $inputData['long']];
                        }
                    }
                }
            }
            $attendanceModel->save();
        }
    }

    public function attendanceLogExists($value)
    {
        return AttendanceLog::where([
            'date' => $value['date'],
            'biometric_emp_id' => $value['biometric_emp_id'],
            'time' => $value['time']
        ])->exists();
    }

    public function getDayWiseShift($employeeId, $punchedDate)
    {
        $day = date('D', strtotime($punchedDate));
        return optional((new EmployeeShiftRepository())->findOne([
            'employee_id' => $employeeId,
            'days' => $day
        ]))->getShift->getShiftDayWise($day) ?? [];
    }

    public function determineInOutMode($time, $daywiseShift, $dateChanged)
    {
        $midTime = $daywiseShift->getCheckpoint();
        $startTime = $daywiseShift->checkin_start_time;

        return (
            ($time < $midTime && $time >= $startTime && !$dateChanged) ||
            ($midTime < $startTime && $time < $midTime) ||
            ($midTime < $startTime && $time >= $startTime && $time > $midTime && !$dateChanged)
        ) ? 0 : 1;
    }

    public function getLateArrivalEarlyDepartureData($emp, $engDate, $time, $type)
    {
        $day = date('D', strtotime($engDate));
        $lateArrival = 0;
        $earlyDeparture = 0;

        $actualShiftGroupMember = ShiftGroupMember::where('group_member', $emp->id)->orderBy('id', 'DESC')->first();
        $empShift = optional(optional($actualShiftGroupMember)->group)->shift;

        $newShiftEmp = NewShiftEmployee::getShiftEmployee($emp->id, $engDate);
        if (isset($newShiftEmp)) {
            $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
            if (isset($rosterShift) && isset($rosterShift->shift_id)) {
                $empShift = (new ShiftRepository())->find($rosterShift->shift_id);
            }
        }
        if (isset($empShift)) {
            if ($actualShiftGroupMember) {
                $checkinTimeWithGrace = (date('H:i', strtotime(intval('+' . optional($actualShiftGroupMember->group)->ot_grace_period ?? 0) . 'minutes', strtotime(optional($empShift->getShiftDayWise($day))->start_time))));

                $checkoutTimeWithGrace = (date('H:i', strtotime(intval('-' . optional($actualShiftGroupMember->group)->grace_period_checkout ?? 0) . 'minutes', strtotime(optional($empShift->getShiftDayWise($day))->end_time))));
            } else {
                $checkinTimeWithGrace = (date('H:i', strtotime(intval('+' . 0) . 'minutes', strtotime(optional($empShift->getShiftDayWise($day))->start_time))));
                $checkoutTimeWithGrace = (date('H:i', strtotime(intval('-' . 0) . 'minutes', strtotime(optional($empShift->getShiftDayWise($day))->end_time))));
            }
        }

        if ($type == 'checkin') {
            if ($checkinTimeWithGrace < date('H:i', strtotime($time))) {
                $checkInTime = Carbon::parse($time);
                $checkInGrace = Carbon::parse($checkinTimeWithGrace);
                $lateArrival = $checkInTime->diffInMinutes($checkInGrace);
            }
        } else {
            if ($checkoutTimeWithGrace > date('H:i', strtotime($time))) {
                $checkOutTime = Carbon::parse($time);
                $checkOutGrace = Carbon::parse($checkoutTimeWithGrace);
                $earlyDeparture = $checkOutGrace->diffInMinutes($checkOutTime);
            }
        }
        return [
            'lateArrival' => $lateArrival,
            'earlyDeparture' => $earlyDeparture,
        ];
    }
}
