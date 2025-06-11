<?php

namespace App\Modules\Leave\Console;


use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Modules\Leave\Entities\LeaveType;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\EmployeeLeave;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Modules\Leave\Entities\EmployeeLeaveOpening;
use App\Modules\LeaveYearSetup\Entities\LeaveYearSetup;

class ProrataLeaveDailyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prorata:daily {increment=0.01}';
    // protected $signature = 'prorata:daily';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Prorata Leave Cron';

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
        // try {
        //     if (!getCurrentLeaveYearId()) {
        //         $this->error('Leave Year Not Found!');
        //         return false;
        //     }

        //     $leaveTypes = LeaveType::with(['employeeLeave' => function ($query) {
        //         $query->where('is_valid', 11);
        //     }])->where([
        //         'status' => 11,
        //         'prorata_status' => 11,
        //         'leave_year_id' => getCurrentLeaveYearId()
        //     ])->get();

        //     $increment = $this->arguments()['increment'];
        //     if ($leaveTypes->count() > 0) {
        //         $getCurrentMonthDays = Carbon::now()->daysInMonth;
        //         foreach ($leaveTypes as $key => $leaveType) {
        //             if ($leaveType->employeeLeave) {
        //                 $bar = $this->output->createProgressBar(count($leaveType->employeeLeave));
        //                 $bar->start();
        //                 foreach ($leaveType->employeeLeave as $key => $employeeLeave) {
        //                     $employeeLeave->prorata_earned += $increment;
        //                     $explodeDecimal = explode(".", $employeeLeave->prorata_earned);
        //                     if (isset($explodeDecimal[1]) && $explodeDecimal[1] >= $getCurrentMonthDays) {

        //                         $prorataEarned = $leaveType->number_of_days / 12;
        //                         $remainingDays = $explodeDecimal[1] - $getCurrentMonthDays;
        //                         $employeeLeave->leave_remaining +=  $prorataEarned;
        //                         $employeeLeave->prorata_earned =  ($remainingDays / 100);
        //                     }

        //                     $employeeLeave->save();
        //                     $bar->advance();
        //                 }
        //                 $bar->finish();
        //             }
        //         }
        //         $this->info('Success');
        //     }
        // } catch (\Throwable $th) {
        //     echo $th->getMessage();
        // }
        try {
            if (!getCurrentLeaveYearId()) {
                $this->error('Leave Year Not Found!');
                return false;
            }


            $currentDate = Carbon::now();
            $nepaliDate = date_converter()->eng_to_nep($currentDate->year, $currentDate->month, $currentDate->day);
            $currentNepDate = date_converter()->eng_to_nep_convert($currentDate);
            $getCurrentMonthDays = date_converter()->getTotalDaysInMonth($nepaliDate['year'], $nepaliDate['month']);
            $isLastDay = false;
            if ($getCurrentMonthDays == $nepaliDate['day']) {
                $isLastDay = true;
            }

            $leaveTypes = LeaveType::with(['employeeLeaveLatest' => function ($query) {
                $query->where('is_valid', 11);
            }])->where([
                'status' => 11,
                'prorata_status' => 11,
                'advance_allocation' => 10,
                'leave_year_id' => getCurrentLeaveYearId()
            ])->get();
            $currentDateEng = date_converter()->nep_to_eng_convert($currentNepDate);
            if ($leaveTypes->count() > 0) {
                foreach ($leaveTypes as $leaveType) {
                    foreach ($leaveType->employeeLeaveLatest as $employeeLeave) {
                        $employee = Employee::where('employee_id', $employeeLeave['employee_id'])->first();
                        $employeeRetirementAge = $employee->retirement_age ?? 0;
                        $updateStatus = true;
                        $archivedDate = $employee->nep_archived_date;
                        if ($archivedDate) {
                            if (strtotime($currentNepDate) > strtotime($archivedDate)) {
                                $updateStatus = false;
                            }
                        }
                        $fetchJobType = $employee->appendPayrollRetatedDetailAttributes($employee);
                        if ($fetchJobType->job_type != null && $fetchJobType->job_type == 12) {
                            $contractStartDate = $fetchJobType->contract_start_date;
                            $contractEndDate = $fetchJobType->contract_end_date;

                            if ($currentDateEng < $contractStartDate || $currentDateEng > $contractEndDate) {
                                $updateStatus = false;
                            }
                        }


                        if ($employeeRetirementAge && $employeeRetirementAge > 0) {
                            $employeeDobNep = explode('-', $employee->nep_dob);
                            $returementDate = date_converter()->nep_to_eng_convert(($employeeDobNep[0] + $employeeRetirementAge) . '-' . $employeeDobNep[1] . '-' . $employeeDobNep[2]);
                            if (strtotime($currentDateEng) > strtotime($returementDate)) {
                                $updateStatus = false;
                            }
                        }

                        if ($updateStatus) {
                            $noOfDaysPerMonth = $leaveType->number_of_days / 12;
                            $dailyIncrement = round($noOfDaysPerMonth / $getCurrentMonthDays, 4);
                            if ($isLastDay) {
                                $dailyIncrement = round($noOfDaysPerMonth - $employeeLeave->prorata_earned, 4);
                            }
                            $employeeLeave->prorata_earned += $dailyIncrement;
                            $integerPart = floor($employeeLeave->prorata_earned);
                            if ($integerPart > 0) {
                                $employeeLeave->leave_remaining += $integerPart;
                                $employeeLeave->prorata_earned -= $integerPart;
                            }
                            $employeeLeave->save();
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }
}
