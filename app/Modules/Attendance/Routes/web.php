<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'admin'], function () {
    Route::get('attendance/run', 'AttendanceController@runAttendance');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'permission', 'XssSanitizer']], function () {
    // Route::get('attendance', 'AttendanceController@index')->name('attendance.index');

    //View Report
    Route::get('attendance-overview', 'AttendanceReportController@monthlyAttendance')->name('monthlyAttendance');
    Route::get('raw-attendance', 'AttendanceReportController@rawAttendance')->name('rawAttendance');
    Route::get('raw-attendance/export', 'AttendanceReportExportController@exportRawAttendance')->name('exportRawAttendance');

    Route::get('monthly-attendance/summary/verification', 'AttendanceReportController@monthlyAttendanceSummaryVerification')->name('monthlyAttendanceSummaryVerification');
    Route::post('monthly-attendance/summary/verification/draft', 'AttendanceReportController@monthlyAttendanceSummaryVerificationDraft')->name('attendanceSummaryVerificationAction.draft');
    Route::post('unlocked/attendance', 'AttendanceReportController@unlockedAttendance')->name('unlocked.attendance');

    Route::get('monthly-attendance/summary', 'AttendanceReportController@monthlyAttendanceSummary')->name('monthlyAttendanceSummary');
    Route::get('monthly-attendance-report', 'AttendanceReportController@dailyAttendance')->name('dailyAttendance');

    // monthly report calender view
    Route::get('monthly-attendance-report/view-calendar', 'AttendanceReportController@viewMonthlyAttendanceCalendar')->name('viewMonthlyAttendanceCalendar');
    Route::get('monthly-attendance-report/calendar-ajax', 'AttendanceReportController@getMonthlyCalendarAttendanceByAjax')->name('getMonthlyCalendarAttendanceByAjax');
    Route::get('get-employees/from/allSetParams', 'AttendanceReportController@getEmployeesFromOrgBranchDepartmentIdDesignationId')->name('getEmployeesFromOrgBranchDepartmentIdDesignationId');

    Route::get('daily-attendance-report', 'AttendanceReportController@regularAttendanceReport')->name('regularAttendanceReport');
    Route::get('app-attendance-report', 'AttendanceReportController@appAttendanceReport')->name('appAttendanceReport');
    Route::get('app-view-logs', 'AttendanceReportController@appViewLogs')->name('appViewLogs');

    // Route::get('attendance-log', 'AttendanceReportController@viewAtdLog')->name('viewAtdLog');

    //

    //calendar view
    Route::get('attendance/view-calendar', 'AttendanceReportController@viewAttendanceCalendar')->name('viewAttendanceCalendar');
    Route::get('attendance/calendar-ajax', 'AttendanceReportController@getCalendarAttendanceByAjax')->name('getCalendarAttendanceByAjax');
    //

    //Export Report
    Route::get('attendance-overview/export', 'AttendanceReportExportController@exportMonthlyAttendance')->name('exportMonthlyAttendance');
    Route::get('attendance-overview/download', 'AttendanceReportExportController@downloadMonthlyAttendance')->name('downloadMonthlyAttendance');

    Route::get('monthly-attendance/summary-export', 'AttendanceReportExportController@exportMonthlySummary')->name('exportMonthlySummary');
    Route::get('monthly-attendance/summary-export/attendance-lock', 'AttendanceReportExportController@exportMonthlySummaryAttendanceLock')->name('exportMonthlySummaryAttendanceLock');
    Route::get('monthly-attendance/summary-download', 'AttendanceReportExportController@downloadMonthlySummary')->name('downloadMonthlySummary');
    Route::get('monthly-attendance/summary-download/attendance-lock', 'AttendanceReportExportController@downloadMonthlySummaryAttendanceLock')->name('downloadMonthlySummaryAttendanceLock');

    Route::get('monthly-attendance-report/export-report', 'AttendanceReportExportController@exportDailyAttendanceReport')->name('exportDailyAttendanceReport');
    Route::get('monthly-attendance-report/download-report', 'AttendanceReportExportController@downloadDailyAttendanceReport')->name('downloadDailyAttendanceReport');
    Route::get('daily-attendance-report/export-report', 'AttendanceReportExportController@exportRegularAttendance')->name('exportRegularAttendance');
    Route::get('daily-attendance-report/download-report', 'AttendanceReportExportController@downloadRegularAttendance')->name('downloadRegularAttendance');

    //Date range attendance
    Route::get('date-range-Attendance', 'AttendanceReportController@DateRangeAttendance')->name('monthlyAttendanceRange');
    // Route::get('monthlyAttendanceRange/export', 'AttendanceReportController@exportAttendanceRangeReport')->name('exportAttendanceRangeReport');

    //

    //Attendance Request
    Route::get('attendance-requests', ['as' => 'attendanceRequest.index', 'uses' => 'AttendanceRequestController@index']);
    Route::get('attendance-request/create', ['as' => 'attendanceRequest.create', 'uses' => 'AttendanceRequestController@create']);
    Route::post('attendance-request/store', ['as' => 'attendanceRequest.store', 'uses' => 'AttendanceRequestController@store']);
    Route::get('attendance-request/edit/{id}', ['as' => 'attendanceRequest.edit', 'uses' => 'AttendanceRequestController@edit'])->where('id', '[0-9]+');
    Route::put('attendance-request/update/{id}', ['as' => 'attendanceRequest.update', 'uses' => 'AttendanceRequestController@update'])->where('id', '[0-9]+');
    Route::get('attendance-request/delete/{id}', ['as' => 'attendanceRequest.delete', 'uses' => 'AttendanceRequestController@destroy'])->where('id', '[0-9]+');
    Route::get('attendance-request/{id}/view', ['as' => 'attendanceRequest.show', 'uses' => 'AttendanceRequestController@show']);

    Route::put('attendance-request/updateStatus', ['as' => 'attendanceRequest.updateStatus', 'uses' => 'AttendanceRequestController@updateStatus']);
    Route::post('attendance-request/update-status-bulk', ['as' => 'attendanceRequest.updateStatusBulk', 'uses' => 'AttendanceRequestController@updateStatusBulk']);
    Route::post('attendance-request/update-team-status-bulk', ['as' => 'attendanceRequest.updateTeamStatusBulk', 'uses' => 'AttendanceRequestController@updateStatusBulk']);

    Route::put('attendance-request/cancelAttendanceRequest', ['as' => 'attendanceRequest.cancelAttendanceRequest', 'uses' => 'AttendanceRequestController@cancelAttendanceRequest']);
    Route::get('team-attendance-requests', ['as' => 'attendanceRequest.showTeamAttendance', 'uses' => 'AttendanceRequestController@showTeamAttendance']);
    //

    Route::get('attendance-request/team-request/create', ['as' => 'attendanceTeamRequest.create', 'uses' => 'AttendanceRequestController@teamRequestCreate']);
    Route::post('attendance-request/team-request/store', ['as' => 'attendanceTeamRequest.store', 'uses' => 'AttendanceRequestController@teamRequestStore']);

    Route::get('/attendance/notify', function () {
        Artisan::call('notify:atdRequest');
    });

    // Site Attendance
    Route::get('attendance/division-role-setups', ['as' => 'siteAttendance.roleSetup', 'uses' => 'SiteAttendanceController@roleSetup']);
    Route::post('attendance/division-role-setup/store', ['as' => 'siteAttendance.storeRoleSetup', 'uses' => 'SiteAttendanceController@storeRoleSetup']);

    Route::get('attendance/division/view-form', ['as' => 'siteAttendance.viewForm', 'uses' => 'SiteAttendanceController@viewForm']);
    Route::post('attendance/division/update-form', ['as' => 'siteAttendance.updateForm', 'uses' => 'SiteAttendanceController@updateForm']);

    Route::get('attendance/division/view-monthly', ['as' => 'siteAttendance.viewMonthly', 'uses' => 'SiteAttendanceController@viewMonthly']);

    Route::post('attendance/division/update-monthly', ['as' => 'siteAttendance.updateMonthly', 'uses' => 'SiteAttendanceController@updateMonthly']);


    //

    //Web Atd Allocation
    Route::get('web-attendance/allocations', 'WebAttendanceAllocationController@allocationList')->name('webAttendance.allocationList');
    Route::get('web-attendance/allocate-form', 'WebAttendanceAllocationController@allocateForm')->name('webAttendance.allocateForm');
    Route::post('web-attendance/allocate', 'WebAttendanceAllocationController@allocate')->name('webAttendance.allocate');

    Route::get('/web-attendance/{id}/edit', 'WebAttendanceAllocationController@editAllocation')->name('webAttendance.editAllocation');
    Route::put('/web-attendance/{id}/update', 'WebAttendanceAllocationController@updateAllocation')->name('webAttendance.updateAllocation');
    Route::get('/web-attendance/{id}/delete', 'WebAttendanceAllocationController@destroyAllocation')->name('webAttendance.destroyAllocation');
    //
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'XssSanitizer']], function () {
    Route::get('/web-attendance/checkExists', 'WebAttendanceAllocationController@checkExists')->name('webAttendance.checkExists');
    Route::get('/web-attendance/clone-day', ['as' => 'webAttendance.clone.day', 'uses' => 'WebAttendanceAllocationController@cloneDay']);
});

//Check attendance request already exist
Route::get('attendance-request/checkRequestExist', ['as' => 'attendanceRequest.checkRequestExist', 'uses' => 'AttendanceRequestController@checkRequestExist']);
Route::post('attendance-request/post-process-data', ['as' => 'attendanceRequest.postProcessData', 'uses' => 'AttendanceRequestController@postProcessData']);

// get checkin time
Route::get('attendance-request/getCheckInCheckOutTime', ['as' => 'attendanceRequest.getCheckInCheckOutTime', 'uses' => 'AttendanceRequestController@getCheckInCheckOutTime']);




//Deduct Leave based on Attendance
Route::get('admin/attendance/deduct-leave-daily', 'AttendanceController@deductLeaveDaily');

// Route::get('admin/attendance/deduct-leave-monthly', 'AttendanceController@deductLeaveMonthly');

//Notify missed checkin, checkout
Route::get('admin/notify/missed-checkin', 'AttendanceController@missedCheckInNotify');
Route::get('admin/notify/missed-checkout', 'AttendanceController@missedCheckOutNotify');
