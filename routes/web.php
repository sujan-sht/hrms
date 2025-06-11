<?php

use App\Modules\Employee\Entities\Employee;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/template', function () {
    return view('template-offerletter');
});

Route::get('/updateEmployeeCode/{organization_id}/series/{series}', function ($organization_id, $series) {
    $employees =  DB::table('employees')->where('organization_id', $organization_id)->orderBy('join_date', 'ASC')->get();
    $i = 0;
    foreach ($employees as $employee) {
        $new_employee_code = $series + $i;
        $emp = Employee::find($employee->id);
        $emp->update([
            'employee_code' => $new_employee_code
        ]);
        $i++;
    }

    return "Employee codes updated successfully!";
});
