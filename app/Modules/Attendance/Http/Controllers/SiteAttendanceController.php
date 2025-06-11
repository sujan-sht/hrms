<?php

namespace App\Modules\Attendance\Http\Controllers;

use App\Helpers\DateTimeHelper;
use App\Modules\Attendance\Entities\DivisionAttendanceMonthly;
use App\Modules\Attendance\Entities\DivisionAttendanceReport;
use App\Modules\Attendance\Entities\DivisionAttendanceRoleSetup;
use App\Modules\Attendance\Repositories\AttendanceInterface;
use App\Modules\Attendance\Repositories\AttendanceReportInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Labour\Entities\Labour;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SiteAttendanceController extends Controller
{
    protected $attendance;
    protected $organization;
    protected $attendanceReport;
    protected $employee;



    public function __construct(
        AttendanceInterface $attendance,
        OrganizationInterface $organization,
        AttendanceReportInterface $attendanceReport,
        EmployeeInterface $employee
    ) {
        $this->attendance = $attendance;
        $this->organization = $organization;
        $this->attendanceReport = $attendanceReport;
        $this->employee = $employee;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function roleSetup()
    {
        $data['organizationList'] = $this->organization->findAll();
        return view('attendance::site-attendance.role-setup', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function storeRoleSetup(Request $request)
    {
        try {
            $orgEmployeeLists = $request->except('_token');
            foreach ($orgEmployeeLists as $data) {
                if(isset($data['reviewer_emp_id'])){
                    DivisionAttendanceRoleSetup::updateOrCreateRoleSetup($data);
                }
            }
            toastr('Data has been updated successfully !!!', 'success');
        } catch (Exception $e) {
            toastr('Error While updating data ', 'error');
        }
        return redirect()->back();
    }

    public function viewForm(Request $request){
        $isExists = DivisionAttendanceRoleSetup::where('reviewer_emp_id', optional(auth()->user()->userEmployer)->id)->exists();
        $data['divisionAttendanceReport'] = [];
        $filter = $request->all();
        $data['date'] = $filter['date'] = setting('calendar_type') == 'BS' ? date_converter()->nep_to_eng_convert($filter['date']) : $filter['date'];
        if($isExists){
            $data['divisionAttendanceReport'] = $this->attendanceReport->divisionAttendanceReport(null, $filter);
        }
        return view('attendance::site-attendance.form', $data);
    }

    public function updateForm(Request $request){
        try {
            $data = $request->except('_token');
            $siteData = [];
            foreach ($data['formAttendance'] as $employeeId => $atdData) {
                $siteData = $atdData;
                $siteData['status'] = $data['status'];
                $siteData['date'] = $data['date'];
                $siteData['nepali_date'] = date_converter()->eng_to_nep_convert($data['date']);
                $siteData['employee_id'] = $employeeId;
                if($atdData['is_absent'] == 11){
                    $siteData['checkin'] = $siteData['checkout'] = null;
                    $siteData['worked_hr'] = $siteData['ot_hr'] = 0;
                }

                $atdReport = DivisionAttendanceReport::where('employee_id', $employeeId)->where('date', $data['date'])->first();
                if($atdReport){
                    $atdReport->update($siteData);
                }else{
                    DivisionAttendanceReport::create($siteData);
                }

                //For status final
                if($data['status'] == 2 && $atdData['is_absent'] == 10){
                    $attendanceExist = $this->attendance->employeeAttendanceExists($employeeId, $data['date']);
                    if ($attendanceExist) {
                        $attendanceExist->fill([ 
                            'checkin' => $atdData['checkin'],
                            'checkout' => $atdData['checkout'],
                            'checkin_from' => 'form',
                            'checkout_from' => 'form',
                            'total_working_hr' => $atdData['checkin'] && $atdData['checkout'] ? DateTimeHelper::getTimeDiff(date('H:i', strtotime($atdData['checkin'])), date('H:i', strtotime($atdData['checkout']))) : 0
                        ]);
                        $attendanceExist->update();
                    } else {
                        $this->attendance->save([
                            'org_id' => optional(auth()->user()->userEmployer)->organization_id,
                            'emp_id' => $employeeId,
                            'date' => $data['date'],
                            'nepali_date' => date_converter()->eng_to_nep_convert($data['date']),
                            'checkin' => $atdData['checkin'],
                            'checkout' => $atdData['checkout'],
                            'checkin_from' => 'form',
                            'checkout_from' => 'form',
                            'total_working_hr' => $atdData['checkin'] && $atdData['checkout'] ? DateTimeHelper::getTimeDiff(date('H:i', strtotime($atdData['checkin'])), date('H:i', strtotime($atdData['checkout']))) : 0,
                        ]);
                    }
                }
                //
            }
            toastr('Attendance Form has been submitted successfully !!!', 'success');
        } catch (\Throwable $th) {
            toastr('Something went wrong', 'error');
        }
        return redirect()->back();
    }

        //Update Monthly employee (old)
        public function viewMonthly(Request $request)
        {
            $filter = $request->all();
    
            if (isset($request->calendar_type)) {
                $year = $request->calendar_type == 'eng' ? $request->eng_year : $request->nep_year;
                $month = $request->calendar_type == 'eng' ? $request->eng_month : $request->nep_month;
                
                $data['field'] = $request->calendar_type == 'eng' ? 'date' : 'nepali_date';
                $data['year'] = $year;
                $data['month'] = $month;
                $data['calendarType'] = $request->calendar_type;
    
                if ($request->calendar_type == 'nep') {
                    $data['days'] = date_converter()->getTotalDaysInMonth($year, $month);
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
                    'calendarType' => $request->calendar_type,
                    'year' => $year,
                    'month' => $month,
                ];
                $getDate = $this->restrictFutureDate($checkDate);
                if ($getDate) {
                    $data['emps'] = $this->attendanceReport->siteAttendanceMonthly($data, $filter, null);
                } else {
                    $data['emps'] = [];
                }
            }
            if (auth()->user()->user_type == 'super_admin' || auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'hr') {
                $data['employees'] = $this->employee->getList();
                $data['organizationList'] = $this->organization->getList();
            }
            $data['eng_years'] = date_converter()->getEngYears();
            $data['nep_years'] = date_converter()->getNepYears();
            $data['eng_months'] = date_converter()->getEngMonths();
            $data['nep_months'] = date_converter()->getNepMonths();
            return view('attendance::site-attendance.form-monthly', $data);
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

        public function updateMonthly(Request $request){
            $data = $request->except('_token');
            $field = 'date';
            if($data['calendarType'] == 'nep'){
                $field = 'nepali_date';
            }
            try {
                if(!empty($data['siteAttendance'])){
                    foreach ($data['siteAttendance'] as $employeeId => $attendanceData) {
                        foreach ($attendanceData as $date => $attendance) {
                            $divisionAtdMonthly = $this->attendanceReport->findSiteAtdMonthly($employeeId, $field, $date);

                            if(isset($divisionAtdMonthly)){
                                if($divisionAtdMonthly['is_present'] != $attendance['is_present']){
                                    $divisionAtdMonthly->update(['is_present' => $attendance['is_present']]);
                                }
                            }else{
                                if($field == 'date'){
                                    $eng_date = $date;
                                    $nep_date = date_converter()->eng_to_nep_convert($date);
                                }else{
                                    $eng_date = date_converter()->nep_to_eng_convert($date);
                                    $nep_date = $date;
                                }
                                $formattedData = [
                                    'employee_id' => $employeeId,
                                    'date' => $eng_date,
                                    'nepali_date' => $nep_date,
                                    'is_present' => $attendance['is_present']
                                ];
                                $this->attendanceReport->saveSiteAtdMonthly($formattedData);
                            }
                            
                        }
                    }
                }
                toastr('success', 'Monthly Site Attendance updated successfully !!!');
            } catch (\Throwable $th) {
                toastr('error', 'Something went wrong !!!');
            }
            return redirect()->back();
        }
         

      
}
