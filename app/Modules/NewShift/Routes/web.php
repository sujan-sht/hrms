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

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'permission', 'XssSanitizer']], function () {

    Route::get('new-shift', 'NewShiftController@index')->name('newShift.index');
    Route::get('new-shift/create', 'NewShiftController@create')->name('newShift.create');
    Route::post('new-shift/store', 'NewShiftController@store')->name('newShift.store');
    Route::get('new-shift/edit/{id}', 'NewShiftController@edit')->name('newShift.edit');
    Route::put('new-shift/update/{id}', 'NewShiftController@update')->name('newShift.update');
    Route::get('new-shift/delete/{id}', 'NewShiftController@destroy')->name('newShift.delete');

    Route::get('new-shift/assign', 'NewShiftController@assignShift')->name('newShift.assignShift');
    Route::post('new-shift-store', 'NewShiftController@newShiftStore')->name('shift.newShiftStore');

    Route::get('new-shift/weekly-report', 'NewShiftController@weeklyReport')->name('newShift.weeklyReport');

    Route::get('travel-request/download-roster-weekly-report', ['as' => 'newShift.downloadWeeklyReport', 'uses' => 'NewShiftController@downloadWeeklyReport']);

    //Requests
    Route::get('new-shift-requests', 'RequestController@index')->name('rosterRequest.index');
    Route::get('new-shift/create-request', 'RequestController@create')->name('rosterRequest.create');
    Route::post('new-shift/store-request', 'RequestController@store')->name('rosterRequest.store');
    Route::get('new-shift/edit-request/{id}', 'RequestController@edit')->name('rosterRequest.edit');
    Route::put('new-shift/update-request/{id}', 'RequestController@update')->name('rosterRequest.update');
    Route::get('new-shift/delete-request/{id}', 'RequestController@destroy')->name('rosterRequest.delete');
    Route::put('new-shift/update-request-status', 'RequestController@updateStatus')->name('rosterRequest.updateStatus');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'XssSanitizer']], function () {

    Route::get('new-shift/clone', 'NewShiftController@cloneNewShift');
});
