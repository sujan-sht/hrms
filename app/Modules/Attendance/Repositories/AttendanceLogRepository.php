<?php

namespace App\Modules\Attendance\Repositories;

use Carbon\Carbon;
use App\Helpers\DateTimeHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Modules\Attendance\Entities\Attendance;
use App\Modules\Attendance\Entities\AttendanceLog;
use App\Modules\NewShift\Entities\NewShiftEmployee;
use App\Modules\Shift\Repositories\ShiftRepository;
use App\Modules\Attendance\Entities\WebAttendanceAllocation;

class AttendanceLogRepository implements AttendanceLogInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'biometric_emp_id', 'sort' => 'ASC'], $status = [0, 1])
    {
        $result = AttendanceLog::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['date'])) {
                $query->where('date', '=', $filter['date']);
            }
            if (isset($filter['emp_id'])) {
                $query->where('emp_id', '=', $filter['emp_id']);
            }
            if (isset($filter['org_id'])) {
                $query->where('org_id', '=', $filter['org_id']);
            }
            if (isset($filter['biometric_emp_id'])) {
                $query->where('biometric_emp_id', '=', $filter['biometric_emp_id']);
            }
            if (isset($filter['time'])) {
                $query->where('time', '=', $filter['time']);
            }
            if (isset($filter['punch_from'])) {
                $query->where('punch_from', '=', $filter['punch_from']);
            }

            if (isset($filter['inout_mode'])) {
                if ($filter['inout_mode'] == 2) {
                    $query->where('inout_mode', 2);
                } else {
                    $query->whereIn('inout_mode', [0, 1]);
                }
            }
        })->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }

    public function find($id)
    {
        return AttendanceLog::find($id);
    }

    public function save($data)
    {
        return AttendanceLog::create($data);
    }

    public function update($id, $data)
    {
        return AttendanceLog::find($id)->update($data);
    }

    public function delete($id)
    {
        return AttendanceLog::find($id)->delete();
    }

    public function findOne($filter)
    {
        $result = AttendanceLog::where($filter)->first();
        return $result;
    }

    public function findMinMaxTime($filter)
    {
        $result = AttendanceLog::select(DB::raw('min(time) as min_time, max(time) as max_time'))->where($filter)->first();
        return $result;
    }

    public function findMinTime($filter)
    {
        // dd($filter);
        //$result = AttendanceLog::select(DB::raw('min(time) as min_time'))->where($filter)->first();

        //DB::connection()->enableQueryLog();
        //$result = AttendanceLog::select(DB::raw('max(time) as max_time'))->where($filter)->first();
        $result =  AttendanceLog::select('punch_from', 'time as min_time','lat','long')
            // ->whereRaw('time in (select min(time) from attendance_logs where date = "' . $filter["date"] . '" and biometric_emp_id = ' . $filter["biometric_emp_id"] . ')')
            ->whereDate('date', $filter["date"])
            ->where('biometric_emp_id', $filter["biometric_emp_id"])
            ->orderBy('time', 'asc')
            ->first();
        //dd(DB::getQueryLog());

        return $result;
    }

    public function findMaxTime($filter)
    { //DB::connection()->enableQueryLog();
        //$result = AttendanceLog::select(DB::raw('max(time) as max_time'))->where($filter)->first();
        $result =  AttendanceLog::select('punch_from', 'time as max_time','lat','long')
            // ->whereRaw('time in (select max(time) from attendance_logs where date = "' . $filter["date"] . '"  and biometric_emp_id = ' . $filter["biometric_emp_id"] . ')')
            ->whereDate('date', $filter["date"])
            ->where('biometric_emp_id', $filter["biometric_emp_id"])
            ->orderBy('time', 'desc')
            ->first();
        //dd(DB::getQueryLog());
        return $result;
    }


    public function getEmpAttLogByOrg($date = '', $yesterday_date = '')
    {
        $result = AttendanceLog::select('date', 'emp_id', 'org_id');
        if ($date != '') {
            $result->where('date', $date);
        }
        if ($yesterday_date != '') {
            $result->oWhere('date', $yesterday_date);
        }
        $result->groupBy('emp_id', 'org_id', 'date');
        return $result->get();
    }

    public function findLastAttendance($date, $emp_id)
    {
        $result = AttendanceLog::where('date', $date)->where('emp_id', $emp_id)->orderBy('created_at', 'DESC')->limit(1)->first();
        return $result;
    }

    //for employee
    public function getTodayCheckInOutOld()
    {
        $activeUserModel = Auth::user();

        $now = Carbon::now()->toDateString();
        $inputParam = [
            'emp_id' => $activeUserModel->emp_id,
            'date' => $now
        ];
        $getTodayAtd['date'] = $now;
        if (setting('real_time_app_atd') && setting('real_time_app_atd') == 11) {
            $total_working_hr = 0;
            $attendance = Attendance::where($inputParam)->first();
            if(isset($attendance)){
                if (isset($attendance->checkin) && isset($attendance->checkout)) {
                    $total_working_hr = (string) DateTimeHelper::getTimeDiff($attendance->checkin, $attendance->checkout);
                }
            }
            $is_check = false;
            $latestCheck = AttendanceLog::where($inputParam)->latest()->first();
            if($latestCheck){
                $is_check= $latestCheck->inout_mode == 0? true:false;
            }
            $data = [
                'date' => $now,
                'checkin' => isset($attendance) && !is_null($attendance->checkin) ? date('h:i A', strtotime($attendance->checkin)) : null,
                'checkout' => isset($attendance) && !is_null($attendance->checkout) ? date('h:i A', strtotime($attendance->checkout)) : null,
                'is_check' => $is_check,
                'inout_mode' => $latestCheck ? $latestCheck->inout_mode : 1,
                'total_working_hr' => $total_working_hr,
            ];
        }else{
            $checkin = AttendanceLog::where($inputParam)->where('inout_mode', 0)->whereIn('punch_from', ['app', 'web'])->min('time');
            $checkout  = AttendanceLog::where($inputParam)->where('inout_mode', 1)->whereIn('punch_from', ['app', 'web'])->max('time');
            $latestCheck = AttendanceLog::where($inputParam)->whereIn('punch_from', ['app', 'web'])->latest()->first();

            $getTodayAtd['inout_mode'] = $latestCheck ? $latestCheck->inout_mode : 1;
            $total_working_hr = 0;
            if ($checkout) {
                $total_working_hr = (string) DateTimeHelper::getTimeDiff($checkin, $checkout);
            }
            if(is_null($latestCheck)){
                $is_check= false;
            }else{
                $is_check= $latestCheck->inout_mode == 0? true:false;
            }

            $data = [
                'date' => $now,
                'checkin' => !is_null($checkin) ? date('h.i A', strtotime($checkin)) : null,
                'checkout' => !is_null($checkout) ? date('h.i A', strtotime($checkout)) : null,
                'is_check' => $is_check,
                'inout_mode' => $latestCheck ? $latestCheck->inout_mode : 1,
                'total_working_hr' => $total_working_hr,
            ];
        }
        return $data;
    }

    public function getTodayCheckInOut()
    {
        $activeUserModel = Auth::user();

        $attendanceDate = Carbon::now()->toDateString();
        $currentTime = Carbon::now()->toTimeString();
        $dayWiseShift = (new AttendanceRepository())->getDayWiseShift($activeUserModel->emp_id, $attendanceDate);
        $newShiftEmp = NewShiftEmployee::getShiftEmployee($activeUserModel->emp_id, $attendanceDate);

        if (isset($newShiftEmp)) {
            $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
            if (isset($rosterShift) && isset($rosterShift->shift_id) && ($rosterShift->shift_id != null)) {
                $employeeShift = (new ShiftRepository())->find($rosterShift->shift_id);
                if($employeeShift){
                    $day = date('D', strtotime($attendanceDate));
                    $dayWiseShift = $employeeShift->getShiftDayWise($day);
                }
            }
        }
        if($dayWiseShift){
            // if(!is_null(optional($dayWiseShift->shift)->is_multi_day_shift) &&  optional($dayWiseShift->shift)->is_multi_day_shift == 1){     //for multi day Shift
                if ($currentTime < $dayWiseShift->checkin_start_time) {
                    $convertedDate = Carbon::parse($attendanceDate);
                    $attendanceDate = $convertedDate->subDay()->toDateString();
                }
            // }
        }
        $inputParam = [
            'emp_id' => $activeUserModel->emp_id,
            'date' => $attendanceDate
        ];
        $getTodayAtd['date'] = $attendanceDate;
        if (setting('real_time_app_atd') && setting('real_time_app_atd') == 11) {
            $total_working_hr = 0;
            $attendance = Attendance::where($inputParam)->first();
            if(isset($attendance)){
                if (isset($attendance->checkin) && isset($attendance->checkout)) {
                    $total_working_hr = (string) DateTimeHelper::getTimeDiff($attendance->checkin, $attendance->checkout);
                }
            }
            $is_check = false;
            $latestCheck = AttendanceLog::where($inputParam)->latest()->first();
            if($latestCheck){
                $is_check= $latestCheck->inout_mode == 0? true:false;
            }
            $data = [
                'date' => $attendanceDate,
                'checkin' => isset($attendance) && !is_null($attendance->checkin) ? date('h:i A', strtotime($attendance->checkin)) : null,
                'checkout' => isset($attendance) && !is_null($attendance->checkout) ? date('h:i A', strtotime($attendance->checkout)) : null,
                'is_check' => $is_check,
                'inout_mode' => $latestCheck ? $latestCheck->inout_mode : 1,
                'total_working_hr' => $total_working_hr,
            ];
        }else{
            $checkin = AttendanceLog::where($inputParam)->where('inout_mode', 0)->whereIn('punch_from', ['app', 'web'])->min('time');
            $checkout  = AttendanceLog::where($inputParam)->where('inout_mode', 1)->whereIn('punch_from', ['app', 'web'])->max('time');
            $latestCheck = AttendanceLog::where($inputParam)->whereIn('punch_from', ['app', 'web'])->latest()->first();

            $getTodayAtd['inout_mode'] = $latestCheck ? $latestCheck->inout_mode : 1;
            $total_working_hr = 0;
            if ($checkout) {
                $total_working_hr = (string) DateTimeHelper::getTimeDiff($checkin, $checkout);
            }
            if(is_null($latestCheck)){
                $is_check= false;
            }else{
                $is_check= $latestCheck->inout_mode == 0? true:false;
            }

            $data = [
                'date' => $attendanceDate,
                'checkin' => !is_null($checkin) ? date('h.i A', strtotime($checkin)) : null,
                'checkout' => !is_null($checkout) ? date('h.i A', strtotime($checkout)) : null,
                'is_check' => $is_check,
                'inout_mode' => $latestCheck ? $latestCheck->inout_mode : 1,
                'total_working_hr' => $total_working_hr,
            ];
        }
        return $data;
    }

    function allocationList(){
        $qry = WebAttendanceAllocation::query();
        if(auth()->user()->user_type == 'division_hr'){
          $qry->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
        }
        $result = $qry->get();
        return $result;
    }
    public function checkAllocationExists($data)
    {
        $qry = WebAttendanceAllocation::query();
        $qry = $qry->where('organization_id', $data['organization_id'])->where('department_id', $data['department_id']);
        if(isset($data['id'])){
            $qry->where('id', '!=', $data['id']);
        }
        $result = $qry->exists();
        return $result;
    }

    public function webAtdAllocation($data) {
        $inputData['organization_id'] = $data['organization_id'];
        $inputData['branch_id'] = $data['branch_id'];

        if(!empty($data['web_atd_details'])){
            foreach ($data['web_atd_details'] as $allocation) {
                if (isset($allocation['department_id']) && isset($allocation['employee_ids'])) {
                    $inputData['department_id'] = $allocation['department_id'];
                    $inputData['employee_id'] = json_encode($allocation['employee_ids']);
                    WebAttendanceAllocation::create($inputData);
                }
            }
        }
        return true;
    }

    public function findAllocation($id)
    {
        return WebAttendanceAllocation::find($id);
    }

    public function updateAllocation($id,$data)
    {
        $result = WebAttendanceAllocation::find($id);
        if(isset($data['employee_ids']) && !empty($data['employee_ids'])){
            $data['employee_id'] = json_encode($data['employee_ids']);
        }else{
            $data['employee_id'] = null;
        }
        return $result->update($data);
    }

    function destroyAllocation($id) {
        return WebAttendanceAllocation::destroy($id);
    }
}
