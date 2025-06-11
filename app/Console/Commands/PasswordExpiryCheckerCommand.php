<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Modules\User\Entities\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordExpiryReminderMail;

class PasswordExpiryCheckerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:password-expiry {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to check password expiry for users';

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
     * @return int
     */
    public function handle()
    {
        if (config('login_attempts.password_reset_days_enable', true)) {
            $for_all = $this->option('all');
            $query = User::where('active', true);
            $users = ($for_all ?? false) ? $query->get() : $query->whereNotNull('password_updated_date')->get();
            foreach ($users as $user) {
                $password_updated_date = $user->password_updated_date;
                $password_expiry_date = Carbon::parse($password_updated_date)->addDays(config('login_attempts.password_reset_days', 60));
                $password_expiry_reminder_date = $password_expiry_date->subDays(config('login_attempts.password_expiry_reminder_days', 5)); // Five Days before
                $remaining_days_before_expiry = $password_expiry_date->diffInDays(now());
                if ($password_expiry_date < now()) {
                    $this->info('User ' . $user->username . ' password is expired.');
                } else if ($password_expiry_reminder_date->isToday()) {
                    $email = $user->getOtpEmail();
                    $name = $user->getOtpName();
                }
                if (!is_null($name) && !is_null($email)) {

                    $receiver =
                        (object) [
                            'email' => $email,
                            'name' => $name,
                        ];
                    Mail::to($receiver)->send(new PasswordExpiryReminderMail($user, $password_expiry_date, $password_expiry_reminder_date, $remaining_days_before_expiry));
                }
            }
        } else {
            $this->info('Password expiry checking is disabled.');
        }
    }
}
