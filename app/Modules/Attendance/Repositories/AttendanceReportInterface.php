<?php

namespace App\Modules\Attendance\Repositories;

interface AttendanceReportInterface
{
    public function isPresent($emp_id, $field, $date);

    public function isPartial($emp_id, $field, $date);

    public function isLeave($emp_id, $field, $date);

    public function employeeAttendance($data, $filter, $limit = '', $type);
    public function employeeRangeAttendance($data, $filter, $limit = '', $type);
    public function labourAttendance($data, $filter, $limit = '', $type);

    public function getCalendarAttendanceDetails($data, $filter, $limit = '');
    public function checkStatus($emp, $field, $fulldate);

    public function monthlyAttendanceSummary($data, $filter);

    public function monthlyLabourAttendanceSummary($data, $filter);


    public function employeeRegularAttendanceData();

    public function dateRangeAttendanceData();

    public function getEmployeeOrganizationListBasedOnRole();

    public function checkODDRequestExist($date, $employee_id);

    public function checkWFHRequestExist($date, $employee_id);
    public function checkForceAtdRequestExist($date, $employee_id);
    public function divisionAttendanceReport($limit = null, $filter = []);

    public function siteAttendanceMonthly($data, $filter, $limit = null);

    public function labourSiteAttendanceMonthly($data, $filter, $limit = null);

    public function findSiteAtdMonthly($employeeId, $field, $date);

    public function findSiteLabourAtdMonthly($employeeId, $field, $date);


    public function saveSiteAtdMonthly($data);

    public function saveSiteLabourAtdMonthly($data);

    public function checkLockedStatus($request);

    public function setLockData($data, $status);

    public function setEmpData($orgData, $emps);

    public function attendanceVerificationSummary($data, $filter);

    public function getEmployeeData($data, $filter);
}
