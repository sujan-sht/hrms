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
    //Asset
    Route::get('asset', ['as' => 'asset.index', 'uses' => 'AssetController@index']);
    Route::get('asset/create', ['as' => 'asset.create', 'uses' => 'AssetController@create']);
    Route::post('asset/store', ['as' => 'asset.store', 'uses' => 'AssetController@store']);
    Route::get('asset/{id}/edit', ['as' => 'asset.edit', 'uses' => 'AssetController@edit']);
    Route::put('asset/{id}/update', ['as' => 'asset.update', 'uses' => 'AssetController@update']);
    Route::get('asset/{id}/delete', ['as' => 'asset.delete', 'uses' => 'AssetController@destroy']);

    //Stocks
    Route::get('asset/stocks', ['as' => 'assetQuantity.index', 'uses' => 'AssetQuantityController@index']);
    Route::get('asset/stock/create', ['as' => 'assetQuantity.create', 'uses' => 'AssetQuantityController@create']);
    Route::post('asset/stock/store', ['as' => 'assetQuantity.store', 'uses' => 'AssetQuantityController@store']);
    Route::get('asset/stock/{id}/edit', ['as' => 'assetQuantity.edit', 'uses' => 'AssetQuantityController@edit']);
    Route::put('asset/stock/{id}/update', ['as' => 'assetQuantity.update', 'uses' => 'AssetQuantityController@update']);
    Route::get('asset/stock/{id}/delete', ['as' => 'assetQuantity.delete', 'uses' => 'AssetQuantityController@destroy']);
    Route::get('asset/stock/check-asset-exists', ['as' => 'assetQuantity.checkAssetExists', 'uses' => 'AssetQuantityController@checkAssetExists']);

    //Asset Allocate
    Route::get('asset/allocate', ['as' => 'assetAllocate.index', 'uses' => 'AssetAllocateController@index']);
    Route::get('asset/allocate/create', ['as' => 'assetAllocate.create', 'uses' => 'AssetAllocateController@create']);
    Route::post('asset/allocate/store', ['as' => 'assetAllocate.store', 'uses' => 'AssetAllocateController@store']);
    Route::get('asset/allocate/{id}/edit', ['as' => 'assetAllocate.edit', 'uses' => 'AssetAllocateController@edit']);
    Route::put('asset/allocate/{id}/update', ['as' => 'assetAllocate.update', 'uses' => 'AssetAllocateController@update']);
    Route::get('asset/allocate/{id}/delete', ['as' => 'assetAllocate.delete', 'uses' => 'AssetAllocateController@destroy']);

    Route::get('asset/allocate/export', ['as' => 'assetAllocate.export', 'uses' => 'AssetAllocateController@export']);


});
