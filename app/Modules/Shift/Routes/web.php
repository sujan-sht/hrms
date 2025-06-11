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
    Route::get('shift', 'ShiftController@index')->name('shift.index');
    Route::get('shift/create', 'ShiftController@create')->name('shift.create');
    Route::post('shift/appendForm', 'ShiftController@getSeasonForm')->name('shift.getSeasonForm');
    Route::post('shift/store', 'ShiftController@store')->name('shift.store');
    Route::get('shift/edit/{id}', 'ShiftController@edit')->name('shift.edit');
    Route::put('shift/update/{id}', 'ShiftController@update')->name('shift.update');
    Route::get('shift/delete/{id}', 'ShiftController@destroy')->name('shift.delete');

    Route::post('employeeshift', 'EmployeeShiftController@store')->name('employeeshift.store');
    Route::get('employeeshift/view', 'EmployeeShiftController@view')->name('employeeshift.view');

    Route::get('employeeshift/add', 'EmployeeShiftController@add')->name('employeeshift.add');
    Route::get('employeeshift/remove', 'EmployeeShiftController@remove')->name('employeeshift.remove');
    Route::get('employeeshift/changeday', 'EmployeeShiftController@change_day_status')->name('employeeshift.changeday');


    Route::post('/update-default-shift', ['as' => 'updateDefaultShift', 'uses' => 'ShiftController@updateDefaulShift']);

    /*
       |--------------------------------------------------------------------------
       | Group CRUD ROUTE
       |--------------------------------------------------------------------------
       */
    Route::get('shift/groups', ['as' => 'shiftGroup.index', 'uses' => 'ShiftGroupController@index']);
    Route::get('shift/group/create', ['as' => 'shiftGroup.create', 'uses' => 'ShiftGroupController@create']);
    Route::post('shift/group/store', ['as' => 'shiftGroup.store', 'uses' => 'ShiftGroupController@store']);
    Route::get('shift/group/edit/{id}', ['as' => 'shiftGroup.edit', 'uses' => 'ShiftGroupController@edit'])->where('id', '[0-9]+');
    Route::put('shift/group/update/{id}', ['as' => 'shiftGroup.update', 'uses' => 'ShiftGroupController@update'])->where('id', '[0-9]+');
    Route::get('shift/group/delete/{id}', ['as' => 'shiftGroup.delete', 'uses' => 'ShiftGroupController@destroy'])->where('id', '[0-9]+');

    Route::get('shift/group/seasonalShift', ['as' => 'shiftGroup.getseasonalshift', 'uses' => 'ShiftGroupController@setSeasonsalShift'])->where('id', '[0-9]+');
    Route::post('/update-default-shift-group', ['as' => 'updateDefaultGroup', 'uses' => 'ShiftGroupController@updateDefaulGroup']);

    Route::get('/get-shift-groups-by-organization', ['as' => 'getShiftgroupByOrganization', 'uses' => 'ShiftGroupController@getShiftGroupsByOrg']);
});
