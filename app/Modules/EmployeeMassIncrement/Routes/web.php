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

use Illuminate\Support\Facades\Route;
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'permission', 'XssSanitizer']], function () {
    Route::prefix('employee')->group(function() {
        Route::get('payroll/mass-increment', 'EmployeeMassIncrementController@index')->name('employeeMassIncrement.index');
        Route::get('payroll/mass-increment/create', 'EmployeeMassIncrementController@create')->name('employeeMassIncrement.create');
        Route::post('payroll/mass-increment/store', 'EmployeeMassIncrementController@store')->name('employeeMassIncrement.store');
        Route::get('payroll/mass-increment/{id}/edit', 'EmployeeMassIncrementController@edit')->name('employeeMassIncrement.edit');
        Route::put('payroll/mass-increment/{id}/update', 'EmployeeMassIncrementController@update')->name('employeeMassIncrement.update');
        Route::get('payroll/mass-increment/{id}/delete', 'EmployeeMassIncrementController@destroy')->name('employeeMassIncrement.delete');
        Route::get('mass-increment/add-income', 'EmployeeMassIncrementController@addIncome')->name('employeeMassIncrement.addIncome');
        Route::get('mass-increment/fetchincome-employee', 'EmployeeMassIncrementController@fetchincomeEmployee')->name('fetchincome.employee');
    });
});
