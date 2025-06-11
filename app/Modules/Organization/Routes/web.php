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

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'permission']], function () {
    Route::get('organizations', ['as' => 'organization.index', 'uses' => 'OrganizationController@index']);
    Route::get('organization/create', ['as' => 'organization.create', 'uses' => 'OrganizationController@create']);
    Route::post('organization/store', ['as' => 'organization.store', 'uses' => 'OrganizationController@store']);
    Route::get('organization/{id}/edit', ['as' => 'organization.edit', 'uses' => 'OrganizationController@edit']);
    Route::put('organization/{id}/update', ['as' => 'organization.update', 'uses' => 'OrganizationController@update']);
    Route::get('organization/{id}/delete', ['as' => 'organization.delete', 'uses' => 'OrganizationController@destroy']);

    Route::get('organization/overview', ['as' => 'organization.overview', 'uses' => 'OrganizationController@overview']);
    Route::get('organization/code-of-conduct', ['as' => 'organization.codeOfConduct', 'uses' => 'OrganizationController@codeOfConduct']);

    Route::get('organization/master-report', ['as' => 'organization.masterReport', 'uses' => 'OrganizationController@masterReport']);
    Route::get('organization/master-report/getLeaveReport', ['as' => 'organization.getLeaveReport', 'uses' => 'OrganizationController@getLeaveReport']);

    Route::get('organization/darbandis', ['as' => 'organization.darbandiList', 'uses' => 'OrganizationController@darbandis']);
    Route::get('organization/darbandi-report/{id}', ['as' => 'organization.darbandiReport', 'uses' => 'OrganizationController@darbandiReport']);
});

Route::group(['middleware' => ['auth']], function () {
    // ajax routes
    Route::get('admin/organization/get-employees', 'OrganizationController@getEmployees');
    Route::get('admin/organization/get-confirmed-employees', 'OrganizationController@getConfirmedEmployees');
    Route::get('admin/organization/get-permanent-employees', 'OrganizationController@getPermanentEmployees');

    Route::get('admin/organization/get-shift-group-exists-employees', 'OrganizationController@getShiftGroupEmployees');

    Route::get('admin/organization/get-multiple-employees', 'OrganizationController@getMultipleEmployees');
    Route::get('admin/organization/get-leave-types', 'OrganizationController@getLeaveTypes');
    Route::get('admin/organization/get-unpaid-leave-types', 'OrganizationController@getUnpaidLeaveTypes');


    Route::get('admin/organization/get-branches', 'OrganizationController@getBranches');
    Route::get('admin/organization/get-departments', 'OrganizationController@getDepartments');
    Route::get('admin/organization/get-designations', 'OrganizationController@getDesignations');
    Route::get('admin/organization/get-levels', 'OrganizationController@getLevels');

    Route::get('admin/organization/get-levels-from-designation', 'OrganizationController@getLevelsFromDesignation');

    Route::get('admin/organization/get-users-except-employee-role', 'OrganizationController@getUsersExceptEmployeeRole');

    Route::get('admin/organization/get-multiple-employees-search', 'OrganizationController@getMultipleEmployeesForFilter');

    Route::get('admin/organization/get-labour', 'OrganizationController@getLabour');

    Route::get('admin/organization/get-income-types', ['as' => 'organization.getIncomeTypes', 'uses' => 'OrganizationController@getIncomeTypes']);
});
