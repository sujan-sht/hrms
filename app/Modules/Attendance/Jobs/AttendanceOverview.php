<?php

namespace App\Modules\Attendance\Jobs;

use App\Exports\MonthlyAttendanceReport;
use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Attendance\Repositories\AttendanceReportRepository;
use App\Modules\Notification\Entities\Notification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceOverview implements ShouldQueue
{
    use Dispatchable, Queueable;
    protected $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $request = $filter = collect($this->data);
            $data['show'] = false;
            $calendar_type = $request['calendar_type'];
            $dateConverter = new DateConverter();
            $data['field'] = 'nepali_date';

            if (isset($calendar_type)) {
                $year = $calendar_type == 'eng' ? $request['eng_year'] : $request['nep_year'];
                $month = $calendar_type == 'eng' ? $request['eng_month'] : $request['nep_month'];
                $data['show'] = true;
                $data['field'] = $calendar_type == 'eng' ? 'date' : 'nepali_date';
                $data['year'] = $year;
                $data['month'] = $month;
                $data['calendarType'] = $calendar_type;

                if ($calendar_type == 'nep') {
                    $data['days'] = $dateConverter->getTotalDaysInMonth($year, $month);
                } else {
                    $data['days'] = Carbon::parse($year . '-' . $month)->daysInMonth;
                }

                //Update no. of days of current month
                if ($data['calendarType'] == 'eng' && $data['year'] == date('Y') && $data['month'] == date('n')) {
                    $data['days'] = date('d');
                } elseif ($data['calendarType'] == 'nep') {
                    $nepDateArray = date_converter()->eng_to_nep(date('Y'), date('m'), date('d'));
                    if ($data['year'] == $nepDateArray['year'] && $data['month'] == $nepDateArray['month']) {
                        $data['days'] = $nepDateArray['date'];
                    }
                }
                //
                $checkDate = [
                    'calendarType' => $calendar_type,
                    'year' => $year,    
                    'month' => $month,
                ];
                $getDate = $this->restrictFutureDate($checkDate);

                if ($getDate) {
                    $data['emps'] = (new AttendanceReportRepository())->employeeAttendance($data, $filter, '', $type = 'export');
                } else {
                    $data['emps'] = [];
                }

                Excel::store(new MonthlyAttendanceReport($data), 'public/exports/attendance-overview-report.xlsx');

                $link = asset('storage/exports/attendance-overview-report.xlsx');
                $notificationData['creator_user_id'] = $filter['authUser']['id'];
                $notificationData['notified_user_id'] = $filter['authUser']['id'];
                $notificationData['message'] = 'Your attendance overview report is ready.Please, <a href="'.$link.'">click here to download</a>';
                $notificationData['link'] = $link;
                $notificationData['type'] = 'Attendance Overview Report';
                $notificationData['type_id_value'] = 1;
                Notification::create($notificationData);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function restrictFutureDate($checkDate)
    {
        if ($checkDate['calendarType'] == 'nep') {
            $nepDateArray = date_converter()->eng_to_nep(date('Y'), date('m'), date('d'));
            if ($checkDate['year'] > $nepDateArray['year']) {
                toastr()->error('Invalid Year');
                return false;
            } elseif ($checkDate['year'] == $nepDateArray['year'] && $checkDate['month'] > $nepDateArray['month']) {
                toastr()->error('Invalid Month');
                return false;
            }
        } elseif ($checkDate['calendarType'] == 'eng') {
            if ($checkDate['year'] > date('Y')) {
                toastr()->error('Invalid Year');
                return false;
            } elseif ($checkDate['year'] == date('Y') && $checkDate['month'] > date('n')) {
                toastr()->error('Invalid Month');
                return false;
            }
        }
        return true;
    }
}
