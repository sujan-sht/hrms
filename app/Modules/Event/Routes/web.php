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

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'permission']], function () {

    Route::get('event', ['as' => 'event.index', 'uses' => 'EventController@index']);

    Route::get('event/create', ['as' => 'event.create', 'uses' => 'EventController@create']);

    Route::post('event/store', ['as' => 'event.store', 'uses' => 'EventController@store']);

    Route::get('event/edit/{id}', ['as' => 'event.edit', 'uses' => 'EventController@edit'])->where('id', '[0-9]+');
    Route::put('event/update/{id}', ['as' => 'event.update', 'uses' => 'EventController@update'])->where('id', '[0-9]+');

    Route::get('event/view/{id}', ['as' => 'event.view', 'uses' => 'EventController@view'])->where('id', '[0-9]+');

    Route::get('event/delete/{id}', ['as' => 'event.delete', 'uses' => 'EventController@destroy'])->where('id', '[0-9]+');

});


Route::group(['prefix' => 'employee', 'middleware' => ['auth', 'permission']], function () {

    Route::get('event', ['as' => 'employee-event.index', 'uses' => 'EmployeeEventController@index']);

    Route::get('event-english', ['as' => 'employee-event-english.index', 'uses' => 'EmployeeEventController@indexEnglish']);

    Route::get('event/create', ['as' => 'employee-event.create', 'uses' => 'EmployeeEventController@create']);
    Route::post('event/store', ['as' => 'employee-event.store', 'uses' => 'EmployeeEventController@store']);

    Route::get('event/edit/{id}', ['as' => 'employee-event.edit', 'uses' => 'EmployeeEventController@edit'])->where('id', '[0-9]+');
    Route::patch('event/update/{id}', ['as' => 'employee-event.update', 'uses' => 'EmployeeEventController@update'])->where('id', '[0-9]+');

    Route::get('event/delete/{id}', ['as' => 'employee-event.delete', 'uses' => 'EmployeeEventController@destroy'])->where('id', '[0-9]+');
    Route::get('event/view/{id}', ['as' => 'employee-event.view', 'uses' => 'EmployeeEventController@view'])->where('id', '[0-9]+');

    Route::get('event/get', 'EmployeeEventController@getDateInfo')->name('employee-event.dateinfo');
    Route::post('event/updateDate/{id}', ['as' => 'employee-event.updateDate', 'uses' => 'EmployeeEventController@updateDate'])->where('id', '[0-9]+');
});
Route::get('admin/event/getOrganizationEmployee', 'EventController@getOrganizationEmployee');
Route::get('admin/event/getOrganizationBranch', 'EventController@getOrganizationBranch');
    Route::get('admin/get-districts', ['as' => 'event.get-districts', 'uses' => 'EventController@getDistricts']);
