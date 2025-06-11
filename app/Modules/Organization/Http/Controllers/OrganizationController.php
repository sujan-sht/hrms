<?php

namespace App\Modules\Organization\Http\Controllers;

use App\Modules\Admin\Entities\DateConverter;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\User\Entities\User;
use App\Modules\Branch\Entities\Branch;
use App\Modules\Leave\Entities\LeaveType;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Organization\Entities\Organization;
use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Employee\Services\ApiService;
use App\Modules\LeaveYearSetup\Repositories\LeaveYearSetupInterface;
use App\Modules\Labour\Entities\Labour;
use App\Modules\Leave\Entities\Leave;
use App\Modules\Leave\Repositories\LeaveInterface;
use App\Modules\Leave\Repositories\LeaveTypeInterface;
use App\Modules\Organization\Http\Requests\OrganizationRequest;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Payroll\Entities\IncomeSetup;
use App\Modules\Setting\Entities\Darbandi;
use App\Modules\Setting\Entities\Setting;
use App\Modules\Shift\Repositories\ShiftGroupInterface;
use App\Modules\User\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    private $organization;
    protected $setting;
    protected $apiService;
    private $employee;
    private $branchObj;
    protected $leaveYearSetup;
    protected $leaveTypeObj;
    private $leave;
    private $shiftGroup;


    /**
     * OrganizationController constructor.
     * @param OrganizationInterface $organization
     * @param DropdownInterface $dropdown
     * @param EmploymentInterface $employment
     * @param FieldInterface $field
     */
    public function __construct(
        OrganizationInterface $organization,
        Setting $setting,
        ApiService $apiService,
        EmployeeInterface $employee,
        BranchInterface $branchObj,
        LeaveYearSetupInterface $leaveYearSetup,
        BranchInterface $branch,
        LeaveTypeInterface $leaveTypeObj,
        LeaveInterface $leave,
        ShiftGroupInterface $shiftGroup
    ) {
        $this->organization = $organization;
        $this->apiService = $apiService;
        $this->setting = $setting;
        $this->leave = $leave;
        $this->employee = $employee;
        $this->branchObj = $branchObj;
        $this->leaveYearSetup = $leaveYearSetup;
        $this->leaveTypeObj = $leaveTypeObj;
        $this->shiftGroup = $shiftGroup;
    }

    public function index(Request $request)
    {
        $syncStatus = $this->setting->getData();
        $data['syncOrganization'] = $syncOrganization = $syncStatus['flag_organization'];
        $filter = $request->all();

        $data['organizationModels'] = $this->organization->findAll(20, $filter);
        // $countOrganization = Organization::count();
        // if ($countOrganization < 3) {
        //     $data['showCreateBtn'] = true;
        // } else {
        //     toastr()->error('You are not allowed to create more than 3 organizations.');
        //     $data['showCreateBtn'] = false;
        // }
        return view('organization::organization.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $syncStatus = $this->setting->getData();
        $data['syncOrganization'] = $syncOrganization = $syncStatus['flag_organization'];
        if ($syncOrganization == 0) {
            toastr()->error('Unauthorized access');
            return redirect(route('organization.index'));
        }
        $data['isEdit'] = false;

        $total = $this->organization->findAll()->count();
        if ($total >= 4) {
            toastr()->error('You cannot add more Organizations. Please contact technical team for more detail.');
            return redirect(route('organization.index'));
        }

        return view('organization::organization.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(OrganizationRequest $request)
    {
        $data = $request->all();
        $syncStatus = $this->setting->getData();
        $hostName = $syncStatus['sync_host_name'] ?? null;
        try {
            if ($request->hasFile('image')) {
                $data['image'] = $this->organization->upload($data['image']);
            }

            if ($request->hasFile('letter_head')) {
                $data['letter_head'] = $this->organization->uploadLetterhead($data['letter_head']);
            }

            $orgData = $this->organization->create($data);

            if ($syncStatus['sync_organization'] == 1) {
                $this->sendManageOrganizatonData($orgData->toArray(), $hostName);
            }

            toastr()->success('Organization Created Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('organization.index'));
    }

    public function sendManageOrganizatonData($orgData, $hostName)
    {
        $organazationData = [];
        $organazationData['id'] = $orgData['id'];
        $organazationData['name'] = $orgData['name'];
        $organazationData['email'] = $orgData['email'];
        $organazationData['address'] = $orgData['address'];
        $response = $this->apiService->sendOrganizationData($organazationData, $hostName);
    }
    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('organization::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $data['isEdit'] = true;
        $data['organizationModel'] = $this->organization->findOne($id);

        return view('organization::organization.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(OrganizationRequest $request, $id)
    {
        $data = $request->all();

        try {
            if ($request->hasFile('image')) {
                $data['image'] = $this->organization->upload($data['image']);
            }

            if ($request->hasFile('letter_head')) {
                $data['letter_head'] = $this->organization->uploadLetterhead($data['letter_head']);
            }

            $this->organization->update($id, $data);

            toastr()->success('Organization Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('organization.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $checkEmployee = $this->employee->getEmployeeByOrganization($id);
            if ($checkEmployee->isNotEmpty() && $checkEmployee->count() > 0) {
                toastr()->error('There are employees under this organization so you cannot delete this organization !!!');
            } else {
                $this->organization->delete($id);
                toastr()->success('Organization Deleted Successfully');
            }
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('organization.index'));
    }

    /**
     *
     */
    public function overview()
    {
        $userModel = User::where('id', auth()->user()->id)->first();
        $organizationId = optional($userModel->userEmployer)->organization_id;

        $data['branchCount'] = Branch::where('organization_id', $organizationId)->count();
        $data['departmentCount'] = Employee::select('department_id')->where('organization_id', $organizationId)->distinct()->get()->count();
        $data['levelCount'] = Employee::select('level_id')->where('organization_id', $organizationId)->distinct()->get()->count();
        $data['employeeCount'] = Employee::where('organization_id', $organizationId)->where('status', 1)->count();
        $data['organizationModel'] = $this->organization->findOne($organizationId);

        $filter = [
            'organization_id' => optional(auth()->user()->userEmployer)->organization_id
        ];
        $data['branchModels'] = $this->branchObj->findAll(null, $filter);


        return view('organization::organization.overview', $data);
    }

    /**
     *
     */
    public function codeOfConduct()
    {
        $userModel = User::where('id', auth()->user()->id)->first();
        $organizationId = optional($userModel->userEmployer)->organization_id;
        $organizationModel = $this->organization->findOne($organizationId);
        $data['organizationModel'] = $organizationModel;

        return view('organization::organization.code_of_conduct', $data);
    }


    /**
     *
     */
    public function masterReport(Request $request)
    {
        $data['organizationLists'] = $this->organization->getList();
        return view('organization::organization.master-report', $data);
    }

    public function getLeaveReport(Request $request)
    {
        $filter = $request->all();
        $data = [];
        $data['monthList'] = (new DateConverter())->getEngMonths();
        $data['branchList'] = Employee::getOrganizationwiseBranches($filter['organization_id']);;
        // $data['leaveTypeList'] = $leaveTypeList = $this->leaveTypeObj->getList()->toArray();
        $filter['leave_year_id'] = isset($filter['leave_year_id']) ? $filter['leave_year_id'] : getCurrentLeaveYearId();
        $data['leaveTypeList'] = LeaveType::getOrganizationwiseLeaveTypes($filter);
        // $data['leaveTypeList'] = LeaveType::getOrganizationwiseLeaveTypes($filter);
        $data['leaveKindList'] = Leave::leaveKindList();
        $data['statusList'] = Leave::statusList();
        $data['leaveModels'] = $this->leave->findAll('', $filter);
        $data['leaveYearList'] = $this->leaveYearSetup->getLeaveYearList();


        $data['count_leave_types'] = $data['leaveModels']->groupBy('leave_type_id')
            ->map(function ($items) {
                // if (array_key_exists($category, $leaveTypeList)) {
                //     $data[$leaveTypeList[$category]] =  $items->count();
                // }
                // return $data;
                return $items->count();
            })->toArray();

        $data['count_leave_status'] = $data['leaveModels']->groupBy('status')
            ->map(function ($items, $category) {
                return $items->count();
            })->sortBy(function ($value, $key) {
                return $key;
            });

        $data['count_leave_kind'] = $data['leaveModels']->groupBy('leave_kind')
            ->map(function ($items, $category) {
                return $items->count();
            });
        // dd($data['count_leave_kind']->toArray(), $data['count_leave_status'], $data['leaveModels']->toArray());

        return response()->json([
            'view' => view('organization::organization.report.partial.leave', $data)->render()
        ]);
    }

    /**
     * Ajax function
     * Get Branches list of specific organization
     */
    public function getBranches(Request $request)
    {
        $inputData = $request->all();
        $models = Employee::getOrganizationwiseBranches($inputData['organization_id']);
        return json_encode($models);
    }

    /**
     * Ajax function
     * Get Departments list of specific organization
     */
    public function getDepartments(Request $request)
    {
        $inputData = $request->all();
        $models = Employee::getOrganizationwiseDepartments($inputData['organization_id']);
        return json_encode($models);
    }

    /**
     * Ajax function
     * Get Designations list of specific organization
     */
    public function getDesignations(Request $request)
    {
        $inputData = $request->all();
        $models = Employee::getOrganizationwiseDesignations($inputData['organization_id']);
        return json_encode($models);
    }

    public function getLevelsFromDesignation(Request $request)
    {
        $inputData = $request->all();
        $models = Employee::getDesignationwiseLevels($inputData['organization_id'], $inputData['designation_id']);
        return json_encode($models);
    }

    /**
     * Ajax function
     * Get Levels list of specific organization
     */
    public function getLevels(Request $request)
    {
        $inputData = $request->all();
        $models = Employee::getOrganizationwiseLevels($inputData['organization_id']);
        return json_encode($models);
    }

    /**
     * Ajax function
     * Get employee list of specific organization
     */
    public function getEmployees(Request $request)
    {
        $inputData = $request->all();
        $employees = Employee::getOrganizationwiseEmployees($inputData);
        return json_encode($employees);
    }

    public function getConfirmedEmployees(Request $request)
    {
        $inputData = $request->all();
        $employees = Employee::getOrganizationEmployeeConfirmations($inputData);
        return json_encode($employees);
    }

    public function getPermanentEmployees(Request $request)
    {
        $inputData = $request->all();
        $employees = Employee::getOrganizationWisePermanentEmployees($inputData);
        return json_encode($employees);
    }

    public function getMultipleEmployees(Request $request)
    {
        $inputData = $request->all();

        $employees = Employee::getOrganizationwiseEmployees($inputData);

        // return json_encode($employees);
        // $users = $request->branch ? $this->user->getAllUsersByBranch($request->branch) : $this->user->getAllActiveUserList() ;
        $view = view('employee::employee.employee-filter', compact('employees'))->render();

        return response()->json(['view' => $view]);
    }

    public function getShiftGroupEmployees(Request $request)
    {
        $inputData = $request->all();
        $allActiveEmployeeList = Employee::getOrganizationwiseEmployees($inputData);
        $employees = [];
        foreach ($allActiveEmployeeList as $empId => $fullName) {
            $isExists = $this->shiftGroup->checkShiftExists($empId);
            if ($isExists) {
                $employees[$empId] = $fullName;
            }
        };
        return json_encode($employees);
    }

    /**
     * Ajax function
     * Get leave type list of specific organization
     */
    public function getLeaveTypes(Request $request)
    {
        $inputData = $request->all();
        $inputData['leave_year_id'] = isset($inputData['leave_year_id']) ? $inputData['leave_year_id'] : getCurrentLeaveYearId();
        $leaveTypes = LeaveType::getOrganizationwiseLeaveTypes($inputData);

        return json_encode($leaveTypes);
    }

    /**
     * Ajax function
     * Get Users list of specific organization
     */
    public function getUsersExceptEmployeeRole(Request $request)
    {
        // $inputData = $request->all();

        // $models = User::getAllActiveUserListExpectEmployeeOrgWise($inputData['organization_id']);
        $models = (new UserRepository())->getListExceptAdmin();
        return json_encode($models);
    }

    public function getMultipleEmployeesForFilter(Request $request)
    {
        $inputData = $request->all();
        $employees = Employee::getOrganizationwiseEmployees($inputData['organization_id']);
        $view = view('employee::employee.carrier-mobility.partial.employee-filter', compact('employees'))->render();
        return response()->json(['view' => $view]);
    }

    public function getUnpaidLeaveTypes(Request $request)
    {
        $inputData = $request->all();
        $inputData['leave_year_id'] = isset($inputData['leave_year_id']) ? $inputData['leave_year_id'] : getCurrentLeaveYearId();
        $inputData['leave_type'] = 11;
        $leaveTypes = LeaveType::getOrganizationwiseLeaveTypes($inputData);
        return json_encode($leaveTypes);
    }


    public function darbandis()
    {
        $data['organizationLists'] = $this->organization->getList();
        if ($data['organizationLists']->count() > 1) {
            return view('organization::organization.darbandis', $data);
        } else {
            $organization_id = $data['organizationLists']->keys()->first();
            return redirect()->route('organization.darbandiReport', $organization_id);
        }
    }

    public function darbandiReport($id)
    {
        $data['darbandis'] = Darbandi::where('organization_id', $id)->latest()->get();
        return view('organization::organization.darbandi-report', $data);
    }

    public function getLabour(Request $request)
    {
        $inputData = $request->all();

        $models = Labour::getOrganizationwiseLabour($inputData['organization_id']);

        return json_encode($models);
    }

    public function getIncomeTypes(Request $request)
    {
        $inputData = $request->all();
        $models = IncomeSetup::getOrganizationwiseIncomeTypes($inputData['organization_id']);
        return json_encode($models);
    }
}
