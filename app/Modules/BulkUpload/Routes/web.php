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

    Route::get('bulkupload/employee/detail', ['as' => 'bulkupload.employeeDetail', 'uses' => 'BulkUploadController@employeeDetail']);
    Route::post('bulkupload/employee/detail/upload', ['as' => 'bulkupload.uploadEmployeeDetail', 'uses' => 'BulkUploadController@uploadEmployeeDetail']);

    Route::get('bulkupload/branch/detail', ['as' => 'bulkupload.branch', 'uses' => 'BulkUploadController@branchDetail']);
    Route::post('bulkupload/branch/detail/upload', ['as' => 'bulkupload.uploadbranchDetail', 'uses' => 'BulkUploadController@uploadbranchDetail']);


    Route::get('bulkupload/family/detail', ['as' => 'bulkupload.familyDetail', 'uses' => 'BulkUploadController@familyDetail']);
    Route::post('bulkupload/family/detail/upload', ['as' => 'bulkupload.uploadfamilyDetail', 'uses' => 'BulkUploadController@uploadfamilyDetail']);

    Route::get('bulkupload/emergency/detail', ['as' => 'bulkupload.emergencyDetail', 'uses' => 'BulkUploadController@emergencyDetail']);
    Route::post('bulkupload/emergency/detail/upload', ['as' => 'bulkupload.uploadEmergencyDetail', 'uses' => 'BulkUploadController@uploadEmergencyDetail']);

    Route::get('bulkupload/benefit/detail', ['as' => 'bulkupload.benefitDetail', 'uses' => 'BulkUploadController@benefitDetail']);
    Route::post('bulkupload/benefit/detail/upload', ['as' => 'bulkupload.uploadBenefitDetail', 'uses' => 'BulkUploadController@uploadbenefitDetail']);

    Route::get('bulkupload/education/detail', ['as' => 'bulkupload.educationDetail', 'uses' => 'BulkUploadController@educationDetail']);
    Route::post('bulkupload/education/detail/upload', ['as' => 'bulkupload.uploadEducationDetail', 'uses' => 'BulkUploadController@uploadEducationDetail']);

    Route::get('bulkupload/previous/job/detail', ['as' => 'bulkupload.previousJobDetail', 'uses' => 'BulkUploadController@previousJobDetail']);
    Route::post('bulkupload/previous/job/detail/upload', ['as' => 'bulkupload.uploadPreviousJobDetail', 'uses' => 'BulkUploadController@uploadPreviousJObDetail']);


    Route::get('bulkupload/contract/detail', ['as' => 'bulkupload.contractDetail', 'uses' => 'BulkUploadController@contractDetail']);
    Route::post('bulkupload/contract/detail/upload', ['as' => 'bulkupload.uploadContractDetail', 'uses' => 'BulkUploadController@uploadContractDetail']);


    Route::get('bulkupload/medical/detail', ['as' => 'bulkupload.medicalDetail', 'uses' => 'BulkUploadController@medicalDetail']);
    Route::post('bulkupload/medical/detail/upload', ['as' => 'bulkupload.uploadMedicalDetail', 'uses' => 'BulkUploadController@uploadMedicalDetail']);

    Route::get('bulkupload/leave/detail', ['as' => 'bulkupload.leaveDetail', 'uses' => 'BulkUploadController@leaveDetail']);
    Route::post('bulkupload/leave/detail/upload', ['as' => 'bulkupload.uploadLeaveDetail', 'uses' => 'BulkUploadController@uploadLeaveDetail']);

    Route::get('bulkupload/leave-history/detail', ['as' => 'bulkupload.leaveHistoryDetail', 'uses' => 'BulkUploadController@leaveHistoryDetail']);
    Route::post('bulkupload/leave-history/detail/upload', ['as' => 'bulkupload.uploadLeaveHistoryDetail', 'uses' => 'BulkUploadController@uploadLeaveHistoryDetail']);

    Route::get('bulkupload/research/detail', ['as' => 'bulkupload.researchDetail', 'uses' => 'BulkUploadController@researchDetail']);
    Route::post('bulkupload/research/detail/upload', ['as' => 'bulkupload.uploadResearchDetail', 'uses' => 'BulkUploadController@uploadResearchDetail']);

    Route::get('bulkupload/bank/detail', ['as' => 'bulkupload.bankDetail', 'uses' => 'BulkUploadController@bankDetail']);
    Route::post('bulkupload/bank/detail/upload', ['as' => 'bulkupload.uploadBankDetail', 'uses' => 'BulkUploadController@uploadBankDetail']);

    Route::get('bulkupload/visa-immigration/detail', ['as' => 'bulkupload.visaImmigrationDetail', 'uses' => 'BulkUploadController@visaImmigrationDetail']);
    Route::post('bulkupload/visa-immigration/detail/upload', ['as' => 'bulkupload.uploadVisaImmigrationDetail', 'uses' => 'BulkUploadController@uploadVisaImmigrationDetail']);

    Route::get('bulkupload/document/detail', ['as' => 'bulkupload.documentDetail', 'uses' => 'BulkUploadController@documentDetail']);
    Route::post('bulkupload/document/detail/upload', ['as' => 'bulkupload.uploadDocumentDetail', 'uses' => 'BulkUploadController@uploadDocumentDetail']);

    Route::get('bulkupload/attendance-log/detail', ['as' => 'bulkupload.attendanceLog', 'uses' => 'BulkUploadController@atdLogDetail']);
    Route::post('bulkupload/attendance-log/detail/upload', ['as' => 'bulkupload.uploadAttendanceLog', 'uses' => 'BulkUploadController@uploadAttendanceLog']);

    Route::get('bulkupload/employee/biometric-detail', ['as' => 'bulkupload.empBiometricDetail', 'uses' => 'BulkUploadController@empBiometricDetail']);
    Route::post('bulkupload/employee/biometric-detail/upload', ['as' => 'bulkupload.uploadEmpBiometricDetail', 'uses' => 'BulkUploadController@uploadEmpBiometricDetail']);

    Route::get('bulkupload/carrier-mobility', ['as' => 'bulkupload.carrierMobility', 'uses' => 'BulkUploadController@carrierMobility']);
    Route::post('bulkupload/carrier-mobility/upload', ['as' => 'bulkupload.uploadCarrierMobility', 'uses' => 'BulkUploadController@uploadCarrierMobility']);

    Route::get('bulkupload/employee/job-description', ['as' => 'bulkupload.employeeJobDescription', 'uses' => 'BulkUploadController@employeeJobDescription']);
    Route::post('bulkupload/employee/job-description/upload', ['as' => 'bulkupload.uploadEmployeeJobDescription', 'uses' => 'BulkUploadController@uploadEmployeeJobDescription']);

    Route::get('bulkupload/darbandi', ['as' => 'bulkupload.darbandis', 'uses' => 'BulkUploadController@darbandis']);
    Route::post('bulkupload/darbandi/upload', ['as' => 'bulkupload.uploadDarbandis', 'uses' => 'BulkUploadController@uploadDarbandi']);

    Route::get('bulkupload/holiday', ['as' => 'bulkupload.holiday', 'uses' => 'BulkUploadController@holiday']);
    Route::post('bulkupload/holiday/upload', ['as' => 'bulkupload.uploadHoliday', 'uses' => 'BulkUploadController@uploadHoliday']);

    Route::get('bulkupload/user', ['as' => 'bulkupload.user', 'uses' => 'BulkUploadController@user']);
    Route::get('bulkupload/user/export', ['as' => 'bulkupload.exportUser', 'uses' => 'BulkUploadController@exportUser']);
    Route::post('bulkupload/user/upload', ['as' => 'bulkupload.uploadUser', 'uses' => 'BulkUploadController@uploadUser']);

    Route::get('bulkupload/labour', ['as' => 'bulkupload.labour', 'uses' => 'BulkUploadController@labour']);
    Route::post('bulkupload/labour/upload', ['as' => 'bulkupload.uploadLabour', 'uses' => 'BulkUploadController@uploadLabour']);

    Route::get('bulkupload/attendance-ot', ['as' => 'bulkupload.attendanceOverStay', 'uses' => 'BulkUploadController@attendanceOverStay']);
    Route::post('bulkupload/attendance-ot', ['as' => 'bulkupload.uploadAttendanceOverStay', 'uses' => 'BulkUploadController@uploadAttendanceOverStay']);


    Route::get('bulkupload/employee-detail-sample-download', ['as' => 'employee-detail-sample-download', 'uses' => 'BulkUploadController@addDropdownToColumnAB']);
    Route::get('bulkupload/employee-upload-sample-download', ['as' => 'employee-upload-sample-download', 'uses' => 'BulkUploadController@addDropdownToEmployeeSample']);
});