<?php

namespace App\Modules\Attendance\Http\Controllers;

use PDF;
use Carbon\Carbon;
use App\Traits\LogTrait;
use Illuminate\Http\Request;
use App\Exports\AttendanceSummary;
use Illuminate\Routing\Controller;
use App\Exports\RawAttendanceReport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use App\Exports\DailyAttendanceReport;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use App\Exports\MonthlyAttendanceReport;
use App\Exports\RegularAttendanceReport;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Admin\Entities\DateConverter;
use App\Exports\AttendanceSummaryAttendanceLock;
use App\Modules\Attendance\Jobs\AttendanceOverview;
use App\Modules\Attendance\Repositories\AttendanceInterface;
use App\Modules\Attendance\Entities\AttendanceOrganizationLock;
use App\Modules\Attendance\Repositories\AttendanceReportInterface;

class AttendanceReportExportController extends Controller
{
    use LogTrait;
    protected $attendance;
    protected $attendanceReport;
    protected $employees;
    protected $organization;

    public function __construct(AttendanceReportInterface $attendanceReport, AttendanceInterface $attendance)
    {
        $this->attendanceReport = $attendanceReport;
        $this->attendance = $attendance;

    }

    public function exportMonthlyAttendance(Request $request)
    {
        
        $filter = $request->all();

        $data['show'] = false;
        $calendar_type = $request->calendar_type;
        $dateConverter = new DateConverter();
        $data['field'] = 'nepali_date';

        if (isset($calendar_type)) {
            $year = $calendar_type == 'eng' ? $request->eng_year : $request->nep_year;
            $month = $calendar_type == 'eng' ? $request->eng_month : $request->nep_month;
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
                $data['emps'] = $this->attendanceReport->employeeAttendance($data, $filter, '', $type='export');
            } else {
                $data['emps'] = [];
            }
            $logData=[
                'title'=>'Attendance overview exported',
                'action_id'=>null,
                'action_model'=>null,
                'route'=>route('monthlyAttendance',$request->all())
            ];
            $this->setActivityLog($logData);
            return Excel::download(new MonthlyAttendanceReport($data), 'attendance-overview-report.xlsx');
        }
        toastr('Please Filter first to download Excel Report', 'warning');
        return back();
    }

    public function exportRawAttendance(Request $request)
    {
        
        $filter = $request->all();

        $data['attendances'] = $this->attendance->findAll(null,$filter);
        $logData=[
            'title'=>'Raw attendance exported',
            'action_id'=>null,
            'action_model'=>null,
            'route'=>route('rawAttendance',$request->all())
        ];
        $this->setActivityLog($logData);
        return Excel::download(new RawAttendanceReport($data), 'raw-attendance-report.xlsx');
        toastr('Please Filter first to download Excel Report', 'warning');
        return back();
    }

    // public function downloadMonthlyAttendance(Request $request)
    // {
        
    //     $filter = $request->all();

    //     $data['show'] = false;
    //     $calendar_type = $request->calendar_type;
    //     $dateConverter = new DateConverter();
    //     $data['field'] = 'nepali_date';

    //     if (isset($calendar_type)) {
    //         $year = $calendar_type == 'eng' ? $request->eng_year : $request->nep_year;
    //         $month = $calendar_type == 'eng' ? $request->eng_month : $request->nep_month;
    //         $data['show'] = true;
    //         $data['field'] = $calendar_type == 'eng' ? 'date' : 'nepali_date';
    //         $data['year'] = $year;
    //         $data['month'] = $month;
    //         $data['calendarType'] = $calendar_type;

    //         if ($calendar_type == 'nep') {
    //             $data['days'] = $dateConverter->getTotalDaysInMonth($year, $month);
    //         } else {
    //             $data['days'] = Carbon::parse($year . '-' . $month)->daysInMonth;
    //         }

    //         //Update no. of days of current month
    //         if ($data['calendarType'] == 'eng' && $data['year'] == date('Y') && $data['month'] == date('n')) {
    //             $data['days'] = date('d');
    //         } elseif ($data['calendarType'] == 'nep') {
    //             $nepDateArray = date_converter()->eng_to_nep(date('Y'), date('m'), date('d'));
    //             if ($data['year'] == $nepDateArray['year'] && $data['month'] == $nepDateArray['month']) {
    //                 $data['days'] = $nepDateArray['date'];
    //             }
    //         }
    //         //

    //         $checkDate = [
    //             'calendarType' => $calendar_type,
    //             'year' => $year,
    //             'month' => $month,
    //         ];
    //         $getDate = $this->restrictFutureDate($checkDate);
    //         if ($getDate) {
    //             $data['emps'] = $this->attendanceReport->employeeAttendance($data, $filter, '', $type='export');
    //         } else {
    //             $data['emps'] = [];
    //         }
    //         $pdf = PDF::loadView('exports.monthly-attendance-report', $data)->setPaper('a4','landscape');
    //         return $pdf->download('monthly-attendance-report.pdf');
    //     }
    //     toastr('Please Filter first to download Excel Report', 'warning');
    //     return back();
    // }

    public function exportMonthlySummary(Request $request)
    {
        $filter = $request->all();
        $currentEngDate = date('Y-m-d');
        $currentNepDate = date_converter()->eng_to_nep_convert($currentEngDate);
        $emps = [];

        $data['show'] = false;
        $calendar_type = $request->calendar_type;
        $dateConverter = new DateConverter();
        $data['field'] = 'date';

        $columns = $data['columns'] = [
            'total_days' => "Total Days",
            'working_days' => 'Total Working Days', 'dayoffs' => 'Week Off', 'public_holiday' => 'Public Holidays', 'working_hour' => 'Total Working Hours', 'worked_days' => 'Total Worked Days',
            'worked_hour' => 'Total Worked Hours', 'leave_taken' => 'Total Leave Taken', 'paid_leave_taken' => 'Total Paid Leave Taken', 'unpaid_leave_taken' => 'Total Unpaid Leave Taken', 'absent_days' => 'Absent Days','over_stay' => 'Over Stay(hr)',
            'ot_value' => 'Ot(hr)'
        ];

        if (isset($calendar_type)) {
            $year = $calendar_type == 'eng' ? $request->eng_year : $request->nep_year;
            $month = $calendar_type == 'eng' ? $request->eng_month : $request->nep_month;
            $data['show'] = true;
            $data['field'] = $calendar_type == 'eng' ? 'date' : 'nepali_date';
            if ($calendar_type == 'nep') {
                $data['days'] = $dateConverter->getTotalDaysInMonth($year, $month);
                $data['currentDay'] =(int) explode('-' ,$currentNepDate)[2];
            } else {
                $data['days'] = Carbon::parse($year . '-' . $month)->daysInMonth;
                $data['currentDay'] =(int) explode('-' ,$currentEngDate)[2];
            }
            $data['year'] = $year;
            $data['month'] = $month;

            $emps = $this->attendanceReport->monthlyAttendanceSummary($data, $filter);
            $verification=$request->summary ? ' verification' : '';
            $route=$request->summary ? 'monthlyAttendanceSummaryVerification' : 'monthlyAttendanceSummary';
            $logData=[
                'title'=>'Attendance summary'.$verification.' report exported',
                'action_id'=>null,
                'action_model'=>null,
                'route'=>route($route,$request->all())
            ];
            $this->setActivityLog($logData);
            return Excel::download(new AttendanceSummary($emps, $columns, $year, $month), 'monthly-attendance-summary.xlsx');
        }
        toastr('Please Filter first to download Excel Report', 'warning');
        return back();
    }

    public function exportMonthlySummaryAttendanceLock(Request $request)
    {
        $attendanceOrgLock=AttendanceOrganizationLock::where('id',$request->id)->first();
        if(!$attendanceOrgLock){
            toastr('Something Went Wrong !!', 'warning');
            return back();
        }
        $data['columns'] = [
            'total_days' => "Total Days",
            'working_days' => 'Total Working Days', 'dayoffs' => 'Week Off', 'public_holiday' => 'Public Holidays', 'working_hour' => 'Total Working Hours', 'worked_days' => 'Total Worked Days',
            'worked_hour' => 'Total Worked Hours', 'leave_taken' => 'Total Leave Taken', 'paid_leave_taken' => 'Total Paid Leave Taken', 'unpaid_leave_taken' => 'Total Unpaid Leave Taken', 'absent_days' => 'Absent Days','over_stay' => 'Over Stay(hr)',
            'ot_value' => 'Ot(hr)'
        ];

        $year=$attendanceOrgLock->year;
        $month=$attendanceOrgLock->month;
        $data['fetchDatas'] = $attendanceOrgLock->getAttendanceSummaryVerification->map(function ($item) use (&$data) {
            $item->getLockAttribute->groupBy('type')->map(function ($value, $index) use (&$data, $item) {
                if ($index == '1') {
                    $data['columns'][(string)$index] = 'Leave Request';
                    $item->setAttribute('1', $value->sum('item_value'));
                } elseif ($index == '2') {
                    $data['columns'][(string)$index] = 'Attendance Request';
                    $item->setAttribute('2', $value->sum('item_value'));
                }
            });
            return $item;
        });
        $logData=[
            'title'=>'Monthly attendance summary verification report exported',
            'action_id'=>null,
            'action_model'=>null,
            'route'=>route('monthlyAttendanceSummaryVerification',$request->all())
        ];
        $this->setActivityLog($logData);
        return Excel::download(new AttendanceSummaryAttendanceLock($data['fetchDatas'], $data['columns'], $year, $month), 'monthly-attendance-summary-attendance-lock.xlsx');
        
    }

    public function downloadMonthlySummary(Request $request)
    {
        $filter = $request->all();
        $currentEngDate = date('Y-m-d');
        $currentNepDate = date_converter()->eng_to_nep_convert($currentEngDate);
        $data['emps'] = [];

        $data['show'] = false;
        $calendar_type = $request->calendar_type;
        $dateConverter = new DateConverter();
        $data['field'] = 'date';

        $columns = $data['columns'] = [
            'total_days' => "Total Days",
            'working_days' => 'Total Working Days', 'dayoffs' => 'Week Off', 'public_holiday' => 'Public Holidays', 'working_hour' => 'Total Working Hours', 'worked_days' => 'Total Worked Days',
            'worked_hour' => 'Total Worked Hours', 'leave_taken' => 'Total Leave Taken', 'paid_leave_taken' => 'Total Paid Leave Taken', 'unpaid_leave_taken' => 'Total Unpaid Leave Taken', 'absent_days' => 'Absent Days'
        ];

        if (isset($calendar_type)) {
            $year = $calendar_type == 'eng' ? $request->eng_year : $request->nep_year;
            $month = $calendar_type == 'eng' ? $request->eng_month : $request->nep_month;
            $data['show'] = true;
            $data['field'] = $calendar_type == 'eng' ? 'date' : 'nepali_date';
            if ($calendar_type == 'nep') {
                $data['days'] = $dateConverter->getTotalDaysInMonth($year, $month);
                $data['currentDay'] =(int) explode('-' ,$currentNepDate)[2];
            } else {
                $data['days'] = Carbon::parse($year . '-' . $month)->daysInMonth;
                $data['currentDay'] =(int) explode('-' ,$currentEngDate)[2];
            }
            $data['year'] = $year;
            $data['month'] = $month;

            $data['emps'] = $this->attendanceReport->monthlyAttendanceSummary($data, $filter);
            $pdf = PDF::loadView('exports.monthly-attendance-summary', $data)->setPaper('a4','landscape');
            $verification=$request->summary ? ' verification' : '';
            $route=$request->summary ? 'monthlyAttendanceSummaryVerification' : 'monthlyAttendanceSummary';
            $logData=[
                'title'=>'Attendance summary'.$verification.' report downloaded',
                'action_id'=>null,
                'action_model'=>null,
                'route'=>route($route,$request->all())
            ];
            $this->setActivityLog($logData);
            return $pdf->download('monthly-attendance-summary.pdf');
        }
        toastr('Please Filter first to download Excel Report', 'warning');
        return back();
    }

    public function downloadMonthlySummaryAttendanceLock(Request $request)
    {
        $attendanceOrgLock=AttendanceOrganizationLock::where('id',$request->id)->first();
        if(!$attendanceOrgLock){
            toastr('Something Went Wrong !!', 'warning');
            return back();
        }
        $data['columns'] = [
            'total_days' => "Total Days",
            'working_days' => 'Total Working Days', 'dayoffs' => 'Week Off', 'public_holiday' => 'Public Holidays', 'working_hour' => 'Total Working Hours', 'worked_days' => 'Total Worked Days',
            'worked_hour' => 'Total Worked Hours', 'leave_taken' => 'Total Leave Taken', 'paid_leave_taken' => 'Total Paid Leave Taken', 'unpaid_leave_taken' => 'Total Unpaid Leave Taken', 'absent_days' => 'Absent Days'
        ];
        $data['year']=$attendanceOrgLock->year;
        $data['month']=$attendanceOrgLock->month;
        $data['datas'] = $attendanceOrgLock->getAttendanceSummaryVerification->map(function ($item) use (&$data) {
            $item->getLockAttribute->groupBy('type')->map(function ($value, $index) use (&$data, $item) {
                if ($index == '1') {
                    $data['columns'][(string)$index] = 'Leave Request';
                    $item->setAttribute('1', $value->sum('item_value'));
                } elseif ($index == '2') {
                    $data['columns'][(string)$index] = 'Attendance Request';
                    $item->setAttribute('2', $value->sum('item_value'));
                }
            });
            return $item;
        });
        $pdf = PDF::loadView('exports.monthly-attendance-summary-att-lock', $data)->setPaper('a4','landscape');
        $logData=[
            'title'=>'Monthly attendance summary verification report downloaded',
            'action_id'=>null,
            'action_model'=>null,
            'route'=>route('monthlyAttendanceSummaryVerification',$request->all())
        ];
        $this->setActivityLog($logData);
        return $pdf->download('monthly-attendance-summary-att-lock.pdf');
    }

    public function exportDailyAttendanceReport(Request $request)
    {
        $filter = $request->all();
        if (isset($filter['emp_id'])) {
            $filter['emp_id'] = ['empId' => $filter['emp_id']];
        }
        $calendar_type = $request->calendar_type;
        $dateConverter = new DateConverter();
        $data['field'] = 'nepali_date';
        $data['log_type'] = isset($filter['log_type']) ? $filter['log_type'] : 1;
        if (isset($calendar_type)) {
            $year = $calendar_type == 'eng' ? $request->eng_year : $request->nep_year;
            $month = $calendar_type == 'eng' ? $request->eng_month : $request->nep_month;
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
                $data['emps'] = $this->attendanceReport->employeeAttendance($data, $filter, '', $type = null);
            } else {
                $data['emps'] = [];
            }
            $logData=[
                'title'=>'Monthly attendance report exported',
                'action_id'=>null,
                'action_model'=>null,
                'route'=>route('dailyAttendance',$request->all())
            ];
            $this->setActivityLog($logData);
            return Excel::download(new DailyAttendanceReport($data), 'monthly-attendance-report.xlsx');
        }
        toastr('Please Filter first to download Excel Report', 'warning');
        return back();
    }

    public function downloadDailyAttendanceReport(Request $request)
    {
        $filter = $request->all();
        if (isset($filter['emp_id'])) {
            $filter['emp_id'] = ['empId' => $filter['emp_id']];
        }
        $calendar_type = $request->calendar_type;
        $dateConverter = new DateConverter();
        $data['field'] = 'nepali_date';
        $data['log_type'] = isset($filter['log_type']) ? $filter['log_type'] : 1;
        if (isset($calendar_type)) {
            $year = $calendar_type == 'eng' ? $request->eng_year : $request->nep_year;
            $month = $calendar_type == 'eng' ? $request->eng_month : $request->nep_month;
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
                $data['emps'] = $this->attendanceReport->employeeAttendance($data, $filter, '', $type = null);
            } else {
                $data['emps'] = [];
            }
            $pdf = PDF::loadView('exports.daily-attendance-report', $data)->setPaper('a4','landscape');
            $logData=[
                'title'=>'Monthly attendance report downloaded',
                'action_id'=>null,
                'action_model'=>null,
                'route'=>route('dailyAttendance',$request->all())
            ];
            $this->setActivityLog($logData);
            return $pdf->download('monthly-attendance.pdf');
        }
        toastr('Please Filter first to download Excel Report', 'warning');
        return back();
    }

    public function exportRegularAttendance(Request $request)
    {
        $data['filter'] = $filter = $request->all();
        $sort = [
            'by' => 'id',
            'sort' => 'DESC'
        ];
        $data['emps'] = $this->attendanceReport->employeeRegularAttendanceData(Config::get('attendance.export-length'), $filter, $sort);
        $logData=[
            'title'=>'Daily attendance report exported',
            'action_id'=>null,
            'action_model'=>null,
            'route'=>route('regularAttendanceReport',$request->all())
        ];
        $this->setActivityLog($logData);
        return Excel::download(new RegularAttendanceReport($data), 'daily-attendance-report.xlsx');
             
        toastr('Please Filter first to download Excel Report', 'warning');
        return back();
    }

    public function downloadRegularAttendance(Request $request)
    {
        $data['filter'] = $filter = $request->all();
        $sort = [
            'by' => 'id',
            'sort' => 'DESC'
        ];
        $data['emps'] = $this->attendanceReport->employeeRegularAttendanceData(Config::get('attendance.export-length'), $filter, $sort);
      
        $pdf = PDF::loadView('exports.regular-attendance-report', $data)->setPaper('a4','landscape');
        $logData=[
            'title'=>'Daily attendance report downloaded',
            'action_id'=>null,
            'action_model'=>null,
            'route'=>route('regularAttendanceReport',$request->all())
        ];
        $this->setActivityLog($logData);
        return $pdf->download('daily-attendance.pdf');
        toastr('Please Filter first to download Excel Report', 'warning');
        return back();
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
