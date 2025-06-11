<?php

namespace App\Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Setting\Entities\Setting;
use App\Modules\Admin\Entities\MailSender;
use App\Modules\Admin\Repositories\SystemReminderInterface;
use App\Modules\Employee\Entities\EmployeePayrollRelatedDetail;

class AdminController extends Controller
{
    public $reminderObj;

    /**
     * 
     */
    public function __construct(
        SystemReminderInterface $reminderObj
    ) {
        $this->reminderObj = $reminderObj;
    }

    /**
     * 
     */
    public function saveSystemReminder()
    {
        $now = Carbon::now();
        $compile_now_date = date('Y-m-d', strtotime('+ 2 days', strtotime($now)));

        // check for probation period end
        $models = EmployeePayrollRelatedDetail::where('probation_end_date', $compile_now_date)->get();
        if(count($models) > 0) {
            foreach ($models as $model) {
                $endDate = date('M d, Y', strtotime($model->probation_end_date));
                $reminderData['user_id'] = optional(optional($model->employeeModel)->getUser)->id;
                $reminderData['title'] = optional($model->employeeModel)->full_name."'s probation period will be ended on ".$endDate;
                $reminderData['date'] = $model->probation_end_date;
                $reminderData['icon'] = "icon-statistics";
                $reminderData['color'] = "success";
                $this->reminderObj->create($reminderData);
            }
        }

        return 'System reminder saved successfully';
    }

    /**
     * 
     */
    public function systemReminderList()
    {
        $data['systemReminders'] = $this->reminderObj->getSystemReminder();

        return view('admin::admin.all_reminder', $data);
    }

    /**
     * For testing mail
     */
    public function testMail(Request $request)
    {
        $inputData = $request->all();

        $mailDetails = array(
            'email' => 'to',
            'message' => 'This is test message from developer.',
            'notified_user_fullname' => 'Test User',
            'setting' => Setting::first()
        );
    
        $mail = new MailSender();
        $mail->sendMail('admin::mail.leave', $mailDetails);
    
        return "Mail sent successful.";        
    }
}
