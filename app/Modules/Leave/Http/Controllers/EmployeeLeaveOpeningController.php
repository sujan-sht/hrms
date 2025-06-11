<?php

namespace App\Modules\Leave\Http\Controllers;

use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\EmployeeLeave;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\FiscalYearSetup\Repositories\FiscalYearSetupInterface;
use App\Modules\LeaveYearSetup\Entities\LeaveYearSetup;
use App\Modules\LeaveYearSetup\Repositories\LeaveYearSetupInterface;
use App\Modules\Leave\Entities\EmployeeLeaveOpening;
use App\Modules\Leave\Entities\Leave;
use App\Modules\Leave\Entities\LeaveEncashable;
use App\Modules\Leave\Entities\LeaveType;
use App\Modules\Leave\Exports\LeaveSummaryReport;
use App\Modules\Leave\Repositories\LeaveInterface;
use App\Modules\Leave\Repositories\LeaveTypeInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Config;

class EmployeeLeaveOpeningController extends Controller
{
    protected $organization;
    protected $employee;
    protected $leaveType;
    protected $leave;
    private $fiscalYearSetup;
    private $branchObj;

    private $leaveYearSetup;
    private $leaveTypeObj;



    public function __construct(
        OrganizationInterface $organization,
        EmployeeInterface $employee,
        LeaveTypeInterface $leaveType,
        LeaveInterface $leave,
        FiscalYearSetupInterface $fiscalYearSetup,
        BranchInterface $branchObj,
        LeaveYearSetupInterface $leaveYearSetup,
        LeaveTypeInterface $leaveTypeObj
    ) {
        $this->organization = $organization;
        $this->employee = $employee;
        $this->leaveType = $leaveType;
        $this->leave = $leave;
        $this->fiscalYearSetup = $fiscalYearSetup;
        $this->branchObj = $branchObj;

        $this->leaveYearSetup = $leaveYearSetup;
        $this->leaveTypeObj = $leaveTypeObj;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data['organizations'] = $this->organization->findAll($limit = 20, []);
        if ($data['organizations']->count() == 1) {
            return redirect()->route('leaveOpening.show', ['id' => $data['organizations'][0]->id, 'leave_year_id' => getCurrentLeaveYearId()]);
        } else {
            return view('leave::leave-opening.index', $data);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('leave::create');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show(Request $request, $id)
    {
        $filter = ($request->all());
        // dd($filter);
        $data = [];
        // $data['employeeLeaveSummaries'] = [];
        if (isset($filter['leave_year_id']) || isset($filter['leave_type_id']) || isset($filter['employee_id'])) {
            $data = self::getLeaveSummaries($filter, $id, 30);
        }
        $data['leaveYearList'] = $this->leaveYearSetup->getLeaveYearList();
        // $data['leaveTypeList']  = $this->leaveTypeObj->getList();
        $data['employeeList']  = $this->employee->getList();
        if (auth()->user()->user_type == 'division_hr' || auth()->user()->user_type == 'employee') {
            $leaveTypeLists = LeaveType::where('status', 11)->where('organization_id', optional(auth()->user()->userEmployer)->organization_id)->where('leave_year_id', getCurrentLeaveYearId())->get();
        } else {
            $leaveTypeLists = LeaveType::where('status', 11)->where('leave_year_id', getCurrentLeaveYearId())->get();
        }
        $data['leaveTypeList'] = [];
        foreach ($leaveTypeLists as $key => $leaveTypeList) {
            $organizationName = $leaveTypeList->organization ? "(" . optional($leaveTypeList->organization)->name . ")" : '';
            $data['leaveTypeList'][$leaveTypeList->id] = $leaveTypeList->name . ' ' . $organizationName;
        }
        $data['id'] = $id;
        return view('leave::leave-opening.show', $data);
    }

    public function exportLeaveSummaryReport(Request $request, $id)
    {
        $filter = $request->filters;

        if (isset($filter['leave_year_id'])) {
            $data = self::getLeaveSummaries($filter, $id, Config::get('leave.export-length'));
            // return view('leave::exports.leave-summary-report',$data);
            return Excel::download(new LeaveSummaryReport($data), 'leave-summary-report.xlsx');
        }
    }

    function getLeaveSummaries($filter, $id, $limit)
    {
        $leave_year_id = $filter['leave_year_id'];

        $leaveTypeQuery = $this->leaveType->getAllLeaveTypes($id, $leave_year_id);
        if (!empty($filter['leave_type_id'])) {
            $leaveTypeQuery = $leaveTypeQuery->where('id', $filter['leave_type_id']);
        }

        $data['allLeaveTypes'] = $this->leaveType->getAllLeaveTypes($id, $leave_year_id);

        $query = Employee::query();
        $query->where('status', '=', 1);
        $query->where('organization_id', $id);

        if (auth()->user()->user_type == 'employee') {
            $query->where('id', auth()->user()->emp_id);
        } elseif (auth()->user()->user_type == 'supervisor') {
            $employeeIds = Employee::getSubordinates(auth()->user()->id);
            array_push($employeeIds, auth()->user()->emp_id);
            $query->whereIn('id', $employeeIds);
        }

        if (isset($filter['employee_id']) && !empty($filter['employee_id'])) {
            $query->where('employee_id', $filter['employee_id']);
        }

        $employees = $query->paginate($limit);
        $result = $employees->setCollection($employees->getCollection()->transform(function ($emp) use ($leave_year_id, $id) {
            $leaveTypeQuery = LeaveType::query();
            $leaveType = $leaveTypeQuery->get();
            $leaveOpening = [];
            $leaveRemaining = [];

            foreach ($leaveType as $lType) {
                $leaveOpening[$lType->id] = EmployeeLeaveOpening::getLeaveOpening($leave_year_id, $id, $emp->id, $lType->id) ?? 0;
                $leaveRemaining[$lType->id] = optional(EmployeeLeave::getLeaveRemaining($leave_year_id, $emp->id, $lType->id))->leave_remaining ?? 0;
            }

            $emp->leaveOpening = $leaveOpening;
            $emp->leaveRemaining = $leaveRemaining;
            return $emp;
        }));
        $data['employeeLeaveSummaries'] = $employees;
        return $data;
    }

    public function encashableLeave(Request $request)
    {
        $filter = $request->all();
        $data['previousLeaveYear'] = LeaveYearSetup::previousLeaveYear();
        if (!$data['previousLeaveYear']) {
            toastr()->error('Previous Leave Year Not Found!');
            return redirect()->route('leaveYearSetup.index');
        }

        if (isset($filter['organization_id'])) {


            $employees = Employee::when(true, function ($query) use ($filter) {
                $query->where('status', 1);
                if (isset($filter['organization_id'])) {
                    $query->where('organization_id', $filter['organization_id']);
                }
                if (isset($filter['branch_id'])) {
                    $query->where('branch_id', $filter['branch_id']);
                }
            })->with(['employeeleave' => function ($q) use ($data) {
                $q->where('leave_year_id', $data['previousLeaveYear']->id);
                $q->whereHas('leaveTypeModel', function ($q1) {
                    $q1->where('encashable_status', 11);
                });
            }])->paginate(20);

            $empArray = $employees->setCollection($employees->getCollection()->transform(function ($employee) use ($data) {
                $leaveDetails = $remainingLeave = [];
                foreach ($employee['employeeleave'] as $employeeleave) {
                    $leaveOpening = EmployeeLeaveOpening::where([
                        'leave_year_id' => $employeeleave->leave_year_id,
                        'organization_id' => $employee->organization_id,
                        'employee_id' => $employeeleave->employee_id,
                        'leave_type_id' => $employeeleave->leave_type_id
                    ])->first();
                    $empArray[$employee->id]['leaveTypes'][] = optional($employeeleave->leaveTypeModel)->name;
                    $leaveRemain = $employeeleave->leave_remaining;
                    $remainingLeave[] = $leaveRemain;

                    $leaveDetails[] = [
                        'leave_type' => optional($employeeleave->leaveTypeModel)->name,
                        'leave_year_id' => $employeeleave->leave_year_id,
                        'employee_id' => $employeeleave->employee_id,
                        'total_leave' =>  $leaveOpening ? $leaveOpening->opening_leave : 0,
                        'leave_remain' => $employeeleave->leave_remaining ?? 0,
                        'leave_taken' => $leaveOpening ? $leaveOpening->opening_leave - ($employeeleave->leave_remaining ?? 0) : 0,
                    ];
                    $empArray[$employee->id]['leaveDetails'] = $leaveDetails;
                    $employee->leaveDetails = $leaveDetails;
                    $employee->leaveTypes = $empArray[$employee->id]['leaveTypes'];
                }
                $employee->remainingLeave = array_sum($remainingLeave);
                unset($employee->employeeleave);

                $checkEnchasableLeave = null;
                if ($employee->leaveEncashable) {
                    $checkEnchasableLeave = $employee->leaveEncashable->where('leave_year_id', $data['previousLeaveYear']->id)->first();
                }
                $employee->checkEnchasableLeave = $checkEnchasableLeave ? true : false;

                return $employee;
            }));

            $data['employeeLeaveTypes'] = $empArray;
            $data['allLeaveTypes'] = $data['employeeLeaveTypes'][0]['leaveTypes'];
        }
        $data['organizationList'] = $this->organization->getList();
        $data['branchList'] = $this->branchObj->getList();

        return view('leave::leave-encashable.index', $data);
    }

    public function storeEncashableLeave(Request $request)
    {
        $inputData = ($request->all());
        try {
            LeaveEncashable::create($inputData);
            toastr()->success('Leave Encashable Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }
}
