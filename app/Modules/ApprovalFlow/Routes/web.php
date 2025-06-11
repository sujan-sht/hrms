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
    //ApprovalFlow
    Route::get('approvalFlows', ['as' => 'approvalFlow.index', 'uses' => 'ApprovalFlowController@index']);
    Route::get('approvalFlow/create', ['as' => 'approvalFlow.create', 'uses' => 'ApprovalFlowController@create']);
    Route::post('approvalFlow/store', ['as' => 'approvalFlow.store', 'uses' => 'ApprovalFlowController@store']);
    Route::get('approvalFlow/{id}/edit', ['as' => 'approvalFlow.edit', 'uses' => 'ApprovalFlowController@edit']);
    Route::put('approvalFlow/{id}/update', ['as' => 'approvalFlow.update', 'uses' => 'ApprovalFlowController@update']);
    Route::get('approvalFlow/{id}/delete', ['as' => 'approvalFlow.delete', 'uses' => 'ApprovalFlowController@destroy']);
    Route::get('approvalFlow/fetchDepartmentApprovals', ['as' => 'approvalFlow.fetchDepartmentApprovals', 'uses' => 'ApprovalFlowController@fetchDepartmentApprovals']);
});
