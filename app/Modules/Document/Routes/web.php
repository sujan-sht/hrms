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

    Route::get('documents', ['as' => 'document.index', 'uses' => 'DocumentController@index']);
    Route::get('document/create', ['as' => 'document.create', 'uses' => 'DocumentController@create']);
    Route::post('document/store', ['as' => 'document.store', 'uses' => 'DocumentController@store']);
    Route::get('document/{id}/view', ['as' => 'document.show', 'uses' => 'DocumentController@show']);
    Route::get('document/{id}/edit', ['as' => 'document.edit', 'uses' => 'DocumentController@edit']);
    Route::put('document/{id}/update', ['as' => 'document.update', 'uses' => 'DocumentController@update']);
    Route::get('document/{id}/delete', ['as' => 'document.delete', 'uses' => 'DocumentController@destroy']);

    //Shared document list
    Route::get('document/shared-list', ['as' => 'shared-list.document', 'uses' => 'DocumentController@sharedDocumentList']);
});
