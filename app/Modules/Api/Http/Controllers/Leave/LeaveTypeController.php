<?php

namespace App\Modules\Api\Http\Controllers\Leave;

use App\Modules\Api\Http\Controllers\ApiController;
use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Employee\Entities\EmployeeLeave;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\LeaveYearSetup\Entities\LeaveYearSetup;
use App\Modules\LeaveYearSetup\Repositories\LeaveYearSetupInterface;
use App\Modules\Leave\Entities\EmployeeLeaveOpening;
use App\Modules\Leave\Entities\Leave;
use App\Modules\Leave\Entities\LeaveType;
use App\Modules\Leave\Repositories\LeaveTypeInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class LeaveTypeController extends ApiController
{
    private $organization;
    private $leaveType;
    private $leaveYearSetup;
    private $dropdown;
    private $employee;


    public function __construct(
        OrganizationInterface $organization,
        LeaveTypeInterface $leaveType,
        LeaveYearSetupInterface $leaveYearSetup,
        DropdownInterface $dropdown,
        EmployeeInterface $employee
    ){
        $this->organization = $organization;
        $this->leaveType = $leaveType;
        $this->leaveYearSetup = $leaveYearSetup;
        $this->dropdown = $dropdown;
        $this->employee = $employee;
    }
    public function getLeaveDetailFromCategory($leaveCategoryId)
    {
        try {
            $user = auth()->user();
            if ($leaveCategoryId == '1') {
                $inputData['half_leave_status'] = "11";
            }

            $inputData['show_on_employee'] = "11";
            $inputData['leave_year_id'] = getCurrentLeaveYearId();
            $inputData['employee_id'] = $user->emp_id;

            $data = [];
            $employeeLeaveList = EmployeeLeave::getList($inputData);
            foreach ($employeeLeaveList as $employeeLeave) {
                $data['remainingLeaveLists'][] = [
                    'id' => optional($employeeLeave->leaveTypeModel)->id,
                    'name' => optional($employeeLeave->leaveTypeModel)->name,
                    'remain' => $employeeLeave->leave_remaining,
                ];
            }

            return  $this->respond(['status' => true, 'data' => $data]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    public function getDropdown()
    {
        try {
            $data['organizationList'] = setObjectIdAndName($this->organization->getList());
            $data['statusList'] = setObjectIdAndName(LeaveType::statusList());
            $data['currentLeaveyear'] = $this->leaveYearSetup->getLeaveYear();
            $data['leaveYearList'] = setObjectIdAndName($this->leaveYearSetup->getLeaveYearList());
            $data['leaveTypeList'] = LeaveType::leaveTypeList();
            $data['genderList'] = setObjectIdAndName($this->dropdown->getFieldBySlug('gender'));
            $data['maritalStatusList'] = setObjectIdAndName($this->dropdown->getFieldBySlug('marital_status'));
            $data['departmentList'] = setObjectIdAndName($this->dropdown->getFieldBySlug('department'));
            $data['levelList'] = setObjectIdAndName($this->dropdown->getFieldBySlug('level'));
            $data['yesNoList'] = setObjectIdAndName(array('11' => 'Yes', '10' => 'No'));
            $data['noYesList'] = setObjectIdAndName(array('10' => 'No', '11' => 'Yes'));
            $data['jobTypeList'] = setObjectIdAndName(LeaveType::JOB_TYPE);
            $data['contractTypeList'] = setObjectIdAndName(LeaveType::CONTRACT);
            $data['halfLeaveList'] = setObjectIdAndName(Leave::halfTypeList());
            $data['statusList'] = setObjectIdAndName(LeaveType::statusList());

            return  $this->respond(['data' => $data]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    public function list(Request $request)
    {
        $filter = $request->all();
        try {
            $leaveTypeModels = $this->leaveType->findAll(20, $filter);
            return $this->respond([
                'status' => true,
                'data' => $leaveTypeModels
            ]);
          
        } catch (\Throwable $th) {
            return $this->respondInvalidQuery();
        }
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validate = Validator::make(
            $data,
            [
                'organization_id' => 'required',
                'leave_year_id' => 'required',
                'name' => 'required',
                'number_of_days' => 'required',
                'departmentArray' => 'required|array',
                'levelArray' => 'required|array',
                'gender',
                'marital_status',
                'code'
            ]
        );
        if($validate->fails()){
            return $this->respondValidatorFailed($validate);
        }
        $data['gender'] = $data['gender'] ==  'all' ? null : $data['gender'];
        $data['marital_status'] = $data['marital_status'] ==  'all' ? null : $data['marital_status'];

        try {
            $leaveTypeData = $this->leaveType->create($data);
            // if (isset($leaveTypeData['fixed_remaining_leave']) && $leaveTypeData['fixed_remaining_leave'] > 0) {
            //     $this->updateFixedEmpRemainingLeave($leaveTypeData);
            // } else {
            // }
            $this->updateLeaveTypeDetails($leaveTypeData);

            if ($leaveTypeData->employeeLeave()->exists()) {
                return $this->respond([
                    'status' => true,
                    'message' => 'Leave type has been created Successfully',
                ]);
            } else {
                return $this->respond([
                    'status' => true,
                    'message' => 'No Employee Found',
                ]);
            }
        } catch (\Throwable $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $validate = Validator::make(
            $data,
            [
                'organization_id' => 'required',
                'leave_year_id' => 'required',
                'name' => 'required',
                'number_of_days' => 'required',
                'departmentArray' => 'required|array',
                'levelArray' => 'required|array',
                'gender',
                'marital_status',
                'code'
            ]
        );
        if($validate->fails()){
            return $this->respondValidatorFailed($validate);
        }
        $data['gender'] = $data['gender'] ==  'all' ? null : $data['gender'];
        $data['marital_status'] = $data['marital_status'] ==  'all' ? null : $data['marital_status'];

        try {
            $leaveType = $this->leaveType->findOne($id);
            $this->leaveType->update($id, $data);
            $leaveType->request_prorata = $data['prorata_status'];
            $leaveType->is_update = true;

            $this->updateLeaveTypeDetails($leaveType);

            // $this->leaveType->update($id, $data);
            // if (isset($data['fixed_remaining_leave']) && $data['fixed_remaining_leave'] > 0) {
            //     // $this->updateFixedEmpRemainingLeave($leaveType, $data);
            //     $this->updateLeaveTypeDetails($leaveType);
            // } else {
            //     if ($leaveType->prorata_status != $data['prorata_status']) {
            //         $this->UpdateLeaveProrataTypeDetails($leaveType, $data);
            //     }
            // }
            return  $this->respond([
                'status' => true,
                'message' => 'Leave Type has been updated Successfully',
            ]);
        } catch (\Throwable $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    public function updateLeaveTypeDetails($leaveType, $lastLeaveYearId = null, $lastLeaveTypeId = null)
    {
        // $leaveType = ($this->leaveType->findOne($leaveTypeId));
        $params['department_ids'] = $leaveType->departments->pluck('department_id', 'department_id')->toArray();
        $params['level_ids']  = $leaveType->levels->pluck('level_id', 'level_id')->toArray();
        $params['gender'] = $leaveType['gender'];
        $params['marital_status'] = $leaveType['marital_status'];
        $params['contract_type'] = $leaveType['contract_type'];
        $params['probation_status'] = $leaveType['job_type'];

        $employees = $this->employee->getEmployeeByOrganization($leaveType['organization_id'], $params);
        $empLeave = $leaveType->employeeLeave->where('is_valid', 11);
        $unsetEmpLeaves = array_diff($empLeave->pluck('employee_id')->toArray(), $employees->pluck('id')->toArray());
        foreach ($unsetEmpLeaves as $key => $value) {
            $leaveType = $empLeave->where('employee_id', $value)->first();
            $leaveType->is_valid = 10;
            $leaveType->save();
        }
        $currentLeaveYearData = LeaveYearSetup::currentLeaveYear();
        if (!is_null($currentLeaveYearData)) {

            foreach ($employees as $employee) {
                if (isset($leaveType['fixed_remaining_leave']) && $leaveType['fixed_remaining_leave'] > 0) {
                    $empLeaveData = [
                        'leave_year_id' => $currentLeaveYearData['id'],
                        'employee_id' => $employee->id,
                        'leave_type_id' => $leaveType->id
                    ];

                    $leaveTaken = Leave::where([
                        'organization_id' => $leaveType['organization_id'],
                        'employee_id' => $employee->id,
                        'leave_type_id' => $leaveType['id']
                    ])
                        ->where('status', '!=', '4')
                        ->count();

                    $empLeave = EmployeeLeave::updateOrCreate(
                        $empLeaveData,
                        $empLeaveData + [
                            'leave_remaining' => $leaveType['fixed_remaining_leave'] - $leaveTaken,
                            'is_valid' => 11
                        ]
                    );

                    EmployeeLeaveOpening::firstOrCreate(
                        $empLeaveData,
                        $empLeaveData + [
                            'opening_leave' => $leaveType['fixed_remaining_leave'],
                            'organization_id' => $leaveType['organization_id']
                        ]
                    );
                    // EmployeeLeave::updateOrCreate(
                    //     $empLeaveData,
                    //     $empLeaveData + [
                    //         'leave_remaining' => $leaveType['fixed_remaining_leave'],
                    //         'is_valid' => 11
                    //     ]
                    // );

                    // EmployeeLeaveOpening::updateOrCreate(
                    //     $empLeaveData,
                    //     $empLeaveData + [
                    //         'opening_leave' => $leaveType['fixed_remaining_leave'],
                    //         'organization_id' => $leaveType['organization_id']
                    //     ]
                    // );
                } else {

                    if ($leaveType->is_update) {
                        if ($leaveType->prorata_status != $leaveType->request_prorata) {
                            continue;
                        }
                    }
                    $dataParams = [
                        'employee_join_date' => $employee->join_date,
                        'employee_nepali_join_date' => $employee->nepali_join_date,
                        'employee_id' => $employee->id,
                        'leaveTypeId' => $leaveType->id,
                        'leaveTypeData' => $leaveType,
                        'leaveYearId' => $currentLeaveYearData['id'],
                        'leaveYearStartDate' => $currentLeaveYearData->start_date_english,
                        'nepaliLeaveYearStartDate' => $currentLeaveYearData['start_date'],
                    ];
                    $this->updateEmployeeData($dataParams, $lastLeaveYearId, $lastLeaveTypeId);
                }
            }
        }
    }

    public function destroy($id)
    {
        try {
            $this->leaveType->delete($id);
            return $this->respond([
                'status' => true,
                'message' => 'Leave type has been deleted Successfully',
            ]);
        } catch (\Throwable $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

}
