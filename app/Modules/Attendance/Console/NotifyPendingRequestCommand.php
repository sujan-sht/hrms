<?php

namespace App\Modules\Attendance\Console;

use App\Modules\Admin\Entities\MailSender;
use App\Modules\Attendance\Entities\AttendanceRequest;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Leave\Entities\Leave;
use App\Modules\Setting\Entities\Setting;
use App\Modules\User\Entities\User;
use Illuminate\Console\Command;

class NotifyPendingRequestCommand extends Command
{
    protected $name = 'pendingRequest:email';
    protected $description = 'Send email to supervisor if he/she has pending request for 7 days';
    
    protected $attendanceRequestRepo;

    public function handle()
    {
        $this->notifyPendingRequests(AttendanceRequest::class);
        $this->notifyPendingRequests(Leave::class);
        
        return true;
    }

    protected function notifyPendingRequests($value)
    {
        $requests = $value::whereBetween('created_at', [now()->locale('en')->startOfWeek(), now()->locale('en')->endOfWeek()])
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
                (new MailSender())->sendMail('admin::mail.pending_req', $details);
            }
        }
    }

}