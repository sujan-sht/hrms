<?php

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

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'permission', 'XssSanitizer']], function () {

    // leave routes
    Route::get('leaves', ['as' => 'leave.index', 'uses' => 'LeaveController@index']);
    Route::get('template', ['as' => 'leave.showTemplate', 'uses' => 'LeaveController@showTemplate']);
    Route::get('leave/create', ['as' => 'leave.create', 'uses' => 'LeaveController@create']);
    Route::post('leave/store', ['as' => 'leave.store', 'uses' => 'LeaveController@store']);
    Route::get('leave/{id}/view', ['as' => 'leave.show', 'uses' => 'LeaveController@show']);
    Route::put('leave/{id}/update', ['as' => 'leave.update', 'uses' => 'LeaveController@update']);
    Route::get('leave/{id}/delete', ['as' => 'leave.delete', 'uses' => 'LeaveController@destroy']);
    Route::post('leave/update-status', ['as' => 'leave.updateStatus', 'uses' => 'LeaveController@updateStatus']);
    Route::post('leave/update-status-bulk', ['as' => 'leave.updateStatusBulk', 'uses' => 'LeaveController@updateStatusBulk']);
    Route::put('leave/cancel-leave-request', ['as' => 'leave.cancelLeaveRequest', 'uses' => 'LeaveController@cancelLeaveRequest']);
    Route::get('leave/history/export', ['as' => 'leave.exportLeaveHistory', 'uses' => 'LeaveController@exportLeaveHistory']);
    Route::get('leave/report', ['as' => 'leave.report', 'uses' => 'LeaveController@report']);
    Route::get('leave/report/export', ['as' => 'leave.exportLeaveReport', 'uses' => 'LeaveController@exportLeaveReport']);

    Route::get('leave/team-request', ['as' => 'leave.showTeamleaves', 'uses' => 'LeaveController@showTeamleaves']);
    Route::get('leave/team-request/create', ['as' => 'leave.teamRequestCreate', 'uses' => 'LeaveController@teamRequestCreate']);
    Route::post('leave/team-request/store', ['as' => 'leave.teamRequestStore', 'uses' => 'LeaveController@teamRequestStore']);

    //Leave Overview
    Route::get('leave-overview', ['as' => 'leave.leaveOverview', 'uses' => 'LeaveOverViewController@leaveOverview']);
    Route::get('leave-monthlySummary', ['as' => 'leave.monthlySummary', 'uses' => 'LeaveOverViewController@monthlySummary']);
    Route::get('leave-annualSummary', ['as' => 'leave.annualSummary', 'uses' => 'LeaveOverViewController@annualSummary']);
    Route::get('/getLeaveYearTypeLeave','LeaveOverViewController@getLeaveYearTypeLeave')->name('getLeaveYearTypeLeave');
    // Route::post('leave-overview/store-previous-remaining-leave', ['as' => 'leave.storePreviousLeaveDetails', 'uses' => 'LeaveOverViewController@storePreviousLeaveDetails']);

    //Leave Overview
    Route::get('leave-overview/createImportFile', ['as' => 'leave.createImportFile', 'uses' => 'LeaveOverViewController@createImportFile']);
    Route::post('leave-overview/postImportFile', ['as' => 'leave.postImportFile', 'uses' => 'LeaveOverViewController@postImportFile']);

    Route::post('leave-overview/store-previous-remaining-leave', ['as' => 'leave.storePreviousLeaveDetails', 'uses' => 'LeaveOverViewController@storePreviousLeaveDetails']);
    Route::get('leave-overview/export-leave-overview', ['as' => 'leave.exportLeaveOverview', 'uses' => 'LeaveOverViewController@exportLeaveOverview']);


    // leave type routes
    Route::get('leave-types', ['as' => 'leaveType.index', 'uses' => 'LeaveTypeController@index']);
    Route::get('leave-type/create', ['as' => 'leaveType.create', 'uses' => 'LeaveTypeController@create']);
    Route::post('leave-type/store', ['as' => 'leaveType.store', 'uses' => 'LeaveTypeController@store']);
    Route::get('leave-type/{id}/edit', ['as' => 'leaveType.edit', 'uses' => 'LeaveTypeController@edit']);
    Route::put('leave-type/{id}/update', ['as' => 'leaveType.update', 'uses' => 'LeaveTypeController@update']);
    Route::get('leave-type/{id}/delete', ['as' => 'leaveType.delete', 'uses' => 'LeaveTypeController@destroy']);
    Route::get('leave-type/{id}/show', ['as' => 'leaveType.show', 'uses' => 'LeaveTypeController@show']);

    // Need to call at the start of the leave year only
    Route::get('leave-type/sync/{id}', ['as' => 'leaveType.sync', 'uses' => 'LeaveTypeController@sync']);

    // Leave opening routes
    Route::get('leave-opening', ['as' => 'leaveOpening.index', 'uses' => 'EmployeeLeaveOpeningController@index']);
    Route::get('leave-opening/show/{id}', ['as' => 'leaveOpening.show', 'uses' => 'EmployeeLeaveOpeningController@show']);
    Route::get('leave-opening/show/{id}/export', ['as' => 'leaveOpening.exportLeaveSummaryReport', 'uses' => 'EmployeeLeaveOpeningController@exportLeaveSummaryReport']);


    // Leave Encashable
    Route::get('leave-encashable', ['as' => 'leave.encashableLeave', 'uses' => 'EmployeeLeaveOpeningController@encashableLeave']);
    Route::post('leave-encashable/store', ['as' => 'leave.storeEncashableLeave', 'uses' => 'EmployeeLeaveOpeningController@storeEncashableLeave']);

    //previous leave year
    Route::get('leave/monthly-report', ['as' => 'leave.monthlyReport', 'uses' => 'ReportController@previousLeaveYearReport']);
    Route::get('leave/monthly-report/export', ['as' => 'leave.exportMonthlyLeaveReport', 'uses' => 'ReportController@exportMonthlyLeaveReport']);

    // Route::get('run-prorata/daily/{increment}', function ($increment) {
    //     Artisan::call('prorata:daily', [
    //         'increment' => $increment,
    //     ]);
    // });

    Route::get('run-prorata/daily', function () {
        Artisan::call('prorata:daily');
    });

    Route::get('run-prorata/monthly', function () {
        Artisan::call('prorata:monthly');
    });

    Route::get('refund/substitute-leave', function () {
        Artisan::call('refund:substituteLeave');
    });

    Route::get('leave/calendar', ['as' => 'leave.calendar', 'uses' => 'LeaveController@viewCalendar']);
    Route::get('leave/calendar-ajax', ['as' => 'leave.get.calendar.ajax', 'uses' => 'LeaveController@getCalendarLeaveByAjax']);

    Route::get('leave/download-pdf/{id}', ['as' => 'leave.downloadPDF', 'uses' => 'LeaveController@downloadPDF']);
    Route::get('leave/encashment', ['as' => 'leave.encashment', 'uses' => 'LeaveController@encashment']);

    Route::post('leave/encashment-update-status', ['as' => 'leave.updateEncashmentStatus', 'uses' => 'LeaveController@updateEncashmentStatus']);

    Route::get('leave/encashment-logs', ['as' => 'leave.encashmentActivity', 'uses' => 'LeaveController@encashmentActivity']);
    Route::post('leave/updateArchivedEncashmentDate', ['as' => 'leave.updateArchivedEncashmentDate', 'uses' => 'LeaveController@updateArchivedEncashmentDate']);


});

Route::get('leave/get-list', ['as' => 'leave.getList', 'uses' => 'LeaveController@getList']);
Route::get('leave/get-remaining-list', ['as' => 'leave.getRemainingList', 'uses' => 'LeaveController@getRemainingList']);
Route::post('leave/pre-process-data', ['as' => 'leave.preProcessData', 'uses' => 'LeaveController@preProcessData']);
Route::post('leave/post-process-data', ['as' => 'leave.postProcessData', 'uses' => 'LeaveController@postProcessData']);
Route::get('leave/get-substitute-date-list', ['as' => 'leave.getSubstituteDateList', 'uses' => 'LeaveController@getSubstituteDateList']);

Route::get('leave/getRemainingLeave', ['as' => 'leave.getRemainingLeave', 'uses' => 'LeaveController@getRemainingLeave']);


// cron routes
Route::get('admin/leave/check-for-new-month', 'LeaveController@checkForNewMonth');
Route::get('admin/leave/check-leave-end', 'LeaveController@checkLeaveEnd');
Route::get('leave/check/day-off', ['as' => 'leave.check.dayoff', 'uses' => 'LeaveController@checkDayOff']);
Route::get('get-employee-department-wise/', ['as' => 'leave.getEmployeeDepartmentWise', 'uses' => 'LeaveTypeController@getEmployeeDepartmentWise']);
