<?php

namespace App\Modules\Leave\Jobs;

use App\Modules\Admin\Entities\MailSender;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Leave\Entities\LeaveType;
use App\Modules\Notification\Entities\Notification;
use App\Modules\Setting\Entities\Setting;
use App\Modules\User\Entities\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Ladumor\OneSignal\OneSignal;

class LeaveJob implements ShouldQueue
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
        $leaveLink = route('leave.index');
        $teamLeaveLink = route('leave.showTeamleaves');

        $leaveTypeModel = LeaveType::find($this->model->leave_type_id);
        $employeeModel = Employee::find($this->model->employee_id);
        $userModel = optional($employeeModel->getUser);
        //check if there is first approval or not
        if (isset(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->first_approval_user_id) && !empty(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->first_approval_user_id)) {
            $singleApproval = false;
        } else {
            $singleApproval = true;
        }

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
        $subject = 'Leave '.$statusTitle. ' for '.$employeeModel->full_name.' ('.$employeeModel->employee_code.')'; 
       
       
        $mailArray = [];
        if (optional($employeeModel->getUser)->id) {
            if ($authUser->id != optional($employeeModel->getUser)->id && ($this->model->status == '1' || $this->model->status == '3' || $this->model->status == '4')) {
                // create notification for employee user
                $notificationData['creator_user_id'] = $authUser->id;
                $notificationData['notified_user_id'] = $userModel->id;
                $notificationData['message'] = "Your " . $leaveTypeModel->name . " has been " . $statusTitle . " by " . $authorName;
                $notificationData['link'] = $leaveLink;
                $notificationData['type'] = 'Leave';
                $notificationData['type_id_value'] = $this->model->id;
                Notification::create($notificationData);

                // send notification in phone
                if ($userModel->device) {
                    $fields['include_player_ids'] = [optional($userModel->device)->os_player_id];
                    $fields['isIos'] = true;
                    $fields['isAndroid'] = true;
                    $message = $notificationData['message'];
                    OneSignal::sendPush($fields, $message);
                }

                if(emailSetting(1) == 11){
                    // send email to employee who needs leave
                    $notified_user_email = User::getUserEmail(optional($employeeModel->getUser)->id);
                    if (isset($notified_user_email) && !empty($notified_user_email) && $this->model->enable_mail == 11) {
                        $notified_user_fullname = Employee::getName(optional($employeeModel->getUser)->id);

                        $leaveTypeWithLink = "<a href='$leaveLink'>$leaveTypeModel->name</a>";

                        $details = array(
                            'email' => $notified_user_email,
                            'message' => "Your " . $leaveTypeWithLink . " has been " . $statusTitle . " by " . $authorName,
                            'notified_user_fullname' => $notified_user_fullname,
                            'setting' => Setting::first(),
                            'leave' => $this->model,
                            'subject' => $subject
                        );
                        $mailArray[] = $details;
                    }
                }
            }
        }

        // check for first approval
        if (optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->first_approval_user_id && $this->model->status == '1') {
            // create notification for first approval
            $notificationData['creator_user_id'] = $authUser->id;
            $notificationData['notified_user_id'] = optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->first_approval_user_id;
            $notificationData['message'] = $employeeModel->full_name . "'s " . $leaveTypeModel->name . " has been " . $statusTitle . " by " . $authorName;
            $notificationData['link'] = $teamLeaveLink;
            $notificationData['type'] = 'Leave';
            $notificationData['type_id_value'] = $this->model->id;
            Notification::create($notificationData);

            // send notification in phone
            if (optional(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->userFirstApproval)->device) {
                $fields['include_player_ids'] = [optional(optional(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->userFirstApproval)->device)->os_player_id];
                $fields['isIos'] = true;
                $fields['isAndroid'] = true;
                $message = $notificationData['message'];
                OneSignal::sendPush($fields, $message);
            }

            // send email to supervisor
            if(emailSetting(1) == 11){
                $notified_user_email = User::getUserEmail(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->first_approval_user_id);
                if (isset($notified_user_email) && !empty($notified_user_email) && $this->model->enable_mail == 11) {
                    $notified_user_fullname = Employee::getName(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->first_approval_user_id);

                    $leaveTypeWithLink = "<a href='$teamLeaveLink'>$leaveTypeModel->name</a>";

                    $details = array(
                        'email' => $notified_user_email,
                        'message' => $employeeModel->full_name . "'s " . $leaveTypeWithLink . " has been " . $statusTitle . " by " . $authorName,
                        'notified_user_fullname' => $notified_user_fullname,
                        'setting' => Setting::first(),
                        'leave' => $this->model,
                        'subject' => $subject
                    );
                    $mailArray[] = $details;
                }
            }
        }

        // check for last approval
        if (optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->last_approval_user_id && ($this->model->status == '2' || ($singleApproval == true && $this->model->status == '1'))) {
            // create notification for last approval
            $notificationData['creator_user_id'] = $authUser->id;
            $notificationData['notified_user_id'] = optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->last_approval_user_id;
            $notificationData['message'] = $employeeModel->full_name . "'s " . $leaveTypeModel->name . " has been " . $statusTitle . " by " . $authorName;
            $notificationData['link'] = $teamLeaveLink;
            $notificationData['type'] = 'Leave';
            $notificationData['type_id_value'] = $this->model->id;
            Notification::create($notificationData);

            // send notification in phone
             if (optional(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->userLastApproval)->device) {
                $fields['include_player_ids'] = [optional(optional(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->userLastApproval)->device)->os_player_id];
                $fields['isIos'] = true;
                $fields['isAndroid'] = true;
                $message = $notificationData['message'];
                OneSignal::sendPush($fields, $message);
            }

            // send email to last approval
            if(emailSetting(1) == 11){
                $notified_user_email = User::getUserEmail(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->last_approval_user_id);
                if (isset($notified_user_email) && !empty($notified_user_email) && $this->model->enable_mail == 11) {
                    $notified_user_fullname = Employee::getName(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->last_approval_user_id);

                    $leaveTypeWithLink = "<a href='$teamLeaveLink'>$leaveTypeModel->name</a>";

                    $details = array(
                        'email' => $notified_user_email,
                        'message' => $employeeModel->full_name . "'s " . $leaveTypeWithLink . " has been " . $statusTitle . " by " . $authorName,
                        'notified_user_fullname' => $notified_user_fullname,
                        'setting' => Setting::first(),
                        'leave' => $this->model,
                        'subject' => $subject
                    );
                    $mailArray[] = $details;
                }
            }
        }

        // check for all hr roles
        $hrs = User::where('user_type', 'hr')->get();
        if (isset($hrs) && !empty($hrs)) {
            foreach ($hrs as $hr) {
                // create notification for all hr
                $notificationData['creator_user_id'] = $authUser->id;
                $notificationData['notified_user_id'] = $hr->id;
                $notificationData['message'] = $employeeModel->full_name . "'s " . $leaveTypeModel->name . " has been " . $statusTitle . " by " . $authorName;
                $notificationData['link'] = $leaveLink;
                $notificationData['type'] = 'Leave';
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
                if(emailSetting(1) == 11){
                    $notified_user_email = User::getUserEmail($hr->id);
                    if (isset($notified_user_email) && !empty($notified_user_email) && $this->model->enable_mail == 11) {
                        $notified_user_fullname = Employee::getName($hr->id);

                        $leaveTypeWithLink = "<a href='$leaveLink'>$leaveTypeModel->name</a>";

                        $details = array(
                            'email' => $notified_user_email,
                            'message' => $employeeModel->full_name . "'s " . $leaveTypeWithLink . " has been " . $statusTitle . " by " . $authorName,
                            'notified_user_fullname' => $notified_user_fullname,
                            'setting' => Setting::first(),
                            'leave' => $this->model,
                            'subject' => $subject
                        );
                        $mailArray[] = $details;
                    }
                }
            }
        }

        // check for division hr roles
        $divisionHrs = User::when(true, function ($query) use ($employeeModel) {
            $query->whereHas('userEmployer', function ($q) use ($employeeModel) {
                $q->where('organization_id', $employeeModel->organization_id)->where('status', 1);
            });
        })->where('user_type', 'division_hr')->get();

        if (isset($divisionHrs) && !empty($divisionHrs)) {
            foreach ($divisionHrs as $divisionHr) {
                // create notification for all division Hr
                $notificationData['creator_user_id'] = $authUser->id;
                $notificationData['notified_user_id'] = $divisionHr->id;
                $notificationData['message'] = $employeeModel->full_name . "'s " . $leaveTypeModel->name . " has been " . $statusTitle . " by " . $authorName;
                $notificationData['link'] = $leaveLink;
                $notificationData['type'] = 'Leave';
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

                // send email to all division Hr
                if(emailSetting(1) == 11){
                    $notified_user_email = User::getUserEmail($divisionHr->id);
                    if (isset($notified_user_email) && !empty($notified_user_email) && $this->model->enable_mail == 11) {
                        $notified_user_fullname = Employee::getName($divisionHr->id);

                        $leaveTypeWithLink = "<a href='$leaveLink'>$leaveTypeModel->name</a>";

                        $details = array(
                            'email' => $notified_user_email,
                            'message' => $employeeModel->full_name . "'s " . $leaveTypeWithLink . " has been " . $statusTitle . " by " . $authorName,
                            'notified_user_fullname' => $notified_user_fullname,
                            'setting' => Setting::first(),
                            'leave' => $this->model,
                            'subject' => $subject
                        );
                        $mailArray[] = $details;
                    }
                }
            }
        }

        if (isset($this->model->alt_employee_id) && $this->model->status == '3') {
            $alternateEmployeeModel = Employee::find($this->model->alt_employee_id);

            // create notification for alternative employee
            $notificationData['creator_user_id'] = $authUser->id;
            $notificationData['notified_user_id'] = optional($alternateEmployeeModel->getUser)->id;
            $notificationData['message'] = "You have been assigned as alternative on behalf of " . $employeeModel->full_name;
            $notificationData['link'] = $leaveLink;
            $notificationData['type'] = 'Leave';
            $notificationData['type_id_value'] = $this->model->id;
            Notification::create($notificationData);

            // send notification in phone
            if ($alternateEmployeeModel->getUser) {
                $fields['include_player_ids'] = [optional(optional($alternateEmployeeModel->getUser)->device)->os_player_id];
                $fields['isIos'] = true;
                $fields['isAndroid'] = true;
                $message = $notificationData['message'];
                OneSignal::sendPush($fields, $message);
            }

            // send email to alternate employee
            if(emailSetting(1) == 11){
                $notified_user_email = User::getUserEmail(optional($alternateEmployeeModel->getUser)->id);
                if (isset($notified_user_email) && !empty($notified_user_email) && $this->model->enable_mail == 11) {
                    $notified_user_fullname = Employee::getName(optional($alternateEmployeeModel->getUser)->id);
                    $details = array(
                        'email' => $notified_user_email,
                        'message' => "You have been assigned as alternative on behalf of " . $employeeModel->full_name,
                        'notified_user_fullname' => $notified_user_fullname,
                        'setting' => Setting::first(),
                        'leave' => $this->model,
                        'subject' => $subject
                    );
                    $mailArray[] = $details;
                }
            }
        }

        // Send all email at once
        if (count($mailArray) > 0) {
            $mail = new MailSender();
            foreach ($mailArray as $mailDetail) {
                try {
                    $mail->sendMail('admin::mail.leave', $mailDetail);
                } catch (\Throwable $th) {
                    continue;
                }
            }
        }

        return true;
    }

   
}
