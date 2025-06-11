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

    Route::get('warning', ['as' => 'warning.index', 'uses' => 'WarningController@index']);

    Route::get('warning/create', ['as' => 'warning.create', 'uses' => 'WarningController@create']);
    Route::post('warning/store', ['as' => 'warning.store', 'uses' => 'WarningController@store']);

    Route::get('warning/edit/{id}', ['as' => 'warning.edit', 'uses' => 'WarningController@edit'])->where('id', '[0-9]+');
    Route::put('warning/update/{id}', ['as' => 'warning.update', 'uses' => 'WarningController@update'])->where('id', '[0-9]+');

    Route::get('warning/delete/{id}', ['as' => 'warning.delete', 'uses' => 'WarningController@destroy'])->where('id', '[0-9]+');
    Route::get('warning/view/{id}', ['as' => 'warning.view', 'uses' => 'WarningController@show'])->where('id', '[0-9]+');

});
