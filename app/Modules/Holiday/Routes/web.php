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

    Route::get('holiday', ['as' => 'holiday.index', 'uses' => 'HolidayController@index']);

    Route::get('holiday/create', ['as' => 'holiday.create', 'uses' => 'HolidayController@create']);
    Route::post('holiday/store', ['as' => 'holiday.store', 'uses' => 'HolidayController@store']);

    Route::get('holiday/edit/{id}', ['as' => 'holiday.edit', 'uses' => 'HolidayController@edit'])->where('id', '[0-9]+');
    Route::put('holiday/update/{id}', ['as' => 'holiday.update', 'uses' => 'HolidayController@update'])->where('id', '[0-9]+');

    Route::get('holiday/view/{id}', ['as' => 'holiday.view', 'uses' => 'HolidayController@show'])->where('id', '[0-9]+');

    Route::get('holiday/delete/{id}', ['as' => 'holiday.delete', 'uses' => 'HolidayController@destroy'])->where('id', '[0-9]+');
});

Route::get('holiday/clone-day', ['as' => 'holiday.clone.day', 'uses' => 'HolidayController@cloneDay']);
Route::get('admin/holiday/getOrganizationBranch', 'HolidayController@getOrganizationBranch');

Route::get('admin/holiday/clone-province-district-fields', ['as' => 'holiday.clone-province-district-fields', 'uses' =>'HolidayController@cloneProvinceDistrictFields']);