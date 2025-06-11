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

use App\Modules\Labour\Http\Controllers\LabourController;

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'permission', 'XssSanitizer']], function () {
    
    Route::get('skill-setup', 'SkillSetupController@index')->name('skillSetup.index');
    Route::get('skill-setup/create', 'SkillSetupController@create')->name('skillSetup.create');
    Route::post('skill-setup/store', 'SkillSetupController@store')->name('skillSetup.store');
    Route::get('skill-setup/{id}/edit', 'SkillSetupController@edit')->name('skillSetup.edit');
    Route::patch('skill-setup/{id}/update', 'SkillSetupController@update')->name('skillSetup.update');
    Route::get('skill-setup/delete/{id}', 'SkillSetupController@destroy')->name('skillSetup.delete');

    // labour kye
    Route::get('labours', 'LabourController@index')->name('labour.index');
    Route::get('labours/create', 'LabourController@create')->name('labour.create');
    Route::post('labours/store', 'LabourController@store')->name('labour.store');
    Route::get('labours/{id}/edit', 'LabourController@edit')->name('labour.edit');
    Route::patch('labours/{id}/update', 'LabourController@update')->name('labour.update');
    Route::get('labours/delete/{id}', 'LabourController@destroy')->name('labour.delete');

    Route::post('labour/archive', ['as' => 'labour.archive', 'uses' => 'LabourController@archiveLabour']);
    Route::get('labour/active/{id}', ['as' => 'labour.active', 'uses' => 'LabourController@activeLabour']);


    Route::get('labour/download-payslip', 'LabourController@downloadPayslip')->name('labour.downloadPayslip');


    // labour payment
    Route::post('labour-payment/store', 'LabourController@paymentStore')->name('labour.payment');

    Route::get('attendance/division/view-labour-monthly', ['as' => 'labour.viewLabourMonthly', 'uses' => 'LabourController@viewLabourMonthly']);
    Route::post('attendance/labour/update-monthly', ['as' => 'siteLabourAttendance.updateMonthly', 'uses' => 'LabourController@updateLabourMonthly']);

    
    Route::get('labour/wage-management', 'LabourController@wageManagement')->name('labour.wageManagement');
    Route::get('labour/print-pay-slip', 'LabourController@printPaySlip')->name('labour.printPaySlip');
    Route::get('labour/view-pay-slip', 'LabourController@viewPayslip')->name('labour.viewPayslip');
    Route::get('labour/wage-management/export', 'LabourController@exportWage')->name('labour.exportWage');



});

Route::group(['prefix' => 'admin', 'middleware' => ['auth','XssSanitizer']], function () {

    Route::get('labour/payroll-detail', ['as'=>'labour.getDailyWage', 'uses' => 'LabourController@getDailyWage']);

});