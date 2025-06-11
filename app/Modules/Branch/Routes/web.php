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

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'permission','XssSanitizer']], function () {
    // Branch routes
    Route::get('branches', ['as' => 'branch.index', 'uses' => 'BranchController@index']);
    Route::get('branch/create', ['as' => 'branch.create', 'uses' => 'BranchController@create']);
    Route::post('branch/store', ['as' => 'branch.store', 'uses' => 'BranchController@store']);
    Route::get('branch/{id}/edit', ['as' => 'branch.edit', 'uses' => 'BranchController@edit']);
    Route::put('branch/{id}/update', ['as' => 'branch.update', 'uses' => 'BranchController@update']);
    Route::get('branch/{id}/delete', ['as' => 'branch.delete', 'uses' => 'BranchController@destroy']);
    Route::get('branch/export', ['as' => 'branch.export', 'uses' => 'BranchController@export']);
});

//Ajax routes
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'XssSanitizer']], function () {
    Route::get('branch/get-districts-by-province', ['as' => 'branch.get-districts-by-province', 'uses' => 'BranchController@getDistrictsByProvince']);
    Route::get('branch/get-districts-by-provinces', ['as' => 'branch.get-districts-by-provinces', 'uses' => 'BranchController@getDistrictsByProvinces']);
});