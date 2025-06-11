<?php

namespace App\Modules\Attendance\Jobs;


use App\Modules\Admin\Entities\MailSender;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Notification\Entities\Notification;
use App\Modules\Setting\Entities\Setting;
use App\Modules\User\Entities\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Ladumor\OneSignal\OneSignal;

class AttendanceRequestJob implements ShouldQueue
{
    use Dispatchable, Queueable;
    protected $model;
    protected $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($model, $user)
    {
        $this->model = $model;
        $this->user = $user;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $authUser = $this->user;
        $employeeModel = Employee::find($this->model->employee_id);
        $userModel = optional($employeeModel->getUser);

        //check if there is first approval or not
        if (isset(optional($employeeModel->employeeAttendanceApprovalFlow)->first_approval_user_id) && !empty(optional($employeeModel->employeeAttendanceApprovalFlow)->first_approval_user_id)) {
            $singleApproval = false;
        } else {
            $singleApproval = true;
        }
        //

        if ($authUser->user_type == 'super_admin') {
            $authorName = $authUser->first_name;
        } else {
            $authorName = optional($authUser->userEmployer)->full_name;
        }

        if ($this->model->status == '1') {
            $statusTitle = 'Created';
        } else {
            $statusTitle = $this->model->getStatus();
        }
        $subject = 'Attendance Request ' . $statusTitle . ' for ' . $employeeModel->full_name . ' (' . $employeeModel->employee_code . ')';

        $mailArray = [];

        if (optional($employeeModel->getUser)->id) {
            if ($authUser->id != optional($employeeModel->getUser)->id && ($this->model->status == '1' || $this->model->status == '3' || $this->model->status == '4')) {

                // create notification for employee user
                $notificationData['creator_user_id'] = $authUser->id;
                $notificationData['notified_user_id'] = optional($employeeModel->getUser)->id;
                $notificationData['message'] = "Your " . $this->model->getType() . " request has been " . $statusTitle . " by " . $authorName;
                $notificationData['link'] = route('attendanceRequest.index');
                $notificationData['type'] = 'Attendance';
                $notificationData['type_id_value'] = $this->model->id;
                Notification::create($notificationData);

                if (isset($userModel->device)) {
                    $fields['include_player_ids'] = [optional($userModel->device)->os_player_id];
                    $fields['isIos'] = true;
                    $fields['isAndroid'] = true;
                    $message = $notificationData['message'];
                    OneSignal::sendPush($fields, $message);
                }

                // send email to employee
                if (emailSetting(2) == 11) {
                    $notified_user_email = User::getUserEmail(optional($employeeModel->getUser)->id);
                    if (isset($notified_user_email) && !empty($notified_user_email) && $this->model->enable_mail == 11) {
                        $notified_user_fullname = Employee::getName(optional($employeeModel->getUser)->id);
                        $details = array(
                            'email' => $notified_user_email,
                            'message' => "Your " . $this->model->getType() . " request has been " . $statusTitle . " by " . $authorName,
                            'notified_user_fullname' => $notified_user_fullname,
                            'setting' => Setting::first(),
                            'subject' => $subject,
                            'attendance_request' => $this->model
                        );
                        $mailArray[] = $details;
                    }
                }
            }
        }

        // check for first approval
        if (optional($employeeModel->employeeAttendanceApprovalFlow)->first_approval_user_id && $this->model->status == '1') {
            // create notification for first approval
            $notificationData['creator_user_id'] = $authUser->id;
            $notificationData['notified_user_id'] = optional($employeeModel->employeeAttendanceApprovalFlow)->first_approval_user_id;
            $notificationData['message'] = $employeeModel->full_name . "'s " . $this->model->getType() . " request has been " . $statusTitle . " by " . $authorName;
            $notificationData['link'] = route('attendanceRequest.showTeamAttendance');
            $notificationData['type'] = 'Attendance';
            $notificationData['type_id_value'] = $this->model->id;
            Notification::create($notificationData);

            // send notification in phone
            if (optional(optional($employeeModel->employeeAttendanceApprovalFlow)->userFirstApproval)->device) {
                $fields['include_player_ids'] = [optional(optional(optional($employeeModel->employeeAttendanceApprovalFlow)->userFirstApproval)->device)->os_player_id];
                $fields['isIos'] = true;
                $fields['isAndroid'] = true;
                $message = $notificationData['message'];
                OneSignal::sendPush($fields, $message);
            }
            // send email to supervisor
            if (emailSetting(2) == 11) {
                $notified_user_email = User::getUserEmail(optional($employeeModel->employeeAttendanceApprovalFlow)->first_approval_user_id);
                if (isset($notified_user_email) && !empty($notified_user_email) && $this->model->enable_mail == 11) {
                    $notified_user_fullname = Employee::getName(optional($employeeModel->employeeAttendanceApprovalFlow)->first_approval_user_id);
                    $details = array(
                        'email' => $notified_user_email,
                        'message' => $employeeModel->full_name . "'s " . $this->model->getType() . " request has been " . $statusTitle . " by " . $authorName,
                        'notified_user_fullname' => $notified_user_fullname,
                        'setting' => Setting::first(),
                        'subject' => $subject,
                        'attendance_request' => $this->model
                    );
                    $mailArray[] = $details;
                }
            }
        }

        // check for last approval
        if (optional($employeeModel->employeeAttendanceApprovalFlow)->last_approval_user_id && ($this->model->status == '2' || ($singleApproval == true && $this->model->status == '1'))) {
            // create notification for last approval
            $notificationData['creator_user_id'] = $authUser->id;
            $notificationData['notified_user_id'] = optional($employeeModel->employeeAttendanceApprovalFlow)->last_approval_user_id;
            $notificationData['message'] = $employeeModel->full_name . "'s " . $this->model->getType() . " request has been " . $statusTitle . " by " . $authorName;
            $notificationData['link'] = route('attendanceRequest.showTeamAttendance');
            $notificationData['type'] = 'Attendance';
            $notificationData['type_id_value'] = $this->model->id;
            Notification::create($notificationData);

            // send notification in phone
            if (optional(optional($employeeModel->employeeAttendanceApprovalFlow)->userLastApproval)->device) {
                $fields['include_player_ids'] = [optional(optional(optional($employeeModel->employeeAttendanceApprovalFlow)->userLastApproval)->device)->os_player_id];
                $fields['isIos'] = true;
                $fields['isAndroid'] = true;
                $message = $notificationData['message'];
                OneSignal::sendPush($fields, $message);
            }

            // send email to last approval
            if (emailSetting(2) == 11) {
                $notified_user_email = User::getUserEmail(optional($employeeModel->employeeAttendanceApprovalFlow)->last_approval_user_id);
                if (isset($notified_user_email) && !empty($notified_user_email) && $this->model->enable_mail == 11) {
                    $notified_user_fullname = Employee::getName(optional($employeeModel->employeeAttendanceApprovalFlow)->last_approval_user_id);
                    $details = array(
                        'email' => $notified_user_email,
                        'message' => $employeeModel->full_name . "'s " . $this->model->getType() . " request has been " . $statusTitle . " by " . $authorName,
                        'notified_user_fullname' => $notified_user_fullname,
                        'setting' => Setting::first(),
                        'subject' => $subject,
                        'attendance_request' => $this->model
                    );
                    $mailArray[] = $details;
                }
            }
        }

        // check for all hr roles
        $hrs = User::where('user_type', 'hr')->get();
        if (isset($hrs) && !empty($hrs)) {
            foreach ($hrs as $hr) {
                // create notification for hr
                $notificationData['creator_user_id'] = $authUser->id;
                $notificationData['notified_user_id'] = $hr->id;
                $notificationData['message'] = $employeeModel->full_name . "'s " . $this->model->getType() . " request has been " . $statusTitle . " by " . $authorName;
                $notificationData['link'] = route('attendanceRequest.index');
                $notificationData['type'] = 'Attendance';
                $notificationData['type_id_value'] = $this->model->id;
                Notification::create($notificationData);

                // send notification in phone
                if ($hr->device) {
                    $fields['include_player_ids'] = [optional($hr->device)->os_player_id];
                    $fields['isIos'] = true;
                    $fields['isAndroid'] = true;
                    $message = $notificationData['message'];
                    OneSignal::sendPush($fields, $message);
                }

                // send email to all hr
                if (emailSetting(2) == 11) {
                    $notified_user_email = User::getUserEmail($hr->id);
                    if (isset($notified_user_email) && !empty($notified_user_email) && $this->model->enable_mail == 11) {
                        $notified_user_fullname = Employee::getName($hr->id);
                        $details = array(
                            'email' => $notified_user_email,
                            'message' => $employeeModel->full_name . "'s " . $this->model->getType() . " request has been " . $statusTitle . " by " . $authorName,
                            'notified_user_fullname' => $notified_user_fullname,
                            'setting' => Setting::first(),
                            'subject' => $subject,
                            'attendance_request' => $this->model
                        );
                        $mailArray[] = $details;
                    }
                }
            }
        }

        // check for all division hr roles
        $divisionHrs = User::when(true, function ($query) use ($employeeModel) {
            $query->whereHas('userEmployer', function ($q) use ($employeeModel) {
                $q->where('organization_id', $employeeModel->organization_id)->where('status', 1);
            });
        })->where('user_type', 'division_hr')->get();

        if (isset($divisionHrs) && !empty($divisionHrs)) {
            foreach ($divisionHrs as $divisionHr) {
                // create notification for division hr
                $notificationData['creator_user_id'] = $authUser->id;
                $notificationData['notified_user_id'] = $divisionHr->id;
                $notificationData['message'] = $employeeModel->full_name . "'s " . $this->model->getType() . " request has been " . $statusTitle . " by " . $authorName;
                $notificationData['link'] = route('attendanceRequest.index');
                $notificationData['type'] = 'Attendance';
                $notificationData['type_id_value'] = $this->model->id;
                Notification::create($notificationData);

                // send notification in phone
                if ($divisionHr->device) {
                    $fields['include_player_ids'] = [optional($divisionHr->device)->os_player_id];
                    $fields['isIos'] = true;
                    $fields['isAndroid'] = true;
                    $message = $notificationData['message'];
                    OneSignal::sendPush($fields, $message);
                }

                // send email to all division hr
                if (emailSetting(2) == 11) {
                    $notified_user_email = User::getUserEmail($divisionHr->id);
                    if (isset($notified_user_email) && !empty($notified_user_email) && $this->model->enable_mail == 11) {
                        $notified_user_fullname = Employee::getName($divisionHr->id);
                        $details = array(
                            'email' => $notified_user_email,
                            'message' => $employeeModel->full_name . "'s " . $this->model->getType() . " request has been " . $statusTitle . " by " . $authorName,
                            'notified_user_fullname' => $notified_user_fullname,
                            'setting' => Setting::first(),
                            'subject' => $subject,
                            'attendance_request' => $this->model
                        );
                        $mailArray[] = $details;
                    }
                }
            }
        }

        //  Send all email at once
        if (count($mailArray) > 0) {
            $mail = new MailSender();
            foreach ($mailArray as $mailDetail) {
                try {
                    $mail->sendMail('admin::mail.attendance-request', $mailDetail);
                } catch (\Throwable $th) {
                    continue;
                }
            }
        }
        return true;
    }


}
