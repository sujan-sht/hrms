<?php

namespace App\Modules\Employee\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Modules\Leave\Entities\LeaveType;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\EmployeeTimeline;
use App\Modules\Branch\Repositories\BranchRepository;
use App\Modules\Setting\Repositories\LevelRepository;
use App\Modules\Employee\Repositories\EmployeeRepository;
use App\Modules\Employee\Entities\EmployeeCarrierMobility;
use App\Modules\Setting\Repositories\DepartmentRepository;
use App\Modules\Setting\Repositories\DesignationRepository;
use App\Modules\Organization\Repositories\OrganizationRepository;
use App\Modules\Employee\Entities\NewEmployeeCareerMobilityTimeline;
use App\Modules\Employee\Entities\EmployeeCarrierMobilityAppointment;
use App\Modules\Employee\Entities\EmployeeCarrierMobilityConfirmation;
use App\Modules\Employee\Entities\EmployeeCarrierMobilityProbationaryPeriod;
use App\Modules\Employee\Services\EmployeeCareerMobilityProbationaryPeriodService;

class EmployeeCareerMobilityExtensionOfProbationaryPeriodController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->all();
        $employee = new EmployeeRepository();
        $branch = new BranchRepository();
        $data['organizationList'] = (new OrganizationRepository())->getList();
        $data['branchList'] = $branch->getList();
        $data['employeeList'] = Employee::contractTypeList(); // probation&Contract

        $data['departmentList'] = (new DepartmentRepository())->getList();
        $data['levelList'] = (new LevelRepository())->getList();
        $data['designationList']  = (new DesignationRepository())->getList();
        $data['typeList'] = EmployeeCarrierMobility::typeList();
        $data['probationStatusList'] = EmployeeCarrierMobility::probationStatusList();
        $data['payrollChangeList'] = EmployeeCarrierMobility::payrollChangeList();
        $data['employee'] = [];
        if (!empty($filter)) {
            $data['employee'] = $employee = $employee->find($filter['employee_id']);
            $data['filteredBranchList'] = $branch->branchListOrganizationwise($employee->organization_id);
            $data['employeeCarrierMobilityProbationaryPeriod'] = EmployeeCarrierMobilityProbationaryPeriod::where('employee_id', $filter['employee_id'])->first();
        }
        $data['contractTypes'] = LeaveType::CONTRACT;
        unset($data['contractTypes'][100]);
        return view('employee::employee.carrier-mobility.probationary-period.index', $data);
    }
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $employeeCareerMobilityProbationaryPeriodService = new EmployeeCareerMobilityProbationaryPeriodService($request);

            $data = $employeeCareerMobilityProbationaryPeriodService->setProbationaryPeriodData();
            $employeeCarrierMobilityProbationaryPeriod =  EmployeeCarrierMobilityProbationaryPeriod::create($data);

            $timelineData = $employeeCareerMobilityProbationaryPeriodService->setTimeLineData($employeeCarrierMobilityProbationaryPeriod);
            NewEmployeeCareerMobilityTimeline::create($timelineData);

            $timelineData['date'] = now();
            $timelineData['description'] = $timelineData['title'] . ' has been extended from ' . $request->extension_from_date . ' to ' . $request->extension_till_date . '.';
            $timelineData['reference'] = 'EmployeeCarrierMobilityProbationaryPeriod';
            $timelineData['reference_id'] = $timelineData['career_mobility_type_id'];
            $timelineData['carrier_mobility_id'] = $timelineData['career_mobility_type_id'];
            EmployeeTimeline::create($timelineData);
            $employeeCareerMobilityProbationaryPeriodService->updateEmployeeExtensionDate(Employee::find($request->employee_id));

            DB::commit();
            toastr('Created Successfully.', 'success');
        } catch (\Throwable $th) {
            DB::rollBack();
            $msg = $th->getMessage() . ' in file ' . $th->getFile() . ' on line ' . $th->getLine();
            // dd($msg);
            toastr($msg, 'error');
        }
        return redirect()->back();
    }

    public function getEmployeesByOrganization(Request $request)
    {
        $employees = Employee::where('organization_id', $request->org_id)
            ->where('status', 1)
            ->whereHas('payrollRelatedDetailModel', function ($query) {
                return $query->where('contract_type', "!=", '12');
            })
            ->orderBy('first_name', 'asc')->get()
            ->mapWithKeys(function ($emp) {
                return [$emp->id => $emp->full_name . ' :: ' . $emp->employee_code];
            })
            ->toArray();
        return response()->json($employees);
    }
}