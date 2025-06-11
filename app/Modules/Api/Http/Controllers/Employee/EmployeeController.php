<?php

namespace App\Modules\Api\Http\Controllers\Employee;

use App\Helpers\DateTimeHelper;
use App\Modules\Api\Http\Controllers\ApiController;
use App\Modules\Api\Service\Employee\EmployeeService;
use App\Modules\Api\Transformers\AssetDetailResource;
use App\Modules\Api\Transformers\BankDetailResource;
use App\Modules\Api\Transformers\BenefitDetailResource;
use App\Modules\Api\Transformers\CareerMobilityResource;
use App\Modules\Api\Transformers\ContractDetailResource;
use App\Modules\Api\Transformers\DocumentDetailResource;
use App\Modules\Api\Transformers\EducationDetailResource;
use App\Modules\Api\Transformers\EmergencyDetailResource;
use App\Modules\Api\Transformers\EmployeeResource;
use App\Modules\Api\Transformers\FamilyDetailResource;
use App\Modules\Api\Transformers\MedicalDetailResource;
use App\Modules\Api\Transformers\PreviousJobDetailResource;
use App\Modules\Asset\Repositories\AssetAllocateRepository;
use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Employee\Entities\AssetDetail;
use App\Modules\Employee\Entities\BankDetail;
use App\Modules\Employee\Entities\BenefitDetail;
use App\Modules\Employee\Entities\ContractDetail;
use App\Modules\Employee\Entities\DocumentDetail;
use App\Modules\Employee\Entities\EducationDetail;
use App\Modules\Employee\Entities\EmergencyDetail;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\EmployeeAdvanceApprovalFlow;
use App\Modules\Employee\Entities\EmployeeAppraisalApprovalFlow;
use App\Modules\Employee\Entities\EmployeeApprovalFlow;
use App\Modules\Employee\Entities\EmployeeCarrierMobility;
use App\Modules\Employee\Entities\EmployeeClaimRequestApprovalFlow;
use App\Modules\Employee\Entities\EmployeeDayOff;
use App\Modules\Employee\Entities\EmployeeLeave;
use App\Modules\Employee\Entities\EmployeeOffboardApprovalFlow;
use App\Modules\Employee\Entities\EmployeeOtDetail;
use App\Modules\Employee\Entities\EmployeePayrollRelatedDetail;
use App\Modules\Employee\Entities\EmployeeThresholdRelatedDetail;
use App\Modules\Employee\Entities\EmployeeTransfer;
use App\Modules\Employee\Entities\FamilyDetail;
use App\Modules\Employee\Entities\MedicalDetail;
use App\Modules\Employee\Entities\PreviousJobDetail;
use App\Modules\Employee\Repositories\AssetDetailInterface;
use App\Modules\Employee\Repositories\BankDetailInterface;
use App\Modules\Employee\Repositories\EmergencyDetailInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Employee\Repositories\FamilyDetailInterface;
use App\Modules\LeaveYearSetup\Entities\LeaveYearSetup;
use App\Modules\Holiday\Repositories\HolidayInterface;
use App\Modules\Leave\Entities\EmployeeLeaveOpening;
use App\Modules\Leave\Entities\Leave;
use App\Modules\Leave\Entities\LeaveType;
use App\Modules\Leave\Repositories\LeaveTypeInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Payroll\Repositories\DeductionSetupInterface;
use App\Modules\Payroll\Repositories\HoldPaymentInterface;
use App\Modules\Payroll\Repositories\PayrollInterface;
use App\Modules\Setting\Entities\OtRateSetup;
use App\Modules\User\Entities\Role;
use App\Modules\User\Entities\User;
use App\Modules\User\Repositories\RoleInterface;
use App\Modules\User\Repositories\UserInterface;
use App\Modules\User\Repositories\UserRoleInterface;
use Dotenv\Exception\ValidationException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends ApiController
{

    private $employee;
    private $organization;
    private $branch;
    private $dropdown;
    private $holiday;
    private $deduction;
    private $leaveType;
    private $user;
    private $role;
    private $userRole;
    protected $familyDetail;
    protected $assetDetail;
    protected $emergencyDetail;
    protected $bankDetail;
    protected $payroll;
    protected $holdPayment;



    public function __construct(
        EmployeeInterface $employee,
        OrganizationInterface $organization,
        BranchInterface $branch,
        DropdownInterface $dropdown,
        HolidayInterface $holiday,
        DeductionSetupInterface $deduction,
        LeaveTypeInterface $leaveType,
        UserInterface $user,
        RoleInterface $role,
        UserRoleInterface $userRole,
        FamilyDetailInterface $familyDetail,
        AssetDetailInterface $assetDetail,
        EmergencyDetailInterface $emergencyDetail,
        BankDetailInterface $bankDetail,
        PayrollInterface $payroll,
        HoldPaymentInterface $holdPayment
    ) {
        $this->employee = $employee;
        $this->organization = $organization;
        $this->branch = $branch;
        $this->dropdown = $dropdown;
        $this->holiday = $holiday;
        $this->deduction = $deduction;
        $this->leaveType = $leaveType;
        $this->user = $user;
        $this->role = $role;
        $this->userRole = $userRole;
        $this->familyDetail = $familyDetail;
        $this->assetDetail = $assetDetail;
        $this->emergencyDetail = $emergencyDetail;
        $this->bankDetail = $bankDetail;
        $this->payroll = $payroll;
        $this->holdPayment = $holdPayment;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function getEmployeeList()
    {
        try {
            $data = (new EmployeeService())->getOtherEmployeeList();
            return $this->respond([
                'status' => true,
                'data' => $data
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    public function getAlternativeEmployees(Request $request)
    {
        try {
            $inputData = $request->all();

            $params['employee_id'] = $inputData['employee_id'];
            $data = (new EmployeeService())->findAlternativeEmployees($params);
            return $this->respond([
                'status' => true,
                'data' => $data
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    public function familyDetail()
    {
        $activeUserModel = auth()->user();
        try {
            $data = FamilyDetail::where('employee_id', $activeUserModel->emp_id)->latest()->get();
            return  $this->respond([
                'status' => true,
                'data' => FamilyDetailResource::collection($data)
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    public function assetDetail()
    {
        $activeUserModel = auth()->user();
        try {
            $filter['employee_id'] = $activeUserModel->emp_id;
            $assetAllocateModels = (new AssetAllocateRepository())->findAll(null, $filter);
            return  $this->respond([
                'status' => true,
                'data' => AssetDetailResource::collection($assetAllocateModels)
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    public function emergencyDetail()
    {
        $activeUserModel = auth()->user();
        try {
            $data = EmergencyDetail::where('employee_id', $activeUserModel->emp_id)->latest()->get();
            return  $this->respond([
                'status' => true,
                'data' => EmergencyDetailResource::collection($data)

            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    public function benefitDetail()
    {
        $activeUserModel = auth()->user();
        try {
            $data = BenefitDetail::where('employee_id', $activeUserModel->emp_id)->latest()->get();
            return  $this->respond([
                'status' => true,
                'data' => BenefitDetailResource::collection($data)

            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    public function educationDetail()
    {
        $activeUserModel = auth()->user();
        try {
            $data = EducationDetail::where('employee_id', $activeUserModel->emp_id)->latest()->get();
            return  $this->respond([
                'status' => true,
                'data' => EducationDetailResource::collection($data)

            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    public function previousJobDetail()
    {
        $activeUserModel = auth()->user();
        try {
            $data = PreviousJobDetail::where('employee_id', $activeUserModel->emp_id)->latest()->get();
            return  $this->respond([
                'status' => true,
                'data' => PreviousJobDetailResource::collection($data)

            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    public function bankDetail()
    {
        $activeUserModel = auth()->user();
        try {
            $data = BankDetail::where('employee_id', $activeUserModel->emp_id)->latest()->get();
            return  $this->respond([
                'status' => true,
                'data' => BankDetailResource::collection($data)

            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    public function contractDetail()
    {
        $activeUserModel = auth()->user();
        try {
            $data = ContractDetail::where('employee_id', $activeUserModel->emp_id)->latest()->get();
            return  $this->respond([
                'status' => true,
                'data' => ContractDetailResource::collection($data)

            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    public function documentDetail()
    {
        $activeUserModel = auth()->user();
        try {
            $data = DocumentDetail::where('employee_id', $activeUserModel->emp_id)->latest()->get();
            return  $this->respond([
                'status' => true,
                'data' => DocumentDetailResource::collection($data)

            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    public function medicalDetail()
    {
        $activeUserModel = auth()->user();
        try {
            $data = MedicalDetail::where('employee_id', $activeUserModel->emp_id)->latest()->get();
            return  $this->respond([
                'status' => true,
                'data' => MedicalDetailResource::collection($data)

            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    public function getDropdown()
    {
        try {
            $data['organizationList'] = setObjectIdAndName($this->organization->getList());
            $data['branchList'] = setObjectIdAndName($this->branch->getList());
            $data['employeeList'] = setObjectIdAndName($this->employee->getList());
            $data['departmentList'] = setObjectIdAndName($this->dropdown->getFieldBySlug('department'));
            $data['levelList'] = setObjectIdAndName($this->dropdown->getFieldBySlug('level'));
            $data['designationList'] = setObjectIdAndName($this->dropdown->getFieldBySlug('designation'));
            $data['gender'] = setObjectIdAndName($this->dropdown->getFieldBySlug('gender'));
            $data['marital_status'] = setObjectIdAndName($this->dropdown->getFieldBySlug('marital_status'));
            $data['typeList'] = setObjectIdAndName(EmployeeCarrierMobility::typeList());
            $data['probationStatusList'] = setObjectIdAndName(EmployeeCarrierMobility::probationStatusList());
            $data['payrollChangeList'] = setObjectIdAndName(EmployeeCarrierMobility::payrollChangeList());

            $data['bloodGroup'] = setObjectIdAndName($this->dropdown->getFieldBySlug('blood_group'));
            $data['ethnic'] = setObjectIdAndName($this->dropdown->getFieldBySlug('ethnic'));
            $data['jobStatusList'] = setObjectIdAndName($this->dropdown->getFieldBySlug('job_status'));
            $data['religionList'] = setObjectIdAndName($this->holiday->getReligionType());
            unset($data['religionList'][1]);
            $data['district'] = setObjectIdAndName($this->employee->getDistrict());
            $data['state'] = setObjectIdAndName($this->employee->getStates());
            $data['countryList'] = setObjectIdAndName($this->employee->getCountries());
            $data['deductionList'] = setObjectIdAndName($this->deduction->getFixedList());

            $data['contractTypeList'] = setObjectIdAndName(LeaveType::CONTRACT);
            unset($data['contractTypeList'][100]);
            $data['statusList'] = setObjectIdAndName([10 => 'No', 11 => 'Yes']);
            $data['otType'] = setObjectIdAndName(OtRateSetup::OT_TYPE);

            $data['familyRelations'] = FamilyDetail::relationType();
            $data['assetTypes'] = setObjectIdAndName($this->dropdown->getFieldBySlug('asset_type'));
            $data['benefitTypes'] = setObjectIdAndName($this->dropdown->getFieldBySlug('benefit_type'));
            $data['bankNames'] = setObjectIdAndName($this->dropdown->getFieldBySlug('bank_name'));
            $data['accountTypes'] = setObjectIdAndName($this->dropdown->getFieldBySlug('account_type'));
            $data['countryList'] = setObjectIdAndName($this->employee->getCountries());
            $data['user_type'] = $this->dropdown->getUserType('user_type');

            return  $this->respond(['data' => $data]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    public function approvalFlowReport(Request $request)
    {
        $filter = $request->all();
        try {
            $query = Employee::query();
            if (!empty($filter['organization_id'])) {
                $query->where('organization_id', $filter['organization_id']);
            }
            if (!empty($filter['employee_id'])) {
                $query->where('id', $filter['employee_id']);
            }
            if (setting('calendar_type') == 'BS') {
                if (isset($filter['from_nep_date']) && !empty($filter['from_nep_date'])) {
                    $query->where('created_at', '>=', date_converter()->nep_to_eng_convert($filter['from_nep_date']));
                }
                if (isset($filter['to_nep_date']) && !empty($filter['to_nep_date'])) {
                    $query->where('created_at', '<=', date_converter()->nep_to_eng_convert($filter['to_nep_date']));
                }
            } else {
                if (isset($filter['date_range'])) {
                    $filterDates = explode(' - ', $filter['date_range']);
                    $query->where('created_at', '>=', $filterDates[0]);
                    $query->where('created_at', '<=', $filterDates[1]);
                }
            }
            $employees = $query->where('status', 1)->get();
            $data = $employees->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'employee_name' => $employee->full_name,

                    'leave_first_approval' => optional(optional($employee->employeeApprovalFlowRelatedDetailModel)->userFirstApproval)->full_name,
                    'leave_second_approval' => optional(optional($employee->employeeApprovalFlowRelatedDetailModel)->userSecondApproval)->full_name,
                    'leave_third_approval' => optional(optional($employee->employeeApprovalFlowRelatedDetailModel)->userThirdApproval)->full_name,
                    'leave_last_approval' => optional(optional($employee->employeeApprovalFlowRelatedDetailModel)->userLastApproval)->full_name,

                    'claim_first_approval' => optional(optional($employee->employeeClaimRequestApprovalDetailModel)->firstApproval)->full_name,
                    'claim_last_approval' => optional(optional($employee->employeeClaimRequestApprovalDetailModel)->lastApproval)->full_name,

                    'offboard_first_approval' => optional(optional($employee->employeeOffboardApprovalDetailModel)->firstApprovalUserModel)->full_name,
                    'offboard_last_approval' => optional(optional($employee->employeeOffboardApprovalDetailModel)->lastApprovalUserModel)->full_name,

                    'appraisal_first_approval' => optional(optional($employee->employeeAppraisalApprovalDetailModel)->firstApprovalUserModel)->full_name,
                    'appraisal_last_approval' => optional(optional($employee->employeeAppraisalApprovalDetailModel)->lastApprovalUserModel)->full_name,
                ];
            });
            return $this->respond([
                'status' => true,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return $this->respondInvalidQuery();
        }
    }

    public function updateApprovalFlow(Request $request, $id)
    {
        $data = $request->all();
        $validate = Validator::make(
            $data,
            [
                'last_approval_user_id' => 'required',
                'last_claim_approval_user_id' => 'required',
                'offboard_first_approval' => 'required',
                'appraisal_first_approval' => 'required',
                // 'name' => 'required',
            ]
        );
        if ($validate->fails()) {
            return $this->respondValidatorFailed($validate);
        }
        try {
            //Employee Leave Approval Flow
            $employeeApprovalFlow = [
                'first_approval_user_id' => isset($data['first_approval_user_id']) ? $data['first_approval_user_id'] : null,
                'second_approval_user_id' => isset($data['second_approval_user_id']) ? $data['second_approval_user_id'] : null,
                'third_approval_user_id' => isset($data['third_approval_user_id']) ? $data['third_approval_user_id'] : null,
                'last_approval_user_id' => $data['last_approval_user_id'],
                'updated_by' => auth()->user()->id,
            ];
            EmployeeApprovalFlow::saveData($id, $employeeApprovalFlow);

            //Employee Claim and Request Approval Flow
            $employeeClaimRequestApprovalFlow = [
                'first_claim_approval_user_id' => $data['first_claim_approval_user_id'],
                'last_claim_approval_user_id' => $data['last_claim_approval_user_id'],
                'updated_by' => auth()->user()->id,
            ];
            EmployeeClaimRequestApprovalFlow::saveData($id, $employeeClaimRequestApprovalFlow);

            // check and save offboard approval flow
            if (isset($data['offboard_first_approval'])) {
                $offboardApprovalData['employee_id'] = $id;
                $offboardApprovalData['offboard_first_approval'] = $data['offboard_first_approval'];
                $offboardApprovalData['offboard_last_approval'] = $data['offboard_last_approval'];
                EmployeeOffboardApprovalFlow::checkAndSaveOffboardApprovalFlow($offboardApprovalData);
            }

            // check and save appraisal approval flow
            if (isset($data['appraisal_first_approval'])) {
                $appraisalApprovalData['employee_id'] = $id;
                $appraisalApprovalData['appraisal_first_approval'] = $data['appraisal_first_approval'];
                $appraisalApprovalData['appraisal_last_approval'] = $data['appraisal_last_approval'];
                EmployeeAppraisalApprovalFlow::checkAndSaveAppraisalApprovalFlow($appraisalApprovalData);
            }

            return  $this->respond([
                'status' => true,
                'message' => 'Approval flow data has been updated successfully',
            ]);
        } catch (\Throwable $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    public function careerMobility(Request $request)
    {
        $filter = $request->all();
        try {
            $data['employee'] = [];
            if (!empty($filter)) {
                $data['employee'] = $employee = $this->employee->find($filter['employee_id']);
                $data['filteredBranchList'] = setObjectIdAndName($this->branch->branchListOrganizationwise($employee->organization_id));
            }
            return $this->respond([
                'status' => true,
                'data' => $data
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    public function storeCareerMobility(Request $request)
    {
        $data = $request->all();
        $validate = Validator::make(
            $data,
            [
                'date' => 'required',
                'type_id' => 'required'
            ]
        );
        if ($validate->fails()) {
            return $this->respondValidatorFailed($validate);
        }
        try {
            $employeeOldData = $this->employee->find($request['employee_id']);
            $current_user_id = auth()->user()->id;

            $data['date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['date']) : $data['date'];

            $storeData = EmployeeCarrierMobility::create($data);
            if ($storeData) {
                if ($employeeOldData['organization_id'] != $data['organization_id']) {
                    $currentLeaveYearModel = LeaveYearSetup::currentLeaveYear();
                    EmployeeLeave::where('leave_year_id', $currentLeaveYearModel->id)->where('employee_id', $request['employee_id'])->delete();
                    EmployeeLeaveOpening::where('leave_year_id', $currentLeaveYearModel->id)->where('organization_id', $employeeOldData['organization_id'])->where('employee_id', $request['employee_id'])->delete();
                    $employeeleaveData = [];
                    $employeeleaveOpeningData = [];
                    if (isset($data['leave_remaining'])) {
                        foreach ($data['leave_remaining'] as $key => $value) {
                            $employeeleaveData['leave_year_id'] = $currentLeaveYearModel->id;
                            $employeeleaveData['employee_id'] = $data['employee_id'];
                            $employeeleaveData['leave_type_id'] = $key;
                            $employeeleaveData['is_valid'] = 11;
                            $employeeleaveData['leave_remaining'] = $value;
                            $employeeleaveData['created_by'] = $current_user_id;
                            EmployeeLeave::create($employeeleaveData);
                            $employeeleaveOpeningData['leave_year_id'] = $currentLeaveYearModel->id;
                            $employeeleaveOpeningData['organization_id'] = $data['organization_id'];
                            $employeeleaveOpeningData['employee_id'] = $data['employee_id'];
                            $employeeleaveOpeningData['leave_type_id'] = $key;
                            $employeeleaveOpeningData['opening_leave'] = $value;
                            EmployeeLeaveOpening::create($employeeleaveOpeningData);
                        }
                    }
                }
                $this->employee->update($request['employee_id'], $data);
                if ($data['type_id'] == 7) {
                    $payrollData['probation_status'] = $data['probation_status'];
                } else if ($data['type_id'] == 8) {
                    $payrollData['payroll_change'] = $data['payroll_change'];
                }
                if (isset($payrollData)) {
                    $payrollData['employee_id'] = $data['employee_id'];
                    EmployeePayrollRelatedDetail::saveData($request['employee_id'], $payrollData);
                }

                // save employee timeline
                $timelineData['employee_id'] = $data['employee_id'];
                $timelineData['date'] = $data['date'];
                $timelineData['title'] = "Employee Career Mobility";
                // $timelineData['description'] = "Transfer from " . optional($result->fromOrganizationModel)->name . " to " . optional($result->toOrganizationModel)->name;
                $description = EmployeeCarrierMobility::getTypewiseName($storeData);
                $timelineData['description'] = $storeData->getTypeList() . $description;
                $timelineData['icon'] = "icon-truck";
                $timelineData['color'] = "secondary";
                $timelineData['reference'] = "employee-career-mobility";
                $timelineData['reference_id'] = $storeData->id;
                $timelineData['carrier_mobility_id'] = $storeData->id;
                Employee::saveEmployeeTimelineData($data['employee_id'], $timelineData);
            }
            return $this->respond([
                'status' => true,
                'message' => 'Employee career mobility data has been updated succesfully !!!'
            ]);
        } catch (\Throwable $th) {
            return $this->respondInvalidQuery($th->getMessage());
        }
    }

    public function careerMobilityReport(Request $request)
    {
        $data['filter'] = $filter = $request->all();
        $sort = [
            'by' => 'id',
            'sort' => 'DESC'
        ];
        try {
            $reports = $this->employee->findMobilityReport(null, $filter, $sort);
            $data = CareerMobilityResource::collection($reports);
            return $this->respond([
                'status' => true,
                'data' => $data
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    public function destroyCareerMobility($id)
    {
        try {
            $this->employee->deleteCarrierMobility($id);
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        } catch (ValidationException $e) {
            return $this->respondValidatorFailed($e->validator);
        }
        return $this->respondObjectDeleted($id);
    }

    public function activeEmployeeList(Request $request)
    {
        $filter = $request->all();
        try {
            $employees = $this->employee->findAll(10, $filter);
            $employeeData = EmployeeResource::collection($employees);
            return $this->respond([
                'status' => true,
                'data' => $employeeData
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
                'organization_id' => "required",
                'employee_code' => "required",
                'biometric_id' => "required",
                'first_name' => "required",
                'last_name' => "required",
                'dayoff' => "required|array",
                'join_date' => "required",
                // 'nepali_join_date'=> "required",
                'dob' => "required",
                // 'nep_dob'=> "required",
                'gender' => "required",
                'permanentprovince' => "required",
                'permanentdistrict' => "required",
                'permanentmunicipality_vdc' => "required",
                'permanentward' => "required",
                'permanentaddress' => "required",
                'branch_id' => "required",
                'department_id' => "required",
                'level_id' => "required",
                'designation_id' => "required",
                'job_title' => "required",
                'last_approval_user_id' => "required",
                'last_claim_approval_user_id' => "required",
                'personal_email' => "email",
                'offboard_first_approval' => 'required',
                'appraisal_first_approval' => 'required',
                'advance_first_approval' => 'required',
            ]
        );
        if ($validate->fails()) {
            return $this->respondValidatorFailed($validate);
        }
        try {
            $data['created_by'] = auth()->user()->id;

            $employee_check = $this->employee->getEmployeeByCode($data['employee_code']);
            if ($employee_check) {
                return $this->respondWithError('Employee Code already exists!');
            }

            if ($request->hasFile('profilepic')) {
                $data['profile_pic'] = $this->employee->uploadProfilePic($data['profilepic']);
            }

            if ($request->hasFile('citizenpic')) {
                $data['citizen_pic'] = $this->employee->uploadCitizen($data['citizenpic']);
            }

            if ($request->hasFile('documentpic')) {
                $data['document_pic'] = $this->employee->uploadDocument($data['documentpic']);
            }

            if ($request->hasFile('signaturepic')) {
                $data['signature'] = $this->employee->uploadSignature($data['signaturepic']);
            }

            $data['join_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['nepali_join_date']) : $data['join_date'];
            $data['nepali_join_date'] = setting('calendar_type') == "AD" ? date_converter()->eng_to_nep_convert($data['join_date']) : $data['nepali_join_date'];

            $data['dob'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['nep_dob']) : $data['dob'];
            $data['nep_dob'] = setting('calendar_type') == "AD" ? date_converter()->eng_to_nep_convert($data['dob']) : $data['nep_dob'];

            if (setting('calendar_type') == "BS" && isset($data['nep_end_date'])) {
                $data['end_date'] = date_converter()->nep_to_eng_convert($data['nep_end_date']);
            } elseif (setting('calendar_type') == "AD" && isset($data['end_date'])) {
                $data['nep_end_date'] = date_converter()->eng_to_nep_convert($data['end_date']);
            }

            unset($data['profilepic']);
            unset($data['citizenpic']);
            unset($data['documentpic']);
            unset($data['signaturepic']);
            unset($data['dayoff']);

            if (isset($data['not_affect_on_payroll'])) {
                $data['not_affect_on_payroll'] = 1;
            }
            $data['status'] = '1';
            $employeeInfo = $this->employee->save($data);
            EmployeePayrollRelatedDetail::saveData($employeeInfo->id, $data);

            //Employee Day Off
            foreach ($request->dayoff as $key => $value) {
                $employeDayOff = [
                    'day_off' => $value,
                    'employee_id' => $employeeInfo->id
                ];
                EmployeeDayOff::create($employeDayOff);
            }
            if (isset($data['ot']) && $data['ot'] == 11) {
                foreach ($data['ot_type'] as $key => $value) {
                    $employeeOtDetail = [
                        'employee_id' => $employeeInfo->id,
                        'ot_type' => $value,
                        'rate' => $data['rate'][$key]
                    ];
                    EmployeeOtDetail::create($employeeOtDetail);
                }
            }

            //Employee Leave Approval Flow
            $employeeApprovalFlow = [
                'first_approval_user_id' => isset($data['first_approval_user_id']) ? $data['first_approval_user_id'] : null,
                'second_approval_user_id' => isset($data['second_approval_user_id']) ? $data['second_approval_user_id'] : null,
                'third_approval_user_id' => isset($data['third_approval_user_id']) ? $data['third_approval_user_id'] : null,
                'last_approval_user_id' => $data['last_approval_user_id'],
                'created_by' => auth()->user()->id,
            ];
            EmployeeApprovalFlow::saveData($employeeInfo->id, $employeeApprovalFlow);
            //

            //Employee Claim and Request Approval Flow
            $employeeClaimRequestApprovalFlow = [
                'first_claim_approval_user_id' => $data['first_claim_approval_user_id'] ?? null,
                'last_claim_approval_user_id' => $data['last_claim_approval_user_id'],
                'created_by' => auth()->user()->id,
            ];
            EmployeeClaimRequestApprovalFlow::saveData($employeeInfo->id, $employeeClaimRequestApprovalFlow);

            //Leave Opening
            $this->createEmployeeLeave($employeeInfo, $data);

            // check and save offboard approval flow
            if (isset($data['offboard_first_approval'])) {
                $offboardApprovalData['employee_id'] = $employeeInfo->id;
                $offboardApprovalData['offboard_first_approval'] = $data['offboard_first_approval'];
                $offboardApprovalData['offboard_last_approval'] = $data['offboard_last_approval'] ?? null;
                EmployeeOffboardApprovalFlow::checkAndSaveOffboardApprovalFlow($offboardApprovalData);
            }

            // check and save appraisal approval flow
            if (isset($data['appraisal_first_approval'])) {
                $appraisalApprovalData['employee_id'] = $employeeInfo->id;
                $appraisalApprovalData['appraisal_first_approval'] = $data['appraisal_first_approval'];
                $appraisalApprovalData['appraisal_last_approval'] = $data['appraisal_last_approval'] ?? null;
                EmployeeAppraisalApprovalFlow::checkAndSaveAppraisalApprovalFlow($appraisalApprovalData);
            }
            if (isset($data['advance_first_approval'])) {
                $advanceApprovalData['employee_id'] = $employeeInfo->id;
                $advanceApprovalData['advance_first_approval'] = $data['advance_first_approval'];
                $advanceApprovalData['advance_last_approval'] = $data['advance_last_approval'] ?? null;
                EmployeeAdvanceApprovalFlow::checkAndSaveAdvanceApprovalFlow($advanceApprovalData);
            }
            return $this->respond([
                'status' => true,
                'message' => 'Employee has been created Successfully',
            ]);
        } catch (\Throwable $th) {
            return $this->respondInvalidQuery($th->getMessage());
        }
    }

    public function view($id)
    {
        $employeeModel = $this->employee->find($id);
        $userDetails = $this->user->getUserByEmpId($id);
        if (isset($userDetails) && !empty($userDetails)) {
            $employeeModel['user_name'] = $userDetails->username;
            $employeeModel['user_type'] = $userDetails->user_type;
        }

        $userId = auth()->user()->id;
        $userType = auth()->user()->user_type;
        if ($userType == 'employee' && $userId != optional($employeeModel->getUser)->id) {
            return $this->respondWithError("Oops! You don't have permission");
        }

        // $data['employeeModel'] = $employeeModel;
        //Dropdowns
        // $data['family_relations'] = $this->dropdown->getFieldBySlug('family_relation');

        $data['family_details'] = $this->familyDetail->findAll($id);
        $data['asset_details'] = $this->assetDetail->findAll($id);
        $data['emergency_details'] = $this->emergencyDetail->findAll($id);
        $data['bank_details'] = $this->bankDetail->findAll($id);
        $data['timelineModels'] = $this->employee->getEmployeeTimelineModel($id);
        $data['payrollModels'] = $this->payroll->getEmployeePayrollList($id);
        $data['holdPayments'] = $this->holdPayment->getAllHoldPaymentByEmployee($id);

        $filter['except_id'] = $employeeModel->organization_id;
        $data['transferOrganizationList'] = $this->organization->getList($filter);
        $data['transferStatusList'] = EmployeeTransfer::getStatusList();

        try {
            $employeeModel = $this->employee->find($id);
            $employeeData = EmployeeResource::collection($employeeModel);
            return $this->respond([
                'status' => true,
                'employee' => $employeeData,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return $this->respondInvalidQuery();
        }
    }
    public function update(Request $request, $id)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'organization_id' => "required",
                'employee_code' => "required",
                'biometric_id' => "required",
                'first_name' => "required",
                'last_name' => "required",
                'dayoff' => "required|array",
                'join_date' => "required",
                // 'nepali_join_date'=> "required",
                'dob' => "required",
                // 'nep_dob'=> "required",
                'gender' => "required",
                'permanentprovince' => "required",
                'permanentdistrict' => "required",
                'permanentmunicipality_vdc' => "required",
                'permanentward' => "required",
                'permanentaddress' => "required",
                'branch_id' => "required",
                'department_id' => "required",
                'level_id' => "required",
                'designation_id' => "required",
                'job_title' => "required",
                'last_approval_user_id' => "required",
                'last_claim_approval_user_id' => "required",
                'personal_email' => "email",
                'offboard_first_approval' => 'required',
                'appraisal_first_approval' => 'required',
                'advance_first_approval' => 'required',
            ]
        );
        if ($validate->fails()) {
            return $this->respondValidatorFailed($validate);
        }

        $data = $request->all();
        $employee_old_data = $this->employee->find($id);
        $old_join_date = $employee_old_data['join_date'];
        $old_nepali_join_date = $employee_old_data['nepali_join_date'];

        $currentLeaveYearModel = LeaveYearSetup::currentLeaveYear();

        try {
            // check if the organization is changed
            $oldOrganizationId = $employee_old_data->organization_id;
            if ($oldOrganizationId != $data['organization_id']) {
                EmployeeLeave::where('leave_year_id', $currentLeaveYearModel->id)->where('employee_id', $id)->delete();
                EmployeeLeaveOpening::where('leave_year_id', $currentLeaveYearModel->id)->where('organization_id', $oldOrganizationId)->where('employee_id', $id)->delete();
            }

            if ($request->hasFile('profilepic')) {
                $data['profile_pic'] = $this->employee->uploadProfilePic($data['profilepic']);
            }

            if ($request->hasFile('citizenpic')) {
                $data['citizen_pic'] = $this->employee->uploadCitizen($data['citizenpic']);
            }

            if ($request->hasFile('documentpic')) {
                $data['document_pic'] = $this->employee->uploadDocument($data['documentpic']);
            }

            if ($request->hasFile('signaturepic')) {
                $data['signature'] = $this->employee->uploadSignature($data['signaturepic']);
            }

            $data['join_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['nepali_join_date']) : $data['join_date'];
            $data['nepali_join_date'] = setting('calendar_type') == "AD" ? date_converter()->eng_to_nep_convert($data['join_date']) : $data['nepali_join_date'];

            $data['dob'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['nep_dob']) : $data['dob'];
            $data['nep_dob'] = setting('calendar_type') == "AD" ? date_converter()->eng_to_nep_convert($data['dob']) : $data['nep_dob'];

            if (setting('calendar_type') == "BS" && isset($data['nep_end_date'])) {
                $data['end_date'] = date_converter()->nep_to_eng_convert($data['nep_end_date']);
            } elseif (setting('calendar_type') == "AD" && isset($data['end_date'])) {
                $data['nep_end_date'] = date_converter()->eng_to_nep_convert($data['end_date']);
            }
            unset($data['profilepic']);
            unset($data['citizenpic']);
            unset($data['documentpic']);
            unset($data['signaturepic']);
            unset($data['biometric_id']);
            unset($data['employee_code']);
            unset($data['dayoff']);

            if (!is_null($employee_old_data->biometric_id)) {
                $data['biometric_id'] = $employee_old_data->biometric_id;
            } elseif (!Employee::where('biometric_id', $request->biometric_id)->exists()) {
                $data['biometric_id'] = $request->biometric_id;
            }

            if (!is_null($employee_old_data->employee_code)) {
                $data['employee_code'] = $employee_old_data->employee_code;
            }
            $married = $this->dropdown->getByDropvalue('Married');
            if (!is_null($employee_old_data->marital_status) && $employee_old_data->marital_status == $married->id) {
                unset($data['marital_status']);
                $data['marital_status'] = $employee_old_data->marital_status;
            }
            if (isset($data['not_affect_on_payroll'])) {
                $data['not_affect_on_payroll'] = 1;
            }
            $this->employee->update($id, $data);

            //if join date changes, update timeline join date
            $this->employee->updateEmployeeTimelineJoinDate($data, $id);

            EmployeePayrollRelatedDetail::saveData($id, $data);

            $data['join_date'] = $old_join_date;
            $data['nepali_join_date'] = $old_nepali_join_date;
            //Employee Day Off
            $employee_old_data->employeeDayOff()->delete();
            foreach ($request->dayoff as $key => $value) {
                $employeDayOff = [
                    'day_off' => $value,
                    'employee_id' => $id
                ];
                EmployeeDayOff::create($employeDayOff);
            }
            if (isset($data['ot']) && $data['ot'] == 11) {
                EmployeeOtDetail::where('employee_id', $id)->delete();
                foreach ($data['ot_type'] as $key => $value) {
                    $employeeOtDetail = [
                        'employee_id' => $id,
                        'ot_type' => $value,
                        'rate' => $data['rate'][$key]
                    ];
                    EmployeeOtDetail::create($employeeOtDetail);
                }
            }

            // $employee_old_data->employeeThresholdDetail()->delete();
            EmployeeThresholdRelatedDetail::where('employee_id', $id)->delete();
            if (isset($request->gross_salary)) {
                foreach ($request->gross_salary as $key => $value) {
                    $employeeThresholdDetail = [
                        'employee_id' => $id,
                        'deduction_setup_id' => $key,
                        'amount' => $value
                    ];
                    EmployeeThresholdRelatedDetail::create($employeeThresholdDetail);
                }
            }

            //Employee Leave Approval Flow
            $employeeApprovalFlow = [
                'first_approval_user_id' => isset($data['first_approval_user_id']) ? $data['first_approval_user_id'] : null,
                'second_approval_user_id' => isset($data['second_approval_user_id']) ? $data['second_approval_user_id'] : null,
                'third_approval_user_id' => isset($data['third_approval_user_id']) ? $data['third_approval_user_id'] : null,
                'last_approval_user_id' => $data['last_approval_user_id'],
                'updated_by' => auth()->user()->id,
            ];
            EmployeeApprovalFlow::saveData($id, $employeeApprovalFlow);

            //Employee Claim and Request Approval Flow
            $employeeClaimRequestApprovalFlow = [
                'first_claim_approval_user_id' => isset($data['first_claim_approval_user_id']) ? $data['first_claim_approval_user_id'] : null,
                'last_claim_approval_user_id' => $data['last_claim_approval_user_id'],
                'updated_by' => auth()->user()->id,
            ];
            EmployeeClaimRequestApprovalFlow::saveData($id, $employeeClaimRequestApprovalFlow);
            //

            // Employee Leave and Employee Leave Opening Detail
            $this->createEmployeeLeave($employee_old_data, $data);

            // check and save offboard approval flow
            if (isset($data['offboard_first_approval'])) {
                $offboardApprovalData['employee_id'] = $id;
                $offboardApprovalData['offboard_first_approval'] = $data['offboard_first_approval'];
                $offboardApprovalData['offboard_last_approval'] = isset($data['offboard_last_approval']) ? $data['offboard_last_approval'] : null;
                EmployeeOffboardApprovalFlow::checkAndSaveOffboardApprovalFlow($offboardApprovalData);
            }

            // check and save appraisal approval flow
            if (isset($data['appraisal_first_approval'])) {
                $appraisalApprovalData['employee_id'] = $id;
                $appraisalApprovalData['appraisal_first_approval'] = $data['appraisal_first_approval'];
                $appraisalApprovalData['appraisal_last_approval'] = isset($data['appraisal_last_approval']) ? $data['appraisal_last_approval'] : null;
                EmployeeAppraisalApprovalFlow::checkAndSaveAppraisalApprovalFlow($appraisalApprovalData);
            }
            if (isset($data['advance_first_approval'])) {
                $advanceApprovalData['employee_id'] = $id;
                $advanceApprovalData['advance_first_approval'] = $data['advance_first_approval'];
                $advanceApprovalData['advance_last_approval'] = isset($data['advance_last_approval']) ? $data['advance_last_approval'] : null;
                EmployeeAdvanceApprovalFlow::checkAndSaveAdvanceApprovalFlow($advanceApprovalData);
            }

            // Employee Leave Detail (overwrite)
            if (isset($data['employee_leave_ids'])) {
                if (count($data['employee_leave_ids']) > 0) {
                    foreach ($data['employee_leave_ids'] as $key => $employeeLeaveId) {
                        if ($data['adjust_days'][$key] != null) {
                            EmployeeLeave::where('id', $employeeLeaveId)->update(['leave_remaining' => $data['adjust_days'][$key]]);
                        }
                    }
                }
            }

            // $result = $this->employee->checkAndCreateEmployeeLeave($id);
            //User Role Update  ::24th Jan 2020 :: Shyam sundar AWal
            $user_id = $this->user->getUserId($id);
            if ($user_id) {
                // $user = $this->user->find($user_id->id);
                $role = Role::where('user_type', $user_id->user_type)->first();

                $role_id = isset($data['role_id']) ? $data['role_id'] : $role->id;

                $update_role = array(
                    'role_id' => $role_id,
                    'created_at' => date('Y-m-d H:i:s')
                );

                $this->userRole->update($user_id->id, $update_role);

                $role = $this->role->find($role_id);
                // $user_type = $role->user_type;

                $user_access = array(
                    'user_type' => $role->user_type
                );
                $this->user->update($user_id->id, $user_access);
            }

            return  $this->respond([
                'status' => true,
                'message' => 'Employee has been updated Successfully',
            ]);
        } catch (\Throwable $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    public function createEmployeeLeave($employee, $data = [])
    {
        $currentDate = date('Y-m-d');
        $current_leave_year_data = LeaveYearSetup::currentLeaveYear();
        if (!is_null($current_leave_year_data)) {
            $leave_year_id = $current_leave_year_data['id'];
            $leave_year_start_date = $current_leave_year_data['start_date'];

            $nepali_join_date = $data['nepali_join_date'];
            if ($nepali_join_date < $leave_year_start_date) {
                $nepali_join_date = $leave_year_start_date;
            }
            $months_diff = DateTimeHelper::DateDiff($leave_year_start_date, $nepali_join_date);
            $emp_remaining_month_in_Current_leave = 12 - $months_diff;

            $params['gender'] = $employee->gender;
            $params['marital_status'] = $employee->marital_status;

            $params['department_id'] = $employee->department_id;
            $params['level_id'] = $employee->level_id;
            $params['contract_type'] = [optional($employee->payrollRelatedDetailModel)->contract_type];
            $params['job_type'] = [optional($employee->payrollRelatedDetailModel)->probation_status];

            $leave_types = $this->leaveType->getLeaveTypesFromOrganization($data['organization_id'], $leave_year_id, $params);
            // EmployeeLeaveOpening::where('employee_id',$id)->delete();
            // EmployeeLeave::where('employee_id',$id)->delete();
            $empLeaves = $employee->employeeleave->pluck('leave_type_id')->toArray();
            $unsetEmpLeaves = array_diff($empLeaves, $leave_types->pluck('id')->toArray());

            foreach ($unsetEmpLeaves as $key => $value) {
                $leaveType = $employee->employeeleave->where('leave_type_id', $value)->first();
                $leaveType->is_valid = 10;
                $leaveType->save();
            }

            foreach ($leave_types as $leave_type) {

                if (isset($leave_type['fixed_remaining_leave']) && $leave_type['fixed_remaining_leave'] > 0) {
                    $empLeaveData = [
                        'leave_year_id' => $leave_year_id,
                        'employee_id' => $employee->id,
                        'leave_type_id' => $leave_type->id
                    ];

                    $leaveTaken = Leave::where([
                        'organization_id' => $leave_type['organization_id'],
                        'employee_id' => $employee->id,
                        'leave_type_id' => $leave_type['id']
                    ])
                        ->where('status', '!=', '4')
                        ->count();

                    $empLeave = EmployeeLeave::updateOrCreate(
                        $empLeaveData,
                        $empLeaveData + [
                            'leave_remaining' => $leave_type['fixed_remaining_leave'] - $leaveTaken,
                            'is_valid' => 11
                        ]
                    );

                    EmployeeLeaveOpening::firstOrCreate(
                        $empLeaveData,
                        $empLeaveData + [
                            'opening_leave' => $leave_type['fixed_remaining_leave'],
                            'organization_id' => $leave_type['organization_id']
                        ]
                    );
                } elseif ((is_null($leave_type->gender) || $leave_type->gender == $data['gender']) &&
                    (is_null($leave_type->marital_status) || $leave_type->marital_status == $data['marital_status'])


                ) {
                    $leave_type_days_in_month = ($leave_type->number_of_days / 12);

                    $leave_opening = round($leave_type_days_in_month * $emp_remaining_month_in_Current_leave, 2);
                    if ($leave_type->prorata_status == '11') {
                        $newMonthsDiff = DateTimeHelper::DateDiff($employee->join_date, $currentDate);
                        if ($newMonthsDiff > 12) {
                            $newMonthsDiff = DateTimeHelper::DateDiff($current_leave_year_data->start_date_english, $currentDate);
                        }
                        $leave_opening = round($leave_type_days_in_month * $newMonthsDiff, 2);
                    }
                    $employeeLeaveOpening = [
                        'leave_year_id' => $leave_year_id,
                        'opening_leave' => $leave_opening,
                    ];
                    EmployeeLeaveOpening::saveData($data['organization_id'], $employee->id, $leave_type->id, $employeeLeaveOpening);

                    $employeeLeaveModel = EmployeeLeave::where([
                        'leave_year_id' => $leave_year_id,
                        'employee_id' => $employee->id,
                        'leave_type_id' => $leave_type->id
                    ])->first();

                    if (empty($employeeLeaveModel)) {
                        $employeeLeaveModel = new EmployeeLeave();
                        $employeeLeaveModel->leave_year_id = $leave_year_id;
                        $employeeLeaveModel->employee_id = $employee->id;
                        $employeeLeaveModel->leave_type_id = $leave_type->id;
                    }

                    $employee_opening_leave = EmployeeLeaveOpening::getLeaveOpening($leave_year_id, $data['organization_id'], $employee->id, $leave_type->id);
                    $employeeLeaveModel->leave_remaining = $employee_opening_leave;


                    $leaveTaken = Leave::where([
                        'organization_id' => $data['organization_id'],
                        'employee_id' => $employee->id,
                        'leave_type_id' => $leave_type->id
                    ])
                        ->where('status', '!=', '4')
                        ->count();

                    if ($leaveTaken > 0) {
                        $employeeLeaveModel->leave_remaining -= $leaveTaken;
                    }
                    $employeeLeaveModel->is_valid = 11;
                    $employeeLeaveModel->save();
                }
            }
        }
    }

    public function updateStatus(Request $request)
    {
        $data = $request->all();
        $validate = Validator::make(
            $data,
            [
                'archive_reason' => "required",
                'employment_id' => "required",
            ]
        );
        if ($validate->fails()) {
            return $this->respondValidatorFailed($validate);
        }

        try {
            $data['archived_date'] = $data['archived_date'] ?  $data['archived_date'] : date('Y-m-d');
            $data['nep_archived_date'] = date_converter()->eng_to_nep_convert($data['archived_date']);
            $this->employee->update($data['employment_id'], $data);
            $this->employee->updateStatus($data['employment_id']);
            return  $this->respond([
                'status' => true,
                'message' => 'Employee status has been updated Successfully',
            ]);
        } catch (\Throwable $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    public function updateStatusArchive($id)
    {
        try {
            $this->employee->updateStatus($id);
            return  $this->respond([
                'status' => true,
                'message' => 'Employee status has been updated Successfully',
            ]);
        } catch (\Throwable $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    public function updateParentId(Request $request)
    {
        $data = $request->all();

        $user_id = $data['user_id'];
        $item['parent_id'] = (int) $data['parent_id'];
        $resp = $this->user->update($user_id, $item);
        return 1;
    }


    public function archiveEmployeeList(Request $request)
    {
        $search = $request->all();
        try {
            $employments = $this->employee->findArchive($limit = 10, $search);
            $data['roles'] = $this->role->getList('name', 'id');
            $data['employmentsList'] = $this->employee->getArchiveList();
            return $this->respond([
                'status' => true,
                'data' => $data,
                'employments' => $employments
            ]);
        } catch (\Throwable $th) {
            return $this->respondInvalidQuery();
        }
    }

    public function resetDevice($id)
    {
        try {
            User::where('emp_id', $id)->update(['imei' => null]);
            return $this->respond([
                'status' => true,
                'message' => 'Device has been reset successfully !!!'
            ]);
        } catch (\Throwable $th) {
            return $this->respondWithError($th->getMessage());
        }
    }
}
