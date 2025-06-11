<?php

namespace App\Modules\Employee\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Modules\User\Entities\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Modules\Employee\Entities\Employee;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Modules\Employee\Notifications\ProbationPeriodNotification;

class NotifyThreeMonthPeriods extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'employee:notify-three-month-periods';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

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
        $employees = Employee::where('status', '1')->with('payrollRelatedDetailModel')
            ->whereHas('payrollRelatedDetailModel', function ($query) {
                $query->whereNotNull('probation_start_date');
            })
            ->get();
        // dd($employees);

        try {
            foreach ($employees as $employee) {
                $start = Carbon::parse($employee->payrollRelatedDetailModel->probation_start_date);
                $months = $start->diffInMonths(Carbon::now());

                if (in_array($months, [3, 5])) {
                    $hrs = User::where('user_type', 'hr')->pluck('email')->toArray();
                    foreach ($hrs as $hrEmail) {
                        Notification::route('mail', $hrEmail)
                            ->notify(new ProbationPeriodNotification($employee, $months));
                    }

                    $first = optional($employee->employeeApprovalFlowRelatedDetailModel)->first_approval_user_id;
                    $last = optional($employee->employeeApprovalFlowRelatedDetailModel)->last_approval_user_id;

                    if ($first) {
                        $email = User::getUserEmail($first);
                        if ($email) {
                            Notification::route('mail', $email)
                                ->notify(new ProbationPeriodNotification($employee, $months));
                        }
                    }

                    if ($last) {
                        $email = User::getUserEmail($last);
                        if ($email) {
                            Notification::route('mail', $email)
                                ->notify(new ProbationPeriodNotification($employee, $months));
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            Log::error('Error sending probation emails: ' . $th->getMessage());
        }

        $this->info('Notifications for 3/5-month probation sent.');
    }
    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}