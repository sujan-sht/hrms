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
    //Poll
    Route::get('polls', ['as' => 'poll.index', 'uses' => 'PollController@index']);
    Route::get('poll/create', ['as' => 'poll.create', 'uses' => 'PollController@create']);
    Route::post('poll/store', ['as' => 'poll.store', 'uses' => 'PollController@store']);
    Route::get('poll/{id}/edit', ['as' => 'poll.edit', 'uses' => 'PollController@edit']);
    Route::put('poll/{id}/update', ['as' => 'poll.update', 'uses' => 'PollController@update']);
    Route::get('poll/{id}/delete', ['as' => 'poll.delete', 'uses' => 'PollController@destroy']);


    //Poll Allocation
    Route::get('poll/{id}/allocate-form', ['as' => 'poll.allocateForm', 'uses' => 'PollController@allocateForm']);
    Route::post('poll/allocate', ['as' => 'poll.allocate', 'uses' => 'PollController@allocate']);
    Route::get('poll/allocations', ['as' => 'poll.allocationList', 'uses' => 'PollController@allocationList']);
    //

    //Poll Response
    Route::post('poll/store-poll-response', ['as' => 'poll.storePollResponse', 'uses' => 'PollController@storePollResponse']);


    //AJAX
    Route::post('poll/appendOptionForm', ['as' => 'poll.getRepeaterForm', 'uses' => 'PollController@addMoreOption']);

    //Report
    Route::get('poll/view-report', ['as' => 'poll.viewReport', 'uses' => 'PollController@viewReport']);
    Route::get('poll/view-employee-report', ['as' => 'poll.viewEmployeeReport', 'uses' => 'PollController@viewEmployeeReport']);



  
});
