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
    //Training
    Route::get('trainings', ['as' => 'training.index', 'uses' => 'TrainingController@index']);
    Route::get('training/create', ['as' => 'training.create', 'uses' => 'TrainingController@create']);
    Route::post('training/store', ['as' => 'training.store', 'uses' => 'TrainingController@store']);
    Route::get('training/{id}/edit', ['as' => 'training.edit', 'uses' => 'TrainingController@edit']);
    Route::put('training/{id}/update', ['as' => 'training.update', 'uses' => 'TrainingController@update']);
    Route::get('training/{id}/delete', ['as' => 'training.delete', 'uses' => 'TrainingController@destroy']);
    Route::get('training/{id}/view-training-attendees', ['as' => 'training.view-training-attendees', 'uses' => 'TrainingController@viewTrainingAttendees']);
    Route::get('training/{training_id}/view-training-attendee/{id}/view-training-certificate', ['as' => 'training.view-training-certificate', 'uses' => 'TrainingController@viewTrainingCertificate']);
    Route::get('training/{training_id}/view-training-attendee/{id}/print-training-certificate', ['as' => 'training.print-training-certificate', 'uses' => 'TrainingController@printTrainingCertificate']);

    //Trainer
    Route::post('training/storeTrainer', ['as' => 'training.storeTrainer', 'uses' => 'TrainingController@storeTrainer']);


    //TrainingParticipant
    Route::get('training/{id}/training-participant', ['as' => 'training-participant.index', 'uses' => 'TrainingParticipantController@index']);
    Route::get('training/{id}/training-participant/create', ['as' => 'training-participant.create', 'uses' => 'TrainingParticipantController@create']);
    Route::post('training/{id}/training-participant/store', ['as' => 'training-participant.store', 'uses' => 'TrainingParticipantController@store']);
    Route::get('training/{training_id}/training-participant/{id}/edit', ['as' => 'training-participant.edit', 'uses' => 'TrainingParticipantController@edit']);
    Route::put('training/{training_id}/training-participant/{id}/update', ['as' => 'training-participant.update', 'uses' => 'TrainingParticipantController@update']);
    Route::get('training/{training_id}/training-participant/{id}/delete', ['as' => 'training-participant.delete', 'uses' => 'TrainingParticipantController@destroy']);

    //TrainingAttendance
    Route::get('training/{id}/training-attendance', ['as' => 'training-attendance.index', 'uses' => 'TrainingAttendanceController@index']);
    Route::get('training/{id}/training-attendance/create', ['as' => 'training-attendance.create', 'uses' => 'TrainingAttendanceController@create']);
    Route::post('training/{id}/training-attendance/store', ['as' => 'training-attendance.store', 'uses' => 'TrainingAttendanceController@store']);
    Route::get('training/{training_id}/training-attendance/{id}/edit', ['as' => 'training-attendance.edit', 'uses' => 'TrainingAttendanceController@edit']);
    Route::put('training/{training_id}/training-attendance/{id}/update', ['as' => 'training-attendance.update', 'uses' => 'TrainingAttendanceController@update']);
    Route::get('training/{training_id}/training-attendance/{id}/delete', ['as' => 'training-attendance.delete', 'uses' => 'TrainingAttendanceController@destroy']);
    Route::post('training/{training_id}/training-attendance/update-status', ['as' => 'training-attendance.update.status', 'uses' => 'TrainingAttendanceController@updateStatus']);

    //Training Report
    Route::get('training-report', ['as' => 'training-report', 'uses' => 'TrainingController@viewReport']);
    Route::get('training-MIS-report', ['as' => 'training-MIS-report', 'uses' => 'TrainingController@viewTrainingMISReport']);
    Route::get('training-attendees-detail-report', ['as' => 'training-attendees-detail-report', 'uses' => 'TrainingController@viewAttendeesDetailReport']);

    Route::get('training-report/annual', ['as' => 'training-annual-calendar-report', 'uses' => 'TrainingReportController@annualCalendarReport']);
});

//Fetch Participant Data
Route::get('training/{training_id}/training-attendance/ParticipantData', ['as' => 'training-attendance.ParticipantData', 'uses' => 'TrainingAttendanceController@ParticipantData']);
//
