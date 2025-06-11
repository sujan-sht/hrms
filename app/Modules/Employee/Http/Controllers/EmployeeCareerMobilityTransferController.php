<?php

namespace App\Modules\Employee\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\EmployeeTimeline;
use App\Modules\Branch\Repositories\BranchRepository;
use App\Modules\Setting\Repositories\LevelRepository;
use App\Modules\Employee\Repositories\EmployeeRepository;
use App\Modules\Employee\Entities\EmployeeCarrierMobility;
use App\Modules\Setting\Repositories\DepartmentRepository;
use App\Modules\Setting\Repositories\DesignationRepository;
use App\Modules\Employee\Entities\EmployeeCareerMobilityTransfer;
use App\Modules\Organization\Repositories\OrganizationRepository;
use App\Modules\Employee\Entities\NewEmployeeCareerMobilityTimeline;
use App\Modules\Employee\Entities\EmployeeCarrierMobilityAppointment;
use App\Modules\Employee\Services\EmployeeCareerMobilityTransferService;

class EmployeeCareerMobilityTransferController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->all();
        $employee = new EmployeeRepository();
        $branch = new BranchRepository();
        $data['organizationList'] = (new OrganizationRepository())->getList();
        $data['employeeList'] = $employee->getList();

        $data['departmentList'] = (new DepartmentRepository())->getList();
        $data['levelList'] = (new LevelRepository())->getList();
        $data['designationList'] = (new DesignationRepository())->getList();

        $data['typeList'] = EmployeeCarrierMobility::typeList();
        $data['probationStatusList'] = EmployeeCarrierMobility::probationStatusList();
        $data['payrollChangeList'] = EmployeeCarrierMobility::payrollChangeList();
        $data['employee'] = [];
        if (!empty($filter)) {
            $data['employee'] = $employee = $employee->find($filter['employee_id']);
            $data['branchList'] = $branch->branchListOrganizationwise($employee->organization_id);
            $data['employeeCareerMobilityTransfer'] = EmployeeCareerMobilityTransfer::where('employee_id', $filter['employee_id'])->first();
            $employeeBranchId = $data['employee']->branch_id;
            $data['filteredBranchList'] = [];
            foreach ($branch->getList() as $branchId => $branchDetails) {
                if ($branchId !== $employeeBranchId) {
                    $data['filteredBranchList'][$branchId] = $branchDetails;
                }
            }
        }

        return view('employee::employee.carrier-mobility.transfer.index', $data);
    }

    public function create(Request $request) {}
    public function edit(Request $request) {}
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $employee = Employee::findOrFail($request->employee_id);

            $employeeTransferService = new EmployeeCareerMobilityTransferService($request);

            $data = $employeeTransferService->setTransferData($employee);
            $employeeCareerMobilityTransfer = EmployeeCareerMobilityTransfer::create($data);

            $timelineData = $employeeTransferService->setTimeLineData($employee, $employeeCareerMobilityTransfer);
            NewEmployeeCareerMobilityTimeline::create($timelineData);

            $timelineData['date'] = now();
            $timelineData['reference'] = 'EmployeeCareerMobilityTransfer';
            $timelineData['reference_id'] = $timelineData['career_mobility_type_id'];
            $timelineData['carrier_mobility_id'] = $timelineData['career_mobility_type_id'];
            // dd($timelineData, $request->all());
            EmployeeTimeline::create($timelineData);

            $employeeTransferService->updateEmployeeDetails($employee);

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

    public function update(Request $request) {}
    public function destroy(Request $request) {}
}
