<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Modules\User\Entities\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PasswordExpiryReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public User $user;
    public Carbon $expiry_date;
    public Carbon $expiry_reminder_date;
    public int $remaining_days_before_expiry;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Carbon $expiry_date, Carbon $expiry_reminder_date, int $remaining_days_before_expiry)
    {
        $this->expiry_date = $expiry_date;
        $this->expiry_reminder_date = $expiry_reminder_date;
        $this->remaining_days_before_expiry = $remaining_days_before_expiry;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.password_expiry_reminder_mail')
            ->subject('Password Expiry Reminder');
    }
}
