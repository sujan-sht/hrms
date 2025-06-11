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
    // unit routes
    Route::get('units', ['as' => 'unit.index', 'uses' => 'UnitController@index']);
    Route::get('unit/create', ['as' => 'unit.create', 'uses' => 'UnitController@create']);
    Route::post('unit/store', ['as' => 'unit.store', 'uses' => 'UnitController@store']);
    Route::get('unit/{id}/edit', ['as' => 'unit.edit', 'uses' => 'UnitController@edit']);
    Route::put('unit/{id}/update', ['as' => 'unit.update', 'uses' => 'UnitController@update']);
    Route::get('unit/{id}/delete', ['as' => 'unit.delete', 'uses' => 'UnitController@destroy']);
    Route::get('unit/export', ['as' => 'unit.export', 'uses' => 'UnitController@export']);
    Route::post('unit/uploadUnit', ['as' => 'unit.uploadUnit', 'uses' => 'UnitController@uploadEmployee']);
});

