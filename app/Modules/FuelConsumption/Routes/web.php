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

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'permission', 'XssSanitizer']], function () {
    //FiscalYearSetup
    Route::get('/fuelConsumption', 'FuelConsumptionController@index')->name('fuelConsumption');
    Route::get('/fuelConsumption/create', 'FuelConsumptionController@create')->name('fuelConsumption.create');
    Route::post('/fuelConsumption/store', 'FuelConsumptionController@store')->name('fuelConsumption.store');
    Route::get('fuelConsumption/edit', 'FuelConsumptionController@edit')->name('fuelConsumption.edit');
    Route::put('fuelConsumption/update/{id}', 'FuelConsumptionController@update')->name('fuelConsumption.update')->where('id', '[0-9]+');
    Route::get('fuelConsumption/delete/{id}', 'FuelConsumptionController@destroy')->name('fuelConsumption.delete')->where('id', '[0-9]+');
    Route::post('fuelConsumption/updateStatus', 'FuelConsumptionController@updateStatus')->name('fuelConsumption.updateStatus');
    Route::get('fuelConsumption/verifyRequest/{id}', 'FuelConsumptionController@verifyRequest')->name('fuelConsumption.verifyRequest')->where('id','[0-9]+');
    
    Route::post('fuelConsumption/get-fuelConsumption-detail-ajax', 'FuelConsumptionController@getfuelConsumptionDetailAjax')->name('fuelConsumption.get-fuelConsumption-detail-ajax');
    Route::get('fuelConsumption/printInvoice/{id}', 'FuelConsumptionController@printInvoice')->name('fuelConsumption.printInvoice')->where('id','[0-9]+');

    Route::get('fuelConsumptionDownload', 'FuelConsumptionController@fuelConsumptionDownload')->name('fuelConsumptionDownload');
    // Route::get('fuelConsumptionDownload', 'FuelConsumptionController@fuelConsumptionReportExport')->name('fuelConsumptionDownload');
});
