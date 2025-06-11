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

use App\Modules\Appraisal\Entities\Respondent;
use App\Modules\Setting\Entities\Setting;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'permission', 'XssSanitizer']], function () {

    Route::get('score', ['as' => 'score.index', 'uses' => 'ScoreController@index']);
    //AjaX
    Route::post('score-update', ['as' => 'score.update', 'uses' => 'ScoreController@update']);

    Route::get('competence-libraries', ['as' => 'competenceLibrary.index', 'uses' => 'CompetencyLibraryController@index']);
    Route::get('competence-library/create', ['as' => 'competenceLibrary.create', 'uses' => 'CompetencyLibraryController@create']);
    Route::post('competence-library/store', ['as' => 'competenceLibrary.store', 'uses' => 'CompetencyLibraryController@store']);
    Route::get('competence-library/{id}/edit', ['as' => 'competenceLibrary.edit', 'uses' => 'CompetencyLibraryController@edit']);
    Route::put('competence-library/{id}/update', ['as' => 'competenceLibrary.update', 'uses' => 'CompetencyLibraryController@update']);
    Route::get('competence-library/{id}/delete', ['as' => 'competenceLibrary.delete', 'uses' => 'CompetencyLibraryController@destroy']);

    Route::get('competencies', ['as' => 'competence.index', 'uses' => 'CompetencyController@index']);
    Route::get('competence/create', ['as' => 'competence.create', 'uses' => 'CompetencyController@create']);
    Route::post('competence/store', ['as' => 'competence.store', 'uses' => 'CompetencyController@store']);
    Route::get('competence/{id}/edit', ['as' => 'competence.edit', 'uses' => 'CompetencyController@edit']);
    Route::put('competence/{id}/update', ['as' => 'competence.update', 'uses' => 'CompetencyController@update']);
    Route::get('competence/{id}/delete', ['as' => 'competence.delete', 'uses' => 'CompetencyController@destroy']);

    //AJAX
    Route::get('competence/show', ['as' => 'competence.show', 'uses' => 'CompetencyController@show']);
    Route::post('competence/appendQuestionForm', ['as' => 'appraisalQuestion.getRepeaterForm', 'uses' => 'CompetencyController@addMoreQuestion']);


    Route::get('questionnaires', ['as' => 'questionnaire.index', 'uses' => 'QuestionnaireController@index']);
    Route::get('questionnaire/create', ['as' => 'questionnaire.create', 'uses' => 'QuestionnaireController@create']);
    Route::post('questionnaire/store', ['as' => 'questionnaire.store', 'uses' => 'QuestionnaireController@store']);
    Route::get('questionnaire/{id}/edit', ['as' => 'questionnaire.edit', 'uses' => 'QuestionnaireController@edit']);
    Route::put('questionnaire/{id}/update', ['as' => 'questionnaire.update', 'uses' => 'QuestionnaireController@update']);
    Route::get('questionnaire/{id}/delete', ['as' => 'questionnaire.delete', 'uses' => 'QuestionnaireController@destroy']);
    Route::get('questionnaire/{id}/show-form', ['as' => 'questionnaire.showForm', 'uses' => 'QuestionnaireController@showForm']);

    //appraisal-rating-scale
    Route::get('ratingscales', ['as' => 'ratingScale.index', 'uses' => 'RatingScaleController@index']);
    Route::get('ratingscale/create', ['as' => 'ratingScale.create', 'uses' => 'RatingScaleController@create']);
    Route::post('ratingscale/store', ['as' => 'ratingScale.store', 'uses' => 'RatingScaleController@store']);
    Route::get('ratingscale/{id}/edit', ['as' => 'ratingScale.edit', 'uses' => 'RatingScaleController@edit']);
    Route::put('ratingscale/{id}/update', ['as' => 'ratingScale.update', 'uses' => 'RatingScaleController@update']);
    Route::get('ratingscale/{id}/delete', ['as' => 'ratingScale.delete', 'uses' => 'RatingScaleController@destroy']);

    // AJAX
    Route::get('questionnaire/show', ['as' => 'questionnaire.show', 'uses' => 'QuestionnaireController@show']);

    Route::get('appraisals', ['as' => 'appraisal.index', 'uses' => 'AppraisalController@index']);
    Route::get('appraisal/appraisee-details', ['as' => 'appraisal.appraiseeDetail', 'uses' => 'AppraisalController@appraiseeDetail']);
    Route::get('appraisal/create', ['as' => 'appraisal.create', 'uses' => 'AppraisalController@create']);
    Route::post('appraisal/store', ['as' => 'appraisal.store', 'uses' => 'AppraisalController@store']);
    Route::get('appraisal/{id}/edit', ['as' => 'appraisal.edit', 'uses' => 'AppraisalController@edit']);
    Route::put('appraisal/{id}/update', ['as' => 'appraisal.update', 'uses' => 'AppraisalController@update']);
    Route::get('appraisal/{id}/delete', ['as' => 'appraisal.delete', 'uses' => 'AppraisalController@destroy']);
    Route::get('appraisal/{id}/report', ['as' => 'appraisal.report', 'uses' => 'AppraisalController@report']);
    Route::get('appraisal/{id}/downloadReport', ['as' => 'appraisal.downloadReport', 'uses' => 'AppraisalController@downloadReport']);


    Route::get('appraisal-respondents/{id}', ['as' => 'appraisal-respondent.index', 'uses' => 'AppraisalRespondentController@index']);
    Route::get('appraisal-repondent/show/{id}', ['as' => 'appraisal-respondent.view', 'uses' => 'AppraisalRespondentController@show']);
    Route::get('appraisal-repondent/resend-mail', ['as' => 'appraisal-respondent.resendEmail', 'uses' => 'AppraisalRespondentController@resendEmail']);

    Route::get('appraisal-repondent/print/{id}', ['as' => 'appraisal-respondent.print', 'uses' => 'AppraisalRespondentController@print']);

    Route::post('appraisal-respondents/addRespondent/{id}', ['as' => 'appraisal-respondent.addRespondent', 'uses' => 'AppraisalRespondentController@addRespondent']);
    Route::get('appraisal-report', ['as' => 'appraisalReport', 'uses' => 'AppraisalReportController@index']);
    Route::get('performance-evaluation-summary-report', ['as' => 'performanceEvaluationSummary', 'uses' => 'AppraisalReportController@performanceEvaluationSummary']);



    // Route::post('appraisal-repondent/store', ['as' => 'appraisal-respondent.store', 'uses' => 'AppraisalRespondentController@store']);
    // Route::get('appraisal-repondent/{id}/edit', ['as' => 'appraisal-respondent.edit', 'uses' => 'AppraisalRespondentController@edit']);
    // Route::put('appraisal-repondent/{id}/update', ['as' => 'appraisal-respondent.update', 'uses' => 'AppraisalRespondentController@update']);
    // Route::get('appraisal-repondent/{id}/delete', ['as' => 'appraisal-respondent.delete', 'uses' => 'AppraisalRespondentController@destroy']);


    //AJAX
    Route::post('appraisal/appendRespondent', ['as' => 'appraisal.appendRespondent', 'uses' => 'AppraisalController@appendRespondent']);
});

Route::get('appraisal-view', ['as' => 'appraisal.viewThroughInvitation', 'uses' => 'AppraisalController@viewThroughInvitation']);
Route::post('appraisal-response', ['as' => 'appraisal.responseViaInvitation', 'uses' => 'AppraisalResponseController@store']);



// Route::get('/mail',function(){
//     $data['setting'] = Setting::first();
//     $data['respondent'] = Respondent::where('invitation_code',8431921672294165)->first();

//     return view('appraisal::mails.appraisal-respondent-mail',$data);
// });
