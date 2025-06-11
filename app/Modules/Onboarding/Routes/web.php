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

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'permission']], function () {
    // mrf routes
    Route::get('mrfs', ['as' => 'mrf.index', 'uses' => 'MrfController@index']);
    Route::get('mrf/history', ['as' => 'mrf.history', 'uses' => 'MrfController@history']);
    Route::get('mrf/create', ['as' => 'mrf.create', 'uses' => 'MrfController@create']);
    Route::post('mrf/store', ['as' => 'mrf.store', 'uses' => 'MrfController@store']);
    Route::get('mrf/{id}/view', ['as' => 'mrf.view', 'uses' => 'MrfController@show']);
    Route::get('mrf/{id}/edit', ['as' => 'mrf.edit', 'uses' => 'MrfController@edit']);
    Route::put('mrf/{id}/update', ['as' => 'mrf.update', 'uses' => 'MrfController@update']);
    Route::get('mrf/{id}/delete', ['as' => 'mrf.delete', 'uses' => 'MrfController@destroy']);
    Route::post('mrf/update-status', ['as' => 'mrf.updateStatus', 'uses' => 'MrfController@updateStatus']);
    Route::get('mrf/{id}/close', ['as' => 'mrf.closeMRF', 'uses' => 'MrfController@closeMRF']);

    // applicant routes
    Route::get('applicants', ['as' => 'applicant.index', 'uses' => 'ApplicantController@index']);
    Route::get('applicant/create', ['as' => 'applicant.create', 'uses' => 'ApplicantController@create']);
    Route::post('applicant/store', ['as' => 'applicant.store', 'uses' => 'ApplicantController@store']);
    Route::get('applicant/{id}/view', ['as' => 'applicant.view', 'uses' => 'ApplicantController@show']);
    Route::get('applicant/{id}/edit', ['as' => 'applicant.edit', 'uses' => 'ApplicantController@edit']);
    Route::put('applicant/{id}/update', ['as' => 'applicant.update', 'uses' => 'ApplicantController@update']);
    Route::get('applicant/{id}/delete', ['as' => 'applicant.delete', 'uses' => 'ApplicantController@destroy']);
    Route::post('applicant/update-status', ['as' => 'applicant.updateStatus', 'uses' => 'ApplicantController@updateStatus']);
    Route::get('applicant/export-excel', ['as' => 'applicant.exportExcel', 'uses' => 'ApplicantController@exportExcel']);

    // interview level routes
    Route::get('interview-levels', ['as' => 'interviewLevel.index', 'uses' => 'InterviewLevelController@index']);
    Route::get('interview-level/create', ['as' => 'interviewLevel.create', 'uses' => 'InterviewLevelController@create']);
    Route::post('interview-level/store', ['as' => 'interviewLevel.store', 'uses' => 'InterviewLevelController@store']);
    Route::get('interview-level/{id}/view', ['as' => 'interviewLevel.view', 'uses' => 'InterviewLevelController@show']);
    Route::get('interview-level/{id}/edit', ['as' => 'interviewLevel.edit', 'uses' => 'InterviewLevelController@edit']);
    Route::put('interview-level/{id}/update', ['as' => 'interviewLevel.update', 'uses' => 'InterviewLevelController@update']);
    Route::get('interview-level/{id}/delete', ['as' => 'interviewLevel.delete', 'uses' => 'InterviewLevelController@destroy']);
    // interview routes
    Route::get('interviews', ['as' => 'interview.index', 'uses' => 'InterviewController@index']);
    Route::get('interview/create', ['as' => 'interview.create', 'uses' => 'InterviewController@create']);
    Route::post('interview/store', ['as' => 'interview.store', 'uses' => 'InterviewController@store']);
    Route::get('interview/{id}/view', ['as' => 'interview.view', 'uses' => 'InterviewController@show']);
    Route::get('interview/{id}/edit', ['as' => 'interview.edit', 'uses' => 'InterviewController@edit']);
    Route::put('interview/{id}/update', ['as' => 'interview.update', 'uses' => 'InterviewController@update']);
    Route::get('interview/{id}/delete', ['as' => 'interview.delete', 'uses' => 'InterviewController@destroy']);
    Route::post('interview/update-status', ['as' => 'interview.updateStatus', 'uses' => 'InterviewController@updateStatus']);
    // evaluation routes
    Route::get('evaluations', ['as' => 'evaluation.index', 'uses' => 'EvaluationController@index']);
    Route::get('evaluation/sub-list', ['as' => 'evaluation.subIndex', 'uses' => 'EvaluationController@subIndex']);
    Route::get('evaluation/create', ['as' => 'evaluation.create', 'uses' => 'EvaluationController@create']);
    Route::post('evaluation/store', ['as' => 'evaluation.store', 'uses' => 'EvaluationController@store']);
    Route::get('evaluation/{id}/report', ['as' => 'evaluation.report', 'uses' => 'EvaluationController@report']);
    Route::get('evaluation/{id}/view', ['as' => 'evaluation.view', 'uses' => 'EvaluationController@show']);
    Route::get('evaluation/{id}/edit', ['as' => 'evaluation.edit', 'uses' => 'EvaluationController@edit']);
    Route::put('evaluation/{id}/update', ['as' => 'evaluation.update', 'uses' => 'EvaluationController@update']);
    Route::get('evaluation/{id}/delete', ['as' => 'evaluation.delete', 'uses' => 'EvaluationController@destroy']);

    //Bulk Offer Letter - Evaluation
    Route::get('evaluation/bulk-offer-letter', ['as' => 'evaluation.bulkOfferLetter', 'uses' => 'EvaluationOfferLetterController@create']);
    Route::post('evaluation/send-bulk-offer-letter', ['as' => 'evaluation.sendBulkOfferLetter', 'uses' => 'EvaluationOfferLetterController@store']);

    // offer letter routes
    Route::get('offer-letters', ['as' => 'offerLetter.index', 'uses' => 'OfferLetterController@index']);
    Route::get('offer-letter/create', ['as' => 'offerLetter.create', 'uses' => 'OfferLetterController@create']);
    Route::post('offer-letter/store', ['as' => 'offerLetter.store', 'uses' => 'OfferLetterController@store']);
    Route::get('offer-letter/{id}/view', ['as' => 'offerLetter.view', 'uses' => 'OfferLetterController@show']);
    Route::get('offer-letter/{id}/edit', ['as' => 'offerLetter.edit', 'uses' => 'OfferLetterController@edit']);
    Route::put('offer-letter/{id}/update', ['as' => 'offerLetter.update', 'uses' => 'OfferLetterController@update']);
    Route::get('offer-letter/{id}/delete', ['as' => 'offerLetter.delete', 'uses' => 'OfferLetterController@destroy']);
    Route::post('offer-letter/update-status', ['as' => 'offerLetter.updateStatus', 'uses' => 'OfferLetterController@updateStatus']);

    // offer letter routes
    Route::get('onboards', ['as' => 'onboard.index', 'uses' => 'OnboardController@index']);
    Route::get('onboard/create', ['as' => 'onboard.create', 'uses' => 'OnboardController@create']);
    Route::post('onboard/store', ['as' => 'onboard.store', 'uses' => 'OnboardController@store']);
    Route::get('onboard/{id}/view', ['as' => 'onboard.view', 'uses' => 'OnboardController@show']);
    Route::get('onboard/edit', ['as' => 'onboard.edit', 'uses' => 'OnboardController@edit']);
    Route::put('onboard/update', ['as' => 'onboard.update', 'uses' => 'OnboardController@update']);
    Route::get('onboard/{id}/delete', ['as' => 'onboard.delete', 'uses' => 'OnboardController@destroy']);
});
