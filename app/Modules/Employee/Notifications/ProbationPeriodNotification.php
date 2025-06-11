<?php

namespace App\Modules\Employee\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ProbationPeriodNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $employee;
    protected $months;

    public function __construct($employee, $months)
    {
        $this->employee = $employee;
        $this->months = $months; // 3 or 5
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("{$this->months}-Month Contract Notification")
            ->markdown('employee::emails.probation-notification', [
                'employee' => $this->employee,
                'months' => $this->months,
            ]);
    }
}
