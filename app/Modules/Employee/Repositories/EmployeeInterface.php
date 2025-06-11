<?php

namespace App\Modules\Employee\Repositories;

interface EmployeeInterface
{

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'first_name', 'sort' => 'asc'], $status = [0, 1]);
    public function fetchTableViewEmployees($filter = []);
    public function findAllArchived($limit = null, $filter = [], $sort = ['by' => 'first_name', 'sort' => 'asc'], $status = [0, 1]);

    public function findArchive($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1]);

    public function filterList($withOrganization = null);
    public function getList($withOrganization = null);
    public function getListExceptSelectedEmployee($reqData, $withOrganization = null);

    public function employeeListWithFilter($filter);

    public function getListWithEmpCode();

    public function getArchiveList();

    public function uploadProfilePic($file);

    public function uploadNationalId($file);

    public function uploadMaritalImg($file);

    public function uploadPassportImg($file);

    public function uploadCitizen($file);

    public function uploadDocument($file);

    public function uploadSignature($file);

    public function save($data);

    public function update($id, $data);

    public function updateStatus($id);

    public function setArchivedDetail($id, $data);

    public function find($id);

    public function findWithFullNameAndEmail($id);

    public function getEmployeeByCode($emp_code);

    public function getEmployeeThresholdList($id);

    public function findByProvince($provinceid);

    public function getStates();

    public function getDistrict();

    public function getActiveEmployee();

    public function getActiveEmployees();

    public function employeeLeaveDetails($employee_id);

    public function getEmployeeFlow($employee_id);

    public function employeeAppraisalApprovalFlow($employee_id);

    public function getCountries();

    public function findCountry($country);

    public function getEmployeeByOrganization($organization_id, $params = null);

    public function getEmpNameByOrganization($organization_id);

    public function checkAndCreateEmployeeLeave($employee_id);

    public function getBirthdayList();

    public function getAnniversaryList();

    public function getNewEmployeeList();

    public function getJobEndList();

    public function getJobEndAndContractEndList();

    public function getEmployeeByBiometric($biometricId);

    public function getEmployeeTimelineModel($employeeId);

    public function getOtherEmployeeList();

    public function getLeaveFromSubsituteDate($employeeId, $sub_date);

    public function employeeLeaveIncrement($leave);

    public function employeeApprovalFlowList($request);

    public function findMobilityReport($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function deleteCarrierMobility($id, $type);
    public function updateEmployeeTimelineJoinDate($data, $employeeId);

    public function findEndDateReport($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);
    public function findProbationEndDateReport($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function findDocExpiryDateReport($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function getListOrganizationwise($orgId);

    public function getEmployeeByIDs($employeeIds);


    public function getLatestShiftGroup($employeeId);

    public function fetchEmployeeForCareerMobilities();

    public function findAllForRoster($limit = null, $filter = [], $sort = ['by' => 'first_name', 'sort' => 'asc'], $status = [0, 1]);

    public function calculateLeaveEarnedTotalDays($emp, $leaveYear, $totalDaysInYear, $leaveYearSetupDetail);
    public function getEarnedLeavePerMonth($emp, $leaveType, $leaveYear, $month, $totalDaysInYear, $calendarType);
    public function calculateLeaveEarnedTotalDaysPerMonth($empJoiningDate, $empEndingDate, $leaveYear, $month, $advance_allocation, $calenderType);
    public function calculateLeaveEarnedTotalDaysProrata($emp, $leaveYear, $totalDaysInYear, $leaveYearSetupDetail, $advanceAllocation);
}
