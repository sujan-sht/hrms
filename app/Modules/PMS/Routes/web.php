<?php

use App\Modules\PMS\Http\Controllers\TargetController;
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
    //KRA
    Route::get('KRAs', ['as' => 'kra.index', 'uses' => 'KRAController@index']);
    Route::get('KRA/create', ['as' => 'kra.create', 'uses' => 'KRAController@create']);
    Route::post('KRA/store', ['as' => 'kra.store', 'uses' => 'KRAController@store']);
    Route::get('KRA/{id}/edit', ['as' => 'kra.edit', 'uses' => 'KRAController@edit']);
    Route::put('KRA/{id}/update', ['as' => 'kra.update', 'uses' => 'KRAController@update']);
    Route::get('KRA/{id}/delete', ['as' => 'kra.delete', 'uses' => 'KRAController@destroy']);
    Route::get('kra/downloadSheet', ['as' => 'kra.downloadSheet', 'uses' => 'KRAController@downloadSheet']);


    //KPI
    Route::get('KPIs', ['as' => 'kpi.index', 'uses' => 'KPIController@index']);
    Route::get('KPI/create', ['as' => 'kpi.create', 'uses' => 'KPIController@create']);
    Route::post('KPI/store', ['as' => 'kpi.store', 'uses' => 'KPIController@store']);
    Route::get('KPI/{id}/edit', ['as' => 'kpi.edit', 'uses' => 'KPIController@edit']);
    Route::put('KPI/{id}/update', ['as' => 'kpi.update', 'uses' => 'KPIController@update']);
    Route::get('KPI/{id}/delete', ['as' => 'kpi.delete', 'uses' => 'KPIController@destroy']);

    //Target
    Route::get('targets', ['as' => 'target.index', 'uses' => 'TargetController@index']);
    Route::get('target/create', ['as' => 'target.create', 'uses' => 'TargetController@create']);
    Route::post('target/store', ['as' => 'target.store', 'uses' => 'TargetController@store']);
    Route::get('target/{id}/edit', ['as' => 'target.edit', 'uses' => 'TargetController@edit']);
    Route::put('target/{id}/update', ['as' => 'target.update', 'uses' => 'TargetController@update']);
    Route::get('target/{id}/delete', ['as' => 'target.delete', 'uses' => 'TargetController@destroy']);
    Route::get('target/fetchKPIs', ['as' => 'target.fetchKPIs', 'uses' => 'TargetController@fetchKPIs']);
    Route::get('employee-target/view', ['as' => 'employee-target.view', 'uses' => 'TargetController@employeeTargetView']);

    //Set Target Quarter
    Route::get('target/{id}/setTargetQuarter', ['as' => 'target.setTargetQuarter', 'uses' => 'TargetController@setTargetQuarter']);
    Route::post('target/setValue', ['as' => 'target.setValue', 'uses' => 'TargetController@setValue']);

    Route::get('target/{id}/set-target', ['as' => 'target.setTarget', 'uses' => 'TargetController@setTarget']);
    Route::post('target/set-target-value', ['as' => 'target.setTargetValue', 'uses' => 'TargetController@setTargetValue']);


    //Report
    Route::get('target/viewReport', ['as' => 'PMS.viewReport', 'uses' => 'TargetController@viewReport']);
    Route::get('target/viewDetail', ['as' => 'PMS.viewDetailQuarterwise', 'uses' => 'TargetController@viewDetailQuarterwise']);
    Route::get('target/viewFinalReport', ['as' => 'PMSViewFinalReport', 'uses' => 'TargetController@viewFinalReport']);


    //Target Achievement
    Route::get('target/{id}/view', ['as' => 'target.viewTargetAchievement', 'uses' => 'TargetController@viewTargetAchievement']);
    Route::post('target/achievement/update', ['as' => 'target.updateAchievement', 'uses' => 'TargetController@updateAchievement']);

    //Dynamic Form Setup
    Route::get('set-form', ['as' => 'set-form.index', 'uses' => 'SetFormController@index']);
    Route::get('set-form/create', ['as' => 'set-form.create', 'uses' => 'SetFormController@create']);
    Route::get('set-form/view', ['as' => 'set-form.view', 'uses' => 'SetFormController@view']);
    Route::post('set-form/filter-kra-list', ['as' => 'set-form.filterKraList', 'uses' => 'SetFormController@filterKraList']);
    Route::get('set-form/fetch-target-details', ['as' => 'set-form.fetchTargetDetails', 'uses' => 'SetFormController@fetchTargetDetails']);
    Route::post('set-form/store', ['as' => 'set-form.store', 'uses' => 'SetFormController@store']);
    Route::post('set-form/store-kra', ['as' => 'set-form.storeSingleKra', 'uses' => 'SetFormController@storeSingleKra']);
    Route::post('set-form/store-kpi', ['as' => 'set-form.storeSingleKpi', 'uses' => 'SetFormController@storeSingleKpi']);
    Route::post('set-form/edit-target-values', ['as' => 'set-form.editTargetValues', 'uses' => 'SetFormController@updateTargetValues']);
    Route::get('set-form/{kpi_id}/delete-kpi', ['as' => 'set-form.deleteKpi', 'uses' => 'SetFormController@destroyKpi']);
    Route::post('set-form/pms-employee-update-status', ['as' => 'set-form.pmsEmployeeupdateStatus', 'uses' => 'SetFormController@pmsEmployeeupdateStatus']);
    Route::post('set-form/pms-employee-set-rollout', ['as' => 'set-form.setRollout', 'uses' => 'SetFormController@setRollout']);



    //static
    Route::get('static-report', ['as' => 'staticReport', 'uses' => 'TargetController@viewStaticReport']);

});
