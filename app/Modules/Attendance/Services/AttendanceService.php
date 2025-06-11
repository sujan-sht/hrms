<?php

namespace App\Modules\Attendance\Services;

use App\Helpers\DateTimeHelper;
use Illuminate\Support\Facades\Storage;
use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Admin\Entities\MailSender;
use App\Modules\Attendance\Entities\Attendance;
use App\Modules\Employee\Repositories\EmployeeRepository;
use App\Modules\Shift\Repositories\EmployeeShiftRepository;
use App\Modules\Attendance\Repositories\AttendanceRepository;
use App\Modules\Attendance\Repositories\AttendanceLogRepository;
use App\Modules\NewShift\Entities\NewShiftEmployee;
use App\Modules\Organization\Repositories\OrganizationRepository;
use App\Modules\Setting\Entities\Setting;
use App\Modules\Shift\Entities\ShiftGroupMember;
use App\Modules\Shift\Repositories\ShiftGroupRepository;
use App\Modules\Shift\Repositories\ShiftRepository;

class AttendanceService
{

    protected $attendance;
    protected $attendanceLog;
    protected $employee;
    protected $organization;
    protected $employeeShift;

    public function __construct()
    {
        $this->attendance = new AttendanceRepository;
        $this->attendanceLog = new AttendanceLogRepository;
        $this->employee = new EmployeeRepository;
        $this->organization = new OrganizationRepository;
        $this->employeeShift = new EmployeeShiftRepository;
    }

    public function runAttendance()
    {
        $todayDate = date('Y-m-d');
        $agoDate = date('Y-m-d', strtotime('-3 days'));

        // get all active employees
        $employees = $this->employee->getActiveEmployees();
        $punchArray = ['web', 'app'];
        if ($employees->count() > 0) {
            $text = '';
            $count = 0;
            foreach ($employees as $employee) {
                if (isset($employee->biometric_id)) {
                    for ($i = strtotime($agoDate); $i <= strtotime($todayDate); $i += 86400) {
                        $currentDate = date('Y-m-d', $i);
                        $weeekDay = date('D', $i);
                        $year = date('Y', $i);
                        $month = date('m', $i);
                        $day = date('d', $i);

                        // get current nepali date
                        $dateConverter = new DateConverter();
                        $dc = $dateConverter->eng_to_nep($year, $month, $day);
                        $currentNepaliDate = $dc['year'] . '-' . sprintf("%02d", $dc['month']) . '-' . sprintf("%02d", $dc['date']);

                        // check employee shift
                        $shiftCheckPoint = '14:00';
                        $empShift = $this->employeeShift->findOne(['employee_id' => $employee->id, 'days' => $weeekDay]);
                        $shiftInfo = optional($empShift->getShift);

                        $newShiftEmp = NewShiftEmployee::getShiftEmployee($employee->id, $currentDate);
                        if (isset($newShiftEmp)) {
                            $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
                            if (isset($rosterShift) && isset($rosterShift->shift_group_id)) {
                                $shiftInfo = optional((new ShiftGroupRepository())->find($rosterShift->shift_group_id)->shift);
                            }
                        }
                        if (!empty($shiftInfo)) {
                            $shiftSeason = $empShift->getShiftSeasonForDate($currentDate);
                            $seasonalShiftId = null;
                            if($shiftSeason){
                                $seasonalShiftId = $shiftSeason->id;
                            }
                            $shiftCheckPoint = optional($shiftInfo->getShiftDayWise($weeekDay, $seasonalShiftId))->getCheckpoint();
                        }

                        // get first time from daily attendance log
                        $filterParams = ['date' => $currentDate, 'biometric_emp_id' => $employee->biometric_id];
                        $checkableTime1 = $this->attendanceLog->findMinTime($filterParams);
                        $checkableTime2 = $this->attendanceLog->findMaxTime($filterParams);
                        if (!$checkableTime1 || !$checkableTime2) {
                            continue;
                        }

                        if (in_array($checkableTime1->punch_from, $punchArray) || in_array($checkableTime2->punch_from, $punchArray)) {
                            $empLeave = $employee->leave->where('date', $currentDate)->where('status', 3)->first();
                            if ($empLeave && $empLeave->exists()) {
                                continue;
                            }
                        }

                        if (strtotime($checkableTime1->min_time) < strtotime($shiftCheckPoint)) {
                            $attendanceState1 = 'checkin';
                        } else {
                            $attendanceState1 = 'checkout';
                        }

                        if (strtotime($checkableTime2->max_time) < strtotime($shiftCheckPoint)) {
                            $attendanceState2 = 'checkin';
                        } else {
                            $attendanceState2 = 'checkout';
                        }

                        $dataParams = array(
                            'emp_id' => $employee->id,
                            'org_id' => $employee->organization_id,
                            'date' => $currentDate,
                        );
                        $attendanceModel = $this->attendance->findOne($dataParams);

                        if (!$attendanceModel) {
                            $attendanceModel = new Attendance();
                            $attendanceModel->emp_id = $employee->id;
                            $attendanceModel->org_id = $employee->organization_id;
                            $attendanceModel->date = $currentDate;
                            $attendanceModel->nepali_date = $currentNepaliDate;
                        }

                        $checkin_coordinates = $checkout_coordinates = null;
                        if ($checkableTime1->lat && $checkableTime1->long) {
                            $checkin_coordinates = ['lat' => $checkableTime1->lat, 'long' => $checkableTime1->long];
                        }

                        if ($checkableTime2->lat && $checkableTime2->long) {
                            $checkout_coordinates = ['lat' => $checkableTime2->lat, 'long' => $checkableTime2->long];
                        }

                        if ($attendanceState1 == 'checkin' && $attendanceState2 == 'checkin') {
                            // if (is_null($attendanceModel->checkin)) {
                                if ($attendanceModel->checkin_from != 'request') {
                                    $attendanceModel->checkin = $checkableTime1->min_time;
                                    $attendanceModel->checkin_from = $checkableTime1->punch_from;
                                }
                            // }

                            $attendanceModel->checkin_original = $checkableTime1->min_time;
                            $attendanceModel->checkin_coordinates = $checkin_coordinates;

                            // $attendanceModel->checkout = null;
                            // $attendanceModel->checkout_original = null;
                            // $attendanceModel->checkout_from = null;
                            // $attendanceModel->checkout_coordinates = null;
                        } elseif ($attendanceState1 == 'checkout' && $attendanceState2 == 'checkout') {
                            // if (is_null($attendanceModel->checkout)) {
                                if ($attendanceModel->checkout_from != 'request') {
                                    $attendanceModel->checkout = $checkableTime2->max_time;
                                    $attendanceModel->checkout_from = $checkableTime2->punch_from;
                                }
                            // }
                            $attendanceModel->checkout_original = $checkableTime2->max_time;
                            $attendanceModel->checkout_coordinates = $checkout_coordinates;

                            // $attendanceModel->checkin = null;
                            // $attendanceModel->checkin_original = null;
                            // $attendanceModel->checkin_from = null;
                            // $attendanceModel->checkin_coordinates = null;
                        } else {
                            // if (is_null($attendanceModel->checkin)) {
                                if ($attendanceModel->checkin_from != 'request') {
                                    $attendanceModel->checkin = $checkableTime1->min_time;
                                    $attendanceModel->checkin_from = $checkableTime1->punch_from;
                                }
                            // }
                            $attendanceModel->checkin_original = $checkableTime1->min_time;
                            $attendanceModel->checkin_coordinates = $checkin_coordinates;

                            // if (is_null($attendanceModel->checkout)) {
                                if ($attendanceModel->checkout_from != 'request') {
                                    $attendanceModel->checkout = $checkableTime2->max_time;
                                    $attendanceModel->checkout_from = $checkableTime2->punch_from;
                                }
                            // }
                            $attendanceModel->checkout_original = $checkableTime2->max_time;
                            $attendanceModel->checkout_coordinates = $checkout_coordinates;
                        }

                        if ($attendanceModel->checkin && $attendanceModel->checkout) {
                            $attendanceModel->total_working_hr = DateTimeHelper::getTimeDiff($attendanceModel->checkin, $attendanceModel->checkout);
                        }

                        $attendanceModel->save();

                        // //send mail for late arrival or early departure
                        // if($attendanceModel->date == date('Y-m-d')){

                        //     $shift = ShiftGroupMember::where('group_member', $employee->id)->first();

                        //     $checkinTimeWithGrace = (date('H:i:s', strtotime(intval('+' . optional($shift->group)->ot_grace_period ?? 0) . 'minutes', strtotime(optional($shiftInfo->getShift)->start_time))));
                        //     $checkoutTimeWithGrace = (date('H:i:s', strtotime(intval('-' . optional($shift->group)->grace_period_checkout ?? 0) . 'minutes', strtotime(optional($shiftInfo->getShift)->end_time))));
                        //     //Checkin Status
                        //     if (isset($attendanceModel->checkin) && $checkinTimeWithGrace < date('H:i', strtotime($attendanceModel->checkin))) {
                        //         $type = ' arrived later ';
                        //         $notified_user_email = $employee->official_email;
                        //         $notified_user_fullname = $employee->getFullName();
                        //         if($notified_user_email){
                        //             $details = array(
                        //                 'email' => $notified_user_email,
                        //                 'notified_user_fullname' => $notified_user_fullname,
                        //                 'setting' => Setting::first(),
                        //                 'subject' => 'Grace Time Notification',
                        //                 'type' => $type 
                        //             );
                        //             $mail = new MailSender();
                        //             $mail->sendMail('admin::mail.grace_time', $details);
                        //         }
                        //     }
                
                        //     //Checkout Status
                        //     if (isset($attendanceModel->checkout) && $checkoutTimeWithGrace > date('H:i', strtotime($attendanceModel->checkout))) {
                        //         $type = ' clocked-out earlier ';
                        //         $notified_user_email = $employee->official_email;
                        //         $notified_user_fullname = $employee->getFullName();
                        //         if($notified_user_email){
                        //             $details = array(
                        //                 'email' => $notified_user_email,
                        //                 'notified_user_fullname' => $notified_user_fullname,
                        //                 'setting' => Setting::first(),
                        //                 'subject' => 'Grace Time Notification',
                        //                 'type' => $type 
                        //             );
                        //             $mail = new MailSender();
                        //             $mail->sendMail('admin::mail.grace_time', $details);
                        //         }
                        //     }
                        // }
                        $count++;

                        // $leave = $this->employee->getLeaveFromSubsituteDate($employee->id, $attendanceModel->date);
                        // if ($leave) {
                        //     $this->employee->employeeLeaveIncrement($leave);
                        // }
                    }
                }
                $text .= $employee->biometric_id . ' - ';
            }
            $text .= 'count=' . $count;
            Storage::put('attentance.txt', $text);
        }

        echo "Cron job run successfully.";
    }
}
