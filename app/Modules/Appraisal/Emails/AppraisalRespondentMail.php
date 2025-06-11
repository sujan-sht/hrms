<?php

namespace App\Modules\Appraisal\Emails;

use App\Modules\Appraisal\Entities\Respondent;
use App\Modules\Setting\Entities\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AppraisalRespondentMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     *
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data['setting'] = Setting::first();
        $data['respondent'] = Respondent::where('invitation_code',$this->details->invitation_code)->first();
        $data['name'] = $this->details->name;
        $data['email'] = $this->details->email;

        return $this->view('view.name');
    }
}
