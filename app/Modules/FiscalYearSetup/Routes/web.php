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
    //FiscalYearSetup
    Route::get('fiscalYearSetups', ['as' => 'fiscalYearSetup.index', 'uses' => 'FiscalYearSetupController@index']);
    Route::get('fiscalYearSetup/create', ['as' => 'fiscalYearSetup.create', 'uses' => 'FiscalYearSetupController@create']);
    Route::post('fiscalYearSetup/store', ['as' => 'fiscalYearSetup.store', 'uses' => 'FiscalYearSetupController@store']);
    Route::post('fiscalYearSetup-ajax/store', ['as' => 'fiscalYearSetupAjax.store', 'uses' => 'FiscalYearSetupController@storeAjax']);
    Route::get('fiscalYearSetup/{id}/edit', ['as' => 'fiscalYearSetup.edit', 'uses' => 'FiscalYearSetupController@edit']);
    Route::put('fiscalYearSetup/{id}/update', ['as' => 'fiscalYearSetup.update', 'uses' => 'FiscalYearSetupController@update']);
    Route::get('fiscalYearSetup/{id}/delete', ['as' => 'fiscalYearSetup.delete', 'uses' => 'FiscalYearSetupController@destroy']);

    // Route::get('fiscalYearSetup/fetchDepartmentApprovals', ['as' => 'fiscalYearSetup.fetchDepartmentApprovals', 'uses' => 'FiscalYearSetupController@fetchDepartmentApprovals']);
});
Route::get('fiscalYearSetup/getFiscalYearById/{id}', ['as' => 'fiscalYearSetup.getFiscalYearById', 'uses' => 'FiscalYearSetupController@getFiscalYearById']);
