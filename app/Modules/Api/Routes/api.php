<?php

use App\Modules\Employee\Entities\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['api.header'])->group(function () {
        Route::post('v1/organization', ['uses' => 'OrganizationController@organizationStore']);
        Route::post('v1/organization/all-data', ['uses' => 'OrganizationController@storeAllOraganization']);
        Route::post('v1/employee', ['uses' => 'EmployeeController@employeeStore']);
        Route::post('v1/employee/all-data', ['uses' => 'EmployeeController@storeAllEmployee']);
    });

// Send all organization erp
Route::post('v1/get-all-organization', ['uses' => 'OrganizationController@getAllOrganization'])->name('v1.organization.getAll');
 // Send all employee to erp
 Route::post('get-all-employee', ['uses' => 'EmployeeController@getAllEmployee'])->name('v1.employee.getAll');

Route::post('v1/login', ['uses' => 'AuthController@login']);
Route::get('v1/payroll/get-list', ['uses' => 'Payroll\PayrollController@getList']);
Route::post('v1/payroll/get-data', ['uses' => 'Payroll\PayrollController@getData']);
Route::post('v1/payroll/upateAccountStatus', ['uses' => 'Payroll\PayrollController@upateAccountStatus']);

Route::post('v1/forget-password', ['uses' => 'AuthController@forgetPassword']);
Route::post('v1/verify-resetpassword-otp', ['uses' => 'AuthController@resetPasswordVerification']);
Route::post('v1/reset-password', ['uses' => 'AuthController@resetPassword']);
// Route::post('register', 'AuthController@register');

// Route::middleware('auth:api')->get('v1/user', function (Request $request) {
//     return $request->user();
// });
Route::get('v1/app-version-check', ['uses' => 'AuthController@appVersionCheck']);
Route::get('mrf/list', ['uses' => 'MrfController@index']);

Route::group(['prefix' => 'applicant'], function () {
    Route::post('store', ['uses' => 'ApplicantController@store']);
    Route::get('dropdown-list', ['uses' => 'ApplicantController@getDropdown']);
});



Route::group(['prefix' => 'v1/', 'middleware' => ['auth:api']], function () {
    Route::get('getRequiredFields', ['uses' => 'OrganizationController@getRequiredFields']);
    Route::post('logout', ['uses' => 'AuthController@logout']);
    Route::group(['prefix' => 'dashboard/'], function () {
        Route::get('/', ['uses' => 'DashboardController@dashboard']);
        Route::get('/get-calendar-event-holiday', ['uses' => 'DashboardController@getCalendarEventHoliday']);
        Route::get('/get-staffs-on-leave', ['uses' => 'DashboardController@getStaffsOnLeave']);
        Route::get('/get-leave-summary', ['uses' => 'DashboardController@getLeaveSummary']);
        Route::get('/get-attendance-overview', ['uses' => 'DashboardController@getAttendanceOverview']);
        Route::get('/get-system-reminders', ['uses' => 'DashboardController@getsystemReminder']);
        Route::get('/get-birthday-aniversary', ['uses' => 'DashboardController@getBirthdayAniversary']);
        Route::get('/get-new-starter', ['uses' => 'DashboardController@getnewStarter']);
        Route::get('/get-survey', ['uses' => 'DashboardController@getSurvey']);
    });

    Route::group(['prefix' => 'user/'], function () {
        Route::get('profile', ['uses' => 'ProfileController@profile']);
        Route::get('notification', ['uses' => 'ProfileController@notification']);

        Route::post('update', ['uses' => 'ProfileController@update']);
        Route::post('change-password', ['uses' => 'ProfileController@updatePassword']);
    });

    Route::group(['prefix' => 'organization/'], function () {
        Route::get('overview', ['uses' => 'OrganizationController@overview']);
        Route::get('code-of-conduct', ['uses' => 'OrganizationController@codeOfConduct']);
        Route::get('directory', ['uses' => 'OrganizationController@directory']);
    });

    Route::group(['prefix' => 'leave'], function () {
        //Leave
        Route::get('dropdown', ['uses' => 'Leave\LeaveController@dropdown']);
        Route::get('remaining', ['uses' => 'Leave\LeaveController@remainingLeave']);
        Route::get('history', ['uses' => 'Leave\LeaveController@index']);
        Route::get('view/{id}', ['uses' => 'Leave\LeaveController@show']);
        Route::post('{id}/update-status', ['uses' => 'Leave\LeaveController@updateStatus']);

        Route::post('store', ['uses' => 'Leave\LeaveController@store']);
        Route::get('pre-process-data', ['uses' => 'Leave\LeaveController@preProcessData']);
        Route::get('post-process-data', ['uses' => 'Leave\LeaveController@postProcessData']);
        Route::delete('delete/{id}', ['uses' => 'Leave\LeaveController@destroy']);
        //

        //Leave Summary
        Route::get('/summary/organization-list', ['uses' => 'Leave\LeaveSummaryController@getOrganizationList']);
        Route::get('/summary/leave-year-list', ['uses' => 'Leave\LeaveSummaryController@getLeaveYearList']);
        Route::get('/summary', ['uses' => 'Leave\LeaveSummaryController@index']);
        Route::get('/summary/get-leave-count', ['uses' => 'Leave\LeaveSummaryController@getLeaveCount']);

        //Leave Type
        Route::get('leave-detail/{leaveCategoryId}', ['uses' => 'Leave\LeaveTypeController@getLeaveDetailFromCategory']);
        Route::get('leave-type/list', ['uses' => 'Leave\LeaveTypeController@list']);
        Route::get('leave-type/dropdown-list', ['uses' => 'Leave\LeaveTypeController@getDropdown']);
        Route::post('leave-type/store', ['uses' => 'Leave\LeaveTypeController@store']);
        Route::post('leave-type/{id}/update', ['uses' => 'Leave\LeaveTypeController@update']);
        Route::delete('leave-type/delete/{id}', ['uses' => 'Leave\LeaveTypeController@destroy']);
        //

    });

    Route::group(['prefix' => 'attendance'], function () {
        Route::get('/dropdown', ['uses' => 'Attendance\AttendanceController@getDropdown']);
        Route::get('/today-attendance', ['uses' => 'Attendance\AttendanceController@getTodayAttendance']);
        Route::post('/store', ['uses' => 'Attendance\AttendanceController@store']);
        Route::get('/overview', ['uses' => 'Attendance\AttendanceController@overview']);
        Route::get('/summary', ['uses' => 'Attendance\AttendanceController@summary']);
        Route::get('/get-calendar-filter', ['uses' => 'Attendance\AttendanceController@getCalenderFilter']);
        Route::get('/view-calendar', ['uses' => 'Attendance\AttendanceController@viewCalendar']);


        //Attendance Request
        Route::get('/request', ['uses' => 'Attendance\AttendanceRequestController@index']);
        Route::get('/request/view/{id}', ['uses' => 'Attendance\AttendanceRequestController@view']);
        Route::post('/request/store', ['uses' => 'Attendance\AttendanceRequestController@store']);
        Route::post('/request/{id}/edit', ['uses' => 'Attendance\AttendanceRequestController@edit']);
        Route::post('/request/{id}/update/status', ['uses' => 'Attendance\AttendanceRequestController@updateStatus']);
        //
    });

    Route::group(['prefix' => 'claim'], function () {
        Route::get('/dropdown', ['uses' => 'Claim\ClaimController@getDropDown']);

        Route::get('/', ['uses' => 'Claim\ClaimController@index']);
        Route::post('/store', ['uses' => 'Claim\ClaimController@store']);
        Route::post('/attachment/store', ['uses' => 'Claim\ClaimController@storeAttachment']);

        Route::get('/view/{id}', ['uses' => 'Claim\ClaimController@view']);
        Route::put('/{id}/edit', ['uses' => 'Claim\ClaimController@edit']);

        Route::delete('/delete/{id}', ['uses' => 'Claim\ClaimController@destroy']);
    });

    Route::group(['prefix' => 'request'], function () {
        Route::post('/store', ['uses' => 'Request\RequestController@store']);
        Route::get('/dropdown', ['uses' => 'Request\RequestController@getDropDown']);

        Route::get('/', ['uses' => 'Request\RequestController@index']);
        Route::get('/view/{id}', ['uses' => 'Request\RequestController@view']);
        Route::put('/{id}/edit', ['uses' => 'Request\RequestController@edit']);
        Route::delete('/delete/{id}', ['uses' => 'Request\RequestController@destroy']);
    });

    Route::group(['prefix' => 'notice/'], function () {
        Route::get('/', ['uses' => 'Notice\NoticeController@index']);
    });

    Route::group(['prefix' => 'employee/'], function () {
        Route::get('list', ['uses' => 'Employee\EmployeeController@getEmployeeList']);
        Route::get('family-detail', ['uses' => 'Employee\EmployeeController@familyDetail']);
        Route::get('asset-detail', ['uses' => 'Employee\EmployeeController@assetDetail']);
        Route::get('emergency-detail', ['uses' => 'Employee\EmployeeController@emergencyDetail']);
        Route::get('benefit-detail', ['uses' => 'Employee\EmployeeController@benefitDetail']);
        Route::get('education-detail', ['uses' => 'Employee\EmployeeController@educationDetail']);
        Route::get('previous-job-detail', ['uses' => 'Employee\EmployeeController@previousJobDetail']);
        Route::get('bank-detail', ['uses' => 'Employee\EmployeeController@bankDetail']);
        Route::get('contract-detail', ['uses' => 'Employee\EmployeeController@contractDetail']);
        Route::get('document-detail', ['uses' => 'Employee\EmployeeController@documentDetail']);
        Route::get('medical-detail', ['uses' => 'Employee\EmployeeController@medicalDe-tail']);

        Route::get('get-alternative-employees', 'Employee\EmployeeController@getAlternativeEmployees');

        //dropdown
        Route::get('/dropdown', ['uses' => 'Employee\EmployeeController@getDropdown']);
        //

        //approval flow
        Route::get('/approval-flow/report', ['uses' => 'Employee\EmployeeController@approvalFlowReport']);
        Route::post('/approval-flow/{id}/update', ['uses' => 'Employee\EmployeeController@updateApprovalFlow']);
        //

        //employee
        Route::get('/active-list', ['uses' => 'Employee\EmployeeController@activeEmployeeList']);
        Route::post('/store', ['uses'=> 'Employee\EmployeeController@store']);
        Route::post('/{id}/update', ['uses'=> 'Employee\EmployeeController@update']);
        Route::get('/view/{id}', ['uses'=> 'Employee\EmployeeController@view']);
        Route::delete('/delete/{id}', ['uses'=> 'Employee\EmployeeController@destroy']);

        Route::get('/archive-list', ['uses' => 'Employee\EmployeeController@archiveEmployeeList']);
        Route::post('/update-status-to-archive/{id}', ['uses'=> 'Employee\EmployeeController@updateStatus']);
        Route::post('/update-status-to-active/{id}', ['uses'=> 'Employee\EmployeeController@updateStatusArchive']);

        Route::post('/{id}/reset-device', ['uses' => 'Employee\EmployeeController@resetDevice']);
        //


        //Career Mobility
        Route::get('/career-mobility/list', ['uses' => 'Employee\EmployeeController@careerMobility']);
        Route::post('/career-mobility/store', ['uses' => 'Employee\EmployeeController@storeCareerMobility']);
        Route::get('/career-mobility/report', ['uses' => 'Employee\EmployeeController@careerMobilityReport']);
        Route::delete('/career-mobility/delete/{id}', ['uses' => 'Employee\EmployeeController@destroyCareerMobility']);
        //


    });

    //Substitute Leave
    Route::group(['prefix' => 'substitute-leave'], function () {
        Route::get('dropdown', ['uses' => 'Leave\SubstituteLeaveController@dropdown']);
        Route::get('history', ['uses' => 'Leave\SubstituteLeaveController@index']);
        Route::get('team-history', ['uses' => 'Leave\SubstituteLeaveController@getTeamLeaves']);


        Route::get('remaining', ['uses' => 'Leave\SubstituteLeaveController@remainingLeave']);
        Route::get('view/{id}', ['uses' => 'Leave\SubstituteLeaveController@show']);
        Route::post('{id}/update-status', ['uses' => 'Leave\SubstituteLeaveController@updateStatus']);

        Route::post('store', ['uses' => 'Leave\SubstituteLeaveController@store']);
        Route::delete('/delete/{id}', ['uses' => 'Leave\SubstituteLeaveController@destroy']);
    });
    //

    Route::group(['prefix' => 'work-log/'], function () {
        Route::get('/dropdown', ['uses' => 'WorkLog\WorkLogController@getDropDown']);

        Route::get('/', ['uses' => 'WorkLog\WorkLogController@index']);
        Route::post('/store', ['uses' => 'WorkLog\WorkLogController@store']);
        Route::get('/view/{id}', ['uses' => 'WorkLog\WorkLogController@view']);
        Route::post('/{id}/edit', ['uses' => 'WorkLog\WorkLogController@edit']);
        Route::delete('/delete/{id}', ['uses' => 'WorkLog\WorkLogController@destroy']);
    });

    Route::group(['prefix' => 'notification/'], function () {
        Route::get('/', ['uses' => 'OneSignalController@getAllNotifications']);
        Route::get('/list', ['uses' => 'NotificationController@notifications']);
        Route::post('/store', ['uses' => 'OneSignalController@storeNotification']);


        Route::post('/register', ['uses' => 'OneSignalController@registerDevice']);
        Route::get('/{playerId}/update-status', ['uses' => 'OneSignalController@updateNotificationStatus']);
    });

    Route::group(['prefix' => 'poll/'], function () {
        Route::get('/list', ['uses' => 'PollController@index']);
        Route::post('/cast-vote', ['uses' => 'PollController@castVote']);
    });

    Route::group(['prefix' => 'holiday/'], function () {
        Route::get('/', ['uses' => 'HolidayController@index']);
    });

    Route::group(['prefix' => 'grievance/'], function () {
        Route::get('/', ['uses' => 'GrievanceController@index']);
        Route::get('/dropdown', ['uses' => 'GrievanceController@dropdown']);
        Route::post('/store', ['uses' => 'GrievanceController@store']);
    });

    Route::group(['prefix' => 'payroll/'], function () {
        Route::get('/get-tds-report', ['uses' => 'Payroll\PayrollController@getTdsReport']);
        Route::get('/get-pay-slip', ['uses' => 'Payroll\PayrollController@paySlip']);
        Route::get('/get-leave-year', ['uses' => 'Payroll\PayrollController@getLeaveYear']);
    });

    Route::group(['prefix' => 'department-member/'], function () {
        Route::get('/', ['uses' => 'Department\DepartmentController@index']);
    });

    Route::group(['prefix' => 'event/'], function () {
        Route::get('/', ['uses' => 'Event\EventController@index']);
    });

    Route::group(['prefix' => 'pending-request/'], function () {
        Route::get('/', ['uses' => 'PendingRequest\PendingRequestController@index']);
    });

    //Organization
    Route::group(['prefix' => 'organization/'], function () {
        Route::get('/list', ['uses' => 'OrganizationLatest\OrganizationController@index']);
        Route::post('/store', ['uses' => 'OrganizationLatest\OrganizationController@store']);
        Route::post('/{id}/update', ['uses' => 'OrganizationLatest\OrganizationController@update']);
        Route::delete('/delete/{id}', ['uses' => 'OrganizationLatest\OrganizationController@destroy']);

    });
    //

    //Branch
    Route::group(['prefix' => 'branch/'], function () {
        Route::get('/list', ['uses' => 'BranchController@index']);
        Route::get('/dropdown-list', ['uses' => 'BranchController@getDropdown']);
        Route::post('/store', ['uses' => 'BranchController@store']);
        Route::post('/{id}/update', ['uses' => 'BranchController@update']);
        Route::delete('/delete/{id}', ['uses' => 'BranchController@destroy']);
    });
    //

    //Organizational Structure
    Route::group(['prefix' => 'structure/'], function () {
        Route::get('/list', ['uses' => 'StructureController@index']);
        Route::get('/dropdown-list', ['uses' => 'StructureController@getDropdown']);
        Route::post('/store', ['uses' => 'StructureController@store']);
        Route::post('/{id}/update', ['uses' => 'StructureController@update']);
    });
    //

    //Setting
    Route::group(['prefix' => 'setting/'], function () {
        Route::get('/create', ['uses' => 'OrganizationLatest\OrganizationController@createSetting']);
        Route::post('/store', ['uses' => 'OrganizationLatest\OrganizationController@storeSetting']);
        Route::post('/{id}/update', ['uses' => 'OrganizationLatest\OrganizationController@updateSetting']);
    });
    //

     //Business Trip
     Route::group(['prefix' => 'business-trip/'], function () {
        Route::get('/get-dropdown', ['uses' => 'BusinessTrip\BusinessTripController@getDropdown']);

        Route::get('/list', ['uses' => 'BusinessTrip\BusinessTripController@index']);
        Route::post('/store', ['uses' => 'BusinessTrip\BusinessTripController@store']);
        Route::get('/view/{id}', ['uses' => 'BusinessTrip\BusinessTripController@view']);
        Route::post('/{id}/update', ['uses' => 'BusinessTrip\BusinessTripController@update']);
        Route::post('{id}/update-status', ['uses' => 'BusinessTrip\BusinessTripController@updateStatus']);
        Route::post('{id}/update-claim-status', ['uses' => 'BusinessTrip\BusinessTripController@updateClaimStatus']);
        Route::get('/team-requests', ['uses' => 'BusinessTrip\BusinessTripController@teamRequests']);

    });
    //

    //Overtime Request
    Route::group(['prefix' => 'overtime-request/'], function () {
        Route::get('/get-dropdown', ['uses' => 'OvertimeRequest\OvertimeRequestController@getDropdown']);

        Route::get('/list', ['uses' => 'OvertimeRequest\OvertimeRequestController@index']);
        Route::post('/store', ['uses' => 'OvertimeRequest\OvertimeRequestController@store']);
        Route::get('/view/{id}', ['uses' => 'OvertimeRequest\OvertimeRequestController@view']);
        Route::post('/{id}/update', ['uses' => 'OvertimeRequest\OvertimeRequestController@update']);
        Route::post('{id}/update-status', ['uses' => 'OvertimeRequest\OvertimeRequestController@updateStatus']);
        Route::post('{id}/update-claim-status', ['uses' => 'OvertimeRequest\OvertimeRequestController@updateClaimStatus']);
        Route::get('/team-requests', ['uses' => 'OvertimeRequest\OvertimeRequestController@teamRequests']);
    });
    //

    // App Module
    Route::group(['prefix' => 'module/'], function () {
        Route::get('/list', ['uses' => 'ModuleController@index']);
    });
});
