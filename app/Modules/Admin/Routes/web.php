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

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'web', 'XssSanitizer']], function () {
    Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);
    Route::get('analytical/dashboard', ['as' => 'analyticaldashboard', 'uses' => 'DashboardController@analyticalIndex']);
    Route::get('calendar', ['as' => 'dashboard.calendar', 'uses' => 'DashboardController@viewCalendar']);
    Route::get('calendar-ajax', ['as' => 'dashboard.get.calendar.ajax', 'uses' => 'DashboardController@getCalendarEventHolidayByAjax']);
    Route::post('store-calendar-event-ajax', ['as' => 'dashboard.store.calendar.ajax', 'uses' => 'DashboardController@calendarEvents']);

    Route::get('sytem-reminders', ['as' => 'systemReminder.list', 'uses' => 'AdminController@systemReminderList']);
    Route::get('test-mail', 'AdminController@testMail');

    Route::post('store-attendance', ['as' => 'store.attendance', 'uses' => 'DashboardController@storeAttendance']);

    Route::get('change-date-format', ['as' => 'tool.changeNepaliDateFormat', 'uses' => 'ToolController@changeNepaliDateFormat']);
    Route::get('update-atd-check', ['as' => 'tool.updateAtdCheck', 'uses' => 'ToolController@updateAtdCheck']);
    Route::get('assign-new-user', ['as' => 'tool.assignNewUser', 'uses' => 'ToolController@storeBulkNewUser']);
    Route::get('set-employee-job-type-permanent', ['as' => 'tool.setEmployeeJobTypePermanent', 'uses' => 'ToolController@setEmployeeJobTypePermanent']);

    Route::get('set-leave-type-org', ['as' => 'tool.deleteOtherOrgnEmployeeOnLeaveType', 'uses' => 'ToolController@deleteOtherOrgnEmployeeOnLeaveType']);
    Route::get('set-attendance-request', ['as' => 'tool.setAttendanceRequest', 'uses' => 'ToolController@setAttendanceRequest']);
    Route::get('one-signal', ['as' => 'tool.oneSignal', 'uses' => 'ToolController@oneSignal']);


    // Master Report
    Route::get('master-report/leave', ['as' => 'masterReport.leave', 'uses' => 'MasterReportController@leaveReport']);
    Route::get('master-report/attendance', ['as' => 'masterReport.attendance', 'uses' => 'MasterReportController@attendanceReport']);







    //change emloyee role into supervisor
    Route::get('change-role-employee-to-supervisor', ['as' => 'changeRoleToSupervisor', 'uses' => 'ToolController@changeRoleToSupervisor']);
    //

    //View Approval Flow Bulk Upload
    Route::get('bulkupload/view-approval-flow-upload', ['as' => 'bulkupload.approvalFlowView', 'uses' => 'ToolController@approvalFlowView']);
    //
    //Assign supervisors for employees for leave, attendance, claim and request module
    Route::post('assign-approval-flow-leave-claim', ['as' => 'bulkupload.assignApprovalFlowLeaveClaim', 'uses' => 'ToolController@assignApprovalFlowLeaveClaim']);
    //

    //Assign supervisors for employees for leave, attendance, claim and request module
    Route::post('assign-approval-flow-offboard-appraisal', ['as' => 'bulkupload.assignApprovalFlowOffboardAppraisal', 'uses' => 'ToolController@assignApprovalFlowOffboardAppraisal']);
    //

    Route::get('update-attendance-data-from-requests/{type}', ['as' => 'updateAttendanceDataFromRequests', 'uses' => 'ToolController@updateAttendanceDataFromRequests']);

    //convert substitute date into english date format
    Route::get('convert-substitutedate-in-english', ['as' => 'convertSubstitutedateInEnglish', 'uses' => 'ToolController@convertSubstitutedateInEnglish']);

    //change users password
    Route::get('change-user-password', 'ToolController@changeUserPassword');

    Route::get('/search/active/modules', ['as' => 'search.active.modules', 'uses' => 'ToolController@searchActiveModules']);

    Route::get('send/employee/birthday/wish/{id}', ['as' => 'tool.sendWishMail', 'uses' => 'ToolController@sendWishMail']);
    Route::get('send/employee/wish-sms/{id}/{type}', ['as' => 'tool.sendWishSMS', 'uses' => 'ToolController@sendWishSMS']);
});


Route::post('admin/convert/eng-to-nep', 'DateConverterController@eng_to_nep');

Route::get('fullcalendar', function () {
    return view('admin::dashboard.full-calendar');
});

Route::group(['prefix' => 'admin'], function () {
    // cron routes
    Route::get('system-reminder', ['as' => 'system.reminder', 'uses' => 'AdminController@saveSystemReminder']);
});
