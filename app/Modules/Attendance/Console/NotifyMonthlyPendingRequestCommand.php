<?php

namespace App\Modules\Attendance\Console;

use App\Modules\Admin\Entities\MailSender;
use App\Modules\Attendance\Entities\AttendanceRequest;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Leave\Entities\Leave;
use App\Modules\Setting\Entities\Setting;
use App\Modules\User\Entities\User;
use Illuminate\Console\Command;

class NotifyMonthlyPendingRequestCommand extends Command
{
    protected $name = 'monthlyPendingRequest:email';
    protected $description = 'Send email to supervisor if he/she has pending request for 1 month';

    protected $attendanceReq;

    public function handle(){
        $this->notifyPendingRequest(AttendanceRequest::class);
        $this->notifyPendingRequest(Leave::class);
        return true;
    }

    public function notifyPendingRequest($value){
        $todayDate = date('Y-m-d');
        $nepTodayDate = date_converter()->eng_to_nep_convert($todayDate);
        $currentYear = explode('-', $nepTodayDate)[0];
        $currentMonth = explode('-', $nepTodayDate)[1];
        $totalDays = date_converter()->getTotalDaysInMonth($currentYear, $currentMonth);
        $current_month_start_date =  $currentYear . '-' . sprintf("%02d", $currentMonth) . '-' . sprintf("%02d", 1);
        $current_month_last_date = $currentYear . '-' . sprintf("%02d", $currentMonth) . '-' . sprintf("%02d", $totalDays);

        $current_month_name = date_converter()->_get_nepali_month((int) $currentMonth);
        $start_date=date_converter()->nep_to_eng_convert($current_month_start_date);
        $end_date=date_converter()->nep_to_eng_convert($current_month_last_date);

        if($nepTodayDate == $current_month_last_date){

            $requests = $value::whereBetween('created_at', [$start_date, $end_date])
                ->where('status', 1)
                ->select('employee_id')
                ->distinct()
                ->get();
            $employeeIds = [];
            $divIds = [];
            foreach ($requests as $request) {
                $employee = Employee::find($request->employee_id);
                if ($employee) {
                    $branchId = $employee->branchModel->id;
                    $hrEmployees = Employee::where('branch_id', $branchId)
                        ->whereHas('user', function ($q) {
                            $q->where('user_type', 'hr');
                        })
                        ->distinct()
                        ->get();

                    $divIds = array_merge($divIds, $hrEmployees->pluck('id')->toArray());

                    $approvalFlow = $employee->employeeApprovalFlowRelatedDetailModel;
                    if ($approvalFlow) {
                        $employeeIds[] = optional(User::find($approvalFlow->first_approval_user_id))->emp_id ?? 
                                        optional(User::find($approvalFlow->last_approval_user_id))->emp_id;
                    }
                }
            }

            $this->sendEmails(array_unique($employeeIds), 'Reminder: Pending Requests for Your Action');
            $this->sendEmails(array_unique($divIds), 'Reminder: Pending Requests for Your Action');
        }
    }

    protected function sendEmails(array $employeeIds, string $subject)
    {
        foreach ($employeeIds as $employeeId) {
            $employee = Employee::find($employeeId);
            if ($employee && $employee->official_email) {
                $details = [
                    'email' => $employee->official_email,
                    'notified_user_fullname' => $employee->getFullName(),
                    'setting' => Setting::first(),
                    'subject' => $subject,
                ];
                (new MailSender())->sendMail('admin::mail.pending_req_monthly', $details);
            }
        }
    }
}