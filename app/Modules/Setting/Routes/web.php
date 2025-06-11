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

    /*
    |--------------------------------------------------------------------------
    | setting CRUD ROUTE
    |--------------------------------------------------------------------------
    */
    Route::get('setting', ['as' => 'setting.create', 'uses' => 'SettingController@create']);

    Route::get('setting', ['as' => 'setting.index', 'uses' => 'SettingController@index']);
    Route::get('setting/create', ['as' => 'setting.create', 'uses' => 'SettingController@create']);
    Route::post('setting/store', ['as' => 'setting.store', 'uses' => 'SettingController@store']);
    Route::get('setting/edit/{id}', ['as' => 'setting.edit', 'uses' => 'SettingController@edit'])->where('id', '[0-9]+');
    Route::put('setting/update/{id}', ['as' => 'setting.update', 'uses' => 'SettingController@update'])->where('id', '[0-9]+');


    Route::get('allowance/create', ['as' => 'allowance.create', 'uses' => 'TravelAllowanceSetupController@create']);
    Route::post('allowance/store', ['as' => 'allowance.store', 'uses' => 'TravelAllowanceSetupController@store']);

    Route::get('gross-salary/create', ['as' => 'gross-salary.create', 'uses' => 'GrossSalarySetupController@create']);
    Route::post('gross-salary/store', ['as' => 'gross-salary.store', 'uses' => 'GrossSalarySetupController@store']);

    // Route::get('branch-setup/create',['as'=>'branch-setup.create','uses'=>'BranchSetupController@create']);
    // Route::post('branch-setup/store',['as'=>'branch-setup.store','uses'=>'BranchSetupController@store']);


    Route::get('device-management/index', ['as' => 'deviceManagement.index', 'uses' => 'DeviceManagementController@index']);
    Route::get('device-management/create', ['as' => 'deviceManagement.create', 'uses' => 'DeviceManagementController@create']);
    Route::post('device-management/store', ['as' => 'deviceManagement.store', 'uses' => 'DeviceManagementController@store']);
    Route::post('device-management/store-ajax', ['as' => 'deviceManagement.storeAjax', 'uses' => 'DeviceManagementController@storeAjax']);
    Route::get('device-management/edit/{id}', ['as' => 'deviceManagement.edit', 'uses' => 'DeviceManagementController@edit'])->where('id', '[0-9]+');
    Route::put('device-management/update/{id}', ['as' => 'deviceManagement.update', 'uses' => 'DeviceManagementController@update'])->where('id', '[0-9]+');
    Route::get('device-management/delete/{id}', ['as' => 'deviceManagement.delete', 'uses' => 'DeviceManagementController@destroy'])->where('id', '[0-9]+');


    Route::get('leave-deduction-setups', ['as' => 'leaveDeductionSetup.index', 'uses' => 'LeaveDeductionSetupController@index']);
    Route::get('leave-deduction-setup/create', ['as' => 'leaveDeductionSetup.create', 'uses' => 'LeaveDeductionSetupController@create']);
    Route::post('leave-deduction-setup/store', ['as' => 'leaveDeductionSetup.store', 'uses' => 'LeaveDeductionSetupController@store']);
    Route::get('leave-deduction-setup/{id}/edit', ['as' => 'leaveDeductionSetup.edit', 'uses' => 'LeaveDeductionSetupController@edit']);
    Route::put('leave-deduction-setup/{id}/update', ['as' => 'leaveDeductionSetup.update', 'uses' => 'LeaveDeductionSetupController@update']);
    Route::get('leave-deduction-setup/{id}/delete', ['as' => 'leaveDeductionSetup.delete', 'uses' => 'LeaveDeductionSetupController@destroy']);

    //Hierarchy Setup
    Route::get('hierarchies', ['as' => 'hierarchySetup.index', 'uses' => 'HierarchySetupController@index']);
    Route::get('hierarchy/create', ['as' => 'hierarchySetup.create', 'uses' => 'HierarchySetupController@create']);
    Route::post('hierarchy/store', ['as' => 'hierarchySetup.store', 'uses' => 'HierarchySetupController@store']);
    Route::get('hierarchy/{id}/edit', ['as' => 'hierarchySetup.edit', 'uses' => 'HierarchySetupController@edit']);
    Route::put('hierarchy/{id}/update', ['as' => 'hierarchySetup.update', 'uses' => 'HierarchySetupController@update']);
    Route::get('hierarchy/{id}/delete', ['as' => 'hierarchySetup.delete', 'uses' => 'HierarchySetupController@destroy']);
    Route::get('hierarchy/{id}/view', ['as' => 'hierarchySetup.view', 'uses' => 'HierarchySetupController@show']);
    //

    // MRF approval flow routes
    Route::get('mrf-approval-flows', ['as' => 'mrfApprovalFlow.index', 'uses' => 'MrfApprovalFlowController@index']);
    Route::get('mrf-approval-flow/create', ['as' => 'mrfApprovalFlow.create', 'uses' => 'MrfApprovalFlowController@create']);
    Route::post('mrf-approval-flow/store', ['as' => 'mrfApprovalFlow.store', 'uses' => 'MrfApprovalFlowController@store']);
    Route::get('mrf-approval-flow/{id}/edit', ['as' => 'mrfApprovalFlow.edit', 'uses' => 'MrfApprovalFlowController@edit']);
    Route::put('mrf-approval-flow/{id}/update', ['as' => 'mrfApprovalFlow.update', 'uses' => 'MrfApprovalFlowController@update']);
    Route::get('mrf-approval-flow/{id}/delete', ['as' => 'mrfApprovalFlow.delete', 'uses' => 'MrfApprovalFlowController@destroy']);


    // Route::get('festival-allowance-setup', ['as' => 'festivalAllowance.index', 'uses' => 'FestivalAllowanceController@index']);
    // Route::post('festival-allowance-setup/store', ['as' => 'festivalAllowance.store', 'uses' => 'FestivalAllowanceController@store']);

    // Route::get('festival-allowance', ['as' => 'festivalAllowance.create', 'uses' => 'FestivalAllowanceController@create']);

    Route::get('festival-allowance/create', ['as' => 'festivalAllowance.create', 'uses' => 'FestivalAllowanceController@create']);
    Route::post('festival-allowance/store', ['as' => 'festivalAllowance.store', 'uses' => 'FestivalAllowanceController@store']);
    Route::get('festival-allowance/edit/{id}', ['as' => 'festivalAllowance.edit', 'uses' => 'FestivalAllowanceController@edit'])->where('id', '[0-9]+');
    Route::put('festival-allowance/update/{id}', ['as' => 'festivalAllowance.update', 'uses' => 'FestivalAllowanceController@update'])->where('id', '[0-9]+');

    Route::get('module', ['as' => 'module.index', 'uses' => 'ModuleController@index']);
    Route::post('module/update', ['as' => 'module.update', 'uses' => 'ModuleController@update']);

    //Enable disable module on app
    Route::get('app-module', ['as' => 'module.apiModuleSetup', 'uses' => 'ModuleController@appModule']);
    Route::post('app-module/update', ['as' => 'module.apiModuleUpdate', 'uses' => 'ModuleController@update']);

    //Payroll Setting
    Route::get('payroll-setting/create', ['as' => 'setting.payrollSetting', 'uses' => 'SettingController@payrollSetting']);
    Route::post('payroll-setting/store', ['as' => 'payrollSetting.store', 'uses' => 'SettingController@storePayrollSetting']);
    Route::put('payroll-setting/update', ['as' => 'payrollSetting.update', 'uses' => 'SettingController@updatePayrollSetting']);

    //Ot Rate Setup
    Route::get('ot-rate-setup/index', ['as' => 'otRateSetup.index', 'uses' => 'OTRateSetupController@index']);
    Route::post('ot-rate-setup/store', ['as' => 'otRateSetup.store', 'uses' => 'OTRateSetupController@store']);
    Route::put('ot-rate-setup/update', ['as' => 'otRateSetup.update', 'uses' => 'OTRateSetupController@update']);
    // Route::get('ot-rate-setup/create', ['as' => 'setting.payrollSetting', 'uses' => 'SettingController@payrollSetting']);
    // setting.otRateSetup

    //ajax
    Route::get('payroll-setting/get-calendar-type', ['as' => 'payrollSetting.getCalenderType', 'uses' => 'SettingController@getCalenderType']);

    // darbandi
    Route::get('darbandi/index', ['as' => 'darbandi.index', 'uses' => 'DarbandiController@index']);
    Route::get('darbandi/create', ['as' => 'darbandi.create', 'uses' => 'DarbandiController@create']);
    Route::post('darbandi/store', ['as' => 'darbandi.store', 'uses' => 'DarbandiController@store']);
    // Route::get('/darbandi/getEmployee', ['as' => 'darbandi.getEmployee', 'uses' => 'DarbandiController@getEmployee']);
    Route::get('darbandi/edit/{id}', ['as' => 'darbandi.edit', 'uses' => 'DarbandiController@edit'])->where('id', '[0-9]+');
    Route::put('darbandi/update/{id}', ['as' => 'darbandi.update', 'uses' => 'DarbandiController@update'])->where('id', '[0-9]+');
    Route::get('darbandi/delete/{id}', ['as' => 'darbandi.delete', 'uses' => 'DarbandiController@destroy'])->where('id', '[0-9]+');


    Route::post('darbandi/upload', ['as' => 'bulkupload.uploadDarbandis', 'uses' => 'DarbandiController@uploadDarbandi']);

    //Email Setting
    Route::get('setting/view-email-setup', ['as' => 'setting.viewEmailSetup', 'uses' => 'SettingController@viewEmailSetup']);
    Route::post('setting/store-email-setup', ['as' => 'setting.storeEmailSetup', 'uses' => 'SettingController@storeEmailSetup']);
    Route::post('setting/store-email-setup-ajax', ['as' => 'setting.storeEmailSetupAjax', 'uses' => 'SettingController@storeEmailSetupAjax']);

    // designation
    Route::get('designation/index', ['as' => 'designation.index', 'uses' => 'DesignationController@index']);
    Route::get('designation/create', ['as' => 'designation.create', 'uses' => 'DesignationController@create']);
    Route::post('designation/store', ['as' => 'designation.store', 'uses' => 'DesignationController@store']);
    Route::get('designation/edit/{id}', ['as' => 'designation.edit', 'uses' => 'DesignationController@edit'])->where('id', '[0-9]+');
    Route::put('designation/update/{id}', ['as' => 'designation.update', 'uses' => 'DesignationController@update'])->where('id', '[0-9]+');
    Route::get('designation/delete/{id}', ['as' => 'designation.delete', 'uses' => 'DesignationController@destroy'])->where('id', '[0-9]+');

    // level
    Route::get('level/index', ['as' => 'level.index', 'uses' => 'LevelController@index']);
    Route::get('level/create', ['as' => 'level.create', 'uses' => 'LevelController@create']);
    Route::post('level/store', ['as' => 'level.store', 'uses' => 'LevelController@store']);
    Route::get('level/edit/{id}', ['as' => 'level.edit', 'uses' => 'LevelController@edit'])->where('id', '[0-9]+');
    Route::put('level/update/{id}', ['as' => 'level.update', 'uses' => 'LevelController@update'])->where('id', '[0-9]+');
    Route::get('level/delete/{id}', ['as' => 'level.delete', 'uses' => 'LevelController@destroy'])->where('id', '[0-9]+');

    //Function
    Route::get('function/index', ['as' => 'function.index', 'uses' => 'FunctionController@index']);
    Route::get('function/create', ['as' => 'function.create', 'uses' => 'FunctionController@create']);
    Route::post('function/store', ['as' => 'function.store', 'uses' => 'FunctionController@store']);
    Route::get('function/edit/{id}', ['as' => 'function.edit', 'uses' => 'FunctionController@edit'])->where('id', '[0-9]+');
    Route::put('function/update/{id}', ['as' => 'function.update', 'uses' => 'FunctionController@update'])->where('id', '[0-9]+');
    Route::get('function/delete/{id}', ['as' => 'function.delete', 'uses' => 'FunctionController@destroy'])->where('id', '[0-9]+');

    // department
    Route::get('department/index', ['as' => 'department.index', 'uses' => 'DepartmentController@index']);
    Route::get('department/create', ['as' => 'department.create', 'uses' => 'DepartmentController@create']);
    Route::post('department/store', ['as' => 'department.store', 'uses' => 'DepartmentController@store']);
    Route::get('department/edit/{id}', ['as' => 'department.edit', 'uses' => 'DepartmentController@edit'])->where('id', '[0-9]+');
    Route::put('department/update/{id}', ['as' => 'department.update', 'uses' => 'DepartmentController@update'])->where('id', '[0-9]+');
    Route::get('department/delete/{id}', ['as' => 'department.delete', 'uses' => 'DepartmentController@destroy'])->where('id', '[0-9]+');

    // Leave Encashment Setup
    Route::get('leave-encashment-setup/index', ['as' => 'leaveEncashmentSetup.index', 'uses' => 'LeaveEncashmentSetupController@index']);
    Route::get('leave-encashment-setup/create', ['as' => 'leaveEncashmentSetup.create', 'uses' => 'LeaveEncashmentSetupController@create']);
    Route::post('leave-encashment-setup/store', ['as' => 'leaveEncashmentSetup.store', 'uses' => 'LeaveEncashmentSetupController@store']);
    Route::get('leave-encashment-setup/edit/{id}', ['as' => 'leaveEncashmentSetup.edit', 'uses' => 'LeaveEncashmentSetupController@edit'])->where('id', '[0-9]+');
    Route::put('leave-encashment-setup/update/{id}', ['as' => 'leaveEncashmentSetup.update', 'uses' => 'LeaveEncashmentSetupController@update'])->where('id', '[0-9]+');
    Route::get('leave-encashment-setup/delete/{id}', ['as' => 'leaveEncashmentSetup.delete', 'uses' => 'LeaveEncashmentSetupController@destroy'])->where('id', '[0-9]+');

    // Force Leave Setup
    Route::get('force-leave-setup/index', ['as' => 'forceLeaveSetup.index', 'uses' => 'ForceLeaveSetupController@index']);
    Route::get('force-leave-setup/create', ['as' => 'forceLeaveSetup.create', 'uses' => 'ForceLeaveSetupController@create']);
    Route::post('force-leave-setup/store', ['as' => 'forceLeaveSetup.store', 'uses' => 'ForceLeaveSetupController@store']);
    Route::get('force-leave-setup/edit/{id}', ['as' => 'forceLeaveSetup.edit', 'uses' => 'ForceLeaveSetupController@edit'])->where('id', '[0-9]+');
    Route::put('force-leave-setup/update/{id}', ['as' => 'forceLeaveSetup.update', 'uses' => 'ForceLeaveSetupController@update'])->where('id', '[0-9]+');
    Route::get('force-leave-setup/delete/{id}', ['as' => 'forceLeaveSetup.delete', 'uses' => 'ForceLeaveSetupController@destroy'])->where('id', '[0-9]+');

    //Province Setting
    Route::get('province-setup/index', ['as' => 'province-setup.index', 'uses' => 'ProvinceSetupController@index']);
    Route::get('province-setup/create', ['as' => 'province-setup.create', 'uses' => 'ProvinceSetupController@create']);
    Route::post('province-setup/store', ['as' => 'province-setup.store', 'uses' => 'ProvinceSetupController@store']);
    Route::get('province-setup/edit/{id}', ['as' => 'province-setup.edit', 'uses' => 'ProvinceSetupController@edit'])->where('id', '[0-9]+');
    Route::put('province-setup/update/{id}', ['as' => 'province-setup.update', 'uses' => 'ProvinceSetupController@update'])->where('id', '[0-9]+');
    Route::get('province-setup/delete/{id}', ['as' => 'province-setup.delete', 'uses' => 'ProvinceSetupController@destroy'])->where('id', '[0-9]+');

    /**
     * Activities log
     */
    Route::get('activity-log', ['as' => 'activitiesLog.index', 'uses' => 'ActivityLogController@index']);
});
