<?php

namespace App\Modules\Leave\Console;


use App\Modules\Employee\Entities\EmployeeLeave;
use App\Modules\Employee\Entities\EmployeeSubstituteLeave;
use App\Modules\Employee\Repositories\EmployeeSubstituteLeaveRepository;
use App\Modules\Leave\Entities\Leave;
use App\Modules\Leave\Entities\LeaveType;
use App\Modules\Leave\Repositories\LeaveTypeRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RefundUnusedSubstituteLeaveCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refund:substituteLeave';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refund Unused Substitute Leave Cron';

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
        try {
            if (!getCurrentLeaveYearId()) {
                $this->error('Leave Year Not Found!');
                return false;
            }
            
            $substituteLeaves = EmployeeSubstituteLeave::where('status', '3')->where('is_expired', '10')->get();
            $bar = $this->output->createProgressBar(count($substituteLeaves));
            $bar->start();

            foreach ($substituteLeaves as  $substituteLeave) {
                $claimedDate = $substituteLeave->date;
                $leaveType = (new LeaveTypeRepository())->findOne($substituteLeave->leave_type_id);

                if(isset($leaveType->max_substitute_days) && $leaveType->max_substitute_days > 0){
                    $dateWithLimit = Carbon::parse($claimedDate)->addDays($leaveType->max_substitute_days)->format('Y-m-d');
                    $leave = Leave::whereHas('leaveTypeModel', function($query){
                        $query->where('leave_year_id', getCurrentLeaveYearId())->where('code', 'SUBLV')->where('status', '11');
                    })->where('employee_id', $substituteLeave->employee_id)->where('substitute_date', $claimedDate)->where('status', '!=', 4)->exists();
    
                    if(!$leave && ($dateWithLimit <= date('Y-m-d'))){
                        $inputData['employee_id'] = $substituteLeave->employee_id;
                        $inputData['leave_type_id'] = $leaveType->id;
                        $inputData['numberOfDays'] = 1;
                        $updateRemainingLeave = EmployeeLeave::updateRemainingLeave($inputData, 'SUB');
                        if($updateRemainingLeave){
                            $data['is_expired'] = '11';
                            (new EmployeeSubstituteLeaveRepository())->update($substituteLeave->id, $data);
                        }
                    }
                }
                $bar->advance();
            }
            $this->info('Employee Unused Substitute Leave Refunded Succesfully!');
            $bar->finish();

        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }
}
