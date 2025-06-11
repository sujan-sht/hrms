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
    Route::prefix('grievance')->group(function () {
        Route::get('/', 'GrievanceController@index')->name('grievance.index');
        Route::get('/create', 'GrievanceController@create')->name('grievance.create');
        Route::post('/store', 'GrievanceController@store')->name('grievance.store');

        Route::get('/view/{id}', 'GrievanceController@view')->name('grievance.view');
        Route::get('/delete/{id}', 'GrievanceController@destroy')->name('grievance.delete');

        Route::post('/updateStatus', 'GrievanceController@updateStatus')->name('grievance.updateStatus');
        Route::get('/exportAll', 'GrievanceController@exportAll')->name('grievance.exportAll');


    });
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'XssSanitizer']], function () {
     //get employee detail
     Route::get('grievance/find-employee', 'GrievanceController@findEmployee')->name('grievance.findEmployee');
});
