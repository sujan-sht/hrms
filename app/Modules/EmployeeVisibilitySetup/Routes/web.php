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
use App\Modules\EmployeeVisibilitySetup\Entities\EmployeeVisibilitySetup;
use App\Modules\EmployeeVisibilitySetup\Http\Controllers\EmployeeVisibilitySetupController;

Route::prefix('admin/employeevisibilitysetup')->group(function() {

    Route::get('index',[EmployeeVisibilitySetupController::class,'ListAll'])->name('employeeVisibilitySetup.index');
    Route::post('store',[EmployeeVisibilitySetupController::class,'Store'])->name('employeeVisibilitySetup.store');

});
