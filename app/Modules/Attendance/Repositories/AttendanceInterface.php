<?php

namespace App\Modules\Attendance\Repositories;

interface AttendanceInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function findAllWithStatus($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function find($id);

    public function save($data);

    public function update($id, $data);

    public function delete($id);

    public function findOne($filter);

    public function employeeAttendanceExists($emp_id, $date);

    public function getAttendance($filter = [], $limit = null, $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function getMonthlyAttendance($date, $emp_id, $calendar_type = 0);

    public function getMonthlyAttendanceList($date, $org_id);

    public function getlateEarlyAndMissed();

    public function getMonths();

    public function getYears();

    public function getDistinctEmployee($org_id);

    public static function getEmpAttendanceByDate($date, $emp_id, $calendar_type = 0);

    public static function getEngByNepDate($year, $month, $day);

    public function getNepaliMonths();

    public static function getEmpAttendanceByDateList($date, $emp_id, $filter = [], $calendar_type = 0, $limit = null,  $sort = ['by' => 'id', 'sort' => 'DESC']);

    public static function getNepByEngDate($year, $month, $day);

    public function getAvgHrMonthlyAttendance($date, $emp_id, $calendar_type = 0);

    public function getAvgHrMonthlyAttendanceByDepartment($year_month, $emp_id, $calendar_type = 0, $department_id);

    public function getOnTimeAttCount($year_month, $emp_id, $calendar_type = 0, $start_time, $start_grace_time);

    public function getOnTimeAttCountByDepartment($year_month, $emp_id, $calendar_type = 0, $department_id, $start_time, $start_grace_time);

    public function dailyLeaveDeductBasedOnAttendance();

    // public function monthlyLeaveDeductBasedOnAttendance();

    public function getMobileAttendance();

    public function saveAttendance($employee, $inputData);

    public function attendanceLogExists($value);

    public function getDayWiseShift($employeeId, $punchedDate);

    public function determineInOutMode($time, $shift, $dateChanged);

    public function getLateArrivalEarlyDepartureData($emp, $engDate, $time, $type);

}
