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


Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'permission']], function () {

    Route::get('organization-structure', ['as' => 'organizationalStructure.index', 'uses' => 'OrganizationalStructureController@index']);
    Route::get('organization-structure/create', ['as' => 'organizationalStructure.create', 'uses' => 'OrganizationalStructureController@create']);
    Route::post('organization-structure/store', ['as' => 'organizationalStructure.store', 'uses' => 'OrganizationalStructureController@store']);
    Route::get('organization-structure/edit/{id}', ['as' => 'organizationalStructure.edit', 'uses' => 'OrganizationalStructureController@edit'])->where('id', '[0-9]+');
    Route::put('organization-structure/update/{id}', ['as' => 'organizationalStructure.update', 'uses' => 'OrganizationalStructureController@update'])->where('id', '[0-9]+');
    Route::get('organization-structure/view/{id}', ['as' => 'organizationalStructure.view', 'uses' => 'OrganizationalStructureController@show'])->where('id', '[0-9]+');
    Route::get('organization-structure/delete/{id}', ['as' => 'organizationalStructure.delete', 'uses' => 'OrganizationalStructureController@destroy'])->where('id', '[0-9]+');
});

Route::get('organization-structure/clone-day', ['as' => 'organizationalStructure.clone.day', 'uses' => 'OrganizationalStructureController@cloneDay']);
Route::get('organization-structure/get-other-employee-list', ['as' => 'organizationalStructure.getOtherEmployeeList', 'uses' => 'OrganizationalStructureController@getOtherEmployeeList']);

