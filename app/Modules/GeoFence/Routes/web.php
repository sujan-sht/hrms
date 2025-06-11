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
    //GeoFence
    Route::get('/geofence', 'GeoFenceController@index')->name('geoFence.index');
    Route::get('/geofence/create', 'GeoFenceController@create')->name('geoFence.create');
    Route::post('/geofence/store', 'GeoFenceController@store')->name('geoFence.store');
    Route::post('geofence/store-ajax', 'GeoFenceController@storeAjax')->name('geoFence.storeAjax');
    Route::get('/geofence/edit/{id}', 'GeoFenceController@edit')->name('geoFence.edit');
    Route::post('/geofence/update/{id}', 'GeoFenceController@update')->name('geoFence.update');
    Route::get('/geofence/delete/{id}', 'GeoFenceController@destroy')->name('geoFence.delete');

    //GeoFence Allocation
    Route::get('geofence/{id}/allocations', 'GeoFenceController@allocationList')->name('geoFence.allocationList');
    Route::get('geofence/{id}/allocate-form', 'GeoFenceController@allocateForm')->name('geoFence.allocateForm');
    Route::post('geofence/{id}/allocate', 'GeoFenceController@allocate')->name('geoFence.allocate');

    Route::get('/geofence/{geofence_id}/edit/{id}', 'GeoFenceController@editAllocation')->name('geoFence.editAllocation');
    Route::put('/geofence/{geofence_id}/update/{id}', 'GeoFenceController@updateAllocation')->name('geoFence.updateAllocation');
    Route::get('/geofence/{geofence_id}/delete/{id}', 'GeoFenceController@destroyAllocation')->name('geoFence.destroyAllocation');
    //
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'XssSanitizer']], function () {
    //Filter
    Route::get('/geofence/filter-org-department-wise', 'GeoFenceController@filterOrgDepartmentwise')->name('geoFence.filterOrgDepartmentwise');

    //Check Exist
    Route::get('/allocation/checkExists', 'GeoFenceController@checkExists')->name('allocation.checkExists');

    Route::get('/geofence/clone-day', ['as' => 'geoFence.clone.day', 'uses' => 'GeoFenceController@cloneDay']);
});
