<?php

namespace App\Modules\Payroll\Console;

use App\Modules\Employee\Repositories\EmployeeRepository;
use App\Modules\Payroll\Repositories\EmployeeSetupRepository;
use App\Modules\Payroll\Repositories\IncomeSetupRepository;
use App\Modules\Payroll\Repositories\MassIncrementRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MassIncrement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'mass:increment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mass Increment';

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
        // $now = Carbon::now()->toDateString();
        // $resigned_users = OffboardResignation::where('status',5)->whereDate('last_working_date', $now)->get();

        // $bar = $this->output->createProgressBar(count($resigned_users));
        // $bar->start();

        // foreach ($resigned_users as $key => $resigned_user) {
        //     $emp = $resigned_user->employeeModel;
        //     $data['archived_date'] = $now;
        //     (new EmployeeRepository())->update($emp->id, $data);
        //     (new EmployeeRepository())->updateStatus($emp->id);
        //     $bar->advance();
        // }
        // // $this->newLine();
        // $this->info('Employee Terminated Succesfully!');
        // $bar->finish();
        // try {
            $massIncrementModel = (new MassIncrementRepository())->getTodayMassIncrement();
            $bar = $this->output->createProgressBar(count($massIncrementModel));
            $bar->start();
            foreach ($massIncrementModel as $key => $massIncrement) {
                $employee_id = $massIncrement->emp_id;
                (new EmployeeSetupRepository())->updateGrosssalary($employee_id, ['gross_salary' => $massIncrement->new_income]);
                $employeeModel = (new EmployeeRepository())->find($employee_id);
                $grossSalary = $employeeModel->employeeGrossSalarySetup;
                $grossSalary = $grossSalary->gross_salary;
                $basics = 0;
                $data['income'] = $income = (new IncomeSetupRepository())->findAll(null, ['organizationId' => $massIncrement->organization_id]);
                foreach ($income as $key => $value) {
                    $filter = [
                        'reference' => 'income',
                        'reference_id' => $value->id,
                        'employee_id' => $employee_id
                    ];
                    $employeeIncome = (new EmployeeSetupRepository())->findOne($filter);
                    if ($employeeIncome) {
                        if ($value->method == 2) {
                            if ($value->salary_type == 2) {

                                if ($value->short_name == 'BS') {
                                    $per = $value->percentage;
                                    $basic = ($per / 100) * $grossSalary;
                                    $basics = $basic;
                                    $amount = ($per / 100) * $grossSalary;
                                } else {
                                    $per = $value->percentage;
                                    $amount = ($per / 100) * $grossSalary;
                                }
                            } else {
                                $per = $value->percentage;
                                $amount = ($per / 100) * $basics;
                            }
                        } else {
                            $amount = $employeeIncome->amount;
                        }
                        $inputArray = [
                            'employee_id' =>  $employee_id,
                            'organization_id' => $massIncrement->organization_id,
                            'reference' => 'income',
                            'reference_id' => $value->id,
                            'amount' => $amount,
                            'status' => 11
                        ];
                    }

                    $employeeIncome->update($inputArray);
                    $bar->advance();
                }
            }
            // $this->newLine();
            $this->info('Gross Salary and Employee Setup Successfully');
            $bar->finish();
        //     toastr()->success('Gross Salary and Employee Setup Successfully');
        // } catch (\Throwable $e) {
        //     toastr()->error('Something Went Wrong !!!');
        // }
        // return redirect(route('massIncrement.index'));
    }
}
