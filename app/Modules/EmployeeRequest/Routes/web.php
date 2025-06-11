<?php

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

	Route::get('employeeRequest', 'EmployeeRequestController@index')->name('employeerequest.index');

	Route::get('employeeRequest/find/{id}', 'EmployeeRequestController@findrequestbytype')->name('employeeRequest.find');
	Route::get('employeeRequest/create', 'EmployeeRequestController@create')->name('employeeRequest.create');
	Route::post('employeeRequest', 'EmployeeRequestController@store')->name('employeeRequest.store');
	Route::get('employeeRequest/edit/{id}', 'EmployeeRequestController@edit')->name('employeeRequest.edit');
	Route::patch('employeeRequest/update/{id}', 'EmployeeRequestController@update')->name('employeeRequest.update');
	Route::get('employeeRequest/delete/{id}', 'EmployeeRequestController@destroy')->name('employeeRequest.delete');
	Route::get('employeeRequest/view', 'EmployeeRequestController@view')->name('employeeRequest.view');
	Route::get('employeeRequest/stat', 'EmployeeRequestController@statistics')->name('employeeRequest.stat');

	Route::get('employeeRequestType', 'EmployeeRequestTypeController@index')->name('employeeRequestType.index');
	Route::get('employeeRequestType/create', 'EmployeeRequestTypeController@create')->name('employeeRequestType.create');
	Route::post('employeeRequestType', 'EmployeeRequestTypeController@store')->name('employeeRequestType.store');
	Route::get('employeeRequestType/edit/{id}', 'EmployeeRequestTypeController@edit')->name('employeeRequestType.edit');
	Route::get('employeeRequestType/delete/{id}', 'EmployeeRequestTypeController@destroy')->name('employeeRequestType.delete');
	Route::patch('employeeRequestType/update/{id}', 'EmployeeRequestTypeController@update')->name('employeeRequestType.update');
});


Route::group(['prefix' => 'employee', 'middleware' => ['auth', 'permission']], function () {
	//claim request
	Route::get('claim-request', 'ClaimRequestController@index')->name('claimRequest.index');
	Route::get('claim-request/attendance-adjust', 'ClaimRequestController@attendanceRequest')->name('claimRequest.attendance-adjust');

	//pre-overtime request
	Route::get('claim-request/pre-overtime', 'PreovertimeRequestController@index')->name('claimRequest.preovertime');
	Route::get('pre-overtime/create', 'PreovertimeRequestController@create')->name('preOvertimeRequest.create');
	Route::post('pre-overtime/store', 'PreovertimeRequestController@store')->name('preOvertimeRequest.store');
	Route::get('pre-overtime/edit/{id}', 'PreovertimeRequestController@edit')->name('preOvertimeRequest.edit');
	Route::patch('pre-overtime/update/{id}', 'PreovertimeRequestController@update')->name('preOvertimeRequest.update');
	Route::get('pre-overtime/delete/{id}', 'PreovertimeRequestController@destroy')->name('preOvertimeRequest.delete');

	//team request mgmt for first and second approval
	Route::get('team/claim-request', 'ClaimRequestController@teamIndex')->name('team-claimRequest.index');
	Route::post('team/claim-request/{id}/update', 'ClaimRequestController@updateRequestStatus')->name('team-claimRequest.update');

	//team preovertime request for first and second approval
	Route::get('team/preovertime-request', 'PreovertimeRequestController@teamIndex')->name('team-preOvertimeRequest.index');
	Route::post('team/preovertime-request/{id}/update', 'PreovertimeRequestController@updateRequestStatus')->name('team-preOvertimeRequest.update');
});
