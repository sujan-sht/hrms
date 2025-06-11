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
    //LeaveYearSetup
    Route::get('leaveYearSetups', ['as' => 'leaveYearSetup.index', 'uses' => 'LeaveYearSetupController@index']);
    Route::get('leaveYearSetup/create', ['as' => 'leaveYearSetup.create', 'uses' => 'LeaveYearSetupController@create']);
    Route::post('leaveYearSetup/store', ['as' => 'leaveYearSetup.store', 'uses' => 'LeaveYearSetupController@store']);
    Route::get('leaveYearSetup/{id}/edit', ['as' => 'leaveYearSetup.edit', 'uses' => 'LeaveYearSetupController@edit']);
    Route::put('leaveYearSetup/{id}/update', ['as' => 'leaveYearSetup.update', 'uses' => 'LeaveYearSetupController@update']);
    Route::get('leaveYearSetup/{id}/delete', ['as' => 'leaveYearSetup.delete', 'uses' => 'LeaveYearSetupController@destroy']);
});
Route::get('leaveYearSetup/getLeaveYearById/{id}', ['as' => 'leaveYearSetup.getLeaveYearById', 'uses' => 'LeaveYearSetupController@getLeaveYearById']);

