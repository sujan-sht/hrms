<?php

namespace App\Modules\Attendance\Console;

use App\Helpers\DateTimeHelper;
use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Attendance\Entities\Attendance;
use App\Modules\Attendance\Repositories\AttendanceLogRepository;
use App\Modules\Attendance\Repositories\AttendanceRepository;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Repositories\EmployeeRepository;
use App\Modules\NewShift\Entities\NewShiftEmployee;
use App\Modules\Organization\Repositories\OrganizationRepository;
use App\Modules\Shift\Repositories\EmployeeShiftRepository;
use App\Modules\Shift\Repositories\ShiftGroupRepository;
use App\Modules\Shift\Repositories\ShiftRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class RunAtdTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'attendance:cron';

    protected $attendance;
    protected $attendanceLog;
    protected $employee;
    protected $organization;
    protected $employeeShift;

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
        $this->attendance = new AttendanceRepository;
        $this->attendanceLog = new AttendanceLogRepository;
        $this->employee = new EmployeeRepository;
        $this->organization = new OrganizationRepository;
        $this->employeeShift = new EmployeeShiftRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // $arguments = $this->arguments();

        $agoDate = $this->ask('Enter Start Date');
        $todayDate = $this->ask('Enter End Date');

        // $start_date = '2023-05-06';
        // $end_date = '2023-05-09';

        // $diffDate = DateTimeHelper::DateDiffInDay($start_date,$end_date);
        // dd($diffDate);


        $employees = Employee::where('status', '1')->whereNotNull('biometric_id');


        $employees->chunk(200, function ($employees) use ($agoDate, $todayDate) {
            $count = 0;
            $punchArray = ['web', 'app'];

            $bar = $this->output->createProgressBar($employees->count());
            $bar->start();
            if ($employees->count() > 0) {
                $text = '';
                $count = 0;
                foreach ($employees as $employee) {
                    if (isset($employee->biometric_id)) {
                        for ($i = strtotime($agoDate); $i <= strtotime($todayDate); $i += 86400) {
                            $currentDate = date('Y-m-d', $i);
                            $weeekDay = date('D', $i);
                            $year = date('Y', $i);
                            $month = date('m', $i);
                            $day = date('d', $i);
    
                            // get current nepali date
                            $dateConverter = new DateConverter();
                            $dc = $dateConverter->eng_to_nep($year, $month, $day);
                            $currentNepaliDate = $dc['year'] . '-' . sprintf("%02d", $dc['month']) . '-' . sprintf("%02d", $dc['date']);
    
                            // check employee shift
                            $shiftCheckPoint = '14:00';
                            $empShiftInfo = $this->employeeShift->findOne(['employee_id' => $employee->id, 'days' => $weeekDay]);
                            $shiftInfo = optional($empShiftInfo->getShift);
                            
                            $newShiftEmp = NewShiftEmployee::getShiftEmployee($employee->id, $currentDate);
                            if (isset($newShiftEmp)) {
                                $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
                                if (isset($rosterShift) && isset($rosterShift->shift_group_id)) {
                                    $shiftInfo = optional((new ShiftGroupRepository())->find($rosterShift->shift_group_id)->shift);
                                }
                            }
                            if (!empty($shiftInfo)) {
                                $shiftSeason = $shiftInfo->getShiftSeasonForDate($currentDate);
                                $seasonalShiftId = null;
                                if($shiftSeason){
                                    $seasonalShiftId = $shiftSeason->id;
                                }
                                $shiftCheckPoint = optional($shiftInfo->getShiftDayWise($day, $seasonalShiftId))->getCheckpoint();
                            }
    
                            // get first time from daily attendance log
                            $filterParams = ['date' => $currentDate, 'biometric_emp_id' => $employee->biometric_id];
                            $checkableTime1 = $this->attendanceLog->findMinTime($filterParams);
                            $checkableTime2 = $this->attendanceLog->findMaxTime($filterParams);
                            if (!$checkableTime1 || !$checkableTime2) {
                                continue;
                            }
    
                            if (in_array($checkableTime1->punch_from, $punchArray) || in_array($checkableTime2->punch_from, $punchArray)) {
                                $empLeave = $employee->leave->where('date', $currentDate)->where('status', 3)->first();
                                if ($empLeave && $empLeave->exists()) {
                                    continue;
                                }
                            }
    
                            if (strtotime($checkableTime1->min_time) < strtotime($shiftCheckPoint)) {
                                $attendanceState1 = 'checkin';
                            } else {
                                $attendanceState1 = 'checkout';
                            }
    
                            if (strtotime($checkableTime2->max_time) < strtotime($shiftCheckPoint)) {
                                $attendanceState2 = 'checkin';
                            } else {
                                $attendanceState2 = 'checkout';
                            }
    
                            $dataParams = array(
                                'emp_id' => $employee->id,
                                'org_id' => $employee->organization_id,
                                'date' => $currentDate,
                            );
                            $attendanceModel = $this->attendance->findOne($dataParams);
    
                            if (!$attendanceModel) {
                                $attendanceModel = new Attendance();
                                $attendanceModel->emp_id = $employee->id;
                                $attendanceModel->org_id = $employee->organization_id;
                                $attendanceModel->date = $currentDate;
                                $attendanceModel->nepali_date = $currentNepaliDate;
                            }
    
                            $checkin_coordinates = $checkout_coordinates = null;
                            if ($checkableTime1->lat && $checkableTime1->long) {
                                $checkin_coordinates = ['lat' => $checkableTime1->lat, 'long' => $checkableTime1->long];
                            }
    
                            if ($checkableTime2->lat && $checkableTime2->long) {
                                $checkout_coordinates = ['lat' => $checkableTime2->lat, 'long' => $checkableTime2->long];
                            }
    
                            if ($attendanceState1 == 'checkin' && $attendanceState2 == 'checkin') {
                                // if (is_null($attendanceModel->checkin)) {
                                    if ($attendanceModel->checkin_from != 'request') {
                                        $attendanceModel->checkin_from = $checkableTime1->punch_from;
                                        $attendanceModel->checkin = $checkableTime1->min_time;
                                    }
    
                                // }
    
                                $attendanceModel->checkin_original = $checkableTime1->min_time;
                                $attendanceModel->checkin_coordinates = $checkin_coordinates;
    
                                // $attendanceModel->checkout = null;
                                // $attendanceModel->checkout_original = null;
                                // $attendanceModel->checkout_from = null;
                                // $attendanceModel->checkout_coordinates = null;
                            } elseif ($attendanceState1 == 'checkout' && $attendanceState2 == 'checkout') {
                                // if (is_null($attendanceModel->checkout)) {
                                    if ($attendanceModel->checkout_from != 'request') {
                                        $attendanceModel->checkout = $checkableTime2->max_time;
                                        $attendanceModel->checkout_from = $checkableTime2->punch_from;
                                    }
                                // }
                                $attendanceModel->checkout_original = $checkableTime2->max_time;
                                $attendanceModel->checkout_coordinates = $checkout_coordinates;
    
                                // $attendanceModel->checkin = null;
                                // $attendanceModel->checkin_original = null;
                                // $attendanceModel->checkin_from = null;
                                // $attendanceModel->checkin_coordinates = null;
                            } else {
                                // if (is_null($attendanceModel->checkin)) {
                                    if ($attendanceModel->checkin_from != 'request') {
                                        $attendanceModel->checkin = $checkableTime1->min_time;
                                        $attendanceModel->checkin_from = $checkableTime1->punch_from;
                                    }
                                // }
                                $attendanceModel->checkin_original = $checkableTime1->min_time;
                                $attendanceModel->checkin_coordinates = $checkin_coordinates;
    
                                // if (is_null($attendanceModel->checkout)) {
                                    if ($attendanceModel->checkout_from != 'request') {
                                        $attendanceModel->checkout = $checkableTime2->max_time;
                                        $attendanceModel->checkout_from = $checkableTime2->punch_from;
                                    }
                                // }
                                $attendanceModel->checkout_original = $checkableTime2->max_time;
                                $attendanceModel->checkout_coordinates = $checkout_coordinates;
                            }
    
                            if ($attendanceModel->checkin && $attendanceModel->checkout) {
                                $attendanceModel->total_working_hr = DateTimeHelper::getTimeDiff($attendanceModel->checkin, $attendanceModel->checkout);
                            }
    
                            $attendanceModel->save();
                            $count++;
    
                            // $leave = $this->employee->getLeaveFromSubsituteDate($employee->id, $attendanceModel->date);
                            // if ($leave) {
                            //     $this->employee->employeeLeaveIncrement($leave);
                            // }
                        }
                    }
                    $text .= $employee->biometric_id . ' - ';
                }
                $text .= 'count=' . $count;
                Storage::put('attentance.txt', $text);

                $bar->advance();
            }
            $bar->finish();
        });
    }
}
