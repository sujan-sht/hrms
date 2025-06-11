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
use App\Modules\Employee\Entities\EmployeeCareerMobilityTransfer;
use App\Modules\Organization\Repositories\OrganizationRepository;
use App\Modules\Employee\Entities\EmployeeCarrierMobilityPromotion;
use App\Modules\Employee\Entities\NewEmployeeCareerMobilityTimeline;
use App\Modules\Employee\Entities\EmployeeCarrierMobilityAppointment;
use App\Modules\Employee\Services\EmployeeCareerMobilityPromotionService;

class EmployeeCareerMobilityPromotionController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->all();
        $employee = new EmployeeRepository();
        $branch = new BranchRepository();
        $data['organizationList'] = (new OrganizationRepository())->getList();
        $data['employeeList'] = Employee::getOrganizationWisePermanentEmployees($filter);
        $data['departmentList'] = (new DepartmentRepository())->getList();
        $data['levelList'] = (new LevelRepository())->getList();
        $data['designationList'] = (new DesignationRepository())->getList();


        $data['typeList'] = EmployeeCarrierMobility::typeList();
        $data['contractTypes'] = LeaveType::CONTRACT;
        unset($data['contractTypes'][100]);


        $data['probationStatusList'] = EmployeeCarrierMobility::probationStatusList();
        $data['payrollChangeList'] = EmployeeCarrierMobility::payrollChangeList();
        $data['employee'] = [];
        if (!empty($filter)) {
            $data['employee'] = $employee = $employee->find($filter['employee_id']);
            $data['filteredBranchList'] = $branch->branchListOrganizationwise($employee->organization_id);
            $data['employeeCareerMobilityPromotion'] = EmployeeCarrierMobilityPromotion::where('employee_id', $filter['employee_id'])->first();
            $employeeBranchId = $data['employee']->branch_id;
            $data['branchList'] = [];
            foreach ($branch->getList() as $branchId => $branchDetails) {
                if ($branchId !== $employeeBranchId) {
                    $data['branchList'][$branchId] = $branchDetails;
                }
            }

            $data['remainingDesignation'] = $data['designationList']->filter(function ($value, $key) use ($filter, $employee) {
                return $key !== $employee->find($filter['employee_id'])->designation_id;
            });

            $data['remainingDepartment'] = $data['departmentList']->filter(function ($value, $key) use ($filter, $employee) {
                return $key !== $employee->find($filter['employee_id'])->department_id;
            });
        }

        return view('employee::employee.carrier-mobility.promotion.index', $data);
    }

    public function create(Request $request) {}
    public function edit(Request $request) {}
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $employee = Employee::findOrFail($request->employee_id);

            $employeeCareerMobilityPromotionService = new EmployeeCareerMobilityPromotionService($request);

            $data = $employeeCareerMobilityPromotionService->setPromotionData();
            $employeeCarrierMobilityPromotion = EmployeeCarrierMobilityPromotion::create($data);

            $timelineData = $employeeCareerMobilityPromotionService->setTimeLineData($employee, $employeeCarrierMobilityPromotion);
            NewEmployeeCareerMobilityTimeline::create($timelineData);

            $timelineData['date'] = now();
            $timelineData['reference'] = 'EmployeeCarrierMobilityPromotion';
            $timelineData['reference_id'] = $timelineData['career_mobility_type_id'];
            $timelineData['carrier_mobility_id'] = $timelineData['career_mobility_type_id'];
            EmployeeTimeline::create($timelineData);

            $employeeCareerMobilityPromotionService->updateEmployeeDetails($employee);

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