<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Advance\Http\Controllers\AdvanceController;
use App\Modules\Advance\Http\Controllers\AdvancePaymentLedgerController;
use App\Modules\Advance\Http\Controllers\AdvanceSettlementPaymentController;

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
    Route::get('advances', [AdvanceController::class, 'index'])->name('advance.index');
    Route::get('advance/create', ['as' => 'advance.create', 'uses' => 'AdvanceController@create']);
    Route::post('advance/store', ['as' => 'advance.store', 'uses' => 'AdvanceController@store']);
    Route::get('advance/{id}/view', ['as' => 'advance.view', 'uses' => 'AdvanceController@show']);
    Route::get('advance/{id}/edit', ['as' => 'advance.edit', 'uses' => 'AdvanceController@edit']);
    Route::put('advance/{id}/update', ['as' => 'advance.update', 'uses' => 'AdvanceController@update']);
    Route::get('advance/{id}/delete', ['as' => 'advance.delete', 'uses' => 'AdvanceController@destroy']);
    Route::post('advance/update-status', ['as' => 'advance.updateStatus', 'uses' => 'AdvanceController@updateStatus']);

    Route::get('advance/print-preview/{id}', ['as' => 'advance.printPreview', 'uses' => 'AdvanceController@printPreview']);

    Route::get('advance/payment-ledger', [AdvancePaymentLedgerController::class, 'report'])->name('advance.paymentLedger');
    Route::post('advance/payment/store', [AdvanceSettlementPaymentController::class, 'store'])->name('advance.pay');
});
Route::get('advance/clone', ['as' => 'advance.clone', 'uses' => 'AdvanceController@clone']);
