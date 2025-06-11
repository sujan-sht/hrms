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

    Route::get('meetingRoom', ['as' => 'meetingRoom.index', 'uses' => 'MeetingRoomController@index']);

    Route::get('meetingRoom/create', ['as' => 'meetingRoom.create', 'uses' => 'MeetingRoomController@create']);
    Route::post('meetingRoom/store', ['as' => 'meetingRoom.store', 'uses' => 'MeetingRoomController@store']);

    Route::get('meetingRoom/edit/{id}', ['as' => 'meetingRoom.edit', 'uses' => 'MeetingRoomController@edit'])->where('id', '[0-9]+');
    Route::put('meetingRoom/update/{id}', ['as' => 'meetingRoom.update', 'uses' => 'MeetingRoomController@update'])->where('id', '[0-9]+');

    Route::get('meetingRoom/delete/{id}', ['as' => 'meetingRoom.delete', 'uses' => 'MeetingRoomController@destroy'])->where('id', '[0-9]+');
    Route::get('meetingRoom/view/{id}', ['as' => 'meetingRoom.view', 'uses' => 'MeetingRoomController@show'])->where('id', '[0-9]+');
    Route::post('meetingRoom/booking', ['as' => 'meetingRoom.booking', 'uses' => 'MeetingRoomController@booking'])->where('id', '[0-9]+');

});
Route::get('admin/meetingRoom/checkBookingExists', ['uses' => 'MeetingRoomController@checkBooking']);
