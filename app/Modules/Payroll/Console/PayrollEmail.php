<?php

namespace App\Modules\Payroll\Console;

use App\Modules\Admin\Entities\MailSender;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Repositories\EmployeeRepository;
use App\Modules\Setting\Entities\Setting;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PayrollEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'payroll:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Payroll Email';

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
        if(emailSetting(6) == 11 && setting('enable_mail') == 11){

            $mail = new MailSender();
            $employees = Employee::where('status', '1')->get();
            $todayDate = date('Y-m-d');
            $nepTodayDate = date_converter()->eng_to_nep_convert($todayDate);
            $current_year = explode('-',$nepTodayDate)[0];
            $current_month = explode('-',$nepTodayDate)[1];
            $total_days = date_converter()->getTotalDaysInMonth($current_year,$current_month);
            $current_month_last_date = $current_year . '-' . sprintf("%02d", $current_month) . '-' . sprintf("%02d", $total_days);

            $current_month_name = date_converter()->_get_nepali_month((int) $current_month);

            if($nepTodayDate == $current_month_last_date){
                foreach($employees as $employee){
                    $notified_user_email = $employee->official_email;
                    $notified_user_fullname = $employee->getFullName();
                    if($notified_user_email){
                        $details = array(
                            'email' => $notified_user_email,
                            'message' => '',
                            'notified_user_fullname' => $notified_user_fullname,
                            'setting' => Setting::first(),
                            'subject' => 'Urgent Reminder: Review Your Leave, Attendance, and Requests for Current Month',
                            'current_month_name' => $current_month_name
                        );
                        $mail->sendMail('admin::mail.payroll', $details);
                    }
                    
                }
            }
        }
    }
}
