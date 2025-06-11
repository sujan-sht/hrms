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

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'permission','XssSanitizer']], function () {

	Route::get('worklog', 'WorklogController@index')->name('worklog.index');
	Route::get('worklog/create', 'WorklogController@create')->name('worklog.create');
	Route::post('worklog/store', 'WorklogController@store')->name('worklog.store');
	Route::get('worklog/edit/{id}', 'WorklogController@edit')->name('worklog.edit');
	Route::put('worklog/update/{id}', 'WorklogController@update')->name('worklog.update');
	Route::get('worklog/delete/{id}', 'WorklogController@destroy')->name('worklog.delete');
	Route::get('worklog/view/{id}', 'WorklogController@show')->name('worklog.view');

    Route::put('worklog/updateStatus', ['as' => 'worklog.updateStatus', 'uses' => 'WorklogController@updateStatus']);

    //Excel Export
    Route::get('worklog/export-report', 'WorklogController@exportWorklogReport')->name('exportWorklogReport');
});
