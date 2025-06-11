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
    // resignation routes
    Route::get('resignations', ['as' => 'resignation.index', 'uses' => 'OffboardResignationController@index']);
    Route::get('resignation/create', ['as' => 'resignation.create', 'uses' => 'OffboardResignationController@create']);
    Route::post('resignation/store', ['as' => 'resignation.store', 'uses' => 'OffboardResignationController@store']);
    Route::get('resignation/{id}/view', ['as' => 'resignation.view', 'uses' => 'OffboardResignationController@show']);
    Route::get('resignation/{id}/edit', ['as' => 'resignation.edit', 'uses' => 'OffboardResignationController@edit']);
    Route::put('resignation/{id}/update', ['as' => 'resignation.update', 'uses' => 'OffboardResignationController@update']);
    Route::get('resignation/{id}/delete', ['as' => 'resignation.delete', 'uses' => 'OffboardResignationController@destroy']);
    Route::post('resignation/update-status', ['as' => 'resignation.updateStatus', 'uses' => 'OffboardResignationController@updateStatus']);
    Route::get('resignation/{id}/show-report', ['as' => 'resignation.showReport', 'uses' => 'OffboardResignationController@showReport']);

    Route::get('resignation/team-request', ['as' => 'resignation.teamRequest', 'uses' => 'OffboardResignationController@teamRequest']);

    Route::post('resignation/letter-issued', ['as' => 'resignation.letterIssued', 'uses' => 'OffboardResignationController@letterIssued']);
    Route::post('resignation/letter-received', ['as' => 'resignation.letterReceived', 'uses' => 'OffboardResignationController@letterReceived']);

    Route::get('resignation/terminate-employee', ['as' => 'resignation.terminateEmployee', 'uses' => 'OffboardResignationController@terminateEmployee']);


    //offboarding clearance route
    Route::get('offboard-clearance', ['as' => 'clearance.index', 'uses' => 'OffboardClearanceController@index']);
    Route::get('offboard-clearance/create', ['as' => 'clearance.create', 'uses' => 'OffboardClearanceController@create']);
    Route::post('offboard-clearance/store', ['as' => 'clearance.store', 'uses' => 'OffboardClearanceController@store']);
    Route::get('offboard-clearance/{id}/view', ['as' => 'clearance.view', 'uses' => 'OffboardClearanceController@show']);
    Route::get('offboard-clearance/{id}/edit', ['as' => 'clearance.edit', 'uses' => 'OffboardClearanceController@edit']);
    Route::put('offboard-clearance/{id}/update', ['as' => 'clearance.update', 'uses' => 'OffboardClearanceController@update']);
    Route::get('offboard-clearance/{id}/delete', ['as' => 'clearance.delete', 'uses' => 'OffboardClearanceController@destroy']);
    Route::post('offboard-clearance/repeater-form', ['as' => 'clearance.getRepeaterForm', 'uses' => 'OffboardClearanceController@getRepeaterForm']);
    Route::get('offboard-clearance/{id}/send-notification', ['as' => 'clearance.sendMailNotification', 'uses' => 'OffboardClearanceController@sendMailNotification']);
    Route::get('offboard-clearance/employee/{id}/show', ['as' => 'clearance.employee.show', 'uses' => 'OffboardClearanceController@showemployeeClearance']);
    Route::post('offboard-clearance/employee/store', ['as' => 'clearance.employee.store', 'uses' => 'OffboardClearanceController@storeEmployeeClearance']);
});