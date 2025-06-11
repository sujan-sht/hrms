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

use App\Modules\Payroll\Http\Controllers\PayrollController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'permission', 'XssSanitizer']], function () {

    // Payroll routes
    Route::get('payroll', 'PayrollController@index')->name('payroll.index');
    Route::get('payroll/create', 'PayrollController@create')->name('payroll.create');
    Route::post('payroll/store', 'PayrollController@store')->name('payroll.store');

    Route::get('payroll/{id}/view-resigned-employee', 'PayrollController@showResignedEmployee')->name('payroll.viewResignedEmployee');
    Route::post('payroll/{id}/draft', 'PayrollController@draft')->name('payroll.draft');
    Route::get('payroll/{id}/delete', 'PayrollController@destroy')->name('payroll.delete');
    Route::get('payroll/{id}/view-employee', 'PayrollController@viewEmployee')->name('payroll.view.employee');
    Route::get('payroll/{id}/employee-history', 'PayrollController@employeeHistory')->name('payroll.employee.history');
    Route::get('payroll/{id}/employee-salary-slip', 'PayrollController@employeeSalarySlip')->name('payroll.employee.salary.slip');
    Route::get('payroll/{id}/salary-transfer-letter', 'PayrollController@salaryTransfer')->name('payroll.salary.transfer');
    Route::get('payroll/{id}/salary-hold-payment', 'PayrollController@holdPayment')->name('payroll.hold.payment');
    Route::get('payroll/log-report', 'PayrollController@logReport')->name('payroll.log.report');
    Route::get('payroll/benefit-report', 'PayrollController@ssfReport')->name('payroll.ssf.report');
    Route::get('payroll/tds-report', 'PayrollController@tdsReport')->name('payroll.tds.report');
    Route::get('payroll/yearly-forecast', 'PayrollController@YearlyTaxReport')->name('payroll.yearlyTax.report');
    Route::get('payroll/yearly-payslip', 'PayrollController@yearlyPaySlip')->name('payroll.yearlyPaySlip');
    Route::get('payroll/{id}/taxCalculation', 'PayrollController@taxCalculation')->name('payroll.taxCalculation');
    Route::get('payroll/fnf-settlement', 'PayrollController@fnfSettlement')->name('payroll.fnfSettlement');
    Route::get('payroll/fnf-settlement-reports', 'PayrollController@fnfSettlementReports')->name('payroll.fnfSettlement-reports');
    Route::get('payroll/fnf-settlement-reports-view/{id}', 'PayrollController@fnfSettlementReportsView')->name('payroll.fnfSettlement-reports-view');
    Route::get('payroll/{id}/report/departmentwise', 'PayrollController@departmentwiseReport')->name('payroll.departmentwiseReport');
    Route::get('payroll/{id}/report/ird', 'PayrollController@irdReport')->name('payroll.irdReport');
    Route::get('payroll/export-payroll-static-data', 'PayrollController@exportStaticData')->name('payroll.exportStaticData');
    Route::post('payroll/upload-static-data', 'PayrollController@uploadPayrollStaticData')->name('payroll.uploadPayrollStaticData');
    Route::post('payroll/full-n-final','PayrollController@saveFullandfinal')->name('save.fullandfinal');
    Route::get('payroll/fnfSettlement-projection-reports', 'PayrollController@fnfSettlementProjectionReports')->name('payroll.fnfSettlement-projection-reports');

    Route::group(['prefix' => 'payroll'], function () {
        //Threshold Benefit Setup
        Route::get('threshold-benefit-setup', 'ThresholdBenefitController@index')->name('thresholdBenefitSetup.index');
        Route::get('threshold-benefit-setup/create', 'ThresholdBenefitController@create')->name('thresholdBenefitSetup.create');
        Route::post('threshold-benefit-setup/store', 'ThresholdBenefitController@store')->name('thresholdBenefitSetup.store');
        Route::get('threshold-benefit-setup/{id}/edit', 'ThresholdBenefitController@edit')->name('thresholdBenefitSetup.edit');
        Route::put('threshold-benefit-setup/{id}/update', 'ThresholdBenefitController@update')->name('thresholdBenefitSetup.update');
        Route::get('threshold-benefit-setup/{id}/delete', 'ThresholdBenefitController@destroy')->name('thresholdBenefitSetup.destroy');

        //Mass Increment
        Route::get('mass-increment', 'MassIncrementController@index')->name('massIncrement.index');
        Route::get('mass-increment/create', 'MassIncrementController@create')->name('massIncrement.create');
        Route::post('mass-increment/store', 'MassIncrementController@store')->name('massIncrement.store');
        Route::get('mass-increment/{id}/edit', 'MassIncrementController@edit')->name('massIncrement.edit');
        Route::put('mass-increment/{id}/update', 'MassIncrementController@update')->name('massIncrement.update');
        Route::get('mass-increment/{id}/delete', 'MassIncrementController@destroy')->name('massIncrement.destroy');
        //cron
        Route::get('mass-increment/gross-employee-setup/store', 'MassIncrementController@updateGrossEmployeeSetup')->name('massIncrement.updateGrossEmployeeSetup');

        //Arrear Adjustment
        Route::get('arrear-adjustment', 'ArrearAdjustmentController@index')->name('arrearAdjustment.index');
        Route::get('arrear-adjustment/create', 'ArrearAdjustmentController@create')->name('arrearAdjustment.create');
        Route::post('arrear-adjustment/store', 'ArrearAdjustmentController@store')->name('arrearAdjustment.store');
        Route::get('arrear-adjustment/{id}/edit', 'ArrearAdjustmentController@edit')->name('arrearAdjustment.edit');
        Route::put('arrear-adjustment/{id}/update', 'ArrearAdjustmentController@update')->name('arrearAdjustment.update');
        Route::get('arrear-adjustment/{id}/delete', 'ArrearAdjustmentController@destroy')->name('arrearAdjustment.destroy');
        Route::get('arrear-adjustment/export-arrear-adjustment', 'ArrearAdjustmentController@exportArrearAdjustment')->name('exportArrearAdjustment');

        Route::get('arrear-adjustment/add-income', 'ArrearAdjustmentController@addIncome')->name('arrearAdjustment.addIncome');


        //Income Setup
        Route::get('income-setup', 'IncomeSetupController@index')->name('incomeSetup.index');
        Route::get('income-setup/create', 'IncomeSetupController@create')->name('incomeSetup.create');
        Route::post('income-setup/store', 'IncomeSetupController@store')->name('incomeSetup.store');
        Route::get('income-setup/{id}/edit', 'IncomeSetupController@edit')->name('incomeSetup.edit');
        Route::put('income-setup/{id}/update', 'IncomeSetupController@update')->name('incomeSetup.update');
        Route::get('income-setup/{id}/delete', 'IncomeSetupController@destroy')->name('incomeSetup.destroy');
        Route::get('income-setup/check-income-setup-order', ['as' => 'income.setup.checkOrder', 'uses' => 'IncomeSetupController@checkIncomeOrder'])->where('id', '[0-9]+');

        Route::get('income-setup/export-employee-income', 'EmployeeSetupController@exportIncome')->name('payroll.exportIncome');
        Route::get('deduction-setup/export-employee-deduction', 'EmployeeSetupController@exportDeduction')->name('payroll.exportDeduction');

        //Deduction Setup
        Route::get('deduction-setup', 'DeductionSetupController@index')->name('deductionSetup.index');
        Route::get('deduction-setup/create', 'DeductionSetupController@create')->name('deductionSetup.create');
        Route::post('deduction-setup/store', 'DeductionSetupController@store')->name('deductionSetup.store');
        Route::get('deduction-setup/{id}/edit', 'DeductionSetupController@edit')->name('deductionSetup.edit');
        Route::put('deduction-setup/{id}/update', 'DeductionSetupController@update')->name('deductionSetup.update');
        Route::get('deduction-setup/{id}/delete', 'DeductionSetupController@destroy')->name('deductionSetup.destroy');
        Route::get('deduction-setup/check-deduction-setup-order', ['as' => 'deduction.setup.checkOrder', 'uses' => 'DeductionSetupController@checkDeductionOrder'])->where('id', '[0-9]+');

        //Leave Amount Setup
        Route::get('leave-amount-setup', 'LeaveAmountSetupController@index')->name('leaveAmountSetup.index');
        Route::get('leave-amount-setup/create', 'LeaveAmountSetupController@create')->name('leaveAmountSetup.create');
        Route::post('leave-amount-setup/store', 'LeaveAmountSetupController@store')->name('leaveAmountSetup.store');
        Route::get('leave-amount-setup/{id}/edit', 'LeaveAmountSetupController@edit')->name('leaveAmountSetup.edit');
        Route::put('leave-amount-setup/{id}/update', 'LeaveAmountSetupController@update')->name('leaveAmountSetup.update');
        Route::get('leave-amount-setup/{id}/delete', 'LeaveAmountSetupController@destroy')->name('leaveAmountSetup.destroy');

        //Bounus Setup
        Route::get('bonus-setup', 'BonusSetupController@index')->name('bonusSetup.index');
        Route::get('bonus-setup/create', 'BonusSetupController@create')->name('bonusSetup.create');
        Route::post('bonus-setup/store', 'BonusSetupController@store')->name('bonusSetup.store');
        Route::get('bonus-setup/{id}/edit', 'BonusSetupController@edit')->name('bonusSetup.edit');
        Route::put('bonus-setup/{id}/update', 'BonusSetupController@update')->name('bonusSetup.update');
        Route::get('bonus-setup/{id}/delete', 'BonusSetupController@destroy')->name('bonusSetup.destroy');

        //Assign Employee Gross Salary
        Route::get('employee-setup/gross-salary', 'EmployeeSetupController@grossSalary')->name('employeeSetup.grossSalary');
        Route::post('employee-setup/gross-salary', 'EmployeeSetupController@storeGrossSalary')->name('employeeSetup.store.grossSalary');

        //gross salary bulk upload
        Route::post('gross-salary/upload', 'EmployeeSetupController@uploadGrossSalary')->name('payroll.uploadGrossSalary');

        //gross salary export
        Route::get('gross-salary/export-report', 'EmployeeSetupController@exportGrossSalary')->name('payroll.exportGrossSalary');

        Route::get('/export-employee-tax-exclude', 'EmployeeSetupController@exportTaxExclude')->name('payroll.exportTaxExclude');

        Route::get('employee-setup/income', 'EmployeeSetupController@income')->name('employeeSetup.income');
        Route::post('employee-setup/income', 'EmployeeSetupController@storeIncome')->name('employeeSetup.store.income');
        Route::get('income-setup/show', 'EmployeeSetupController@showIncomes')->name('employeeSetup.showIncomes');
        Route::get('fetchIncomeUpdateCalculation','EmployeeSetupController@fetchIncomeUpdateCalculation')->name('fetchIncomeUpdateCalculation');

        //Employee Income bulk upload
        Route::post('employee-setup/income/bulk-upload', 'EmployeeSetupController@uploadEmployeeIncome')->name('payroll.uploadEmployeeIncome');
        Route::post('employee-setup/tax-exclude/bulk-upload', 'EmployeeSetupController@uploadEmployeeTaxExclude')->name('payroll.uploadEmployeeTaxExclude');

        Route::get('employee-setup/deduction', 'EmployeeSetupController@deduction')->name('employeeSetup.deduction');
        Route::post('employee-setup/deduction', 'EmployeeSetupController@storeDeduction')->name('employeeSetup.store.deduction');
        Route::get('employee-setup/view-deduction', 'EmployeeSetupController@viewDeduction')->name('payroll.viewDeduction');

        //Employee Deduction bulk upload
        Route::post('employee-setup/deduction/bulk-upload', 'EmployeeSetupController@uploadEmployeeDeduction')->name('payroll.uploadEmployeeDeduction');

        Route::get('employee-setup/bonus', 'EmployeeSetupController@bonus')->name('employeeSetup.bonus');
        Route::post('employee-setup/bonus', 'EmployeeSetupController@storeBonus')->name('employeeSetup.store.bonus');

        Route::get('employee-setup/taxExclude', 'EmployeeSetupController@taxExclude')->name('employeeSetup.taxExclude');
        Route::post('employee-setup/taxExclude/store', 'EmployeeSetupController@storeTaxExclude')->name('employeeSetup.store.taxExclude');

        //Tax-Slab
        Route::get('tax-slab', 'TaxSlabController@index')->name('taxSlab.index');
        Route::post('tax-slab/store', 'TaxSlabController@store')->name('taxSlab.store');

        //Hold Payment
        Route::get('hold-payment', 'HoldPaymentController@index')->name('holdPayment.index');
        Route::get('hold-payment/create', 'HoldPaymentController@create')->name('holdPayment.create');
        Route::post('hold-payment/store', 'HoldPaymentController@store')->name('holdPayment.store');
        Route::get('hold-payment/{id}/edit', 'HoldPaymentController@edit')->name('holdPayment.edit');
        Route::put('hold-payment/{id}/update', 'HoldPaymentController@update')->name('holdPayment.update');
        Route::get('hold-payment/{id}/delete', 'HoldPaymentController@destroy')->name('holdPayment.destroy');
        Route::get('hold-payment/{id}/cancel', 'HoldPaymentController@cancel')->name('holdPayment.cancel');
        Route::post('hold-payment/update-status', 'HoldPaymentController@updateStatus')->name('holdPayment.updateStatus');
        Route::post('hold-payment/getChangeDate', 'HoldPaymentController@getDates')->name('holdpayment.getDates');
        Route::get('filter-hold-payment-mont', 'HoldPaymentController@filterMonth')->name('filter-hold-payment-mont');
        Route::get('hold-payment/export-hold-payment', 'HoldPaymentController@exportHoldPayment')->name('exportHoldPayment');

        //Stop Payment
        Route::get('stop-payment', 'StopPaymentController@index')->name('stopPayment.index');
        Route::get('stop-payment/create', 'StopPaymentController@create')->name('stopPayment.create');
        Route::post('stop-payment/store', 'StopPaymentController@store')->name('stopPayment.store');
        Route::put('stop-payment/{id}/update', 'StopPaymentController@update')->name('stopPayment.update');
        Route::post('stop-payment/update-status', 'StopPaymentController@updateStatus')->name('stopPayment.updateStatus');
        Route::post('stop-payment/getChangeDate', 'StopPaymentController@getDates')->name('stoppayment.getDates');

        //Tax Exclude Setup
        Route::get('tax-exclude-setup', 'TaxExcludeSetupController@index')->name('taxExcludeSetup.index');
        Route::get('tax-exclude-setup/create', 'TaxExcludeSetupController@create')->name('taxExcludeSetup.create');
        Route::post('tax-exclude-setup/store', 'TaxExcludeSetupController@store')->name('taxExcludeSetup.store');
        Route::get('tax-exclude-setup/{id}/edit', 'TaxExcludeSetupController@edit')->name('taxExcludeSetup.edit');
        Route::put('tax-exclude-setup/{id}/update', 'TaxExcludeSetupController@update')->name('taxExcludeSetup.update');
        Route::get('tax-exclude-setup/{id}/delete', 'TaxExcludeSetupController@destroy')->name('taxExcludeSetup.destroy');

        Route::get('tax-exclude-setup/check-tax-exclude-setup-order', ['as' => 'taxExcludeSetup.checkOrder', 'uses' => 'TaxExcludeSetupController@checkTaxExcludeOrder'])->where('id', '[0-9]+');

        //Bonus
        Route::get('bonus', 'BonusController@index')->name('bonus.index');
        Route::get('bonus/create', 'BonusController@create')->name('bonus.create');
        Route::post('bonus/store', 'BonusController@store')->name('bonus.store');
        Route::get('bonus/{id}/view', 'BonusController@show')->name('bonus.view');
        Route::get('bonus/{id}/delete', 'BonusController@destroy')->name('bonus.delete');
        Route::get('bonus/{id}/salary-transfer-letter', 'BonusController@salaryTransfer')->name('bonus.salary.transfer');

        //export employee Bonus
        Route::get('employee-setup/export-employee-bonus', 'EmployeeSetupController@exportBonus')->name('payroll.exportBonus');

        //Employee bonus bulk upload
        Route::post('employee-setup/bonus/bulk-upload', 'EmployeeSetupController@uploadEmployeeBonus')->name('payroll.uploadEmployeeBonus');
    });

    //Reports
    Route::get('reports/payroll-report', 'PayrollController@payrollReport')->name('reports.payrollReport');
    Route::get('reports/get-organization-year-month', 'PayrollController@getOrganizationYearMonth')->name('payroll.getOrganizationYearMonth');
    Route::get('reports/get-organization-month', 'PayrollController@getOrganizationMonth')->name('payroll.getOrganizationMonth');
    Route::get('reports/cit', 'PayrollController@citReport')->name('reports.citReport');
    Route::get('reports/ssf', 'PayrollController@ssfReports')->name('reports.ssfReport');
    Route::get('reports/pf', 'PayrollController@pfReport')->name('reports.pfReport');
    Route::get('reports/tds', 'PayrollController@tdsReports')->name('reports.tdsReports');
    Route::get('reports/branchPayrollReport', 'PayrollController@branchPayrollReport')->name('reports.branchPayrollReport');
    Route::get('reports/branchSummaryReport', 'PayrollController@branchSummaryReport')->name('reports.branchSummaryReport');
    Route::get('reports/annualProjectionReport', 'PayrollController@annualProjectionReport')->name('reports.annualProjectionReport');

    //Ajax
    Route::get('deduction-setup/get-incomes', 'DeductionSetupController@getIncomeTypes')->name('deductionSetup.getIncomeTypes');
    Route::get('deduction-setup/get-incomes-gross', 'DeductionSetupController@getIncomeTypesWithGross')->name('deductionSetup.getIncomeTypesWithGross');


    /**
     * Allownace  Report Controller
     */

    Route::get('/allownace-report', ['as' => 'allowanceReport.index', 'uses' => 'AllowanceReportController@index']);
    Route::get('/all-allownace-report', ['as' => 'allowanceReport.allReport', 'uses' => 'AllowanceReportController@allReportIndex']);

});

Route::post('admin/getTaxCalculation',[PayrollController::class,'getTaxCalculation'])->name('getTaxCalculation');
Route::get('admin/payroll/{id}/view', [PayrollController::class,'show'])->name('payroll.view');
Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {
    // ajax routes
    Route::post('payroll/recalculate', 'PayrollController@reCalculate');
});
Route::get('/updateMonth','DeductionSetupController@updateMonth')->name('updateMonth');
    Route::get('/allownace-employee-list', ['as' => 'allowanceReport.getEmployee', 'uses' => 'AllowanceReportController@getEmployee']);
