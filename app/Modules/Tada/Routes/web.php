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


Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'permission', 'XssSanitizer']], function () {
    Route::get('billType', 'BillTypeController@index')->name('billType.index');
    Route::post('billType', 'BillTypeController@store')->name('billType.store');
    Route::get('billType/create', 'BillTypeController@create')->name('billType.create');
    Route::get('billType/edit/{id}', 'BillTypeController@edit')->name('billType.edit');
    Route::patch('billType/update/{id}', 'BillTypeController@update')->name('billType.update');
    Route::get('billType/delete/{id}', 'BillTypeController@destroy')->name('billType.delete');

    Route::get('allowanceType', 'AllowanceTypeController@index')->name('allowanceType.index');
    Route::post('allowanceType', 'AllowanceTypeController@store')->name('allowanceType.store');
    Route::get('allowanceType/create', 'AllowanceTypeController@create')->name('allowanceType.create');
    Route::get('allowanceType/edit/{id}', 'AllowanceTypeController@edit')->name('allowanceType.edit');
    Route::patch('allowanceType/update/{id}', 'AllowanceTypeController@update')->name('allowanceType.update');
    Route::get('allowanceType/delete/{id}', 'AllowanceTypeController@destroy')->name('allowanceType.delete');

    Route::get('tadaBill', 'TadaBillController@index')->name('tadaBill.index');
    Route::post('tadaBill', 'TadaBillController@store')->name('tadaBill.store');
    Route::get('tadaBill/create', 'TadaBillController@create')->name('tadaBill.create');
    Route::get('tadaBill/edit/{id}', 'TadaBillController@edit')->name('tadaBill.edit');
    Route::patch('tadaBill/update/{id}', 'TadaBillController@update')->name('tadaBill.update');
    Route::get('tadaBill/delete/{id}', 'TadaBillController@destroy')->name('tadaBill.delete');


    // Request From
    Route::get('tada/request', 'TadaRequestController@index')->name('tadaRequest.index');
    Route::post('tada/request', 'TadaRequestController@store')->name('tadaRequest.store');
    Route::get('tada/request/create', 'TadaRequestController@create')->name('tadaRequest.create');
    Route::get('tada/request/show/{id}', 'TadaRequestController@show')->name('tadaRequest.show');
    Route::get('tada/request/edit/{id}', 'TadaRequestController@edit')->name('tadaRequest.edit');
    Route::patch('tada/request/update/{id}', 'TadaRequestController@update')->name('tadaRequest.update');
    Route::get('tada/request/delete/{id}', 'TadaRequestController@destroy')->name('tadaRequest.delete');
    Route::post('tada/request/update-status/{id}', 'TadaRequestController@updateStatus')->name('tadaRequest.updateStatus');
    Route::get('tada/request/update-status/{id}/form', 'TadaRequestController@updateStatusForm')->name('tadaRequest.updateStatusForm');
    Route::post('tada/request/repeater-form', 'TadaRequestController@getRepeaterForm')->name('tadaRequest.getRepeaterForm');
    Route::get('tada/team-request', 'TadaRequestController@showTeamRequest')->name('tadaRequest.showTeamRequest');
    Route::get('tada/team-request/download-pdf/{id}', 'TadaRequestController@downloadPdfRequest')->name('tadaRequest.downloadPdfRequest');

    /** Transportation Type */
    Route::get('transportation/index', ['as' => 'transportation.view', 'uses' => 'TransportationTypeController@index']);
    Route::post('transportation/store', ['as' => 'transportation.storeUpdate', 'uses' => 'TransportationTypeController@store']);
    Route::get('transportation/delete/{id}', ['as' => 'transportation.delete', 'uses' => 'TransportationTypeController@delete']);


    /**
     * expenses Head
     */

    Route::get('expensehead/index', ['as' => 'expensehead.index', 'uses' => 'ExpenseHeadController@index']);
    Route::post('expensehead/store', ['as' => 'expensehead.storeUpdate', 'uses' => 'ExpenseHeadController@store']);
    Route::get('expensehead/delete/{id}', ['as' => 'expensehead.delete', 'uses' => 'ExpenseHeadController@delete']);

      /**
     * ER type
     */

    Route::get('ertype/index', ['as' => 'ertype.index', 'uses' => 'ErTypeController@index']);
    Route::post('ertype/store', ['as' => 'ertype.storeUpdate', 'uses' => 'ErTypeController@store']);
    Route::get('ertype/delete/{id}', ['as' => 'ertype.delete', 'uses' => 'ErTypeController@delete']);


    Route::get('tadaType', 'TadaTypeController@index')->name('tadaType.index');
    Route::post('tadaType', 'TadaTypeController@store')->name('tadaType.store');
    Route::get('tadaType/create', 'TadaTypeController@create')->name('tadaType.create');
    Route::get('tadaType/edit/{id}', 'TadaTypeController@edit')->name('tadaType.edit');
    Route::patch('tadaType/update/{id}', 'TadaTypeController@update')->name('tadaType.update');
    Route::get('tadaType/delete/{id}', 'TadaTypeController@destroy')->name('tadaType.delete');

    Route::get('tada', 'TadaController@index')->name('tada.index');
    // Route::get('tada', 'TadaController@filter')->name('tada.filter');
    Route::post('tada', 'TadaController@store')->name('tada.store');
    Route::get('tada/create', 'TadaController@create')->name('tada.create');
    Route::get('tada/edit/{id}', 'TadaController@edit')->name('tada.edit');
    Route::patch('tada/update/{id}', 'TadaController@update')->name('tada.update');
    Route::get('tada/delete/{id}', 'TadaController@destroy')->name('tada.delete');
    Route::get('tada/show/{id}', 'TadaController@show')->name('tada.show');
    Route::get('tada/team-claim', 'TadaController@showTeamClaim')->name('tada.showTeamClaim');
    Route::get('tada/download-pdf/{id}', 'TadaController@downloadPdfClaim')->name('tada.downloadPdfClaim');



    Route::get('tada/bill-image/delete/{id}', 'TadaController@deleteBillImage')->name('tada.deleteBillImage');
    Route::post('tada/update-status/{id}', 'TadaController@updateStatus')->name('tada.updateStatus');
    Route::get('tada/update-status/{id}/form', 'TadaController@updateStatusForm')->name('tada.updateStatusForm');
    Route::post('tada/repeater-form', 'TadaController@getRepeaterForm')->name('tada.getRepeaterForm');
});

Route::post('admin/tada/add-more-sub-type', 'TadaTypeController@addMoreSubType')->name('tadaType.addMoreSubType');
Route::post('admin/tada/sub-type-list', 'TadaTypeController@getSubTypeList')->name('tadaType.getSubTypeList');



Route::group(['prefix' => 'employee', 'middleware' => ['auth', 'permission', 'XssSanitizer']], function () {

    Route::get('tada', 'EmployeeTadaController@index')->name('employee-tada.index');
    Route::post('tada', 'EmployeeTadaController@store')->name('employee-tada.store');
    Route::get('tada/create', 'EmployeeTadaController@create')->name('employee-tada.create');
    Route::get('tada/edit/{id}', 'EmployeeTadaController@edit')->name('employee-tada.edit');
    Route::patch('tada/update/{id}', 'EmployeeTadaController@update')->name('employee-tada.update');
    Route::get('tada/delete/{id}', 'EmployeeTadaController@destroy')->name('employee-tada.delete');
    Route::get('tada/show/{id}', 'EmployeeTadaController@show')->name('employee-tada.show');

    Route::get('tada/bill-image/delete/{id}', 'EmployeeTadaController@deleteBillImage')->name('employee-tada.deleteBillImage');

    //team tada request for first and second approval
    Route::get('team/tada-request', 'EmployeeTadaController@teamIndex')->name('team-tadaRequest.index');
    Route::post('team/tada-request/{id}/update', 'EmployeeTadaController@updateRequestStatus')->name('team-tadaRequest.update');


    //Employee Claim Routes
    Route::get('tada/claim', 'EmployeeTadaClaimController@index')->name('employeetadaClaim.index');
    Route::post('tada/claim', 'EmployeeTadaClaimController@store')->name('employeetadaClaim.store');
    Route::get('tada/claim/create', 'EmployeeTadaClaimController@create')->name('employeetadaClaim.create');
    Route::get('tada/claim/show/{id}', 'EmployeeTadaClaimController@show')->name('employeetadaClaim.show');
    Route::get('tada/claim/edit/{id}', 'EmployeeTadaClaimController@edit')->name('employeetadaClaim.edit');
    Route::patch('tada/claim/update/{id}', 'EmployeeTadaClaimController@update')->name('employeetadaClaim.update');
    Route::get('tada/claim/delete/{id}', 'EmployeeTadaClaimController@destroy')->name('employeetadaClaim.delete');
    Route::post('tada/claim/update-status/{id}', 'EmployeeTadaClaimController@updateStatus')->name('employeetadaClaim.updateStatus');
    Route::get('tada/claim/update-status/{id}/form', 'EmployeeTadaClaimController@updateStatusForm')->name('employeetadaClaim.updateStatusForm');
    Route::post('tada/claim/repeater-form', 'EmployeeTadaClaimController@getRepeaterForm')->name('employeetadaClaim.getRepeaterForm');


    // Employee Request Routes
    Route::get('tada/request', 'EmployeeTadaRequestController@index')->name('employeetadaRequest.index');
    Route::post('tada/request', 'EmployeeTadaRequestController@store')->name('employeetadaRequest.store');
    Route::get('tada/request/create', 'EmployeeTadaRequestController@create')->name('employeetadaRequest.create');
    Route::get('tada/request/show/{id}', 'EmployeeTadaRequestController@show')->name('employeetadaRequest.show');
    Route::get('tada/request/edit/{id}', 'EmployeeTadaRequestController@edit')->name('employeetadaRequest.edit');
    Route::patch('tada/request/update/{id}', 'EmployeeTadaRequestController@update')->name('employeetadaRequest.update');
    Route::get('tada/request/delete/{id}', 'EmployeeTadaRequestController@destroy')->name('employeetadaRequest.delete');
    Route::post('tada/request/update-status/{id}', 'EmployeeTadaRequestController@updateStatus')->name('employeetadaRequest.updateStatus');
    Route::get('tada/request/update-status/{id}/form', 'EmployeeTadaRequestController@updateStatusForm')->name('employeetadaRequest.updateStatusForm');
    Route::post('tada/request/repeater-form', 'EmployeeTadaRequestController@getRepeaterForm')->name('employeetadaRequest.getRepeaterForm');
});
