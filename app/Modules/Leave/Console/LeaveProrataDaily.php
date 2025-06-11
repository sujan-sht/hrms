<?php

namespace App\Modules\Leave\Console;
use App\Modules\Leave\Entities\LeaveType;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class LeaveProrataDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prorata:leave';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update prorata leave based on advance allocation';

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
    public function handle(){
        try {
            if (!getCurrentLeaveYearId()) {
                $this->error('Leave Year Not Found!');
                return false;
            }

            $calendarType = leaveYearSetup('calendar_type');
            $currentDate = Carbon::now();
            $nepaliDate = $calendarType == 'BS' 
                ? date_converter()->eng_to_nep($currentDate->year, $currentDate->month, $currentDate->day) 
                : null;

            $isFirstDay = $this->isFirstDayOfMonth($calendarType, $nepaliDate);
            $isLastDay = $this->isLastDayOfMonth($calendarType, $nepaliDate);
            $getCurrentMonthDays = $calendarType == 'AD' 
                ? $currentDate->daysInMonth 
                : date_converter()->getTotalDaysInMonth($nepaliDate['year'], $nepaliDate['month']);

            // Advance leave allocation logic
            if ($isFirstDay) {
                $this->allocateAdvanceLeaves();
            }

            // Daily prorata adjustment
            $this->adjustDailyProrata($getCurrentMonthDays, $isLastDay);

            $this->info('Prorata adjustment completed successfully.');
        } catch (\Throwable $th) {
            Log::error('Error in LeaveProrataDaily: ' . $th->getMessage());
            $this->error('An error occurred. Check logs for details.');
        }
    }

    private function allocateAdvanceLeaves(){
        $advanceAllocationLeaveTypes = LeaveType::with(['employeeLeave' => function ($query) {
            $query->where('is_valid', 11);
        }])->where([
            'status' => 11,
            'prorata_status' => 11,
            'leave_year_id' => getCurrentLeaveYearId(),
            'advance_allocation' => 11,
        ])->get();

        if($advanceAllocationLeaveTypes->count() > 0){
            foreach ($advanceAllocationLeaveTypes as $leaveType) {
                foreach ($leaveType->employeeLeave as $employeeLeave) {
                    $increment = $leaveType->number_of_days / 12;
                    $employeeLeave->prorata_earned += $increment;
    
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

    private function adjustDailyProrata($getCurrentMonthDays, $isLastDay){
        $leaveTypes = LeaveType::with(['employeeLeave' => function ($query) {
            $query->where('is_valid', 11);
        }])->where([
            'status' => 11,
            'prorata_status' => 11,
            'leave_year_id' => getCurrentLeaveYearId(),
            'advance_allocation' => 10,
        ])->get();
        if($leaveTypes->count() > 0){
            foreach ($leaveTypes as $leaveType) {
                foreach ($leaveType->employeeLeave as $employeeLeave) {
                    $noOfDaysPerMonth = $leaveType->number_of_days / 12;
                    $dailyIncrement = round($noOfDaysPerMonth / $getCurrentMonthDays, 2);
    
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
}
