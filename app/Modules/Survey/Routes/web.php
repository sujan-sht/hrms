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
    //Survey
    Route::get('surveys', ['as' => 'survey.index', 'uses' => 'SurveyController@index']);
    Route::get('survey/create', ['as' => 'survey.create', 'uses' => 'SurveyController@create']);
    Route::post('survey/store', ['as' => 'survey.store', 'uses' => 'SurveyController@store']);
    Route::get('survey/{id}/edit', ['as' => 'survey.edit', 'uses' => 'SurveyController@edit']);
    Route::put('survey/{id}/update', ['as' => 'survey.update', 'uses' => 'SurveyController@update']);
    Route::get('survey/{id}/delete', ['as' => 'survey.delete', 'uses' => 'SurveyController@destroy']);
    Route::get('survey/{id}/view-report', ['as' => 'survey.viewReport', 'uses' => 'SurveyController@viewReport']);
    //

    //Survey Question
    Route::get('survey/{survey_id}/survey-questions', ['as' => 'surveyQuestion.index', 'uses' => 'SurveyQuestionController@index']);
    Route::get('survey/{survey_id}/survey-question/create', ['as' => 'surveyQuestion.create', 'uses' => 'SurveyQuestionController@create']);
    Route::post('survey/survey-question/store', ['as' => 'surveyQuestion.store', 'uses' => 'SurveyQuestionController@store']);
    Route::get('survey/{survey_id}/survey-question/{id}/edit', ['as' => 'surveyQuestion.edit', 'uses' => 'SurveyQuestionController@edit']);
    Route::put('survey/survey-question/{id}/update', ['as' => 'surveyQuestion.update', 'uses' => 'SurveyQuestionController@update']);
    Route::get('survey/survey-question/{id}/delete', ['as' => 'surveyQuestion.delete', 'uses' => 'SurveyQuestionController@destroy']);
    //

    //Survey Allocation
    Route::get('survey/{id}/allocate-form', ['as' => 'survey.allocateForm', 'uses' => 'SurveyController@allocateForm']);
    Route::post('survey/allocate', ['as' => 'survey.allocate', 'uses' => 'SurveyController@allocate']);
    Route::get('survey/allocations', ['as' => 'survey.allocationList', 'uses' => 'SurveyController@allocationList']);
    //

    //Survey Response
    Route::get('survey/{id}/view-survey', ['as' => 'survey.viewSurveyByEmpoyee', 'uses' => 'SurveyController@viewSurveyByEmpoyee']);
    Route::post('survey/storeResponse', ['as' => 'survey.storeResponse', 'uses' => 'SurveyController@storeResponse']);

    //Report
    Route::get('survey/{id}/view-survey-report', ['as' => 'survey.viewSurveyReportByEmpoyee', 'uses' => 'SurveyController@viewSurveyReportByEmpoyee']);
    //


  
});
