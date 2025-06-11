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

Route::prefix('overtimerequest')->group(function() {
    Route::get('/', 'OvertimeRequestController@index');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'permission', 'XssSanitizer']], function () {

    Route::get('overtime-requests', ['as' => 'overtimeRequest.index', 'uses' => 'OvertimeRequestController@index']);
    Route::get('overtime-request/create', ['as' => 'overtimeRequest.create', 'uses' => 'OvertimeRequestController@create']);
    Route::post('overtime-request/store', ['as' => 'overtimeRequest.store', 'uses' => 'OvertimeRequestController@store']);
    Route::get('overtime-request/edit/{id}', ['as' => 'overtimeRequest.edit', 'uses' => 'OvertimeRequestController@edit'])->where('id', '[0-9]+');
    Route::put('overtime-request/update/{id}', ['as' => 'overtimeRequest.update', 'uses' => 'OvertimeRequestController@update'])->where('id', '[0-9]+');
    Route::get('overtime-request/delete/{id}', ['as' => 'overtimeRequest.delete', 'uses' => 'OvertimeRequestController@destroy'])->where('id', '[0-9]+');
    Route::get('overtime-request/{id}/view-detail', ['as' => 'overtimeRequest.viewDetail', 'uses' => 'OvertimeRequestController@viewDetail']);
    Route::get('overtime-request/{id}/claim', ['as' => 'overtimeRequest.claim', 'uses' => 'OvertimeRequestController@claim']);


    Route::put('overtime-request/updateStatus', ['as' => 'overtimeRequest.updateStatus', 'uses' => 'OvertimeRequestController@updateStatus']);
    Route::put('overtime-request/updateClaimStatus', ['as' => 'overtimeRequest.updateClaimStatus', 'uses' => 'OvertimeRequestController@updateClaimStatus']);
    Route::put('overtime-request/cancel-request', ['as' => 'overtimeRequest.cancelRequest', 'uses' => 'OvertimeRequestController@cancelRequest']);


    Route::get('overtime-request/team-requests', ['as' => 'overtimeRequest.teamRequests', 'uses' => 'OvertimeRequestController@teamRequests']);
    Route::get('overtime-request/view-report', ['as' => 'overtimeRequest.viewReport', 'uses' => 'OvertimeRequestController@viewReport']);

    //

    // Route::get('overtime-request/download-pdf/{id}', ['as' => 'overtimeRequest.downloadPDF', 'uses' => 'OvertimeRequestController@downloadPDF']);
});


Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'XssSanitizer']], function () {
    Route::get('overtime-request/check-min-Ot-time', ['as' => 'overtimeRequest.checkMinOtTime', 'uses' => 'OvertimeRequestController@checkMinOtTime']);
});