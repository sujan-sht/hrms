<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/attendance', function (Request $request) {
    return $request->user();
});

// Route::post('daily-attendance-logs', 'AttendanceController@saveAttendanceLogs');

Route::post('save-attendance-data', 'AttendanceController@saveAttendanceData');

Route::delete('delete-attendance-data/{date}', 'AttendanceController@deleteAttendanceData');

