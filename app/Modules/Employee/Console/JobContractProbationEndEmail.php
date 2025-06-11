<?php

namespace App\Modules\Employee\Console;

use App\Modules\Admin\Entities\MailSender;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Notification\Entities\Notification;
use App\Modules\Setting\Entities\Setting;
use App\Modules\User\Entities\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class JobContractProbationEndEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'jobProbationContractEnd:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to employees whose Job End Date, Probation End Date or Contract End Date has been finished.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
            $finalArray = [];
            $todayDate = date('Y-m-d');
            $employees = Employee::where('status', '1')->get();
            foreach($employees as $employee){
                // For Job End Date
                if(isset($employee->end_date) && $todayDate >= $employee->end_date){
                    $subject = 'Job End Date Reminder';
                    $type = 'Job Period';
                    $finalArray = array_merge($finalArray, $this->setDetails($employee, $type, $subject));
                }

                // For Contract End Date
                if(isset(optional($employee->payrollRelatedDetailModel)->contract_end_date) && $todayDate >= optional($employee->payrollRelatedDetailModel)->contract_end_date){
                    $subject = 'Contract End Date Reminder';
                    $type = 'Contract Period';
                    $finalArray = array_merge($finalArray, $this->setDetails($employee, $type, $subject));
                }

                // For Probation End Date
                if(isset(optional($employee->payrollRelatedDetailModel)->probation_end_date) && $todayDate >= optional($employee->payrollRelatedDetailModel)->probation_end_date){
                    $subject = 'Probation End Date Reminder';
                    $type = 'Probation Period';
                    $finalArray = array_merge($finalArray, $this->setDetails($employee, $type, $subject));
                }
            }
            // Send all email at once
            if(emailSetting(5) == 11 && setting('enable_mail') == 11){
                if (count($finalArray) > 0) {
                    foreach($finalArray as $mailDetail){
                        $mail = new MailSender();
                        $mail->sendMail('admin::mail.job_probation_contract_end', $mailDetail);
                    }
                }
            }
        return true;
    }

    public function setDetails($employee, $type, $subject){
        $mailArray = [];
        $link = route('employee.edit',$employee->id);

        // For employee
        if(isset(optional($employee->getUser)->id)){
            $notificationData['creator_user_id'] = optional($employee->getUser)->id;
            $notificationData['notified_user_id'] = optional($employee->getUser)->id;
            $notificationData['message'] = "Your ". $type. " has come to an end. Kindly, contact the HR department.";
            $notificationData['link'] = '';
            $notificationData['type'] = $type;
            $notificationData['type_id_value'] = 1;
            Notification::create($notificationData);
        }
        
        $notified_user_email = User::getUserEmail(optional($employee->getUser)->id);
        if (isset($notified_user_email) && !empty($notified_user_email)) {
            $notified_user_fullname = Employee::getName(optional($employee->getUser)->id);

            $details = array(
                'email' => $notified_user_email,
                'message' => "Your ". $type. " has come to an end. Kindly, contact the HR department.",
                'notified_user_fullname' => $notified_user_fullname,
                'setting' => Setting::first(),
                'subject' => $subject,
                'role' => 'employee'
            );
            $mailArray[] = $details;
        }

        // check for all HR role
        $hrs = User::where('user_type', 'hr')->pluck('id');
        if (isset($hrs) && !empty($hrs)) {
            foreach ($hrs as $hr) {

            if(isset($hr)){
                $notificationData['creator_user_id'] = $hr;
                $notificationData['notified_user_id'] = $hr;
                $notificationData['message'] = $employee->full_name . "'s " .$type." has come to an end.";
                $notificationData['link'] = $link;
                $notificationData['type'] = $type;
                $notificationData['type_id_value'] = 2;
                Notification::create($notificationData);
            }

                $notified_user_email = User::getUserEmail($hr);
                if (isset($notified_user_email) && !empty($notified_user_email)) {
                    $notified_user_fullname = Employee::getName($hr);

                    $details = array(
                        'email' => $notified_user_email,
                        'message' => $employee->full_name . "'s " .$type." has come to an end.",
                        'notified_user_fullname' => $notified_user_fullname,
                        'setting' => Setting::first(),
                        'subject' => $subject,
                    );
                    $mailArray[] = $details;
                }
            }
        }

        // For all division HR roles
        $divisionHrs = User::when(true, function ($query) use ($employee) {
            $query->whereHas('userEmployer', function ($q) use ($employee) {
                $q->where('organization_id', $employee->organization_id)->where('status', 1);
            });
        })->where('user_type', 'division_hr')->pluck('id');
        if (isset($divisionHrs) && !empty($divisionHrs)) {
            foreach ($divisionHrs as $divisionHr) {

            if(isset($divisionHr)){
                $notificationData['creator_user_id'] = $divisionHr;
                $notificationData['notified_user_id'] = $divisionHr;
                $notificationData['message'] = $employee->full_name . "'s " .$type." has come to an end.";
                $notificationData['link'] = $link;
                $notificationData['type'] = $type;
                $notificationData['type_id_value'] = 3;
                Notification::create($notificationData);
            }

                $notified_user_email = User::getUserEmail($divisionHr);
                if (isset($notified_user_email) && !empty($notified_user_email)) {
                    $notified_user_fullname = Employee::getName($divisionHr);
                    $details = array(
                        'email' => $notified_user_email,
                        'message' => $employee->full_name . "'s " .$type." has come to an end.",
                        'notified_user_fullname' => $notified_user_fullname,
                        'setting' => Setting::first(),
                        'subject' => $subject,
                    );
                    $mailArray[] = $details;
                }
            }
        }
        return $mailArray;
    }

}
