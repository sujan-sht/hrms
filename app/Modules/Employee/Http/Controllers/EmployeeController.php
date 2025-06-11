<?php

namespace App\Modules\Employee\Http\Controllers;

use PDF;
use File;
use Exception;
use Carbon\Carbon;
use ReflectionClass;
use App\Traits\LogTrait;
use Illuminate\Support\Str;

use Laravel\Passport\Token;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\DateTimeHelper;
use Illuminate\Validation\Rule;
use App\Service\Import\ImportFile;
use Illuminate\Routing\Controller;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Yoeunes\Toastr\Facades\Toastr;
use App\Jobs\SendLoginNotification;
use App\Modules\Unit\Entities\Unit;
use App\Modules\User\Entities\Role;
use App\Modules\User\Entities\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeeDetailReport;
use App\Modules\Leave\Entities\Leave;
use App\Service\Import\EmployeeImport;
use App\Modules\User\Entities\UserRole;
use App\Service\Import\EmployeeImport1;
use App\Exports\EmployeeDirectoryReport;
use App\Modules\Leave\Entities\LeaveType;
use App\Modules\Setting\Entities\Setting;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Modules\Admin\Entities\MailSender;
use App\Modules\Employee\Jobs\EmployeeJob;
use App\Modules\Setting\Entities\Darbandi;
use App\Modules\Shift\Entities\ShiftGroup;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\Language;
use App\Modules\User\Entities\AssignedRole;
use App\Modules\Setting\Entities\Department;
use App\Modules\Setting\Entities\Functional;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Employee\Services\ApiService;
use App\Modules\Setting\Entities\Designation;
use App\Modules\Setting\Entities\OtRateSetup;
use App\Modules\User\Services\CheckUserRoles;
use App\Service\Import\EmployeeArchivedImport;
use App\Modules\Employee\Entities\FamilyDetail;
use App\Modules\Employee\Entities\EmployeeLeave;
use App\Modules\Employee\Enum\ArvhiveStatusEnum;
use App\Modules\Payroll\Entities\DeductionSetup;
use App\Modules\Setting\Entities\HierarchySetup;
use App\Modules\Shift\Entities\ShiftGroupMember;
use App\Modules\Tada\Repositories\TadaInterface;
use App\Modules\User\Repositories\RoleInterface;
use App\Modules\User\Repositories\UserInterface;
use App\Modules\Employee\Entities\ArchivedDetail;
use App\Modules\Employee\Entities\EmployeeDayOff;
use App\Modules\Employee\Entities\RequestChanges;
use App\Modules\Leave\Entities\LeaveEncashmentLog;
use App\Modules\Leave\Repositories\LeaveInterface;
use App\Modules\Shift\Repositories\ShiftInterface;
use App\Modules\Employee\Entities\EmployeeOtDetail;
use App\Modules\Employee\Entities\EmployeeTimeline;
use App\Modules\Employee\Entities\EmployeeTransfer;
use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Employee\Entities\EmployeeJobDetail;
use App\Modules\Employee\Entities\PerformanceDetail;
use App\Modules\Leave\Entities\EmployeeLeaveOpening;
use App\Modules\Setting\Repositories\LevelInterface;
use App\Modules\User\Repositories\UserRoleInterface;
use App\Modules\Holiday\Repositories\HolidayInterface;
use App\Modules\Leave\Repositories\LeaveTypeInterface;
use App\Modules\Payroll\Repositories\PayrollInterface;
use App\Modules\Setting\Repositories\SettingInterface;
use App\Modules\Employee\Entities\EmployeeApprovalFlow;
use App\Modules\LeaveYearSetup\Entities\LeaveYearSetup;
use App\Modules\Shift\Repositories\ShiftGroupInterface;
use App\Modules\Tada\Repositories\TadaRequestInterface;
use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Dropdown\Repositories\DropdownRepository;
use App\Modules\Employee\Repositories\EmployeeRepository;
use App\Modules\Setting\Repositories\DepartmentInterface;
use App\Modules\Employee\Entities\EmployeeCarrierMobility;
use App\Modules\Employee\Repositories\BankDetailInterface;
use App\Modules\Payroll\Repositories\HoldPaymentInterface;
use App\Modules\Setting\Repositories\DesignationInterface;
use App\Modules\Shift\Repositories\EmployeeShiftInterface;
use App\Modules\Employee\Repositories\AssetDetailInterface;
use App\Modules\Onboarding\Repositories\ApplicantInterface;
use App\Modules\Employee\Repositories\FamilyDetailInterface;
use App\Modules\Payroll\Repositories\DeductionSetupInterface;
use App\Modules\Setting\Repositories\HierarchySetupInterface;
use App\Modules\Employee\Entities\EmployeeAdvanceApprovalFlow;
use App\Modules\Employee\Entities\EmployeeOffboardApprovalFlow;
use App\Modules\Employee\Entities\EmployeePayrollRelatedDetail;
use App\Modules\Employee\Repositories\EducationDetailInterface;
use App\Modules\Employee\Repositories\EmergencyDetailInterface;
use App\Modules\BusinessTrip\Repositories\BusinessTripInterface;
use App\Modules\Employee\Entities\EmployeeAppraisalApprovalFlow;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Employee\Entities\EmployeeAttendanceApprovalFlow;
use App\Modules\Employee\Entities\EmployeeThresholdRelatedDetail;
use App\Modules\Employee\Http\Requests\CreateEmployerUserRequest;
use App\Modules\Attendance\Repositories\AttendanceRequestInterface;
use App\Modules\Employee\Entities\EmployeeBusinessTripApprovalFlow;
use App\Modules\Employee\Entities\EmployeeClaimRequestApprovalFlow;
use App\Modules\LeaveYearSetup\Repositories\LeaveYearSetupInterface;
use App\Modules\FiscalYearSetup\Repositories\FiscalYearSetupInterface;
use App\Modules\Employee\Repositories\VisaAndImmigrationDetailInterface;

// use App\Modules\Shift\Repositories\ShiftGroupInterface;

class EmployeeController extends Controller
{

    protected $organization;
    protected $apiService;
    protected $employee;
    protected $user;
    protected $role;
    protected $user_role;
    protected $dropdown;
    protected $setting;
    protected $familyDetail;
    protected $assetDetail;
    protected $emergencyDetail;
    protected $bankDetail;
    protected $applicantObj;
    protected $leaveYearSetup;
    protected $fiscalYearSetup;
    protected $leaveType;
    protected $branchObj;
    protected $attendanceRequest;
    protected $tadaClaim;
    protected $tadaRequest;
    protected $leaveObj;
    protected $deduction;
    protected $hierarchySetup;
    protected $payroll;
    protected $holdPayment;
    protected $holiday;
    protected $document;
    protected $businessTrip;
    protected $archiveDate;
    protected $employeeTimeLine;

    protected $department;
    protected $level;
    protected $designation;
    protected $shiftGroup;
    protected $shift;
    protected $employeeShift;
    protected $educationDetail;


    public function __construct(
        ArchivedDetail $archiveDate,
        EmployeeTimeline $employeeTimeLine,
        OrganizationInterface $organization,
        ApiService $apiService,
        EmployeeInterface $employee,
        UserInterface $user,
        RoleInterface $role,
        UserRoleInterface $user_role,
        DropdownInterface $dropdown,
        SettingInterface $setting,
        FamilyDetailInterface $familyDetail,
        AssetDetailInterface $assetDetail,
        EmergencyDetailInterface $emergencyDetail,
        BankDetailInterface $bankDetail,
        // ApplicantInterface $applicantObj,
        LeaveYearSetupInterface $leaveYearSetup,
        FiscalYearSetupInterface $fiscalYearSetup,
        LeaveTypeInterface $leaveType,
        BranchInterface $branchObj,
        LeaveInterface $leaveObj,
        AttendanceRequestInterface $attendanceRequest,
        TadaInterface $tadaClaim,
        TadaRequestInterface $tadaRequest,
        DeductionSetupInterface $deduction,
        HierarchySetupInterface $hierarchySetup,
        PayrollInterface $payroll,
        HoldPaymentInterface $holdPayment,
        HolidayInterface $holiday,
        VisaAndImmigrationDetailInterface $document,
        BusinessTripInterface $businessTrip,
        DepartmentInterface $department,
        LevelInterface $level,
        DesignationInterface $designation,
        ShiftGroupInterface $shiftGroup,
        ShiftInterface $shift,
        EducationDetailInterface $educationDetail,

        EmployeeShiftInterface $employeeShift

    ) {
        $this->educationDetail = $educationDetail;

        $this->shiftGroup = $shiftGroup;
        $this->shift = $shift;
        $this->archiveDate = $archiveDate;
        $this->designation = $designation;
        $this->employeeTimeLine = $employeeTimeLine;
        $this->organization = $organization;
        $this->apiService = $apiService;
        $this->employee = $employee;
        $this->user = $user;
        $this->role = $role;
        $this->user_role = $user_role;
        $this->dropdown = $dropdown;
        $this->setting = $setting;
        $this->familyDetail = $familyDetail;
        $this->assetDetail = $assetDetail;
        $this->emergencyDetail = $emergencyDetail;
        $this->bankDetail = $bankDetail;
        // $this->applicantObj = $applicantObj;
        $this->leaveYearSetup = $leaveYearSetup;
        $this->fiscalYearSetup = $fiscalYearSetup;
        $this->leaveType = $leaveType;
        $this->leaveObj = $leaveObj;
        $this->branchObj = $branchObj;
        $this->attendanceRequest = $attendanceRequest;
        $this->tadaClaim = $tadaClaim;
        $this->tadaRequest = $tadaRequest;
        $this->deduction = $deduction;
        $this->hierarchySetup = $hierarchySetup;
        $this->payroll = $payroll;
        $this->holdPayment = $holdPayment;
        $this->holiday = $holiday;
        $this->document = $document;
        $this->department = $department;
        $this->businessTrip = $businessTrip;
        $this->level = $level;
        $this->employeeShift = $employeeShift;

        // $this->shiftGroup = $shiftGroup;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $search = $request->all();
        $data['list-view-action'] = $request->switch_view;
        $data['employees'] = $this->employee->findAll(20, $search);
        $response = $this->employee->fetchTableViewEmployees($search);
        $data['tableViewEmployees'] =  collect(json_decode($response->getContent(), true)['data']);
        $data['branchList'] = $this->branchObj->getList();
        $data['deductionList'] = $this->deduction->getList();

        $data['departmentList'] = $this->department->getList();
        $data['levelList'] = $this->level->getList();
        $data['designationList'] = $this->designation->getList();

        $data['user_type'] = $this->dropdown->getUserType('user_type');
        $data['roles'] = $this->role->getList('name', 'id');
        $data['rolesLists'] = $this->role->findAll(100);
        $data['employee_list'] = $this->employee->getList();
        $data['organizationList'] = $this->organization->getList();
        $data['state'] = $this->employee->getStates();
        $data['jobTypeList'] = LeaveType::JOB_TYPE;


        $data['column_lists'] = Employee::getFilterColumnList();
        $data['jobStatusList'] = (new DropdownRepository())->getFieldBySlug('job_status');
        $data['displayAll'] = isset($request->columns) && count($request->columns) > 0 ? true : false;
        $data['select_columns'] = $request->columns;
        $data['actionTypeList'] = Employee::ACTION_TYPE;
        $data['search_value'] = $search;
        $archiveStatusValue = new ReflectionClass(ArvhiveStatusEnum::class);
        $data['archiveStatus'] = $archiveStatusValue->getConstants();
        $data['functionList'] = Functional::pluck('title', 'id');
        // dd($data);
        return view('employee::employee.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Request $request)
    {
        $currentFiscalyear = $this->fiscalYearSetup->getCurrentFiscalYear();
        if ($currentFiscalyear->isEmpty() && $currentFiscalyear->count() < 1) {
            toastr()->error('Please set Active Fiscal Year first !!!');
            return redirect()->route('fiscalYearSetup.index');
        }
        $data['is_edit'] = false;
        $data['user'] = true;
        $data['isEmployee'] = auth()->user()->user_type == 'employee';
        $data['organizationList'] = $this->organization->getList();
        $data['total_employees'] = $this->user->getAllActiveUser()->count();
        $data['gender'] = $this->dropdown->getFieldBySlug('gender');
        $data['marital_status'] = $this->dropdown->getFieldBySlug('marital_status');
        $data['branchList'] = $this->branchObj->getList();
        $data['department'] = $this->department->getList();
        $data['levelList'] = $this->level->getList();
        $data['designation'] = $this->designation->getList();
        $data['leaveYearList'] = $this->leaveYearSetup->getLeaveYearList();
        $data['fiscalYearList'] = $this->fiscalYearSetup->getFiscalYearList();
        $data['blood_group'] = $this->dropdown->getFieldBySlug('blood_group');
        $data['ethnic'] = $this->dropdown->getFieldBySlug('ethnic');
        $data['jobStatusList'] = $this->dropdown->getFieldBySlug('job_status');
        $data['religionList'] = $this->holiday->getReligionType();
        unset($data['religionList'][1]);
        $data['district'] = $this->employee->getDistrict();
        $data['state'] = $this->employee->getStates();
        $data['countryList'] = $this->employee->getCountries();
        $data['shiftGroupList'] = $this->shiftGroup->getList();
        $data['deductionList'] =  $this->deduction->getFixedList();
        $data['contractTypeList'] = LeaveType::CONTRACT;
        unset($data['contractTypeList'][100]);
        $data['statusList'] = [10 => 'No', 11 => 'Yes'];
        $data['otType'] = OtRateSetup::OT_TYPE;
        $data['userList'] = $this->user->getListExceptAdmin();
        $data['applicantId'] = null;
        $data['employee_day_shift'] = [];
        $data['employeeList'] = $this->employee->getList();

        $lastEmployee = Employee::whereNotNull('employee_code')
            ->orderByRaw('LENGTH(employee_code) DESC')
            ->orderBy('employee_code', 'DESC')
            ->first();

        $empCode = $lastEmployee->employee_code;

        if (!$empCode) {
            $newEmpCode = '1';
        } elseif (preg_match('/^([A-Za-z]+)(\d+)$/', $empCode, $matches)) {
            $prefix = $matches[1];
            $code = (int)$matches[2];
            $newEmpCode = $prefix . ($code + 1);
        } elseif (is_numeric($empCode)) {
            $newEmpCode = (int)$empCode + 1;
        } else {
            $newEmpCode = $empCode . '1';
        }
        // dd($newEmpCode);

        $data['newEmpCode'] = $newEmpCode;
        $data['shiftList'] = $this->shift->getList();
        $data['functionList'] = Functional::pluck('title', 'id');

        return view('employee::employee.create', $data);
    }

    public function filterBranchUnit(Request $request)
    {
        $units = Unit::when($request->branchId, function ($item, $value) {
            $item->where('branch_id', $value);
        })
            ->get()->pluck('title', 'id');
        return response()->json($units, 200);
    }
    public function getDist($provinceid)
    {
        $data['districts'] = $this->employee->findByProvince($provinceid);
        return view('employee::employee.partial.districtSearch', $data);
    }

    public function search(Request $request)
    {
        if ($request->ajax()) {

            $query = Employee::where('first_name', 'like', '%' . $request->search . '%')
                ->orWhere('middle_name', 'like', '%' . $request->search . '%')
                ->orWhere('last_name', 'like', '%' . $request->search . '%')
                ->paginate(20);

            return response()->json(['status' => true, 'data' => $query]);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {

        $syncStatus = $this->setting->getdata();
        $hostName = $syncStatus['sync_host_name'] ?? null;
        $data = $request->all();
        // dd($data);

        try {
            $employee_check = $this->employee->getEmployeeByCode($data['employee_code']);
            if ($employee_check) {
                toastr()->error('Employee Code already exists!');
                return redirect(route('employee.index'));
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

            if ($request->hasFile('signature')) {
                $data['signature'] = $this->employee->uploadSignature($data['signature']);
            }

            if ($request->hasFile('initial_signature')) {
                $data['initial_signature'] = $this->employee->uploadSignature($data['initial_signature']);
            }

            if (isset($data['resume']) && !is_null($data['resume'])) {
                $data['resume'] = (new EmployeeRepository())->uploadResume($data['resume']);
            }


            if (isset($data['languages']) && !is_null($data['languages'])) {
                $data['languages'] = implode(',', $data['languages']);
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
            // $data['group_id'] = $request->group_id;

            $employeeInfo = $this->employee->save($data);
            if ($employeeInfo) {
                $darbandiQty = Darbandi::where('organization_id', $data['organization_id'])->where('designation_id', $data['designation_id'])->first()->no ?? 0;
                if ($darbandiQty > 0) {
                    $fulfilledPosition = Employee::where('organization_id', $data['organization_id'])
                        ->where('designation_id', $data['designation_id'])
                        ->count();
                    $fulfilledPosition = $fulfilledPosition + 1;
                    $openPosition = $darbandiQty - $fulfilledPosition;
                    if ($openPosition <= 1) {
                        $users = User::orwhere('user_type', 'hr')->orWhere('user_type', 'division_hr')->get();
                        $designation = Designation::where('id', $data['designation_id'])->first();
                        if ($users->count() > 0) {
                            foreach ($users as $user) {
                                $details = [
                                    'email' => optional($user->userEmployer)->official_email,
                                    'notified_user_fullname' => $user->userEmployer->getFullName(),
                                    'setting' => Setting::first(),
                                    'subject' => 'Darbandi Quantity About To Exceed',
                                    'designation' => $designation->title ?? ''

                                ];
                                (new MailSender())->sendMail('admin::mail.darbandi_qty_exceed', $details);
                            }
                        }
                    }
                }
            }
            // assign to shift
            $week_days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
            // $shiftGroups = ShiftGroup::where('shift_id', $data['shift_id'])->where('org_id', $employeeInfo->organization_id)->get();
            // foreach ($shiftGroups as $shiftGroup) {
            //     $checkIfExistInShiftGroup = ShiftGroupMember::where('group_id', $shiftGroup->id)->where('group_member', $employeeInfo->id)->exists();
            //     if (!$checkIfExistInShiftGroup) {
            //         $shiftGroupMember['group_member'] = $employeeInfo->id;
            //         $shiftGroupMember['group_id'] = $shiftGroup->id;
            //         $this->shiftGroup->saveGroupMember($shiftGroupMember);
            //         foreach ($week_days as $day) {
            //             $employee_shift = [
            //                 'employee_id' => $employeeInfo->id,
            //                 'shift_id' => $shiftGroup->shift_id,
            //                 'days' => $day,
            //                 'group_id' => $shiftGroup->id,
            //                 'updated_by' => auth()->user()->id,
            //                 'created_by' => auth()->user()->id
            //             ];
            //             $this->employeeShift->save($employee_shift);
            //         }
            //     }
            // }

            $shiftGroup = ShiftGroup::where('org_id', $employeeInfo->organization_id)->find($data['shift_group_id']);
            $checkIfExistInShiftGroup = ShiftGroupMember::where('group_id', $shiftGroup->id)->where('group_member', $employeeInfo->id)->exists();
            if (!$checkIfExistInShiftGroup) {
                $shiftGroupMember['group_member'] = $employeeInfo->id;
                $shiftGroupMember['group_id'] = $shiftGroup->id;
                $this->shiftGroup->saveGroupMember($shiftGroupMember);
                foreach ($week_days as $day) {
                    $employee_shift = [
                        'employee_id' => $employeeInfo->id,
                        'shift_id' => $shiftGroup->shift_id,
                        'days' => $day,
                        'group_id' => $shiftGroup->id,
                        'updated_by' => auth()->user()->id,
                        'created_by' => auth()->user()->id
                    ];
                    $this->employeeShift->save($employee_shift);
                }
            }


            // assign to shift end
            if ($syncStatus['sync_employee'] == 1) {
                $this->sendManageEmployeeData($employeeInfo, $hostName);
            }

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
                'created_by' => Auth::user()->id,
            ];
            EmployeeApprovalFlow::saveData($employeeInfo->id, $employeeApprovalFlow);

            $employeeAttendanceApprovalFlow = [
                'first_approval_user_id' => isset($data['attendance_first_approval_user_id']) ? $data['attendance_first_approval_user_id'] : null,
                'second_approval_user_id' => isset($data['attendance_second_approval_user_id']) ? $data['attendance_second_approval_user_id'] : null,
                'third_approval_user_id' => isset($data['attendance_third_approval_user_id']) ? $data['attendance_third_approval_user_id'] : null,
                'last_approval_user_id' => $data['attendance_last_approval_user_id'] ?? null,
                'updated_by' => Auth::user()->id,
            ];
            EmployeeAttendanceApprovalFlow::saveOrUpdate($employeeInfo->id, $employeeAttendanceApprovalFlow);
            //

            //Employee Claim and Request Approval Flow
            $employeeClaimRequestApprovalFlow = [
                'first_claim_approval_user_id' => $data['first_claim_approval_user_id'],
                'last_claim_approval_user_id' => $data['last_claim_approval_user_id'],
                'created_by' => Auth::user()->id,
            ];
            EmployeeClaimRequestApprovalFlow::saveData($employeeInfo->id, $employeeClaimRequestApprovalFlow);

            //Leave Opening
            $this->createEmployeeLeave($employeeInfo, $data);

            // check and save offboard approval flow
            if (isset($data['offboard_first_approval'])) {
                $offboardApprovalData['employee_id'] = $employeeInfo->id;
                $offboardApprovalData['offboard_first_approval'] = $data['offboard_first_approval'];
                $offboardApprovalData['offboard_last_approval'] = $data['offboard_last_approval'];
                EmployeeOffboardApprovalFlow::checkAndSaveOffboardApprovalFlow($offboardApprovalData);
            }

            // check and save appraisal approval flow
            if (isset($data['appraisal_first_approval'])) {
                $appraisalApprovalData['employee_id'] = $employeeInfo->id;
                $appraisalApprovalData['appraisal_first_approval'] = $data['appraisal_first_approval'];
                $appraisalApprovalData['appraisal_last_approval'] = $data['appraisal_last_approval'];
                EmployeeAppraisalApprovalFlow::checkAndSaveAppraisalApprovalFlow($appraisalApprovalData);
            }
            if (isset($data['advance_first_approval'])) {
                $advanceApprovalData['employee_id'] = $employeeInfo->id;
                $advanceApprovalData['advance_first_approval'] = $data['advance_first_approval'];
                $advanceApprovalData['advance_last_approval'] = $data['advance_last_approval'];
                EmployeeAdvanceApprovalFlow::checkAndSaveAdvanceApprovalFlow($advanceApprovalData);
            }
            if (isset($data['business_trip_last_approval'])) {
                $businessTripApprovalData['employee_id'] = $employeeInfo->id;
                $businessTripApprovalData['business_trip_first_approval'] = $data['business_trip_first_approval'];
                $businessTripApprovalData['business_trip_last_approval'] = $data['business_trip_last_approval'];
                EmployeeBusinessTripApprovalFlow::checkAndSaveBusinessTripApprovalFlow($businessTripApprovalData);
            }
            toastr()->success('Employee Created Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('employee.index'));
    }


    // Fetch languages
    public function fetchLanguages(Request $request)
    {
        $search = $request->get('search', '');

        $languages = Language::where('name', 'like', "%{$search}%")
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($languages);
    }

    // Add a new language
    public function storeLanguage(Request $request)
    {

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:languages,name',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()]);
            }

            $language = Language::create(['name' => $request->name]);
            return response()->json($language);
        }
    }

    public function sendManageEmployeeData($employeeInfo, $hostName)
    {
        $employeeData = [];
        $employeeData['id'] = $employeeInfo['id'];
        $employeeData['organization_id'] = $employeeInfo['organization_id'];
        $employeeData['employee_code'] = $employeeInfo['employee_code'];
        // $employeeData['biometric_id'] = $employeeInfo['biometric_id'];
        $employeeData['first_name'] = $employeeInfo['first_name'];
        $employeeData['middle_name'] = $employeeInfo['middle_name'];
        $employeeData['last_name'] = $employeeInfo['last_name'];
        // $employeeData['nationality'] = $employeeInfo['nationality'];
        $employeeData['signature_pic'] = $employeeInfo['signature'];
        $employeeData['phone'] = $employeeInfo['phone'];
        $employeeData['mobile'] = $employeeInfo['mobile'];
        $employeeData['department_id'] = $employeeInfo['department_id'];
        // $employeeData['branch_id'] = $employeeInfo['branch_id'];
        $employeeData['job_title'] = $employeeInfo['job_title'];
        $employeeData['gender'] = $employeeInfo['gender'];
        // $employeeData['marital_status'] = $employeeInfo['marital_status'];
        $employeeData['status'] = $employeeInfo['status'];
        $employeeData['dob'] = $employeeInfo['dob'];
        $employeeData['is_user_access'] = 0;
        $response = $this->apiService->sendEmployeeData($employeeData, $hostName);
    }


    /**
     * Internal function
     * Update data in employee_leaves and employee_leave_openings tables
     */
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
            $params['designation_id'] = $employee->designation_id;
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

            $encashmentLogs = $employee->leaveEncashmentLogs->pluck('leave_type_id')->toArray();
            $unsetEncashmentLogs = array_diff($encashmentLogs, $leave_types->pluck('id')->toArray());

            foreach ($unsetEncashmentLogs as $key => $value) {
                $leaveEncashment = $employee->leaveEncashmentLogs->where('leave_type_id', $value)->first();
                $leaveEncashment->is_valid = 10;
                $leaveEncashment->save();
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

                    //For Leave encashment Log
                    if ($leave_type['encashable_status'] == 11 && !is_null($leave_type['max_encashable_days'])) {
                        $leaveEncashmentModel = LeaveEncashmentLog::where([
                            'employee_id' => $employee->id,
                            'leave_type_id' => $leave_type->id
                        ])->first();

                        if (empty($leaveEncashmentModel)) {
                            $leaveEncashmentModel = new LeaveEncashmentLog();
                            $leaveEncashmentModel->employee_id = $employee->id;
                            $leaveEncashmentModel->leave_type_id = $leave_type->id;
                        }

                        if ($employeeLeaveModel['leave_remaining'] >= 0 && ($employeeLeaveModel['leave_remaining'] > $leave_type['max_encashable_days'])) {
                            $exceeded_balance = $employeeLeaveModel['leave_remaining'] - $leave_type['max_encashable_days'];
                        } else {
                            $exceeded_balance = 0;
                        }
                        $leaveEncashmentModel->encashment_threshold = $leave_type->max_encashable_days;
                        $leaveEncashmentModel->leave_remaining = $employeeLeaveModel->leave_remaining;
                        $leaveEncashmentModel->exceeded_balance = $exceeded_balance;
                        $leaveEncashmentModel->total_balance = $employeeLeaveModel->leave_remaining;
                        $leaveEncashmentModel->eligible_encashment = $exceeded_balance;
                        $leaveEncashmentModel->status = 1;
                        $leaveEncashmentModel->is_valid = 11;

                        $leaveEncashmentModel->save();
                    }
                    //
                }
            }
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
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
            toastr()->warning("Oops! You don't have permission");
            return redirect()->back();
        }
        $data['employeeModel'] = $employeeModel;
        $data['family_relations'] = FamilyDetail::relationType();

        $data['asset_types'] = $this->dropdown->getFieldBySlug('asset_type');
        $data['benefit_types'] = $this->dropdown->getFieldBySlug('benefit_type');
        // $data['bank_names'] = json_decode(file_get_contents(public_path('list/banklist.json')), true);
        // $data['account_types'] = json_decode(file_get_contents(public_path('list/accountTypeList.json')), true);
        $data['bank_names'] = $this->dropdown->getFieldBySlug('bank_name');
        $data['account_types'] = $this->dropdown->getFieldBySlug('account_type');
        $data['family_details'] = $this->familyDetail->findAll($id);
        $data['asset_details'] = $this->assetDetail->findAll($id);
        $data['emergency_details'] = FamilyDetail::where('employee_id', $id)->where('is_emergency_contact', 1)->get();
        $data['bank_details'] = $this->bankDetail->findAll($id);
        $data['countryList'] = $this->employee->getCountries();
        $data['stateList'] = $this->employee->getStates();
        $data['timelineModels'] = $this->employee->getEmployeeTimelineModel($id);
        $data['payrollModels'] = $this->payroll->getEmployeePayrollList($id);
        $data['holdPayments'] = $this->holdPayment->getAllHoldPaymentByEmployee($id);
        $filter['except_id'] = $employeeModel->organization_id;
        $data['transferOrganizationList'] = $this->organization->getList($filter);
        $data['transferStatusList'] = EmployeeTransfer::getStatusList();
        $data['employeeList'] = $this->employee->getList();
        $data['marital_status'] = $this->dropdown->getFieldBySlug('marital_status');
        $data['blood_group'] = $this->dropdown->getFieldBySlug('blood_group');
        $data['ethnic'] = $this->dropdown->getFieldBySlug('ethnic');
        $data['nominee_details'] = FamilyDetail::with([
            'province',
            'district',
            'employeeAddress.permanentProvinceModel',
            'employeeAddress.permanentDistrictModel'
        ])
            ->where('employee_id', $id)
            ->where('is_nominee_detail', 1)
            ->get();
        $data['education_details'] = $this->educationDetail->findAll($employeeModel->employee_id)
            ->transform(function ($item) {
                if (isset($item->equivalent_certificates) && !is_null($item->equivalent_certificates)) {
                    foreach (json_decode($item->equivalent_certificates, true) as $key => $value) {
                        $equivalent_certificates[$key] = asset('uploads/education/' . $value);
                    }
                }
                if (isset($item->degree_certificates) && !is_null($item->degree_certificates)) {
                    foreach (json_decode($item->degree_certificates, true) as $key => $value) {
                        $degree_certificates[$key] = asset('uploads/education/' . $value);
                    }
                }
                if (isset($item->is_foreign_board_file) && !is_null($item->is_foreign_board_file)) {
                    $is_foreign_board_file = asset('uploads/education/' . $item->is_foreign_board_file);
                }

                return [
                    'course_name' => $item->course_name ?? null,
                    'id' => $item->id,
                    'level' => $item->level ?? null,
                    'score' => $item->score ?? null,
                    'division' => $item->division ?? null,
                    'faculty' => $item->faculty ?? null,
                    'specialization' => $item->specialization ?? null,
                    'university_name' => $item->university_name ?? null,
                    'major_subject' => $item->major_subject ?? null,
                    'type_of_institution' => $item->type_of_institution ?? null,
                    'institution_name' => $item->institution_name ?? null,
                    'affiliated_to' => $item->affiliated_to ?? null,
                    'attended_from' => $item->attended_from ?? null,
                    'attended_to' => $item->attended_to ?? null,
                    'passed_year' =>  $item->passed_year ?? null,
                    'note' =>  $item->note ?? null,
                    'is_foreign_board' =>  $item->is_foreign_board == 1  ? 'Yes' : 'No',
                    'is_foreign_board_file' =>  $is_foreign_board_file ?? null,
                    'equivalent_certificates' =>  $equivalent_certificates ?? null,
                    'degree_certificates' =>  $degree_certificates ?? null
                ];
            });

        $data['employeeDetail'] = Employee::with(['branchModel', 'level', 'designation', 'department'])->findOrFail($id);

        return view('employee::employee.view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {


        $data['is_edit'] = true;

        $userInfo = $this->user->getUserId($id);
        if ($userInfo) {
            $data['user'] = $this->user->find($userInfo->id);
            $data['user_role'] = $this->user_role && $this->user_role->getRoleById($userInfo->id) ? $this->user_role->getRoleById($userInfo->id)->role_id : '';
        } else {
            $data['user'] = true;
            $data['user_role'] = '';
        }

        $data['isEmployee'] = auth()->user()->user_type == 'employee';

        $employeeModel = $this->employee->find($id);
        $data['employeeList'] = $this->employee->getList();
        $data['employees'] = $employeeModel;
        $data['employees'] = $employeeModel->appendPayrollRetatedDetailAttributes($data['employees']);
        $data['employees'] = $employeeModel->appendEmployeeApprovalFlowDetailAttributes($data['employees']);
        $data['employees'] = $employeeModel->appendEmployeeClaimRequestApprovalFlowDetailAttributes($data['employees']);
        $data['employees'] = $employeeModel->appendEmployeeOffboardApprovalFlowDetailAttributes($data['employees']);
        $data['employees'] = $employeeModel->appendEmployeeAppraisalApprovalFlowDetailAttributes($data['employees']);
        $data['employees'] = $employeeModel->appendEmployeeAdvanceApprovalFlowDetailAttributes($data['employees']);
        $data['employees'] = $employeeModel->appendEmployeeBusinessTripApprovalFlowDetailAttributes($data['employees']);

        $data['employeeThresholdList'] = DeductionSetup::with(['employeeThresholdBenefit' => function ($query) use ($id) {
            $query->where('employee_id', $id);
        }])->where('organization_id', $employeeModel->organization_id)->where('method', 1)->get();

        $data['branchList'] = $this->branchObj->getList();
        $data['organizationList'] = $this->organization->getList();
        $data['total_employees'] = $this->user->getAllActiveUser()->count();
        $data['gender'] = $this->dropdown->getFieldBySlug('gender');
        $data['marital_status'] = $this->dropdown->getFieldBySlug('marital_status');

        // $data['department'] = $this->dropdown->getFieldBySlug('department');
        // $data['levelList'] = $this->dropdown->getFieldBySlug('level');
        // $data['designation'] = $this->dropdown->getFieldBySlug('designation');

        $data['department'] = $this->department->getList();
        $data['levelList'] = $this->level->getList();
        $data['designation'] = $this->designation->getList();

        $data['countryList'] = $this->employee->getCountries();
        $data['blood_group'] = $this->dropdown->getFieldBySlug('blood_group');
        $data['ethnic'] = $this->dropdown->getFieldBySlug('ethnic');
        $data['jobStatusList'] = $this->dropdown->getFieldBySlug('job_status');
        $data['religionList'] = $this->holiday->getReligionType();
        unset($data['religionList'][1]);
        $data['district'] = $this->employee->getDistrict();
        $data['state'] = $this->employee->getStates();
        $data['roles'] = $this->role->getList('name', 'id');
        $data['deductionList'] = $deductionList = $this->deduction->getList();
        $data['leaveYearList'] = $this->leaveYearSetup->getLeaveYearList();
        $data['fiscalYearList'] = $this->fiscalYearSetup->getFiscalYearList();

        // dd($data['leaveYearList']);
        // $data['contractTypeList'] = $this->dropdown->getFieldBySlug('contract_type');
        $data['contractTypeList'] = LeaveType::CONTRACT;
        unset($data['contractTypeList'][100]);

        $data['statusList'] = [10 => 'No', 11 => 'Yes'];
        $data['otType'] = OtRateSetup::OT_TYPE;
        $data['shiftList'] = $this->shift->getListOrganizationWise($employeeModel->organization_id);
        $groupMembers = ShiftGroupMember::where('group_member', $id)->get();
        foreach ($groupMembers as $groupMember) {
            $shiftSeason = optional($groupMember->group)->shiftSeason_info;
            if ($shiftSeason) {
                $todayDate = date('Y-m-d');
                if ($shiftSeason->date_from <= $todayDate && $shiftSeason->date_to >= $todayDate) {
                    $data['shift'] = $groupMember->group->shift->id;
                }
            }
        }
        // $data['shiftGroup'] = $this->employee->getLatestShiftGroup($id);
        $data['shiftGroupList'] = $this->shiftGroup->getList();

        // dd($data['shiftGroup']);

        // Example: Accessing first group's ID
        // dd($data['otType']);
        // $data['contractTypeList'] = array(2 => 'Regular', 3 => 'Contract');
        // $data['statusList'] = [10 => 'No', 11 => 'Yes'];
        // $data['userList'] = $this->user->getAllActiveUserList();
        // $data['userList'] = $this->user->getAllActiveUserListExpectEmployee();

        if (auth()->user()->user_type == 'division_hr') {
            $filterArray = [
                'model' => 'user',
                'user_type' => ['supervisor', 'divison_hr', 'hr'],
                // 'organization_id' => optional(auth()->user()->userEmployer)->organization_id,
            ];
            $data['userList'] = employee_helper()->getUserListsByType($filterArray);
        } else {
            $data['userList'] = $this->user->getListExceptAdmin();
        }

        if ($userInfo->id != null) {
            $assigned_roles = $userInfo->assignedRoles();
            $assigned_role_ids = $assigned_roles->count() > 0 ? $assigned_roles->pluck('role_id')->toArray() : [];
            $user_roles = $this->user_role->getByUserId($employeeModel->user->id);
            $role_ids = $user_roles->count() > 0 ? $user_roles->pluck('role_id')->toArray() : [];
            $data['assigned_role_ids'] = array_unique(array_merge($assigned_role_ids, $role_ids));
        }
        $data['employeeLeaveDetails'] = $this->employee->employeeLeaveDetails($employeeModel->id);
        $data['functionList'] = Functional::pluck('title', 'id');

        activity()
            ->causedBy(Auth::user())
            ->withProperties([
                'action' => route('employee.edit', $id),
                'employee_id' => $id,
            ])
            ->log('Employee edit');
        return view('employee::employee.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();

        $employee_old_data = $this->employee->find($id);
        $old_join_date = $employee_old_data['join_date'];
        $old_nepali_join_date = $employee_old_data['nepali_join_date'];

        $currentLeaveYearModel = LeaveYearSetup::currentLeaveYear();

        // try {
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

        if ($request->hasFile('signature')) {
            $data['signature'] = $this->employee->uploadSignature($data['signature']);
        }

        if ($request->hasFile('initial_signature')) {
            $data['initial_signature'] = $this->employee->uploadSignature($data['initial_signature']);
        }


        if (isset($data['resume']) && !is_null($data['resume'])) {
            $data['resume'] = (new EmployeeRepository())->uploadResume($data['resume']);
        }

        if (isset($data['job_description']) && !is_null($data['job_description'])) {
            $employee_old_data->update(['resume' => null]);
            unlink(asset('uploads/employee/resume/' . $employee_old_data->resume));
        }


        if (isset($data['languages']) && !is_null($data['languages'])) {
            $data['languages'] = implode(',', $data['languages']);
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
        } else {
            $data['marital_status'] = $data['marital_status'];
        }

        if (isset($data['not_affect_on_payroll'])) {
            $data['not_affect_on_payroll'] = 1;
        }
        $data['group_id'] = $request->group_id;
        $this->employee->update($id, $data);

        $empModel = $this->employee->find($id);

        // assign to shift
        $week_days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
        // $shiftGroups = ShiftGroup::where('shift_id', $data['shift_id'])->where('org_id', $empModel->organization_id)->get();
        // foreach ($shiftGroups as $shiftGroup) {
        //     $checkIfExistInShiftGroup = ShiftGroupMember::where('group_id', $shiftGroup->id)->where('group_member', $id)->exists();
        //     if (!$checkIfExistInShiftGroup) {
        //         $shiftGroupMember['group_member'] = $id;
        //         $shiftGroupMember['group_id'] = $shiftGroup->id;
        //         $this->shiftGroup->saveGroupMember($shiftGroupMember);
        //         foreach ($week_days as $day) {
        //             $employee_shift = [
        //                 'employee_id' => $id,
        //                 'shift_id' => $shiftGroup->shift_id,
        //                 'days' => $day,
        //                 'group_id' => $shiftGroup->id,
        //                 'updated_by' => auth()->user()->id,
        //                 'created_by' => auth()->user()->id
        //             ];
        //             $this->employeeShift->save($employee_shift);
        //         }
        //     }
        // }


        $shiftGroup = ShiftGroup::where('org_id', $empModel->organization_id)->find($data['shift_group_id']);
        $checkIfExistInShiftGroup = ShiftGroupMember::where('group_id', $shiftGroup->id)->where('group_member', $id)->exists();
        if (!$checkIfExistInShiftGroup) {
            $shiftGroupMember['group_member'] = $id;
            $shiftGroupMember['group_id'] = $shiftGroup->id;
            $this->shiftGroup->saveGroupMember($shiftGroupMember);
            foreach ($week_days as $day) {
                $employee_shift = [
                    'employee_id' => $id,
                    'shift_id' => $shiftGroup->shift_id,
                    'days' => $day,
                    'group_id' => $shiftGroup->id,
                    'updated_by' => auth()->user()->id,
                    'created_by' => auth()->user()->id
                ];
                $this->employeeShift->save($employee_shift);
            }
        }
        // assign to shift end

        //if join date changes, update timeline join date
        $this->employee->updateEmployeeTimelineJoinDate($data, $id);
        // dd($data);

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
            'updated_by' => Auth::user()->id,
        ];

        $employeeAttendanceApprovalFlow = [
            'first_approval_user_id' => isset($data['attendance_first_approval_user_id']) ? $data['attendance_first_approval_user_id'] : null,
            'second_approval_user_id' => isset($data['attendance_second_approval_user_id']) ? $data['attendance_second_approval_user_id'] : null,
            'third_approval_user_id' => isset($data['attendance_third_approval_user_id']) ? $data['attendance_third_approval_user_id'] : null,
            'last_approval_user_id' => $data['attendance_last_approval_user_id'] ?? null,
            'updated_by' => Auth::user()->id,
        ];
        EmployeeApprovalFlow::saveData($id, $employeeApprovalFlow);
        if ($employeeAttendanceApprovalFlow) {
            EmployeeAttendanceApprovalFlow::saveOrUpdate($id, $employeeAttendanceApprovalFlow);
        }

        //Employee Claim and Request Approval Flow
        $employeeClaimRequestApprovalFlow = [
            'first_claim_approval_user_id' => $data['first_claim_approval_user_id'],
            'last_claim_approval_user_id' => $data['last_claim_approval_user_id'],
            'updated_by' => Auth::user()->id,
        ];
        EmployeeClaimRequestApprovalFlow::saveData($id, $employeeClaimRequestApprovalFlow);
        //

        // Employee Leave and Employee Leave Opening Detail
        $this->createEmployeeLeave($employee_old_data, $data);

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
        if (isset($data['advance_first_approval'])) {
            $advanceApprovalData['employee_id'] = $id;
            $advanceApprovalData['advance_first_approval'] = $data['advance_first_approval'];
            $advanceApprovalData['advance_last_approval'] = $data['advance_last_approval'];
            EmployeeAdvanceApprovalFlow::checkAndSaveAdvanceApprovalFlow($advanceApprovalData);
        }

        if (isset($data['business_trip_last_approval'])) {
            $businessTripApprovalData['employee_id'] = $id;
            $businessTripApprovalData['business_trip_first_approval'] = $data['business_trip_first_approval'];
            $businessTripApprovalData['business_trip_last_approval'] = $data['business_trip_last_approval'];
            EmployeeBusinessTripApprovalFlow::checkAndSaveBusinessTripApprovalFlow($businessTripApprovalData);
        }

        // Employee Leave Detail (overwrite)
        if(isset($data['edit_leave']) && $data['edit_leave'] == 1){
            if (isset($data['employee_leave_ids'])) {
                if (count($data['employee_leave_ids']) > 0) {
                    foreach ($data['employee_leave_ids'] as $key => $employeeLeaveId) {

                        if ($data['edit_adjust_days'][$key] != null) {
                            $employeeLeave = EmployeeLeave::where('id', $employeeLeaveId)->first();
                            if ($employeeLeave) {
                                $employeeLeaveOpening = EmployeeLeaveOpening::where([
                                    'leave_year_id' => $employeeLeave->leave_year_id,
                                    'employee_id' => $employeeLeave->employee_id,
                                    'leave_type_id' => $employeeLeave->leave_type_id
                                ])->first();

                                if ($employeeLeaveOpening) {
                                    $employeeLeaveOpening->opening_leave = $data['edit_adjust_days'][$key];
                                    $employeeLeaveOpening->save();
                                }else{
                                    EmployeeLeaveOpening::create([
                                        'leave_year_id' => $employeeLeave->leave_year_id,
                                        'employee_id' => $employeeLeave->employee_id,
                                        'leave_type_id' => $employeeLeave->leave_type_id,
                                        'opening_leave' => $data['edit_adjust_days'][$key],
                                        'organization_id' => $data['organization_id']
                                    ]);
                                }
                            }
                            EmployeeLeave::where('id', $employeeLeaveId)->update(['leave_remaining' => $data['edit_adjust_days'][$key]]);
                        }
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

            $this->user_role->update($user_id->id, $update_role);

            $role = $this->role->find($role_id);
            // $user_type = $role->user_type;
            $changesLogs['new_role'] = $role->name ?? '';


            $user_access = array(
                'user_type' => $role->user_type
            );
            $this->user->update($user_id->id, $user_access);
        }
        //   Assign Roles
        if (count($request->assigned_role_ids ?? []) > 0) {
            $employee = $this->employee->find($id);
            $user_id = $employee->user->id;
            AssignedRole::where('user_id', $user_id)->delete();
            foreach ($request->assigned_role_ids  as $assigned_role_id) {
                $assignRole['user_id'] = $user_id;
                $assignRole['role_id'] = $assigned_role_id;
                AssignedRole::create($assignRole);
            }
        }

        $logData = [
            'title' => 'Employee Profile Updated',
            'action_id' => $employee_old_data->id,
            'action_model' => get_class($employee_old_data),
            'route' => route('employee.update', $employee_old_data->id)
        ];

        activity()
            ->causedBy(Auth::user())
            ->withProperties([
                'action' => route('employee.update', $id),
                'employee_id' => $id,
                'logData' => $logData,
                'changesData' => $changesLogs
            ])
            ->log('Employee Updated');

        toastr()->success('Employee Updated Successfully');
        // } catch (\Throwable $e) {
        //     toastr()->error('Something Went Wrong !!!');
        // }
        return redirect(route('employee.index'));
    }


    public function checkAvailability(Request $request)
    {
        $input = $request->all();
        $username = $input['username'];

        $check = $this->user->checkUsername($username);

        if (count($check) > 0) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function checkOthersUsername(Request $request)
    {
        $input = $request->all();
        $username = $input['username'];
        $userid = $input['userid'];

        $check = $this->user->othersUsername($username, $userid);

        if (count($check) > 0) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function createUser(CreateEmployerUserRequest $request)
    {
        $input = $request->all();

        $ip_address =  $request->ip();

        $employer_id = $input['employer_id'];
        $username = $input['username'];
        $email = $input['email'];
        $password = $input['password'];
        $role_id = $input['role_id'];
        $role = $this->role->find($role_id);
        $user_type = $role->user_type;
        $employment_info = $this->employee->find($employer_id);

        $first_name = $employment_info->first_name;
        $middle_name = $employment_info->middle_name;
        $last_name = $employment_info->last_name;
        $phone = $employment_info->phone;

        $userInfo = Auth::user();
        $parent_id = $userInfo->id;

        $user_access = array(
            'ip_address' => $ip_address,
            'username' => $username,
            'password' => bcrypt($password),
            'email' => $email,
            'user_type' => $user_type,
            'active' => '1',
            'first_name' => $first_name,
            'middle_name' => $middle_name,
            'last_name' => $last_name,
            'phone' => $phone,
            'emp_id' => $employer_id,
            'remember_token' => '$b$d' . $password . '$e$p',
            'parent_id' => $parent_id
        );

        $user = $this->user->save($user_access);

        //Insert into User Role
        $role_data = array(
            'user_id' => $user->id,
            'role_id' => $role_id
        );
        $this->user_role->save($role_data);

        if (!empty($input['assigned_role_ids'])) {
            $input['assigned_role_ids'][] = $role_id;
            foreach ($input['assigned_role_ids'] as $assigned_role_id) {
                $assignRole['user_id'] = $user['id'];
                $assignRole['role_id'] = $assigned_role_id;
                AssignedRole::create($assignRole);
            }
        }

        $update_emp = array(
            'is_user_access' => '1',
            'pass_token' => $password,
        );

        $this->employee->update($employer_id, $update_emp);
        // $logData = [
        //     'title' => 'User Access Granted To Employee',
        //     'action_id' => $employer_id,
        //     'action_model' => get_class($this->employee->find($employer_id)),
        //     'route' => route('employee.view', $employer_id)
        // ];
        // dd($employer_id);
        // $this->setActivityLog($logData);
        toastr()->success('Employee User Access Created Successfully');
        return redirect()->back();
    }



    public function updateType(Request $request)
    {

        $input = $request->all();

        $empId = $input['employerId'];
        $parent_id = $input['parent_id'];

        $userData = $this->user->getUserId($empId);
        if (!empty($userData)) {
            $user_id = $userData['id'];

            $update_emp = array(
                'is_parent_link' => '1',
            );
            $this->employee->update($empId, $update_emp);

            $user_data = array(
                'parent_id' => $parent_id,
            );
            $this->user->update($user_id, $user_data);

            toastr()->success('Employer Link with Respective Head Dept.');
        } else {
            toastr()->error('User not created yet for the employee!');
        }

        return redirect(route('employee.index'));
    }

    public function updateStatus(Request $request)
    {
        $data = $request->all();

        try {
            // $data['archived_date'] = $data['archived_date'] ?  $data['archived_date'] : date('Y-m-d');
            // $data['nep_archived_date'] = date_converter()->eng_to_nep_convert($data['archived_date']);
            $data['archived_type'] = $data['archived_type'] ?? null;
            $this->employee->update($data['employment_id'], $data);
            $this->employee->updateStatus($data['employment_id']);
            $data['status'] = '1';
            $data['employee_id'] = $data['employment_id'];
            $this->employee->setArchivedDetail($data['employment_id'], $data);
            toastr()->success('Employee status updated');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect()->back();
    }

    public function updateStatusArchive($id)
    {

        try {
            $currentDate = Carbon::now()->format('Y-m-d H:m:s');
            $detail = [
                'employee_id' => $id,
                'date' => $currentDate,
                'archived_date' => $currentDate,
                'nep_archived_date' => date_converter()->eng_to_nep_convert($currentDate),
                'status' => '2',
                'title' => 'Active State',
                'description' => 'Move To Active State',
                'icon' => 'icon-user',
                'color' => 'primary',
                'reference' => 'employee',
                'reference_id' => $id,
            ];
            $this->employee->updateStatus($id);
            $this->archiveDate->fill($detail);
            $this->archiveDate->save();
            $this->employeeTimeLine->fill($detail);
            $this->employeeTimeLine->save();
            toastr()->success('Employee status updated');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect()->back();
    }

    public function updateEmployeeStatus(Request $request)
    {
        $data = $request->all();
        // dd($data);
        try {
            $data = $request->all();
            $data['employee_id'] = $request->employee_id;
            $data['archived_date'] = $request->archived_date ?? date('Y-m-d');
            $data['nep_archived_date'] = @$request->nep_archived_date;
            $data['archived_type'] = $request->archived_type ?? 'Move to Active';
            $this->employee->update($data['employee_id'], $data);
            $this->employee->updateStatus($data['employee_id']);
            $data['status'] = '1';

            $detail = [
                'employee_id' => $data['employee_id'],
                'date' => $data['archived_date'],
                'title' => 'Unarchived [' . $data['archived_type'] . '] ',
                'description' => $data['archive_reason'],
                'icon' => 'icon-user',
                'color' => 'primary',
                'reference' => 'employee',
                'reference_id' => $data['employee_id']
            ];
            EmployeeTimeline::create($detail);
            toastr()->success('Employee status updated');
        } catch (\Throwable $e) {
            dd($e);
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect()->back();
    }

    public function updateParentId(Request $request)
    {
        $data = $request->all();

        $user_id = $data['user_id'];
        $item['parent_id'] = (int) $data['parent_id'];
        $resp = $this->user->update($user_id, $item);
        return 1;
    }


    public function indexArchive(Request $request)
    {
        $search = $request->all();
        $data['employments'] = $this->employee->findArchive($limit = 50, $search);
        $data['department'] = $this->department->getList();
        $data['designation'] = $this->designation->getList();

        $data['user_type'] = $this->dropdown->getUserType('user_type');
        $data['roles'] = $this->role->getList('name', 'id');
        $data['employments_list'] = $this->employee->getArchiveList();

        return view('employee::employee.index_archive', $data);
    }

    public function downloadSheet(Request $request)
    {
        $inputData = $request->all();
        $data['employees'] = $this->employee->findAll(null, $inputData);


        return Excel::download(new EmployeeDetailReport($data), 'employee_detail_report.xlsx');
        toastr('Please Filter first to download Excel Report', 'warning');
        return back();
    }

    public function downloadPdf(Request $request)
    {
        $inputData = $request->all();
        $data['emps'] = $this->employee->findAll(null, $inputData);

        $pdf = PDF::loadView('exports.employee-detail-report', $data)->setPaper('a4', 'landscape');
        return $pdf->download('employee_detail_report.pdf');
        // return Excel::download(new EmployeeDetailReport($data), 'employee_detail_report.xlsx');
        toastr('Please Filter first to download Excel Report', 'warning');
        return back();
    }

    public function uploadEmployee(Request $request)
    {
        $files = $request->upload_employee;
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($files);
        \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());

        $spreadsheet->getActiveSheet()->getStyle('G')
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);

        $spreadsheet->getActiveSheet()->getStyle('H')
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);

        $spreadsheet->getActiveSheet()->getStyle('X')
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        array_shift($sheetData);
        $import_file = ImportFile::import(new EmployeeImport, $sheetData);
        if ($import_file['success']) {
            return redirect()->back()->with('success', $import_file['message']);
        } else {
            return redirect()->back()->with('error', $import_file['message']);
        }

        return redirect()->route('employee.index');
    }

    public function uploadEmployeeArchived(Request $request)
    {
        $files = $request->upload_employee;
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($files);
        \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());

        $spreadsheet->getActiveSheet()->getStyle('G')
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);

        $spreadsheet->getActiveSheet()->getStyle('H')
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);

        $spreadsheet->getActiveSheet()->getStyle('X')
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        array_shift($sheetData);
        $import_file = ImportFile::import(new EmployeeArchivedImport, $sheetData);

        if ($import_file) {
            toastr()->success('Employee Imported Successfully');
        }

        return redirect()->route('employee.indexArchive');
    }
    /**
     *
     */
    public function directory(Request $request)
    {
        $inputData = $request->all();

        $data['column_lists'] = [
            'address' => 'Address',
            'mobile' => 'Mobile',
            'phone' => ' CUG Number',
            'official_email' => 'Official Email',
            'dob' => 'DOB',
            'level' => 'Level',
            'join_date' => 'Join Date',
            'group' => 'Group',
            'designation' => 'Designation',
            'gpa_enable' => 'GPA',
            'gmi_enable' => 'GMI'
        ];

        $data['displayAll'] = isset($request->columns) && count($request->columns) > 0 ? true : false;
        $data['select_columns'] = $request->columns;

        $limit = 20;
        if (isset($inputData['sortBy']) && !empty($inputData['sortBy'])) {
            $limit = $inputData['sortBy'];
        }

        $inputData['archive_status'] = 10;
        $data['employeeModels'] = $this->employee->findAll($limit, $inputData);
        $data['title'] = 'Employee';
        $data['employeeList'] = $this->employee->findAll()->pluck('full_name', 'id');
        $data['organizationList'] = $this->organization->getList();
        $data['branchList'] = $this->branchObj->getList();
        $data['departmentList'] = $this->department->getList();
        $data['levelList'] = $this->level->getList();
        $data['designationList'] = $this->designation->getList();

        $data['rolesLists'] = $this->role->findAll(100);
        $data['jobStatusList'] = $this->dropdown->getFieldBySlug('job_status');
        $data['actionTypeList'] = Employee::ACTION_TYPE;
        $data['state'] = $this->employee->getStates();
        $data['jobTypeList'] = LeaveType::JOB_TYPE;
        return view('employee::employee.directory', $data);
    }

    public function archivedDirectory(Request $request)
    {
        $inputData = $request->all();
        $data['column_lists'] = [
            'address' => 'Address',
            'mobile' => 'Mobile',
            'phone' => ' CUG Number',
            'official_email' => 'Official Email',
            'dob' => 'DOB',
            'level' => 'Level',
            'join_date' => 'Join Date',
            'group' => 'Group',
            'designation' => 'Designation'
        ];

        $data['displayAll'] = isset($request->columns) && count($request->columns) > 0 ? true : false;
        $data['select_columns'] = $request->columns;

        $limit = 20;
        if (isset($inputData['sortBy']) && !empty($inputData['sortBy'])) {
            $limit = $inputData['sortBy'];
        }

        $inputData['archive_status'] = 10;
        $data['employeeModels'] = $this->employee->findAllArchived($limit, $inputData);
        $data['title'] = 'Archived Employee';
        $data['employeeList'] = $this->employee->findAll()->pluck('full_name', 'id');
        $data['organizationList'] = $this->organization->getList();
        $data['branchList'] = $this->branchObj->getList();
        $data['departmentList'] = $this->department->getList();
        $data['levelList'] = $this->level->getList();
        $data['designationList'] = $this->designation->getList();

        $data['rolesLists'] = $this->role->findAll(100);
        $data['jobStatusList'] = $this->dropdown->getFieldBySlug('job_status');
        $data['actionTypeList'] = Employee::ACTION_TYPE;
        $data['state'] = $this->employee->getStates();
        $data['jobTypeList'] = LeaveType::JOB_TYPE;
        return view('employee::employee.archivedDirectory', $data);
    }

    //Ajax
    public function getNameandEmail(Request $request)
    {
        $emp = $this->employee->findWithFullNameAndEmail($request->id);
        return $emp;
    }

    /**
     * Update Employee Profile
     *
     * @param Request $request
     * @param integer $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    // public function updateEmployeeProfile(Request $request, $id)
    // {
    //     $data = $request->all();
    //     try {
    //         if (!is_null($data['full_name'])) {
    //             $name = explode(' ', $data['full_name']);
    //             if (count($name) > 2) {
    //                 $data['first_name'] = $name[0];
    //                 $data['middle_name'] = $name[1];
    //                 $data['last_name'] = $name[2];
    //             } else {
    //                 $data['first_name'] = $name[0];
    //                 $data['last_name'] = $name[1];
    //             }
    //         }
    //         if ($request->hasFile('citizenpic')) {
    //             $data['citizen_pic'] = $this->employee->uploadCitizen($data['citizenpic']);
    //         }
    //         if ($request->hasFile('nationalId_file')) {
    //             $data['national_pic'] = $this->employee->uploadNationalId($data['nationalId_file']);
    //         }

    //         if ($request->hasFile('marital_image')) {
    //             $data['marital_pic'] = $this->employee->uploadMaritalImg($data['marital_image']);
    //         }

    //         if ($request->hasFile('passport')) {
    //             $data['passport_pic'] = $this->employee->uploadPassportImg($data['passport']);
    //         }
    //         $this->employee->update($id, $data);
    //         toastr()->success('Employee Updated Successfully !!!');
    //     } catch (\Throwable $th) {
    //         toastr()->error('Something Went Wrong !!!');
    //     }
    //     // return redirect(route('dashboard'));
    //     return redirect()->back();
    // }

    public function updateEmployeeProfile(Request $request, $id)
    {
        $data = $request->all();
        $old = Employee::find($id);
        // dd($old, $data);
        DB::beginTransaction();
        try {
            // RequestChanges::truncate();
            $fullName = $request->full_name;
            $nameParts = explode(' ', trim($fullName));
            $partCount = count($nameParts);
            $data['first_name'] = $nameParts[0] ?? null;
            $data['middle_name'] = $partCount === 3 ? $nameParts[1] : ($partCount > 3 ? implode(' ', array_slice($nameParts, 1, -1)) : null);
            $data['last_name'] = $partCount > 1 ? $nameParts[$partCount - 1] : null;


            $fields = [
                'first_name',
                'middle_name',
                'last_name',
                'mobile',
                'phone',
                'personal_email',
                'permanentaddress',
                'temporaryaddress',
                'national_id',
                'passport_no',
                'telephone',
                'official_email',
                'marital_status',
                'citizenship_no',
                'blood_group',
                'ethnicity',
                'language'
            ];
            $changes = ['employee_id' => $id];

            foreach ($fields as $field) {
                $changes["old_{$field}"] = @$old->$field;
                $newFieldKey = $field;
                $changes["new_{$newFieldKey}"] = @$request->$field;
            }
            // dd($changes);
            $changes['change_date'] = now();

            $already_exist = RequestChanges::where('employee_id', $id)->whereNull('entity')->where('status', 'pending')->first();
            if ($already_exist) {
                $already_exist->update($changes);
                $change = $already_exist;
            } else {
                $change = RequestChanges::create($changes);
            }
            // dd(date_converter()->eng_to_nep_convert($change->change_date->format("Y-m-d")));

            DB::commit();
            EmployeeJob::dispatch($change, $id);

            // $this->employee->update($id, $data);
            toastr()->success('Changes Requested !!!');
        } catch (\Throwable $th) {
            DB::rollBack();
            // dd($th);
            toastr()->error('Something Went Wrong !!!');
        }
        // return redirect(route('dashboard'));
        return redirect()->back();
    }


    public function changeUserType(Request $request)
    {
        $data = $request->all();
        try {
            $role = $this->role->find($data['role_id']);
            $update_role = array(
                'role_id' => $data['role_id'],
                'created_at' => date('Y-m-d H:i:s')
            );
            $this->user_role->update(auth()->user()->id, $update_role);

            $user_access = array(
                'user_type' => $role->user_type
            );
            $this->user->update(auth()->user()->id, $user_access);
            toastr()->success('Dashboard switched successfully !!!');
        } catch (\Throwable $th) {
            toastr()->error('Something Went Wrong');
        }
        return redirect('/admin/dashboard');
    }


    public function pendingApproval(Request $request)
    {
        $filter = $request->all();
        $activeUserModel = Auth::user();
        $data['title'] = "Pending Approval";

        $leaves =  $this->leaveObj->getEmployeeLeaves()->where('status', 1)->toArray();
        $attendanceRequests = $this->attendanceRequest->getEmployeeAttendanceRequest($activeUserModel->emp_id)->toArray();
        $claims = $this->tadaClaim->getEmployeeClaim($activeUserModel->emp_id)->toArray();
        $requests = $this->tadaRequest->getEmployeeTadaRequest($activeUserModel->emp_id)->toArray();
        $businessTrips = $this->businessTrip->getEmployeeBusinessTrips($activeUserModel->emp_id)->toArray();
        $mergeArray = array_merge($leaves, $attendanceRequests, $claims, $requests, $businessTrips);
        usort($mergeArray, function ($a, $b) {
            return  strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        $myCollectionObj = collect($mergeArray);
        $result = $myCollectionObj;

        if (isset($filter['type']) && !empty($filter['type'])) {
            $result =   $result->where('type', $filter['type']);
        }
        if (setting('calendar_type') == 'BS') {
            if (isset($filter['from_nep_date']) && !empty($filter['from_nep_date'])) {
                $result = $result->where('date', '>=', date_converter()->nep_to_eng_convert($filter['from_nep_date']));
            }
            if (isset($filter['to_nep_date']) && !empty($filter['to_nep_date'])) {
                $result = $result->where('date', '<=', date_converter()->nep_to_eng_convert($filter['to_nep_date']));
            }
        } else {
            if (isset($filter['date_range']) && !empty($filter['date_range'])) {
                $filterDates = explode(' - ', $filter['date_range']);
                $result =   $result->where('date', '>=', $filterDates[0]);
                $result =   $result->where('date', '<=', $filterDates[1]);
            }
        }

        if (isset($filter['employee_id']) && !empty($filter['employee_id'])) {
            $result =   $result->where('employee_id', $filter['employee_id']);
        }
        $data['pendingApprovals'] = paginate($result, 30, '', ['path' => request()->url()]);


        $employees = $this->employee->getList();
        if (auth()->user()->emp_id) {
            $employees[auth()->user()->emp_id] = optional(auth()->user()->userEmployer)->full_name;
        }

        $data['employeeList'] = $employees;

        $data['requestType'] = ['leave' => 'Leave', 'attendance' => 'Attendance', 'claim' => 'Claim', 'request' => 'Request', 'businessTrip' => 'Business Trip'];

        return view('employee::employee.pending-approval', $data);
    }

    public function checkEmployeeCode(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($request->all(), [
            'employee_code' => 'required|unique:employees,employee_code,' . $data['id'],
        ]);
        if ($validator->fails()) {
            return  json_encode(false);
        }
        return  json_encode(true);
    }

    public function checkBiometricId(Request $request)
    {
        $data = $request->all();
        $employee = '';
        if (isset($data['id']) && !empty($data['id'])) {
            $employee = Employee::find($data['id']);
        }

        if (!empty($employee) && ($employee->biometric_id == $data['biometric_id'])) {
            return  json_encode(true);
        }

        $validator = Validator::make($request->all(), [
            'biometric_id' => [
                Rule::unique('employees')->where(function ($query) use ($data) {
                    return  $query->where('biometric_id', $data['biometric_id']);
                })
            ],
        ]);

        if ($validator->fails()) {
            return  json_encode(false);
        }
        return  json_encode(true);
    }

    public function updateEmployeeTimelineRecord()
    {
        $employeeList = Employee::where('status', 1)->get();

        if (count($employeeList) > 0) {
            foreach ($employeeList as $employee) {
                // save employee timeline
                $timelineData['employee_id'] = $employee->id;
                $timelineData['date'] = $employee->join_date;
                $timelineData['title'] = "New Join";
                $timelineData['description'] = "Join " . optional($employee->organizationModel)->name;
                $timelineData['icon'] = "icon-user";
                $timelineData['color'] = "primary";
                $timelineData['reference'] = "employee";
                $timelineData['reference_id'] = $employee->id;
                Employee::saveEmployeeTimelineData($employee->id, $timelineData);
            }
        }

        toastr()->success('Timeline updated successfully');

        return redirect()->back();
    }

    /**
     *
     */
    public function uploadEmployeeDetail()
    {
        $data = [];
        return view('employee::employee.upload-sample', $data);
    }

    /**
     *
     */
    public function updateEmployeeDetail(Request $request)
    {
        $inputData = $request->all();

        $files = $request->file;
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Csv");

        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($files);
        \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        array_shift($sheetData);

        ImportFile::import(new EmployeeImport1, $sheetData);

        echo 'Employee updated Successfully';
        die();
    }

    public function approvalFlowReport(Request $request)
    {
        $filter = $request->all();

        $data['employees'] = $this->employee->employeeApprovalFlowList(20, $filter);
        $data['organizationList'] = $this->organization->getList();
        $data['employeeList'] = $this->employee->getList();
        $data['jobTypeList'] = LeaveType::JOB_TYPE;

        return view('employee::employee.employee-approval-flow-list', $data);
    }

    public function getApprovalUsers(Request $request)
    {

        $employeeModel = $this->employee->find($request->emp_id);
        $data['employees'] = $employeeModel;

        $data['employees'] = $employeeModel->appendPayrollRetatedDetailAttributes($data['employees']);
        $data['employees'] = $employeeModel->appendEmployeeApprovalFlowDetailAttributes($data['employees']);
        $data['employees'] = $employeeModel->appendEmployeeClaimRequestApprovalFlowDetailAttributes($data['employees']);
        $data['employees'] = $employeeModel->appendEmployeeOffboardApprovalFlowDetailAttributes($data['employees']);
        $data['employees'] = $employeeModel->appendEmployeeAppraisalApprovalFlowDetailAttributes($data['employees']);
        $data['employees'] = $employeeModel->appendEmployeeAdvanceApprovalFlowDetailAttributes($data['employees']);
        $data['employees'] = $employeeModel->appendEmployeeBusinessTripApprovalFlowDetailAttributes($data['employees']);

        $data['userList'] = $this->user->getListExceptAdmin();
        return response()->json([
            'view' => view('employee::employee.modal.approval-flow', $data)->render()
        ]);
    }

    public function updateApprovalFlow(Request $request, $id)
    {
        try {
            $data = $request->all();
            //Employee Leave Approval Flow
            $employeeApprovalFlow = [
                'first_approval_user_id' => isset($data['first_approval_user_id']) ? $data['first_approval_user_id'] : null,
                'second_approval_user_id' => isset($data['second_approval_user_id']) ? $data['second_approval_user_id'] : null,
                'third_approval_user_id' => isset($data['third_approval_user_id']) ? $data['third_approval_user_id'] : null,
                'last_approval_user_id' => $data['last_approval_user_id'],
                'updated_by' => Auth::user()->id,
            ];
            EmployeeApprovalFlow::saveData($id, $employeeApprovalFlow);

            $employeeAttendanceApprovalFlow = [
                'first_approval_user_id' => isset($data['attendance_first_approval_user_id']) ? $data['attendance_first_approval_user_id'] : null,
                'second_approval_user_id' => isset($data['attendance_second_approval_user_id']) ? $data['attendance_second_approval_user_id'] : null,
                'third_approval_user_id' => isset($data['attendance_third_approval_user_id']) ? $data['attendance_third_approval_user_id'] : null,
                'last_approval_user_id' => $data['attendance_last_approval_user_id'] ?? null,
                'updated_by' => Auth::user()->id,
            ];

            if ($employeeAttendanceApprovalFlow) {
                EmployeeAttendanceApprovalFlow::saveOrUpdate($id, $employeeAttendanceApprovalFlow);
            }

            //Employee Claim and Request Approval Flow
            $employeeClaimRequestApprovalFlow = [
                'first_claim_approval_user_id' => $data['first_claim_approval_user_id'],
                'last_claim_approval_user_id' => $data['last_claim_approval_user_id'],
                'updated_by' => Auth::user()->id,
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
            // check and save advance approval flow
            if (isset($data['advance_first_approval'])) {
                $advanceApprovalData['employee_id'] = $id;
                $advanceApprovalData['advance_first_approval'] = $data['advance_first_approval'];
                $advanceApprovalData['advance_last_approval'] = $data['advance_last_approval'];
                EmployeeAdvanceApprovalFlow::checkAndSaveAdvanceApprovalFlow($advanceApprovalData);
            }

            if (isset($data['business_trip_last_approval'])) {
                $businessTripApprovalData['employee_id'] = $id;
                $businessTripApprovalData['business_trip_first_approval'] = $data['business_trip_first_approval'];
                $businessTripApprovalData['business_trip_last_approval'] = $data['business_trip_last_approval'];
                EmployeeBusinessTripApprovalFlow::checkAndSaveBusinessTripApprovalFlow($businessTripApprovalData);
            }

            EmployeeApprovalFlow::saveData($id, $businessTripApprovalData);
            // toastr()->success('User Approval Updated Succesfull');
            // ->with('success','User Approval Updated Succesfull');
        } catch (\Throwable $e) {
            // toastr()->error($e->getMessage());
        }
        // return redirect()->route('employee.approvalFlowReport');
        return redirect()->back();
    }

    /**
     *
     */
    public function getAlternativeEmployees(Request $request)
    {
        $inputData = $request->all();

        $params['employee_id'] = $inputData['employee_id'];
        $leaveTypes = Employee::findAlternativeEmployees($params);

        return json_encode($leaveTypes);
    }

    public function directoryReportExport(Request $request)
    {
        $inputData = $request->all();

        $data['column_lists'] = [
            'address' => 'Address',
            'mobile' => 'Mobile',
            'phone' => ' CUG Number',
            'official_email' => 'Official Email',
            'dob' => 'DOB',
            'level' => 'Level',
            'join_date' => 'Join Date',
            'group' => 'Group',
            'designation' => 'Designation',
            'gpa_enable' => 'GPA',
            'gmi_enable' => 'GMI'
        ];

        $data['displayAll'] = isset($request->columns) && count($request->columns) > 0 ? true : false;
        $data['select_columns'] = $request->columns;
        $inputData['archive_status'] = 10;
        $response = $this->employee->fetchTableViewEmployees($inputData);
        $data['employeeModels'] =  collect(json_decode($response->getContent(), true)['data']);
        $data['title'] = 'Employee';
        return Excel::download(new EmployeeDirectoryReport($data), 'employee-directory-report.xlsx');
        toastr('Please Filter first to download Excel Report', 'warning');
        return back();
    }

    public function downloadTimelineReport($id)
    {
        $data['employeeTimelines'] = $this->employee->getEmployeeTimelineModel($id);
        $pdf = PDF::loadView('exports.employee-timeline-report', $data)->setPaper('a4', 'landscape');
        return $pdf->download('employee-timeline-report.pdf');
    }

    public function viewPerformanceManagement(Request $request)
    {
        $filter = $request->all();
        $data['organizationList'] = $this->organization->getList();
        $data['employeeList'] = $this->employee->getList();
        $data['branchList'] = $this->branchObj->getList();
        $data['departmentList'] = $this->department->getList();
        $data['levelList'] = $this->level->getList();
        $data['designationList'] = $this->designation->getList();

        $data['typeList'] = PerformanceDetail::typeList();
        $data['employee'] = [];
        if (!empty($filter)) {
            $data['employee'] = $this->employee->find($filter['employee_id']);
        }
        return view('employee::employee.performance-management.index', $data);
    }

    public function storePerformanceDetails(Request $request)
    {
        try {
            $employeeOldData = $this->employee->find($request['employee_id']);
            $employeeNewData = $request->except('_token');

            $employeeLog = [
                'employee_id' => $employeeOldData['employee_id'],
                'organization_id' => $employeeOldData['organization_id'],
                'branch_id' => $employeeOldData['branch_id'],
                'department_id' => $employeeOldData['department_id'],
                'level_id' => $employeeOldData['level_id'],
                'designation_id' => $employeeOldData['designation_id'],
                'job_title' => $employeeOldData['job_title'],
                'category' => 'log',
            ];
            $storeEmpLog = PerformanceDetail::storePerformanceDetail($employeeLog);

            $employeeHistory = [
                'employee_id' => $employeeNewData['employee_id'],
                'organization_id' => $employeeNewData['organization_id'],
                'branch_id' => $employeeNewData['branch_id'],
                'department_id' => $employeeNewData['department_id'],
                'level_id' => $employeeNewData['level_id'],
                'designation_id' => $employeeNewData['designation_id'],
                'job_title' => $employeeNewData['job_title'],
                'category' => 'history',
                'type_id' => $employeeNewData['type_id'],
                'date' => $employeeNewData['date']
            ];
            $storeEmpHistory = PerformanceDetail::storePerformanceDetail($employeeHistory);

            if ($storeEmpLog && $storeEmpHistory) {
                if ($employeeOldData['organization_id'] != $employeeNewData['organization_id']) {
                    $currentLeaveYearModel = LeaveYearSetup::currentLeaveYear();
                    EmployeeLeave::where('leave_year_id', $currentLeaveYearModel->id)->where('employee_id', $request['employee_id'])->delete();
                    EmployeeLeaveOpening::where('leave_year_id', $currentLeaveYearModel->id)->where('organization_id', $employeeOldData['organization_id'])->where('employee_id', $request['employee_id'])->delete();
                }
                $this->employee->update($request['employee_id'], $employeeNewData);
                toastr()->success('Employee Details updated Successfully !!!');
            }
        } catch (\Throwable $th) {
            toastr()->error('Something went wrong');
        }
        return redirect()->back();
    }

    public function carrierMobility(Request $request)
    {
        $filter = $request->all();
        $data['organizationList'] = $this->organization->getList();
        $data['branchList'] = $this->branchObj->getList();
        $data['employeeList'] = $this->employee->getList();

        $data['departmentList'] = $this->department->getList();
        $data['levelList'] = $this->level->getList();
        $data['designationList'] = $this->designation->getList();

        $data['typeList'] = EmployeeCarrierMobility::typeList();
        $data['probationStatusList'] = EmployeeCarrierMobility::probationStatusList();
        $data['payrollChangeList'] = EmployeeCarrierMobility::payrollChangeList();
        $data['employee'] = [];
        if (!empty($filter)) {
            $data['employee'] = $employee = $this->employee->find($filter['employee_id']);
            $data['filteredBranchList'] = $this->branchObj->branchListOrganizationwise($employee->organization_id);
        }
        return view('employee::employee.carrier-mobility.index', $data);
    }

    public function storeCarrierMobility(Request $request)
    {
        try {
            $employeeOldData = $this->employee->find($request['employee_id']);
            $data = $request->except('_token');
            $current_user_id = Auth::user()->id;
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
                $description = EmployeeCarrierMobility::getTypewiseName($storeData);
                $timelineData['description'] = $storeData->getTypeList() . $description;
                $timelineData['icon'] = "icon-truck";
                $timelineData['color'] = "secondary";
                $timelineData['reference'] = "employee-career-mobility";
                $timelineData['reference_id'] = $storeData->id;
                $timelineData['carrier_mobility_id'] = $storeData->id;
                Employee::saveEmployeeTimelineData($data['employee_id'], $timelineData);

                toastr()->success('Employee Career Mobility data updated successfully !!!');
            }
        } catch (\Throwable $th) {
            $msg = $th->getMessage() . ' Line no: ' .  $th->getLine();
            Log::error($msg);
            dd($msg);
            toastr()->error('Something went wrong');
        }
        return redirect()->back();
    }

    public function carrierMobilityReport(Request $request)
    {
        $data['filter'] = $filter = $request->all();
        $sort = [
            'by' => 'id',
            'sort' => 'DESC'
        ];
        $data['reports'] = $this->employee->findMobilityReport(20, $filter, $sort);
        $data['organizationList'] = $this->organization->getList();
        $data['employeeList'] = $this->employee->fetchEmployeeForCareerMobilities();
        $data['typeList'] = EmployeeCarrierMobility::typeList();
        $data['contractTypeList'] = LeaveType::CONTRACT;
        unset($data['contractTypeList'][100]);

        return view('employee::employee.carrier-mobility.report', $data);
    }

    public function destroyCarrierMobility(string $id, string $type)
    {

        try {
            $this->employee->deleteCarrierMobility($id, $type);

            toastr()->success('Employee Career Mobility Report Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }

    public function addTimeline()
    {
        $employees = $this->employee->findAll();
        foreach ($employees as $employee) {
            if (optional($employee->payrollRelatedDetailModel)->probation_end_date >= Carbon::now()->toDateString()) {
                $data = [
                    'employee_id' => $employee->id,
                    'date' => optional($employee->payrollRelatedDetailModel)->probation_end_date,
                    'title' => 'Probation Date',
                    'description' => 'Your probation period has ended',
                    'icon' => 'icon-user',
                    'color' => 'primary',
                    'reference' => 'employee',
                    'reference_id' => $employee->id

                ];
                EmployeeTimeline::create($data);
            }
            toastr()->success('Employee Timeline Successfully');
            return redirect()->route('dashboard');
        }
    }

    public function deleteProfilePic(Request $request, $id)
    {
        try {
            $employeeModel  = $this->employee->find($id);
            $employeeModel->profile_pic = null;
            $employeeModel->save();
            return response()->json([
                'status' => true
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'msg' => $th->getMessage()
            ]);
        }
    }

    public function employeeSuggestionList(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->all()['query'];
            $employees = Employee::where('first_name', 'like', '%' . $search . '%')
                ->orWhere('middle_name', 'like', '%' . $search . '%')
                ->orWhere('last_name', 'like', '%' . $search . '%')
                ->get()
                ->transform(function ($employee) {
                    return [
                        'id' => $employee->id,
                        'fullname' => $employee->fullname
                    ];
                });

            return response()->json($employees);
        }
    }

    public function downloadProfile(string $id)
    {
        $employee = $this->employee->find($id);
        $employee->load([
            'designation',
            'department',
            'level',
            'branchModel',
            'educationDetail',
            'permanentProvinceModel',
            'previousJobDetail'
        ]);
        $customPaper = [0, 0, 1000.00, 595.28];
        $pdf = PDF::loadView('employee::employee.download', ['employee' => $employee])
            ->setPaper($customPaper, 'landscape')
            ->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->download('profile.pdf');
    }

    public function appendLeaveDetail(Request $request)
    {
        $inputData = $request->all();
        // dd($inputData);
        $data['employee_id'] = $inputData['employee_id'];
        $employee = $this->employee->find($data['employee_id']);
        $data['organization_id'] = $inputData['organization_id'];
        $data['date'] = $inputData['date'];
        $data['type_id'] = $inputData['type_id'];
        $data['employeeLeaveDetails'] = $this->employee->employeeLeaveDetails($inputData['employee_id']);
        $current_leave_year_data = LeaveYearSetup::currentLeaveYear();
        if (!is_null($current_leave_year_data)) {
            $leave_year_id = $current_leave_year_data['id'];
            $params['gender'] = $employee->gender;
            $params['marital_status'] = $employee->marital_status;
            $params['department_id'] = $employee->department_id;
            $params['level_id'] = $employee->level_id;
            $params['contract_type'] = [optional($employee->payrollRelatedDetailModel)->contract_type];
            $params['job_type'] = [optional($employee->payrollRelatedDetailModel)->probation_status];
            $data['leave_types'] = $this->leaveType->getLeaveTypesFromOrganization($data['organization_id'], $leave_year_id, $params);
        }
        $empLeaveDetail = view('employee::employee.carrier-mobility.partial.emp_leave_details', $data)->render();
        return response()->json(['data' => $empLeaveDetail]);
    }

    public function resetDevice($id)
    {
        try {
            $user = User::where('emp_id', $id)->first();
            $user->update(['imei' => null]);
            $tokens = Token::select('id')->where('user_id', $user->id)
                ->where('name', 'API-TOKEN')
                ->where('revoked', 'false')
                ->get();
            foreach ($tokens as $token) {
                $token->revoke();
            }
            toastr()->success('Employee Device Reset Successfully');
        } catch (\Throwable $th) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect()->back();
    }

    public function showJobDetail($employeeId)
    {
        $data['employee'] = $this->employee->find($employeeId);
        $data['genderList'] = $this->dropdown->getFieldBySlug('gender');
        $data['branchList'] = $this->branchObj->getList();
        $data['departmentList'] = $this->department->getList();
        $data['designationList'] = $this->designation->getList();

        $data['typeList'] = [1 => 'Job Type Change', 2 => 'Extend End Date', 3 => 'Archive Employee'];
        $data['jobTypeList'] = [1 => 'Probation', 2 => 'Contract', 3 => 'Permanent'];

        return view('employee::job-type-details.index', $data);
    }

    public function storeJobDetail(Request $request)
    {
        $data = $request->except('_token');
        $employee = $this->employee->find($data['employee_id']);

        $data['extend_end_date'] = $data['extend_end_date'] ? (setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['extend_end_date']) : $data['extend_end_date']) : null;
        $data['contract_start_date'] = $data['contract_start_date'] ? (setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['contract_start_date']) : $data['contract_start_date']) : null;
        $data['contract_end_date'] = $data['contract_end_date'] ? (setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['contract_end_date']) : $data['contract_end_date']) : null;

        try {
            $employeeJobDetail = EmployeeJobDetail::where('employee_id', $employee['id'])->first();
            if ($employeeJobDetail) {
                $employeeJobDetail->update($data);
            } else {
                $data['created_by'] = auth()->user()->id;
                EmployeeJobDetail::create($data);
            }

            if ($data['type_id'] == 1) {
                $employeePayrollData['employee_id'] = $employee['id'];
                $employeePayrollData['join_date'] = $employee['join_date'];
                $empPayroll =  EmployeePayrollRelatedDetail::where('employee_id', $employee['id'])->first();

                if ($data['job_type_id'] == 1) {
                    $employeePayrollData['probation_status'] = 11;
                    $employeePayrollData['probation_period_days'] = $data['probation_period_days'];
                    EmployeePayrollRelatedDetail::saveData($employee['id'], $employeePayrollData);
                } elseif ($data['job_type_id'] == 2) {
                    $employeePayrollData['contract_type'] = 11;
                    $employeePayrollData['contract_start_date'] = $data['contract_start_date'];
                    $employeePayrollData['contract_end_date'] = $data['contract_end_date'];

                    if ($empPayroll) {
                        $empPayroll->update($employeePayrollData);
                    } else {
                        EmployeePayrollRelatedDetail::create($employeePayrollData);
                    }
                } elseif ($data['job_type_id'] == 3) {
                    $employeePayrollData['contract_type'] = 10;
                    $employeePayrollData['contract_start_date'] = null;
                    $employeePayrollData['contract_end_date'] = null;
                    $employeePayrollData['probation_status'] = 10;
                    $employeePayrollData['probation_period_days'] = null;

                    if ($empPayroll) {
                        $empPayroll->update($employeePayrollData);
                    } else {
                        EmployeePayrollRelatedDetail::create($employeePayrollData);
                    }
                }
            } elseif ($data['type_id'] == 2) {
                $employeeData['end_date'] = $data['extend_end_date'];
                $employeeData['nep_end_date'] = date_converter()->eng_to_nep_convert($data['extend_end_date']);
                $employee->update($employeeData);
            } elseif ($data['type_id'] == 3) {
                $data['archived_date'] = $data['archived_date'] ? (setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['archived_date']) : $data['archived_date']) : date('Y-m-d');
                $data['nep_archived_date'] = date_converter()->eng_to_nep_convert($data['archived_date']);

                if ($employee->status == 1) {
                    $data['status'] = 0;
                    $user_status['active'] = 0;
                    if ($employee->getUser != null) {
                        $employee->getUser->update($user_status);
                    }
                    $employee->update($data);
                }
            }
            toastr()->success('Job details updated successfully !!!');
        } catch (\Throwable $th) {
            toastr()->error('Something went wrong');
        }
        return redirect()->back();
    }

    public function jobEndDateReport(Request $request)
    {
        $data['filter'] = $filter = $request->all();
        $sort = [
            'by' => 'end_date',
            'sort' => 'ASC'
        ];
        $data['employees'] = $this->employee->findEndDateReport(20, $filter, $sort);
        $data['organizationList'] = $this->organization->getList();
        $data['employeeList'] = $this->employee->getList();
        $data['jobTypeList'] = LeaveType::JOB_TYPE;
        return view('employee::employee.job-end-date.report', $data);
    }

    public function probationEndDateReport(Request $request)
    {
        $data['filter'] = $filter = $request->all();
        $sort = [
            'by' => 'end_date',
            'sort' => 'ASC'
        ];
        $data['employees'] = $this->employee->findProbationEndDateReport(20, $filter, $sort);
        $data['organizationList'] = $this->organization->getList();
        $data['employeeList'] = $this->employee->getList();
        $data['jobTypeList'] = LeaveType::JOB_TYPE;
        return view('employee::employee.job-end-date.probation-end-date-report', $data);
    }



    public function documentExpiryDateReport(Request $request)
    {
        $data['filter'] = $filter = $request->all();
        $sort = [
            'by' => 'visa_expiry_date',
            'sort' => 'ASC'
        ];
        $data['visaDetails'] = $this->employee->findDocExpiryDateReport(20, $filter, $sort);
        $data['organizationList'] = $this->organization->getList();
        $data['employeeList'] = $this->employee->getList();
        $data['jobTypeList'] = LeaveType::JOB_TYPE;
        return view('employee::employee.document-expiry-date.report', $data);
    }

    public function showDocumentDetail($documentId)
    {
        $data['document'] = $this->document->findOne($documentId);
        if (setting('calendar_type') == 'BS') {
            if (!is_null($data['document']['issued_date'])) {
                $data['document']['issued_date'] = date_converter()->eng_to_nep_convert($data['document']['issued_date']);
            }
            if (!is_null($data['document']['visa_expiry_date'])) {
                $data['document']['visa_expiry_date'] = date_converter()->eng_to_nep_convert($data['document']['visa_expiry_date']);
            }
        }
        $data['countryList'] = $this->employee->getCountries();
        $data['typeList'] = [1 => 'Extend Expiry Date', 2 => 'Delete Document'];
        return view('employee::document-details.index', $data);
    }
    public function updateDocumentDetail(Request $request)
    {
        $data = $request->except('_token');
        try {
            if ($data['type_id'] == 1 && isset($data['visa_expiry_date'])) {
                $this->document->update($request->id, $data);
            }
            toastr()->success('Document details updated successfully');
        } catch (Exception $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect()->back();
    }
    public function destroyDocumentDetail($id)
    {
        try {
            $this->document->delete($id);
            toastr()->success('Document details deleted successfully');
        } catch (Exception $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect('/admin/dashboard');
    }

    public function checkPanUnique(Request $request)
    {
        $data = $request->except('_token');
        $validator = Validator::make($request->all(), [
            'pan_no' => 'nullable|unique:employees,pan_no,' . $data['id'],
        ]);
        if ($validator->fails()) {
            return  json_encode(false);
        }
        return  json_encode(true);
    }

    public function bulkUserStatusActive(Request $request)
    {
        $inputData = $request->except('_token');
        $employeeIds = json_decode($inputData['employee_multiple_id'][0], true);
        try {
            // if (!empty($employeeIds)) {
            //     foreach ($employeeIds as $employeeId) {
            //         $empModel = $this->employee->find($employeeId);
            //         if ($empModel->status == 0) {
            //             $data['status'] = 1;
            //             $empModel->update($data);
            //         }
            //         if ($empModel->getUser != null) {
            //             $userData['active'] = 1;
            //             $empModel->getUser->update($userData);

            //             if (isset($empModel->official_email)) {
            //                 $details = [
            //                     'full_name' => $empModel->full_name,
            //                     'username' => optional($empModel->user)->username,
            //                     'password' => 'Password@123',
            //                     'setting' => Setting::first()
            //                 ];
            //                 Mail::send('admin::mail.user-activation-mail', $details, function ($message) use ($empModel) {
            //                     $message->from(config('mail.from.address') ?? env('MAIL_TO_ADDRESS'));
            //                     $message->to($empModel->official_email);
            //                     $message->subject('User Activation Notification');
            //                 });
            //             }
            //         }
            //     }
            // }
            $employees = Employee::whereIn('id', $employeeIds)->get();
            if (env('JOB_LOGIN_DETAIL_NOTIFICATION', false)) {
                SendLoginNotification::dispatch($employees);
            } else {
                foreach ($employees as $employee) {
                    $user = $employee->getUser;
                    if (!is_null($user)) {
                        $user->activateUserAccess();
                    }
                }
            }
            toastr()->success('User status changed successfully !!!');
        } catch (\Throwable $th) {
            if (env('APP_DEBUG')) {
                throw $th;
            } else {
                toastr()->error('Something went wrong');
            }
        }
        return redirect()->back();
    }

    public function getPayrollDetail(Request $request)
    {
        $employee = $this->employee->find($request->employee_id);
        $payrollDetail = $employee->payrollRelatedDetailModel;
        $amount = $payrollDetail->basic_salary - ($payrollDetail->basic_salary * 0.01);
        $rate = $amount / 30;
        return $rate;
    }

    public function getEmployeeByOrganization(Request $request)
    {


        $employees = $this->employee->getEmployeeByOrganization($request->organization_id);
        return response()->json($employees);
    }

    public function convertDob(Request $request)
    {

        if ($request->ajax()) {
            $nepaliYear = date_converter()->nep_to_eng_convert($request->date);
            $age = Carbon::parse($nepaliYear)->age;
            return response()->json(['age' => $age]);
        }
    }

    public function getShiftByOrganization(Request $request)
    {
        $shifts = $this->shift->getShiftByOrganization($request->org_id);
        return response()->json($shifts);
    }

    public function getEmployeeApprovalFlow(Request $request)
    {
        $appFlow = EmployeeApprovalFlow::where('employee_id', $request->employee_id)
            ->with(['userFirstApproval', 'userSecondApproval', 'userThirdApproval', 'userLastApproval'])
            ->first();

        if (!$appFlow) {
            return response()->json(['message' => 'Approval flow not found.'], 404);
        }

        $mapFullName = function ($user) {
            if (!$user) return null;

            $parts = array_filter([
                $user->first_name,
                $user->middle_name,
                $user->last_name
            ]);

            return implode(' ', $parts);
        };

        $data = [
            'first_approver'  => $mapFullName($appFlow->userFirstApproval),
            'second_approver' => $mapFullName($appFlow->userSecondApproval),
            'third_approver'  => $mapFullName($appFlow->userThirdApproval),
            'last_approver'   => $mapFullName($appFlow->userLastApproval),
        ];

        return response()->json($data);
    }


    public function getSubFunction(Request $request)
    {
        $functionId = $request->get('function_id');
        $departments = Department::where('function_id', $functionId)->pluck('title', 'id');
        return response()->json($departments);
    }
}
