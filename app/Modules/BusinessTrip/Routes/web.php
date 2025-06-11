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
    //Types
    Route::get('travel-request-types', ['as' => 'travelRequestType.index', 'uses' => 'TravelRequestTypeController@index']);
    Route::get('travel-request-type/create', ['as' => 'travelRequestType.create', 'uses' => 'TravelRequestTypeController@create']);
    Route::post('travel-request-type/store', ['as' => 'travelRequestType.store', 'uses' => 'TravelRequestTypeController@store']);
    Route::get('travel-request-type/edit/{id}', ['as' => 'travelRequestType.edit', 'uses' => 'TravelRequestTypeController@edit'])->where('id', '[0-9]+');
    Route::put('travel-request-type/update/{id}', ['as' => 'travelRequestType.update', 'uses' => 'TravelRequestTypeController@update'])->where('id', '[0-9]+');
    Route::get('travel-request-type/delete/{id}', ['as' => 'travelRequestType.delete', 'uses' => 'TravelRequestTypeController@destroy'])->where('id', '[0-9]+');

    Route::get('travel-requests', ['as' => 'businessTrip.index', 'uses' => 'BusinessTripController@index']);
    Route::get('travel-request/create', ['as' => 'businessTrip.create', 'uses' => 'BusinessTripController@create']);
    Route::post('travel-request/store', ['as' => 'businessTrip.store', 'uses' => 'BusinessTripController@store']);
    Route::get('travel-request/edit/{id}', ['as' => 'businessTrip.edit', 'uses' => 'BusinessTripController@edit'])->where('id', '[0-9]+');
    Route::put('travel-request/update/{id}', ['as' => 'businessTrip.update', 'uses' => 'BusinessTripController@update'])->where('id', '[0-9]+');
    Route::get('travel-request/delete/{id}', ['as' => 'businessTrip.delete', 'uses' => 'BusinessTripController@destroy'])->where('id', '[0-9]+');
    Route::get('travel-request/{id}/view', ['as' => 'businessTrip.show', 'uses' => 'BusinessTripController@show']);

    Route::put('travel-request/updateStatus', ['as' => 'businessTrip.updateStatus', 'uses' => 'BusinessTripController@updateStatus']);
    Route::put('travel-request/updateClaimStatus', ['as' => 'businessTrip.updateClaimStatus', 'uses' => 'BusinessTripController@updateClaimStatus']);
    Route::put('travel-request/cancel-request', ['as' => 'businessTrip.cancelRequest', 'uses' => 'BusinessTripController@cancelRequest']);


    Route::get('travel-request/team-requests', ['as' => 'businessTrip.teamRequests', 'uses' => 'BusinessTripController@teamRequests']);
    //

    // Route::get('travel-request/allowance-setup', ['as' => 'businessTrip.allowanceSetup', 'uses' => 'BusinessTripController@allowanceSetup']);
    Route::get('travel-request/allowance-setup', ['as' => 'businessTrip.allowanceSetupTest', 'uses' => 'BusinessTripController@allowanceSetupTest']);
    Route::post('travel-request/allowance-setup/store', ['as' => 'businessTrip.storeEmployeeAllowance', 'uses' => 'BusinessTripController@storeEmployeeAllowance']);
    Route::post('travel-request/allowance-setup/store/test', ['as' => 'businessTrip.storeEmployeeAllowanceTest', 'uses' => 'BusinessTripController@storeEmployeeAllowanceTest']);

    Route::get('travel-request/download-pdf/{id}', ['as' => 'businessTrip.downloadPDF', 'uses' => 'BusinessTripController@downloadPDF']);
    Route::get('filter-bussiness-trip', ['as' => 'filter.bussinesstripe', 'uses' => 'BusinessTripController@filterBussinessTrip']);
    Route::get('bussiness-trip-report', ['as' => 'businessTrip.report', 'uses' => 'BusinessTripController@bussinessTripReport']);

    /**
     * Travel Expense
     */

    Route::get('travel-expense', ['as' => 'travelexpense.index', 'uses' => 'TravelExpenseController@index']);
    Route::get('travel-expense/create', ['as' => 'travelexpense.create', 'uses' => 'TravelExpenseController@create']);
    Route::post('travel-expense/create', ['as' => 'travelexpense.store', 'uses' => 'TravelExpenseController@store']);
    Route::get('travel-expense/edit/{id}', ['as' => 'travelexpense.edit', 'uses' => 'TravelExpenseController@edit']);
    Route::put('travel-expense/update/{id}', ['as' => 'travelexpense.update', 'uses' => 'TravelExpenseController@update']);
    Route::get('travel-expense/delete/{id}', ['as' => 'travelexpense.destroy', 'uses' => 'TravelExpenseController@destroy']);
});

Route::post('travel-request/post-process-data', ['as' => 'businessTrip.postProcessData', 'uses' => 'BusinessTripController@postProcessData']);
Route::get('get-employee-info', ['as' => 'get.employee.info', 'uses' => 'BusinessTripController@getEmployee']);
