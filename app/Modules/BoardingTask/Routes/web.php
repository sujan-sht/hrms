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

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'permission','XssSanitizer']], function () {
    // leave type routes
    Route::get('boarding-tasks', ['as' => 'boardingTask.index', 'uses' => 'BoardingTaskController@index']);
    Route::get('boarding-task/create', ['as' => 'boardingTask.create', 'uses' => 'BoardingTaskController@create']);
    Route::post('boarding-task/store', ['as' => 'boardingTask.store', 'uses' => 'BoardingTaskController@store']);
    Route::get('boarding-task/{id}/edit', ['as' => 'boardingTask.edit', 'uses' => 'BoardingTaskController@edit']);
    Route::put('boarding-task/{id}/update', ['as' => 'boardingTask.update', 'uses' => 'BoardingTaskController@update']);
    Route::get('boarding-task/{id}/delete', ['as' => 'boardingTask.delete', 'uses' => 'BoardingTaskController@destroy']);
});
