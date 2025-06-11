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

    Route::get('notice', ['as' => 'notice.index', 'uses' => 'NoticeController@index']);

    Route::get('notice/create', ['as' => 'notice.create', 'uses' => 'NoticeController@create']);
    Route::post('notice/store', ['as' => 'notice.store', 'uses' => 'NoticeController@store']);

    Route::get('notice/edit/{id}', ['as' => 'notice.edit', 'uses' => 'NoticeController@edit'])->where('id', '[0-9]+');
    Route::put('notice/update/{id}', ['as' => 'notice.update', 'uses' => 'NoticeController@update'])->where('id', '[0-9]+');

    Route::get('notice/delete/{id}', ['as' => 'notice.delete', 'uses' => 'NoticeController@destroy'])->where('id', '[0-9]+');
    Route::get('notice/view/{id}', ['as' => 'notice.view', 'uses' => 'NoticeController@view'])->where('id', '[0-9]+');
});

// Route::group(['prefix' => 'employee', 'middleware' => ['auth', 'permission','XssSanitizer']], function () {

//     Route::get('notice', ['as' => 'employee-notice.index', 'uses' => 'EmployeeNoticeController@index']);

//     Route::get('notice/create', ['as' => 'employee-notice.create', 'uses' => 'EmployeeNoticeController@create']);
//     Route::post('notice/store', ['as' => 'employee-notice.store', 'uses' => 'EmployeeNoticeController@store']);

//     Route::get('notice/edit/{id}', ['as' => 'employee-notice.edit', 'uses' => 'EmployeeNoticeController@edit'])->where('id', '[0-9]+');
//     Route::put('notice/update/{id}', ['as' => 'employee-notice.update', 'uses' => 'EmployeeNoticeController@update'])->where('id', '[0-9]+');

//     Route::get('notice/delete/{id}', ['as' => 'employee-notice.delete', 'uses' => 'EmployeeNoticeController@destroy'])->where('id', '[0-9]+');
//     Route::get('notice/view/{id}', ['as' => 'employee-notice.view', 'uses' => 'EmployeeNoticeController@view'])->where('id', '[0-9]+');

// });
Route::get('admin/notice/getOrganizationEmployee', 'NoticeController@getOrganizationEmployee');
Route::get('admin/notice/getOrganizationBranch', 'NoticeController@getOrganizationBranch');


Route::get('admin/notice/downloadSheet', 'NoticeController@downloadSheet')->name('notice.downloadSheet');
