<?php

namespace App\Modules\Offboarding\Console;

use App\Modules\Employee\Repositories\EmployeeRepository;
use App\Modules\Offboarding\Entities\OffboardResignation;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class TerminateResignedUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'terminate:resigned-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Terminated Resigned User';

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
        $now = Carbon::now()->toDateString();
        $resigned_users = OffboardResignation::where('status',5)->whereDate('last_working_date', $now)->get();

        $bar = $this->output->createProgressBar(count($resigned_users));
        $bar->start();

        foreach ($resigned_users as $key => $resigned_user) {
            $emp = $resigned_user->employeeModel;
            $data['archived_date'] = $now;
            (new EmployeeRepository())->update($emp->id, $data);
            (new EmployeeRepository())->updateStatus($emp->id);
            $bar->advance();
        }
        // $this->newLine();
        $this->info('Employee Terminated Succesfully!');
        $bar->finish();
    }
}
