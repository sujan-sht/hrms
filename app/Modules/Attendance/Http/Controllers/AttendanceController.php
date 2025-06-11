<?php

namespace App\Modules\Attendance\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\DateTimeHelper;
use Illuminate\Routing\Controller;
use App\Modules\Shift\Entities\Shift;
use Illuminate\Support\Facades\Storage;
use App\Modules\Setting\Entities\Setting;
use Dotenv\Exception\ValidationException;
use App\Modules\Admin\Entities\MailSender;
use App\Modules\Shift\Entities\ShiftGroup;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Attendance\Entities\Attendance;
use App\Modules\Shift\Entities\ShiftGroupMember;
use App\Modules\Attendance\Entities\AttendanceLog;
use App\Modules\NewShift\Entities\NewShiftEmployee;
use App\Modules\Notification\Entities\Notification;
use App\Modules\Shift\Repositories\ShiftRepository;
use App\Modules\Attendance\Services\AttendanceService;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Shift\Repositories\ShiftGroupRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\Shift\Repositories\EmployeeShiftInterface;
use App\Modules\Attendance\Repositories\AttendanceInterface;
use App\Modules\Attendance\Repositories\AttendanceLogInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Attendance\Repositories\AttendanceReportInterface;

class AttendanceController extends Controller
{
    protected $attendance;
    protected $attendanceReport;
    protected $attendanceLog;
    protected $employee;
    protected $organization;
    protected $employeeShift;

    public function __construct(
        AttendanceInterface $attendance,
        AttendanceLogInterface $attendanceLog,
        EmployeeInterface $employee,
        EmployeeShiftInterface $employeeShift,
        OrganizationInterface $organization,
        AttendanceReportInterface $attendanceReport
    ) {
        $this->attendance = $attendance;
        $this->attendanceLog = $attendanceLog;
        $this->employee = $employee;
        $this->organization = $organization;
        $this->employeeShift = $employeeShift;
        $this->attendanceReport = $attendanceReport;
    }

    /**
     * By: Er. Niraj Thike
     * Used for get data from device
     */
    // public function saveAttendanceLogs(Request $request)
    // {
    //     $attendance_array = $request->all();
    //     try {
    //         if (!empty($attendance_array)) {
    //             foreach ($attendance_array as $value) {
    //                 $endDate = date('Y-m-d', strtotime('-7 Days', strtotime($value['date'])));
    //                 if ($value['date'] >= $endDate) {
    //                     $employeeModel = $this->employee->getEmployeeByBiometric($value['biometric_emp_id']);
    //                     if ($employeeModel !== null) {
    //                         $attendanceLogModel = AttendanceLog::where('date', $value['date'])->where('biometric_emp_id', $value['biometric_emp_id'])->where('time', $value['time'])->first();
    //                         if ($attendanceLogModel == null) {
    //                             $attendanceLogModel = new AttendanceLog();
    //                             $attendanceLogModel->org_id = $employeeModel->organization_id;
    //                             $attendanceLogModel->biometric_emp_id = $value['biometric_emp_id'];
    //                             $attendanceLogModel->emp_id = $employeeModel->id;
    //                             $attendanceLogModel->date = $value['date'];
    //                             $attendanceLogModel->time = $value['time'];
    //                             $attendanceLogModel->inout_mode = $value['inout_mode'];
    //                             $attendanceLogModel->verifymode = $value['verify_mode'];
    //                             $attendanceLogModel->ip_address = $value['IpAddress'] ?? null;
    //                             $attendanceLogModel->save();
    //                         }
    //                     }
    //                 }
    //             }
    //         }

    //         $data['status'] = "success";
    //         $data['message'] = "Success";
    //         $data['status_code'] = Response::HTTP_OK;
    //     } catch (\Throwable $t) {
    //         $data['error'] = true;
    //         $data['message'] = "Something went wrong";
    //         $data['exception_msg'] = $t->getMessage();
    //         $data['status_code'] = Response::HTTP_INTERNAL_SERVER_ERROR;
    //     }

    //     return Response()->json($data);
    // }

    //Biometric Attendance hit
    public function saveAttendanceData(Request $request)
    {
        $attendance_array = $request->all();
        try {
            if (!empty($attendance_array)) {
                foreach ($attendance_array as $value) {
                    $employeeModel = $this->employee->getEmployeeByBiometric($value['biometric_emp_id']);
                    if (isset($employeeModel)) {
                        $attendanceLogModel = AttendanceLog::where('date', $value['date'])->where('biometric_emp_id', $value['biometric_emp_id'])->where('time', $value['time'])->first();
                        if (!isset($attendanceLogModel)) {
                            $employeeShift = optional(optional(ShiftGroupMember::where('group_member', $employeeModel->id)->orderBy('id', 'DESC')->first())->group)->shift;
                            $day = date('D', strtotime($value['date']));

                            $newShiftEmp = NewShiftEmployee::getShiftEmployee($employeeModel->id, $value['date']);


                            if (isset($newShiftEmp)) {
                                $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
                                if (isset($rosterShift) && isset($rosterShift->shift_group_id) && ($rosterShift->shift_group_id != null)) {
                                    $employeeShift = optional((new ShiftGroupRepository())->find($rosterShift->shift_group_id)->shift);
                                }
                            }

                            if (isset($employeeShift)) {
                                $shiftSeason = $employeeShift->getShiftSeasonForDate($value['date']);
                                $seasonalShiftId = null;
                                if ($shiftSeason) {
                                    $seasonalShiftId = $shiftSeason->id;
                                }
                                // dd('here',$employeeShift->getShiftDayWise($day, $seasonalShiftId));
                                $shifDateTime = optional($employeeShift->getShiftDayWise($day, $seasonalShiftId));
                                $shiftCheckPoint = optional($employeeShift->getShiftDayWise($day, $seasonalShiftId))->getCheckpoint();
                            } else {
                                $shiftCheckPoint = '14:00';
                            }
                            $time = strtotime($value['time']); // actual log time, e.g. 22:15:57 or 02:00
                            $shiftStart = strtotime($shifDateTime['start_time']); // e.g. 22:00
                            $shiftCheckPoint = strtotime($shiftCheckPoint); // e.g. start time or some decision time

                            // Determine if this is a night shift (starts after 6 PM)
                            $isNightShift = strtotime($shifDateTime['start_time']) > strtotime('18:00');


                            $attendanceLogModel = new AttendanceLog();
                            $attendanceLogModel->org_id = $employeeModel->organization_id;
                            $attendanceLogModel->biometric_emp_id = $value['biometric_emp_id'];
                            $attendanceLogModel->emp_id = $employeeModel->id;
                            $attendanceLogModel->date = $value['date'];
                            $attendanceLogModel->time = $value['time'];
                            // dd('time',$value['time'], $shiftCheckPoint);
                            // $attendanceLogModel->inout_mode = $value['inout_mode'];
                            $logTime = strtotime($value['time']);
                            if ($isNightShift) {
                                if ($logTime < strtotime('12:00')) {
                                    // Time is in early morning (next day) — should be checkout (1)
                                    $attendanceLogModel->inout_mode = $value['inout_mode'] = 1;
                                } else {
                                    // Time is in the evening (start of shift) — should be checkin (0)
                                    $attendanceLogModel->inout_mode = $value['inout_mode'] = 0;
                                }
                            } else {
                                if (strtotime($value['time']) < strtotime($shiftCheckPoint)) {
                                    $attendanceLogModel->inout_mode = $value['inout_mode'] = 0;
                                } else {

                                    $attendanceLogModel->inout_mode = $value['inout_mode'] = 1;
                                }
                            }

                            // $attendanceLogModel->verifymode = $value['verify_mode'];
                            $attendanceLogModel->ip_address = $value['IpAddress'] ?? null;
                            $attendanceLogModel->punch_from = $value['punch_from'];
                            $attendanceLogModel->save();

                            $this->attendance->saveAttendance($employeeModel, $value);
                        }
                    }
                }
            }

            $data['status'] = "success";
            $data['message'] = "Success";
            $data['status_code'] = Response::HTTP_OK;
        } catch (\Throwable $t) {
            $data['error'] = true;
            $data['message'] = "Something went wrong";
            $data['exception_msg'] = $t->getMessage();
            $data['status_code'] = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return Response()->json($data);
    }

    public function deleteAttendanceData($date)
    {
        try {
            if (isset($date)) {
                AttendanceLog::whereDate('date', $date)->delete();
                Attendance::whereDate('date', $date)->delete();
            }
            $data['status'] = "success";
            $data['message'] = "Success";
        } catch (ModelNotFoundException $e) {
            $data['error'] = true;
            $data['message'] = "Something went wrong";
            $data['exception_msg'] = $e->getMessage();
        }
        return Response()->json($data);
    }
    public function runAttendance()
    {
        (new AttendanceService())->runAttendance();
    }

    public function deductLeaveDaily()
    {
        $this->attendance->dailyLeaveDeductBasedOnAttendance();
        echo "Daily Leave Deduction based on Attendance run successfully.";
    }

    public function missedCheckInNotify()
    {
        try {
            Employee::when(true, function ($query) {
                $query->where('status', 1);
            })
                ->chunk('50', function ($employees) {
                    foreach ($employees as $employee) {
                        $status = $this->attendanceReport->checkStatus($employee, 'date', date('Y-m-d'));
                        if ($status == 'A') {
                            //missed checkin
                            $message = 'Did you forget to Check In today (' . date('Y-m-d') . ') ?';
                            $this->notification($employee, $message);

                            $notified_user_email = $employee->official_email;
                            $notified_user_fullname = $employee->getFullName();
                            if ($notified_user_email) {
                                $details = array(
                                    'email' => $notified_user_email,
                                    'notified_user_fullname' => $notified_user_fullname,
                                    'setting' => Setting::first(),
                                    'subject' => 'Reminder: Missing Check-In',
                                    'type' => 'check-in',
                                    'date' => date('Y-m-d')
                                );
                                $mail = new MailSender();
                                $mail->sendMail('admin::mail.missedcheckin_missedcheckout', $details);
                            }
                        }
                    }
                });
            return 'Success';
        } catch (\Throwable $e) {
            echo $e->getMessage();
        }
    }

    public function missedCheckOutNotify()
    {
        try {
            Employee::when(true, function ($query) {
                $query->where('status', 1);
            })
                ->chunk('50', function ($employees) {
                    foreach ($employees as $employee) {

                        $empAtd = $this->attendance->employeeAttendanceExists($employee->id, date('Y-m-d'));
                        if (isset($empAtd) && $empAtd->checkout == null) {
                            // missed checkout
                            $message = 'Did you forget to Check Out today (' . date('Y-m-d') . ') ?';
                            $this->notification($employee, $message);

                            $notified_user_email = $employee->official_email;
                            $notified_user_fullname = $employee->getFullName();
                            if ($notified_user_email) {
                                $details = array(
                                    'email' => $notified_user_email,
                                    'notified_user_fullname' => $notified_user_fullname,
                                    'setting' => Setting::first(),
                                    'subject' => 'Reminder: Missing Check-Out',
                                    'type' => 'check-out',
                                    'date' => date('Y-m-d')
                                );
                                $mail = new MailSender();
                                $mail->sendMail('admin::mail.missedcheckin_missedcheckout', $details);
                            }
                        }
                    }
                });
            return 'Success';
        } catch (\Throwable $e) {
            echo $e->getMessage();
        }
    }

    public function notification($employee, $message)
    {
        $nepDateArray = date_converter()->eng_to_nep(date('Y'), date('m'), date('d'));
        $nepYear = $nepDateArray['year'];
        $nepMonth = $nepDateArray['month'];

        if (setting('calendar_type') == 'BS') {
            $route = route('monthlyAttendance', [
                'org_id' => $employee->organization_id,
                'emp_id' => ['empId' => $employee->id],
                'calendar_type' => 'nep',
                'nep_year' => $nepYear,
                'nep_month' => $nepMonth,
            ]);
        } else {
            $route = route('monthlyAttendance', [
                'org_id' => $employee->organization_id,
                'emp_id' => ['empId' => $employee->id],
                'calendar_type' => 'eng',
                'eng_year' => date('Y'),
                'eng_month' => (int) date('m'),
            ]);
        }
        $notificationData['creator_user_id'] = 1;
        $notificationData['notified_user_id'] = optional($employee->getUser)->id;
        $notificationData['message'] = $message;
        $notificationData['link'] = $route;
        $notificationData['type'] = 'Missed Checkin/Checkout Notify';
        $notificationData['type_id_value'] = optional($employee->getUser)->id;
        Notification::create($notificationData);
    }

    // public function deductLeaveMonthly()
    // {
    //     $this->attendance->monthlyLeaveDeductBasedOnAttendance();
    //     echo "Monthly Leave Deduction based on Attendance run successfully.";
    // }
}
