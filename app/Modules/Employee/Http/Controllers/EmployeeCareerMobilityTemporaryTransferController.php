<?php

namespace App\Modules\Employee\Http\Controllers;

use App\Modules\Branch\Repositories\BranchRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\EmployeeCareerMobilityTransfer;
use App\Modules\Employee\Entities\EmployeeCarrierMobility;
use App\Modules\Employee\Entities\EmployeeCarrierMobilityAppointment;
use App\Modules\Employee\Entities\EmployeeCarrierMobilityTemporaryTransfer;
use App\Modules\Employee\Entities\NewEmployeeCareerMobilityTimeline;
use App\Modules\Employee\Repositories\EmployeeRepository;
use App\Modules\Employee\Services\EmployeeCareerMobilityTemporaryTransferService;
use App\Modules\Organization\Repositories\OrganizationRepository;
use App\Modules\Setting\Repositories\DepartmentRepository;
use App\Modules\Setting\Repositories\DesignationRepository;
use App\Modules\Setting\Repositories\LevelRepository;
use Illuminate\Support\Facades\DB;

class EmployeeCareerMobilityTemporaryTransferController extends Controller
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
            $data['employeeCareerMobilityTemporaryTransfer'] = EmployeeCarrierMobilityTemporaryTransfer::where('employee_id', $filter['employee_id'])->first();
            $employeeBranchId = $data['employee']->branch_id;
            $data['filteredBranchList'] = [];
            foreach ($branch->getList() as $branchId => $branchDetails) {
                if ($branchId !== $employeeBranchId) {
                    $data['filteredBranchList'][$branchId] = $branchDetails;
                }
            }
        }

        return view('employee::employee.carrier-mobility.temporary-transfer.index', $data);
    }

    public function create(Request $request) {}
    public function edit(Request $request) {}
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $employee = Employee::findOrFail($request->employee_id);

            $employeeCareerMobilityTemporaryTransferService = new EmployeeCareerMobilityTemporaryTransferService($request);

            $data = $employeeCareerMobilityTemporaryTransferService->setTemporaryTransferData();
            $employeeCarrierMobilityTemporaryTransfer = EmployeeCarrierMobilityTemporaryTransfer::create($data);

            $timelineData = $employeeCareerMobilityTemporaryTransferService->setTimeLineData($employee, $employeeCarrierMobilityTemporaryTransfer);
            NewEmployeeCareerMobilityTimeline::create($timelineData);


            $employeeCareerMobilityTemporaryTransferService->updateEmployeeDetails($employee);

            DB::commit();
            toastr('Created Successfully.', 'success');
        } catch (\Throwable $th) {
            DB::rollBack();
            $msg = $th->getMessage() . ' in file ' . $th->getFile() . ' on line ' . $th->getLine();
            dd($msg);
            toastr($msg, 'error');
        }
        return redirect()->back();
    }

    public function update(Request $request) {}
    public function destroy(Request $request) {}
}
