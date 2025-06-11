<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendLoginNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $employees;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($employees)
    {
        $this->employees = $employees;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->employees->count() > 0) {
            foreach ($this->employees as $employee) {
                $user = $employee->getUser;
                if (!is_null($user)) {
                    $user->activateUserAccess();
                }
            }
        }
    }
}
