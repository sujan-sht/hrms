<?php

namespace App\Modules\Attendance\Console;

use Illuminate\Console\Command;
use App\Modules\Attendance\Services\AttendanceService;

class RunAttendanceCommand extends Command
{
    protected $attendance;
    protected $attendanceLog;
    protected $employee;
    protected $organization;
    protected $employeeShift;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to run attendance';

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
        (new AttendanceService)->runAttendance();
    }
}
