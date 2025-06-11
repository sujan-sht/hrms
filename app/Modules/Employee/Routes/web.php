<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Employee\Http\Controllers\EmployeeController;
use App\Modules\Employee\Http\Controllers\RequestChangeController;

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

    /*
    |--------------------------------------------------------------------------
    | Employee CRUD ROUTE
    |--------------------------------------------------------------------------
     */
    Route::get('employee/directory', ['as' => 'employee.directory', 'uses' => 'EmployeeController@directory']);
    Route::get('employee/directory/export-report', ['as' => 'employee.directoryReportExport', 'uses' => 'EmployeeController@directoryReportExport']);

    Route::get('employee/archived/directory', ['as' => 'employee.archivedDirectory', 'uses' => 'EmployeeController@archivedDirectory']);

    Route::get('employee/approval-flow-report', ['as' => 'employee.approvalFlowReport', 'uses' => 'EmployeeController@approvalFlowReport']);
    Route::get('employee/get-approval-users', ['as' => 'employee.getApprovalUsers', 'uses' => 'EmployeeController@getApprovalUsers']);
    Route::put('employee/{id}/update-approval-users', ['as' => 'employee.updateApprovalFlow', 'uses' => 'EmployeeController@updateApprovalFlow']);

    Route::post('employee/uploadEmployee', ['as' => 'employee.uploadEmployee', 'uses' => 'EmployeeController@uploadEmployee']);
    Route::post('employee/uploadEmployee/Archived', ['as' => 'employee.uploadEmployeeArchived', 'uses' => 'EmployeeController@uploadEmployeeArchived']);

    Route::get('employee/downloadSheet', ['as' => 'employee.downloadSheet', 'uses' => 'EmployeeController@downloadSheet']);
    Route::get('employee/downloadPdf', ['as' => 'employee.downloadPdf', 'uses' => 'EmployeeController@downloadPdf']);


    Route::get('employee/archive-export', 'EmployeeController@archiveExportToExcel')->name('employee.archive.export');

    Route::get('employee', ['as' => 'employee.index', 'uses' => 'EmployeeController@index']);
    Route::get('employee-search', ['as' => 'employee.search', 'uses' => 'EmployeeController@search']);

    Route::get('employee/archive', ['as' => 'employee.indexArchive', 'uses' => 'EmployeeController@indexArchive']);

    Route::get('employee/create', ['as' => 'employee.create', 'uses' => 'EmployeeController@create']);
    Route::post('employee/store', ['as' => 'employee.store', 'uses' => 'EmployeeController@store']);

    Route::get('filter-branch-unit', 'EmployeeController@filterBranchUnit')->name('filter-branch-unit');

    Route::get('employee/edit/{id}', ['as' => 'employee.edit', 'uses' => 'EmployeeController@edit'])->where('id', '[0-9]+');
    Route::put('employee/update/{id}', ['as' => 'employee.update', 'uses' => 'EmployeeController@update'])->where('id', '[0-9]+');
    Route::put('employee/updateEmployeeProfile/{id}', ['as' => 'employee.updateEmployeeProfile', 'uses' => 'EmployeeController@updateEmployeeProfile'])->where('id', '[0-9]+');


    Route::get('employee/view/{id}', ['as' => 'employee.view', 'uses' => 'EmployeeController@show'])->where('id', '[0-9]+');
    Route::get('employee/viewSelfProfile/{id}', ['as' => 'employee.viewSelfProfile', 'uses' => 'EmployeeController@show'])->where('id', '[0-9]+');

    Route::post('employee/createUser', ['as' => 'employee.createUser', 'uses' => 'EmployeeController@createUser']);

    Route::post('employee/grantRole', ['as' => 'employee.grantRoleToUser', 'uses' => 'EmployeeController@grantRoleToUser']);

    Route::post('employee/updateType', ['as' => 'employee.updateType', 'uses' => 'EmployeeController@updateType']);

    Route::get('employee/checkAvailability', ['as' => 'employee.checkAvailability', 'uses' => 'EmployeeController@checkAvailability']);
    Route::get('employee/checkAvailabilityOthers', ['as' => 'employee.checkAvailabilityOthers', 'uses' => 'EmployeeController@checkOthersUsername']);


    Route::get('employee/updateParentId', array('as' => 'employee.updateParentId', 'uses' => 'EmployeeController@updateParentId'));

    Route::post('employee/update-status', ['as' => 'employee.update.status.user', 'uses' => 'EmployeeController@updateStatus']);

    Route::get('employee/update-status-archive/{id}', ['as' => 'employee.update.status.archive.user', 'uses' => 'EmployeeController@updateStatusArchive']);
    Route::post('employee/update/status-archive', ['as' => 'employee.update.status', 'uses' => 'EmployeeController@updateEmployeeStatus']);

    Route::get('employee/archive/{id}', ['as' => 'employee.update.status.archive.message', 'uses' => 'EmployeeController@archiveMessage']);

    Route::post('employee/bulk-user-status-active', ['as' => 'employee.bulkUserStatusActive', 'uses' => 'EmployeeController@bulkUserStatusActive']);


    /**
     * language
     */
    Route::get('employee/language', ['as' => 'employee.languages', 'uses' => 'EmployeeController@fetchLanguages']);
    Route::post('employee/language/create', ['as' => 'employee.language.create', 'uses' => 'EmployeeController@storeLanguage']);



    //cron for employeetimeline
    Route::get('employee/add-timeline', ['as' => 'employee.addTimeline', 'uses' => 'EmployeeController@addTimeline']);

    //check email unique
    Route::get('checkEmailAvailability', 'EmployeeController@checkEmailAvailability')->name('employee.checkAvailability');


    //for dropdown value of district
    Route::get('employee/getdistrict/{province_id}', ['as' => 'employee.getDistrict', 'uses' => 'EmployeeController@getDist'])->where('province_id', '[0-9]+');

    //check if employee id already exists
    // Route::get('employee/check-employee-id', ['as' => 'employee.checkEmpId', 'uses' => 'EmployeeController@checkEmployeeId'])->where('id', '[0-9]+');
    Route::get('employee/check-employee-code', ['as' => 'employee.checkEmpCode', 'uses' => 'EmployeeController@checkEmployeeCode'])->where('id', '[0-9]+');
    Route::get('employee/check-biometric-id', ['as' => 'employee.checkBiometricId', 'uses' => 'EmployeeController@checkBiometricId'])->where('id', '[0-9]+');

    Route::get('employee/{id}/reset-password', ['as' => 'employee.resetPassword', 'uses' => 'EmployeeController@resetPassword']);
    Route::post('employee/reset-password/store', ['as' => 'employee.storeResetPassword', 'uses' => 'EmployeeController@storeResetPassword']);

    Route::post('employee/getNameandEmail', ['as' => 'employee.getNameandEmail', 'uses' => 'EmployeeController@getNameandEmail']);

    Route::get('employee/pending-approval', ['as' => 'pendingApproval', 'uses' => 'EmployeeController@pendingApproval']);

    Route::get('employee/dob/convert', ['as' => 'employee.dob.convert', 'uses' => 'EmployeeController@convertDob']);

    Route::get('employee-suggestion/list', ['as' => 'search.employee.fullname', 'uses' => 'EmployeeController@employeeSuggestionList']);


    ///Ajax Routes for Employee Profile Page

    Route::get('appendleaveReport', ['as' => 'leaveDetail.leaveReport', 'uses' => 'LeaveDetailController@leaveReport']);
    Route::get('appendleaveRemaining', ['as' => 'leaveDetail.leaveRemaining', 'uses' => 'LeaveDetailController@leaveRemaining']);

    Route::get('appendFamilyDetail', ['as' => 'familyDetail.appendAll', 'uses' => 'FamilyDetailController@appendAll']);
    Route::post('saveFamilyDetail', ['as' => 'familyDetail.save', 'uses' => 'FamilyDetailController@store']);
    Route::post('updateFamilyDetail', ['as' => 'familyDetail.update', 'uses' => 'FamilyDetailController@update']);
    Route::delete('deleteFamilyDetail', ['as' => 'familyDetail.delete', 'uses' => 'FamilyDetailController@destroy']);

    // Award Details
    Route::get('appendAwardDetail', ['as' => 'awardDetail.appendAll', 'uses' => 'AwardDetailController@appendAll']);
    Route::post('saveAwardDetail', ['as' => 'awardDetail.save', 'uses' => 'AwardDetailController@store']);
    Route::post('updateAwardDetail', ['as' => 'awardDetail.update', 'uses' => 'AwardDetailController@update']);
    Route::delete('deleteAwardDetail', ['as' => 'awardDetail.delete', 'uses' => 'AwardDetailController@destroy']);

    // Skill Details
    Route::get('appendSkillDetail', ['as' => 'skillDetail.appendAll', 'uses' => 'SkillDetailController@appendAll']);
    Route::post('saveSkillDetail', ['as' => 'skillDetail.save', 'uses' => 'SkillDetailController@store']);
    Route::post('updateSkillDetail', ['as' => 'skillDetail.update', 'uses' => 'SkillDetailController@update']);
    Route::delete('deleteSkillDetail', ['as' => 'skillDetail.delete', 'uses' => 'SkillDetailController@destroy']);

    Route::get('appendAssetDetail', ['as' => 'assetDetail.appendAll', 'uses' => 'AssetDetailController@appendAll']);
    Route::post('saveAssetDetail', ['as' => 'assetDetail.save', 'uses' => 'AssetDetailController@store']);
    Route::post('updateAssetDetail', ['as' => 'assetDetail.update', 'uses' => 'AssetDetailController@update']);
    Route::delete('deleteAssetDetail', ['as' => 'assetDetail.delete', 'uses' => 'AssetDetailController@destroy']);

    Route::get('appendEmergencyDetail', ['as' => 'emergencyDetail.appendAll', 'uses' => 'EmergencyDetailController@appendAll']);
    Route::post('saveEmergencyDetail', ['as' => 'emergencyDetail.save', 'uses' => 'EmergencyDetailController@store']);
    Route::post('updateEmergencyDetail', ['as' => 'emergencyDetail.update', 'uses' => 'EmergencyDetailController@update']);
    Route::delete('deleteEmergencyDetail', ['as' => 'emergencyDetail.delete', 'uses' => 'EmergencyDetailController@destroy']);

    Route::get('appendBenefitDetail', ['as' => 'benefitDetail.appendAll', 'uses' => 'BenefitDetailController@appendAll']);
    Route::post('saveBenefitDetail', ['as' => 'benefitDetail.save', 'uses' => 'BenefitDetailController@store']);
    Route::post('updateBenefitDetail', ['as' => 'benefitDetail.update', 'uses' => 'BenefitDetailController@update']);
    Route::delete('deleteBenefitDetail', ['as' => 'benefitDetail.delete', 'uses' => 'BenefitDetailController@destroy']);

    Route::get('appendEducationDetail', ['as' => 'educationDetail.appendAll', 'uses' => 'EducationDetailController@appendAll']);
    Route::get('editEducationDetail', ['as' => 'educationDetail.edit', 'uses' => 'EducationDetailController@edit']);
    Route::post('saveEducationDetail', ['as' => 'educationDetail.save', 'uses' => 'EducationDetailController@store']);
    Route::post('updateEducationDetail', ['as' => 'educationDetail.update', 'uses' => 'EducationDetailController@update']);
    Route::delete('deleteEducationDetail', ['as' => 'educationDetail.delete', 'uses' => 'EducationDetailController@destroy']);

    Route::get('appendPreviousJobDetail', ['as' => 'previousJobDetail.appendAll', 'uses' => 'PreviousJobDetailController@appendAll']);
    Route::post('savePreviousJobDetail', ['as' => 'previousJobDetail.save', 'uses' => 'PreviousJobDetailController@store']);
    Route::post('updatePreviousJobDetail', ['as' => 'previousJobDetail.update', 'uses' => 'PreviousJobDetailController@update']);
    Route::delete('deletePreviousJobDetail', ['as' => 'previousJobDetail.delete', 'uses' => 'PreviousJobDetailController@destroy']);

    Route::get('appendBankDetail', ['as' => 'bankDetail.appendAll', 'uses' => 'BankDetailController@appendAll']);
    Route::post('saveBankDetail', ['as' => 'bankDetail.save', 'uses' => 'BankDetailController@store']);
    Route::post('updateBankDetail', ['as' => 'bankDetail.update', 'uses' => 'BankDetailController@update']);
    Route::delete('deleteBankDetail', ['as' => 'bankDetail.delete', 'uses' => 'BankDetailController@destroy']);

    Route::get('appendContractDetail', ['as' => 'contractDetail.appendAll', 'uses' => 'ContractDetailController@appendAll']);
    Route::post('saveContractDetail', ['as' => 'contractDetail.save', 'uses' => 'ContractDetailController@store']);
    Route::post('updateContractDetail', ['as' => 'contractDetail.update', 'uses' => 'ContractDetailController@update']);
    Route::delete('deleteContractDetail', ['as' => 'contractDetail.delete', 'uses' => 'ContractDetailController@destroy']);

    Route::get('appendDocumentDetail', ['as' => 'documentDetail.appendAll', 'uses' => 'DocumentDetailController@appendAll']);
    Route::post('saveDocumentDetail', ['as' => 'documentDetail.save', 'uses' => 'DocumentDetailController@store']);
    Route::post('updateDocumentDetail', ['as' => 'documentDetail.update', 'uses' => 'DocumentDetailController@update']);
    Route::delete('deleteDocumentDetail', ['as' => 'documentDetail.delete', 'uses' => 'DocumentDetailController@destroy']);

    Route::get('appendResearchAndPublicationDetail', ['as' => 'researchAndPublicationDetail.appendAll', 'uses' => 'ResearchAndPublicationDetailController@appendAll']);
    Route::post('saveResearchAndPublicationDetail', ['as' => 'researchAndPublicationDetail.save', 'uses' => 'ResearchAndPublicationDetailController@store']);
    Route::post('updateResearchAndPublicationDetail', ['as' => 'researchAndPublicationDetail.update', 'uses' => 'ResearchAndPublicationDetailController@update']);
    Route::delete('deleteResearchAndPublicationDetail', ['as' => 'researchAndPublicationDetail.delete', 'uses' => 'ResearchAndPublicationDetailController@destroy']);

    Route::get('appendVisaAndImmigrationDetail', ['as' => 'visaAndImmigrationDetail.appendAll', 'uses' => 'VisaAndImmigrationDetailController@appendAll']);
    Route::post('saveVisaAndImmigrationDetail', ['as' => 'visaAndImmigrationDetail.save', 'uses' => 'VisaAndImmigrationDetailController@store']);
    Route::post('updateVisaAndImmigrationDetail', ['as' => 'visaAndImmigrationDetail.update', 'uses' => 'VisaAndImmigrationDetailController@update']);
    Route::delete('deleteVisaAndImmigrationDetail', ['as' => 'visaAndImmigrationDetail.delete', 'uses' => 'VisaAndImmigrationDetailController@destroy']);

    Route::get('appendMedicalDetail', ['as' => 'medicalDetail.appendAll', 'uses' => 'MedicalDetailController@appendAll']);
    Route::post('saveMedicalDetail', ['as' => 'medicalDetail.save', 'uses' => 'MedicalDetailController@store']);
    Route::post('updateMedicalDetail', ['as' => 'medicalDetail.update', 'uses' => 'MedicalDetailController@update']);
    Route::delete('deleteMedicalDetail', ['as' => 'medicalDetail.delete', 'uses' => 'MedicalDetailController@destroy']);

    Route::get('substitute-leaves', ['as' => 'substituteLeave.index', 'uses' => 'EmployeeSubstituteLeaveController@index']);
    Route::get('claimed-substitute-leaves', ['as' => 'substituteLeave.claimedSubstituteLeaves', 'uses' => 'EmployeeSubstituteLeaveController@claimedSubstituteLeaves']);
    Route::put('leave/cancel-substitute-leave-request', ['as' => 'substituteLeave.cancelSubstituteLeaveRequest', 'uses' => 'EmployeeSubstituteLeaveController@cancelSubstituteLeaveRequest']);

    Route::get('substitute-leave/create', ['as' => 'substituteLeave.create', 'uses' => 'EmployeeSubstituteLeaveController@create']);
    Route::post('substitute-leave/store', ['as' => 'substituteLeave.store', 'uses' => 'EmployeeSubstituteLeaveController@store']);
    Route::get('substitute-leave/{id}/view', ['as' => 'substituteLeave.show', 'uses' => 'EmployeeSubstituteLeaveController@show']);
    Route::post('substitute-leave/update-status', ['as' => 'substituteLeave.updateStatus', 'uses' => 'EmployeeSubstituteLeaveController@updateStatus']);
    Route::post('substitute-leave/update-claim-status', ['as' => 'substituteLeave.updateClaimStatus', 'uses' => 'EmployeeSubstituteLeaveController@updateClaimStatus']);

    Route::get('substitute-leave/{id}/edit', ['as' => 'substituteLeave.edit', 'uses' => 'EmployeeSubstituteLeaveController@edit']);
    Route::put('substitute-leave/{id}/update', ['as' => 'substituteLeave.update', 'uses' => 'EmployeeSubstituteLeaveController@update']);
    Route::get('substitute-leave/{id}/delete', ['as' => 'substituteLeave.delete', 'uses' => 'EmployeeSubstituteLeaveController@destroy']);
    Route::get('substitute-leave/team-request', ['as' => 'substituteLeave.teamRequest', 'uses' => 'EmployeeSubstituteLeaveController@getTeamLeaves']);

    Route::get('substitute-leave/claim/{id}', ['as' => 'substituteLeave.claim', 'uses' => 'EmployeeSubstituteLeaveController@claim']);

    Route::get('appendTrainingDetail', ['as' => 'trainingDetail.appendAll', 'uses' => 'TrainingDetailController@appendAll']);
    Route::post('updateTrainingDetail', ['as' => 'trainingDetail.update', 'uses' => 'TrainingDetailController@update']);

    Route::get('update-timeline-record', 'EmployeeController@updateEmployeeTimelineRecord');
    Route::get('timeline-report-download/{id}', 'EmployeeController@downloadTimelineReport')->name('downloadTimelineReport');

    Route::get('upload-employee-detail', 'EmployeeController@uploadEmployeeDetail');
    Route::post('update-employee-detail', 'EmployeeController@updateEmployeeDetail');

    //Performance Management
    Route::get('transfers', ['as' => 'employeeTransfer.list', 'uses' => 'EmployeeTransferController@appendAllTransferList']);
    Route::post('transfer/create', ['as' => 'employeeTransfer.create', 'uses' => 'EmployeeTransferController@store']);
    Route::post('transfer/edit', ['as' => 'employeeTransfer.update', 'uses' => 'EmployeeTransferController@update']);
    Route::delete('transfer/delete', ['as' => 'employeeTransfer.delete', 'uses' => 'EmployeeTransferController@destroy']);

    Route::get('promotions', ['as' => 'employeePromotion.list', 'uses' => 'EmployeeTransferController@appendAllPromotionList']);
    Route::get('demotions', ['as' => 'employeeDemotion.list', 'uses' => 'EmployeeTransferController@appendAllDemotionList']);

    Route::get('employee/view-performance-details', ['as' => 'employee.viewPerformanceManagement', 'uses' => 'EmployeeController@viewPerformanceManagement']);
    Route::post('employee/store-performance-details', ['as' => 'employee.storePerformanceDetails', 'uses' => 'EmployeeController@storePerformanceDetails']);
    //

    //Carrier Mobility
    Route::get('carrier-mobility-detail', ['as' => 'employeeCarrierMobility.list', 'uses' => 'EmployeeTransferController@appendAllCarrierMobilityList']);

    Route::get('employee/carrier-mobility', ['as' => 'employee.carrierMobility', 'uses' => 'EmployeeController@carrierMobility']);
    Route::post('employee/store-carrier-mobility', ['as' => 'employee.storeCarrierMobility', 'uses' => 'EmployeeController@storeCarrierMobility']);
    Route::get('employee/carrier-mobility-report', ['as' => 'employee.carrierMobilityReport', 'uses' => 'EmployeeController@carrierMobilityReport']);
    Route::get('employee/carrier-mobility-report/{id}/{type}', ['as' => 'employee.deleteCarrierMobilityReport', 'uses' => 'EmployeeController@destroyCarrierMobility']);
    //

    //Job End date Report
    Route::get('employee/job-end-date-report', ['as' => 'employee.jobEndDateReport', 'uses' => 'EmployeeController@jobEndDateReport']);
    Route::get('employee/probation-end-date-report', ['as' => 'employee.probationEndDateReport', 'uses' => 'EmployeeController@probationEndDateReport']);
    //

    //Document Expiry date Report
    Route::get('employee/document-expiry-date-report', ['as' => 'employee.documentExpiryDateReport', 'uses' => 'EmployeeController@documentExpiryDateReport']);
    //

    //ajax
    Route::get('employee/carrier-mobility/append-leave-details', ['as' => 'employee.carrierMobility.appendLeaveDetail', 'uses' => 'EmployeeController@appendLeaveDetail']);

    Route::delete('employee/deleteProfilePic/{id}', ['as' => 'employee.deleteProfilePic', 'uses' => 'EmployeeController@deleteProfilePic']);

    Route::get('employee/profile/download/{id}', ['as' => 'employee.downloadProfile', 'uses' => 'EmployeeController@downloadProfile']);

    //reset device
    Route::get('employee/{id}/reset-device', ['as' => 'employee.resetDevice', 'uses' => 'EmployeeController@resetDevice']);
    //

    //check role for emp leave detail
    Route::get('employee/show-leave-detail', ['as' => 'employee.showLeaveDetail']);
    //

    //show employee job detail
    Route::get('show-job-detail/{id}', ['as' => 'employee.showJobDetail', 'uses' => 'EmployeeController@showJobDetail']);
    Route::post('store-job-detail', ['as' => 'employee.storeJobDetail', 'uses' => 'EmployeeController@storeJobDetail']);
    //

    //show document detail
    Route::get('show-document-detail/{id}', ['as' => 'employee.showDocumentDetail', 'uses' => 'EmployeeController@showDocumentDetail']);
    Route::post('update-document-detail', ['as' => 'employee.updateDocumentDetail', 'uses' => 'EmployeeController@updateDocumentDetail']);
    Route::get('delete-document-detail/{id}', ['as' => 'employee.destroyDocumentDetail', 'uses' => 'EmployeeController@destroyDocumentDetail']);

    //Insurance
    Route::get('appendInsuranceDetail', ['as' => 'insuranceDetail.appendAll', 'uses' => 'InsuranceDetailController@appendAll']);
    Route::post('saveInsuranceDetail', ['as' => 'insuranceDetail.save', 'uses' => 'InsuranceDetailController@store']);
    Route::post('updateInsuranceDetail', ['as' => 'insuranceDetail.update', 'uses' => 'InsuranceDetailController@update']);
    Route::delete('deleteInsuranceDetail', ['as' => 'insuranceDetail.delete', 'uses' => 'InsuranceDetailController@destroy']);

    /**
     * Employee Career Mobility Appointment
     */
    Route::get('employee/career-mobility-appointments', ['as' => 'employee.careerMobilityAppointment.index', 'uses' => 'EmployeeCareerMobilityAppointmentController@index']);
    Route::get('employee/career-mobility-appointment/create', ['as' => 'employee.careerMobilityAppointment.create', 'uses' => 'EmployeeCareerMobilityAppointmentController@create']);
    Route::post('employee/career-mobility-appointment/store', ['as' => 'employee.careerMobilityAppointment.store', 'uses' => 'EmployeeCareerMobilityAppointmentController@store']);

    /**
     * Employee Career Mobility Transfer
     */
    Route::get('employee/career-mobility-transfers', ['as' => 'employee.careerMobilityTransfer.index', 'uses' => 'EmployeeCareerMobilityTransferController@index']);
    Route::get('employee/career-mobility-transfer/create', ['as' => 'employee.careerMobilityTransfer.create', 'uses' => 'EmployeeCareerMobilityTransferController@create']);
    Route::post('employee/career-mobility-transfer/store', ['as' => 'employee.careerMobilityTransfer.store', 'uses' => 'EmployeeCareerMobilityTransferController@store']);


    /**
     * Employee Career Mobility Confirmation
     */
    Route::get('employee/career-mobility-confirmations', ['as' => 'employee.careerMobilityConfirmation.index', 'uses' => 'EmployeeCareerMobilityConfirmationController@index']);
    Route::post('employee/career-mobility-confirmation/store', ['as' => 'employee.careerMobilityConfirmation.store', 'uses' => 'EmployeeCareerMobilityConfirmationController@store']);

    /**
     * Employee Career Mobility Promotion
     */
    Route::get('employee/career-mobility-promotions', ['as' => 'employee.careerMobilityPromotion.index', 'uses' => 'EmployeeCareerMobilityPromotionController@index']);
    Route::post('employee/career-mobility-promotion/store', ['as' => 'employee.careerMobilityPromotion.store', 'uses' => 'EmployeeCareerMobilityPromotionController@store']);

    /**
     * Employee Career Mobility Demotion
     */
    Route::get('employee/career-mobility-demotions', ['as' => 'employee.careerMobilityDemotion.index', 'uses' => 'EmployeeCareerMobilityDemotionController@index']);
    Route::post('employee/career-mobility-demotion/store', ['as' => 'employee.careerMobilityDemotion.store', 'uses' => 'EmployeeCareerMobilityDemotionController@store']);

    /**
     * Employee Career Mobility Temporary Transfer
     */
    Route::get('employee/career-mobility-temporary-transfers', ['as' => 'employee.careerMobilityTemporaryTransfer.index', 'uses' => 'EmployeeCareerMobilityTemporaryTransferController@index']);
    Route::post('employee/career-mobility-temporary-transfer/store', ['as' => 'employee.careerMobilityTemporaryTransfer.store', 'uses' => 'EmployeeCareerMobilityTemporaryTransferController@store']);

    /**
     * Employee Career Mobility Extension of Probationary Period
     */

    Route::get('employee/career-mobility-extension-of-probationary-periods', ['as' => 'employee.careerMobilityExtensionOfProbationaryPeriod.index', 'uses' => 'EmployeeCareerMobilityExtensionOfProbationaryPeriodController@index']);
    Route::post('employee/career-mobility-extension-of-probationary-period/store', ['as' => 'employee.careerMobilityExtensionOfProbationaryPeriod.store', 'uses' => 'EmployeeCareerMobilityExtensionOfProbationaryPeriodController@store']);

    Route::get('employee/pending-previous-job-detail', ['as' => 'employee.pendingPreviousJobDetail', 'uses' => 'PreviousJobDetailController@pendingJobDetail']);
    Route::post('employee/update-pending-previous-job-detail-status', ['as' => 'employee.updatePendingPreviousJobDetail', 'uses' => 'PreviousJobDetailController@updatePendingPreviousJobDetail']);

    Route::get('/get-employees-by-org', ['as' => 'get.contractemployees.by.organization', 'uses' => 'EmployeeCareerMobilityExtensionOfProbationaryPeriodController@getEmployeesByOrganization']);
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'XssSanitizer']], function () {

    // ajax routes
    Route::get('employee/get-alternative-employees', 'EmployeeController@getAlternativeEmployees');
    Route::get('substitute-leave/min-date', ['as' => 'substituteLeave.minSubstituteDate', 'uses' => 'EmployeeSubstituteLeaveController@minSubstituteDate']);
    Route::get('substitute-leave/get-attendance', ['as' => 'substituteLeave.getAttendance', 'uses' => 'EmployeeSubstituteLeaveController@getAttendance']);

    Route::get('employee/check-pan-unique', 'EmployeeController@checkPanUnique');
    Route::get('employee/payroll-detail', ['as' => 'employee.getPayrollDetail', 'uses' => 'EmployeeController@getPayrollDetail']);

    Route::get('employee/changeUserType', ['as' => 'employee.changeUserType', 'uses' => 'EmployeeController@changeUserType']);
});


Route::get('get/employee/by/organization', [EmployeeController::class, 'getEmployeeByOrganization'])->name('getEmployee_By_Organization');
Route::get('get/employee/approval-flow', [EmployeeController::class, 'getEmployeeApprovalFlow'])->name('getEmployeeApprovalFlow');
Route::post('getShiftOrganizationWise', [EmployeeController::class, 'getShiftByOrganization'])->name('employee.getShiftOrganizationWise');

//requestchanges
Route::post('request-changes/add', [RequestChangeController::class, 'store'])->name("request-change.store");
Route::get('admin/request-changes/view/{id}', [RequestChangeController::class, 'view'])->name("request-change.view");
Route::get('admin/request-changes/', [RequestChangeController::class, 'index'])->name("request-change.index");
//approvedchanges
Route::get('admin/change-request/{status}/{id}', [RequestChangeController::class, 'approvedChanges'])->name('change-approval');


Route::get('get-sub-function', [EmployeeController::class, 'getSubFunction'])->name('getSubFunction');
