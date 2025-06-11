<?php

namespace App\Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class MailSender extends Model
{

    protected $fillable = [];

    public function sendMail($file_path, $details)
    {
        Mail::send($file_path, ['data' => $details], function ($message) use ($details) {
            $message->from(config('mail.from.address'));
            $message->to($details['email'] ?? env('MAIL_TO_ADDRESS'));
            $message->subject($details['subject'] ?? 'Leave Notification');
        });
    }
}
