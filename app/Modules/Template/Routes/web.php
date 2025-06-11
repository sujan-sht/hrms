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
    Route::get('cheat-sheets', ['as' => 'cheatSheet.index', 'uses' => 'CheatSheetController@index']);
    Route::get('cheat-sheet/create', ['as' => 'cheatSheet.create', 'uses' => 'CheatSheetController@create']);
    Route::post('cheat-sheet/store', ['as' => 'cheatSheet.store', 'uses' => 'CheatSheetController@store']);
    Route::get('cheat-sheet/{id}/edit', ['as' => 'cheatSheet.edit', 'uses' => 'CheatSheetController@edit']);
    Route::put('cheat-sheet/{id}/update', ['as' => 'cheatSheet.update', 'uses' => 'CheatSheetController@update']);
    Route::get('cheat-sheet/{id}/delete', ['as' => 'cheatSheet.delete', 'uses' => 'CheatSheetController@destroy']);

    Route::get('templates', ['as' => 'template.index', 'uses' => 'TemplateController@index']);
    Route::get('template/create/{templateTypeId}', ['as' => 'template.create', 'uses' => 'TemplateController@create']);
    Route::post('template/store', ['as' => 'template.store', 'uses' => 'TemplateController@store']);
    Route::get('template/{id}/edit', ['as' => 'template.edit', 'uses' => 'TemplateController@edit']);
    Route::put('template/{id}/update', ['as' => 'template.update', 'uses' => 'TemplateController@update']);
    Route::get('template/{id}/delete', ['as' => 'template.delete', 'uses' => 'TemplateController@destroy']);
    Route::get('template/{id}/show', ['as' => 'template.show', 'uses' => 'TemplateController@show']);

    Route::get('template-type', ['as' => 'templateType.index', 'uses' => 'TemplateTypeController@index']);
    Route::get('template-type/create', ['as' => 'templateType.create', 'uses' => 'TemplateTypeController@create']);
    Route::post('template-type/store', ['as' => 'templateType.store', 'uses' => 'TemplateTypeController@store']);
    Route::get('template-type/{id}/edit', ['as' => 'templateType.edit', 'uses' => 'TemplateTypeController@edit']);
    Route::put('template-type/{id}/update', ['as' => 'templateType.update', 'uses' => 'TemplateTypeController@update']);
    Route::get('template-type/{id}/delete', ['as' => 'templateType.delete', 'uses' => 'TemplateTypeController@destroy']);

    //Letter Management
    Route::resource('letter-management', LetterManagementController::class)->names([
        'index' => 'letterManagement.index',
        'create' => 'letterManagement.create',
        'store' => 'letterManagement.store',
        'show' => 'letterManagement.show',
        'edit' => 'letterManagement.edit',
        'update' => 'letterManagement.update',
    ]);
    Route::get('letter-management/{id}/delete', ['as' => 'letterManagement.delete', 'uses' => 'LetterManagementController@destroy']);
});
