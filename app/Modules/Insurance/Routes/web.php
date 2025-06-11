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


Route::group(['prefix' => 'admin/insurance', 'middleware' => ['auth', 'permission', 'XssSanitizer']], function () {

    /**
     * Insurance Type
     */
    Route::get('/type', ['as' => 'insurance.type.index', 'uses' => 'InsuranceTypeController@index']);
    Route::get('/type/create', ['as' => 'insurance.type.create', 'uses' => 'InsuranceTypeController@create']);
    Route::post('/type/store', ['as' => 'insurance.type.store', 'uses' => 'InsuranceTypeController@store']);
    Route::get('/type/{id}/edit', ['as' => 'insurance.type.edit', 'uses' => 'InsuranceTypeController@edit']);
    Route::get('/type/show', ['as' => 'insurance.type.show', 'uses' => 'InsuranceTypeController@show']);
    Route::put('/type/{id}/update', ['as' => 'insurance.type.update', 'uses' => 'InsuranceTypeController@update']);
    Route::get('/type/{id}/delete', ['as' => 'insurance.type.delete', 'uses' => 'InsuranceTypeController@destroy']);


    /**
     * Insurance
     */
    Route::get('/', ['as' => 'insurance.index', 'uses' => 'InsuranceController@index']);
    Route::get('/create', ['as' => 'insurance.create', 'uses' => 'InsuranceController@create']);
    Route::post('/store', ['as' => 'insurance.store', 'uses' => 'InsuranceController@store']);
    Route::get('/{id}/show', ['as' => 'insurance.show', 'uses' => 'InsuranceController@show']);
    Route::get('/{id}/edit', ['as' => 'insurance.edit', 'uses' => 'InsuranceController@edit']);
    Route::put('/{id}/update', ['as' => 'insurance.update', 'uses' => 'InsuranceController@update']);
    Route::get('/{id}/delete', ['as' => 'insurance.delete', 'uses' => 'InsuranceController@destroy']);
});
