<?php

use App\Modules\Loan\Http\Controllers\LoanController;
use Illuminate\Support\Facades\Route;



Route::group(
    [
        'prefix' => 'admin/loan',
        'middleware' => ['auth', 'permission', 'XssSanitizer']
    ],
    function () {
        Route::get('/', [LoanController::class, 'index'])->name('loan.index');
        Route::get('/create', [LoanController::class, 'create'])->name('loan.create');
        Route::post('/store', [LoanController::class, 'store'])->name('loan.store');
        Route::get('/edit/{id}', [LoanController::class, 'edit'])->name('loan.edit');
        Route::patch('/update/{id}', [LoanController::class, 'update'])->name('loan.update');
        Route::get('/delete/{id}', [LoanController::class, 'destroy'])->name('loan.delete');
    }
);
