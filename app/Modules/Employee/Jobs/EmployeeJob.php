<?php

namespace App\Modules\Employee\Jobs;

use App\Modules\Admin\Entities\MailSender;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\RequestChanges;
use App\Modules\Empployee\Entities\RequestChange;
use App\Modules\Notification\Entities\Notification;
use App\Modules\Setting\Entities\Setting;
use App\Modules\User\Entities\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Ladumor\OneSignal\OneSignal;

class EmployeeJob implements ShouldQueue
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
        $change_req_link = route('request-change.view', $this->user);
        $changesModel = RequestChanges::find($this->model->id);
        $employeeModel = Employee::find($this->model->employee_id);
        $data['model'] = $changesModel;
        $data['employee'] = $employeeModel;
        $data['setting'] = Setting::first();

        $subject = 'Request for ' . $changesModel->entity ?? ' Profile ' . ' changes for ' . $employeeModel->full_name . ' (' . $employeeModel->employee_code . ')';


        $mailArray = [];
        // check for all hr roles
        $hrs = User::where('user_type', 'hr')->get();
        if (isset($hrs) && !empty($hrs)) {
            foreach ($hrs as $hr) {
                // create notification for all hr
                $notificationData['creator_user_id'] = $authUser;
                $notificationData['notified_user_id'] = $hr->id;
                $notificationData['message'] = $employeeModel->full_name . " has requested for changes.";
                $notificationData['link'] = $change_req_link;
                $notificationData['type'] = 'Changes Request';
                $notificationData['type_id_value'] = $this->model->id;
                Notification::create($notificationData);

                // send notification in phone
                // if ($hr->device) {
                //     $fields['include_player_ids'] = [optional($hr->device)->os_player_id];
                //     $fields['isIos'] = true;
                //     $fields['isAndroid'] = true;
                //     $message = $notificationData['message'];
                //     // OneSignal::sendPush($fields, $message);
                // }

                // // send email to all hr
                // if (emailSetting(1) == 11) {
                //     $notified_user_email = User::getUserEmail($hr->id);
                //     if (isset($notified_user_email) && !empty($notified_user_email) && $this->model->enable_mail == 11) {
                //         $notified_user_fullname = Employee::getName($hr->id);

                //         $change_link = "<a href='$change_req_link'>$employeeModel->full_name</a>";

                //         $details = array(
                //             'email' => $notified_user_email,
                //             'message' => $employeeModel->full_name . " has requested for changes his/her details. Check the changes here: " . $change_req_link,
                //             'notified_user_fullname' => $notified_user_fullname,
                //             'setting' => Setting::first(),
                //             'leave' => $this->model,
                //             'subject' => $subject
                //         );
                //         $mailArray[] = $details;
                //     }
                // }
            }
        }

        // Send all email at once
        // if (count($mailArray) > 0) {
        //     $mail = new MailSender();
        //     foreach ($mailArray as $mailDetail) {
        //         try {
        //             $mail->sendMail('admin::mail.change_request', $mailDetail);
        //         } catch (\Throwable $th) {
        //             continue;
        //         }
        //     }
        // }

        return true;
    }
}
