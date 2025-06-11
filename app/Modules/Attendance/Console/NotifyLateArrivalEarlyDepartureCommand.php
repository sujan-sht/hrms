<?php

namespace App\Modules\Attendance\Console;

use App\Modules\Admin\Entities\MailSender;
use App\Modules\Attendance\Repositories\AttendanceRepository;
use App\Modules\Employee\Repositories\EmployeeRepository;
use App\Modules\NewShift\Entities\NewShiftEmployee;
use App\Modules\Setting\Entities\Setting;
use App\Modules\Shift\Entities\ShiftGroupMember;
use App\Modules\Shift\Repositories\EmployeeShiftRepository;
use App\Modules\Shift\Repositories\ShiftGroupRepository;
use App\Modules\Shift\Repositories\ShiftRepository;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class NotifyLateArrivalEarlyDepartureCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'graceTimeNotify:email';
    protected $employee;
    protected $attendance;
    protected $employeeShift;




    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to user for late arrival and early departure';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->employee = new EmployeeRepository;
        $this->attendance = new AttendanceRepository;
        $this->employeeShift = new EmployeeShiftRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if(emailSetting(8) == 11 && setting('enable_mail') == 11){

            $employees = $this->employee->getActiveEmployees();
            if ($employees->count() > 0) {
                foreach ($employees as $employee) {
                    if (isset($employee->biometric_id)) {
                        $dataParams = array(
                            'emp_id' => $employee->id,
                            'org_id' => $employee->organization_id,
                            'date' => date('Y-m-d'),
                        );
                        $weeekDay = date('D', strtotime(date('Y-m-d')));

                        $attendanceModel = $this->attendance->findOne($dataParams);
                        if(isset($attendanceModel)){
                            //send mail for late arrival or early departure
                            if($attendanceModel->date == date('Y-m-d')){
                                $day = date('D', strtotime($attendanceModel->date));
                                
                                $shiftGroupMember = ShiftGroupMember::where('group_member', $employee->id)->orderBy('id', 'DESC')->first();
                                $shiftInfo = $this->employeeShift->findOne(['employee_id' => $employee->id, 'days' => $weeekDay]);

                                $newShiftEmp = NewShiftEmployee::getShiftEmployee($employee->id, $attendanceModel->date);
                                if (isset($newShiftEmp)) {
                                    $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
                                    if (isset($rosterShift) && isset($rosterShift->shift_group_id)) {
                                        $shiftInfo = optional((new ShiftGroupRepository())->find($rosterShift->shift_group_id)->shift);
                                    }
                                }
                                if (!empty($shiftInfo)) {
                                    $shiftSeason = $shiftInfo->getShiftSeasonForDate($attendanceModel->date);
                                    $seasonalShiftId = null;
                                    if($shiftSeason){
                                        $seasonalShiftId = $shiftSeason->id;
                                    }
                                    if(isset($shiftGroupMember)){
                                        $checkinTimeWithGrace = (date('H:i', strtotime(intval('+' . optional($shiftGroupMember->group)->ot_grace_period ?? 0) . 'minutes', strtotime(optional(optional($shiftInfo->getShift)->getShiftDayWise($day, $seasonalShiftId))->start_time))));
                                        $checkoutTimeWithGrace = (date('H:i', strtotime(intval('-' . optional($shiftGroupMember->group)->grace_period_checkout ?? 0) . 'minutes', strtotime(optional(optional($shiftInfo->getShift)->getShiftDayWise($day, $seasonalShiftId))->end_time))));

                                    }else{
                                        $checkinTimeWithGrace = (date('H:i', strtotime(intval('+' . 0) . 'minutes', strtotime(optional(optional($shiftInfo->getShift)->getShiftDayWise($day, $seasonalShiftId))->start_time))));
                                        $checkoutTimeWithGrace = (date('H:i', strtotime(intval('-' . 0) . 'minutes', strtotime(optional(optional($shiftInfo->getShift)->getShiftDayWise($day, $seasonalShiftId))->end_time))));
                                    }

                                    //For Late Arrival
                                    if (isset($attendanceModel->checkin) && $checkinTimeWithGrace < date('H:i', strtotime($attendanceModel->checkin))) {
                                        $type = ' arrived later ';
                                        $notified_user_email = $employee->official_email;
                                        $notified_user_fullname = $employee->getFullName();
                                        if($notified_user_email){
                                            $details = array(
                                                'email' => $notified_user_email,
                                                'notified_user_fullname' => $notified_user_fullname,
                                                'setting' => Setting::first(),
                                                'subject' => 'Grace Time Notification',
                                                'type' => $type 
                                            );
                                            $mail = new MailSender();
                                            $mail->sendMail('admin::mail.grace_time', $details);
                                        }
                                    }
                        
                                    //For Early Departure
                                    if (isset($attendanceModel->checkout) && $checkoutTimeWithGrace > date('H:i', strtotime($attendanceModel->checkout))) {
                                        $type = ' clocked-out earlier ';
                                        $notified_user_email = $employee->official_email;
                                        $notified_user_fullname = $employee->getFullName();
                                        if($notified_user_email){
                                            $details = array(
                                                'email' => $notified_user_email,
                                                'notified_user_fullname' => $notified_user_fullname,
                                                'setting' => Setting::first(),
                                                'subject' => 'Grace Time Notification',
                                                'type' => $type 
                                            );
                                            $mail = new MailSender();
                                            $mail->sendMail('admin::mail.grace_time', $details);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return true;
    }
  
}
