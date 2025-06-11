<?php

namespace App\Modules\Attendance\Console;

use App\Modules\Attendance\Entities\Attendance;
use App\Modules\Attendance\Entities\AttendanceRequest;
use App\Modules\Attendance\Repositories\AttendanceReportRepository;
use App\Modules\Notification\Entities\Notification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class NotifyAtdRequestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'notify:atdRequest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'User notification about attendance requests';

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
        $attendances = Attendance::where('date', Carbon::now()->toDateString())->get();

        $bar = $this->output->createProgressBar(count($attendances));
        $bar->start();
        foreach ($attendances as $key => $atd) {
            $employeeModel = $atd->employee;
            $shift = (new AttendanceReportRepository())->getShift($employeeModel, $atd);

            if (!empty($shift['checkInShift'])) {
                $this->notification($atd, $shift['checkInShift']);
            }

            if (!empty($shift['checkOutShift'])) {
                $this->notification($atd, $shift['checkOutShift']);
            }
            $bar->advance();
        }
        $bar->finish();
    }

    public function notification($atd, $shift)
    {
        $employeeModel = $atd->employee;

        $notificationData['creator_user_id'] = 1;
        $notificationData['notified_user_id'] = optional($employeeModel->getUser)->id;
        $notificationData['message'] = "You have been " . $shift;
        $notificationData['link'] = route('monthlyAttendance');
        $notificationData['type'] = 'Attendance';
        $notificationData['type_id_value'] = $atd->id;
        Notification::create($notificationData);
    }
}
