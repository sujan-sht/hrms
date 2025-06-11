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

use App\Modules\User\Http\Controllers\OtpResetPasswordController;

Route::middleware('throttle:10,1')->prefix('')->group(function () {

    Route::get('admin/permission-denied', ['as' => 'permission.denied', 'uses' => 'LoginController@permissionDenied']);

    //Login
    Route::get('/', ['as' => '/', 'uses' => 'LoginController@login']);
    Route::get('/', ['as' => 'login', 'uses' => 'LoginController@login']);
    Route::post('login', ['as' => 'login-post', 'uses' => 'LoginController@authenticate']);

    //Change Password

    Route::get('change-password', ['as' => 'change-password', 'uses' => 'LoginController@changePassword']);
    Route::post('update-password', ['as' => 'update-password', 'uses' => 'LoginController@updatePassword']);

    Route::post('change-username', ['as' => 'change-username', 'uses' => 'LoginController@changeUsername']);

    // forgot password
    Route::get('reset-password', ['as' => 'forgot-password', 'uses' => 'LoginController@resetPassword']);
    Route::post('send-reset-password-link', ['as' => 'reset-password-link', 'uses' => 'LoginController@resetPasswordLink']);

    Route::get('set-password/{username}', ['as' => 'set-password-view', 'uses' => 'LoginController@setPasswordView']);
    Route::post('set-password', ['as' => 'set-password', 'uses' => 'LoginController@setPassword']);


    //Logout
    Route::get('logout', ['as' => 'logout', 'uses' => 'LoginController@logout']);

    Route::group(['prefix' => 'otp-reset-password', 'as' => 'otp-reset-password.'], function () {
        Route::get('get-user', [OtpResetPasswordController::class, 'getResetPasswordUser'])->name('get-user');
        Route::post('grab-user', [OtpResetPasswordController::class, 'grabResetPasswordUser'])->name('grab-user');

        Route::get('/otp/{otp}', [OtpResetPasswordController::class, 'showOtp'])->name('otp.show');
        Route::post('/otp/{otp}', [OtpResetPasswordController::class, 'verifyOtp'])->name('otp.verify');
        Route::post('/otp-choose-mode/{otp}', [OtpResetPasswordController::class, 'chooseModeOtp'])->name('otp.chooseMode');
        Route::get('/otp-choose-different-mode/{otp}', [OtpResetPasswordController::class, 'chooseDifferentModeOtp'])->name('otp.chooseDifferentMode');
        Route::get('otp/regenerate/{otp}', [OtpResetPasswordController::class, 'regenerateOtp'])->name('otp.regenerate');

        // Reset Form
        Route::get('/change-password/{otp}', [OtpResetPasswordController::class, 'resetPassword'])->name('change-password');
        Route::post('/change-password/{otp}', [OtpResetPasswordController::class, 'updateResetPassword'])->name('update-password');
    });
});



Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'web', 'XssSanitizer', 'permission']], function () {

    Route::get('role', ['as' => 'role.index', 'uses' => 'RoleController@index']);

    Route::get('role/create', ['as' => 'role.create', 'uses' => 'RoleController@create']);
    Route::post('role/store', ['as' => 'role.store', 'uses' => 'RoleController@store']);

    Route::get('role/edit/{id}', ['as' => 'role.edit', 'uses' => 'RoleController@edit'])->where('id', '[0-9]+');
    Route::put('role/update/{id}', ['as' => 'role.update', 'uses' => 'RoleController@update'])->where('id', '[0-9]+');

    Route::get('role/delete/{id}', ['as' => 'role.delete', 'uses' => 'RoleController@destroy'])->where('id', '[0-9]+');

    Route::get('user/remove-access/{id}/{emp_id}', ['as' => 'user.removeAccess', 'uses' => 'UserController@removeAccess'])->where('id', '[0-9]+');

    //activity log report
    Route::get('setting/activity-logs', ['as' => 'setting.activityLogReport', 'uses' => 'LoginController@activityLogReport']);
});
