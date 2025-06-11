<?php

namespace App\Modules\Employee\Http\Controllers;

use App\Modules\Branch\Repositories\BranchRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\EmployeeCareerMobilityAppointment;
use App\Modules\Employee\Entities\EmployeeCarrierMobility;
use App\Modules\Employee\Entities\EmployeeCarrierMobilityAppointment;
use App\Modules\Employee\Entities\NewEmployeeCareerMobilityTimeline;
use App\Modules\Employee\Repositories\EmployeeRepository;
use App\Modules\Employee\Services\EmployeeCareerMobilityAppointmentService;
use App\Modules\Leave\Entities\LeaveType;
use App\Modules\Organization\Repositories\OrganizationRepository;
use App\Modules\Setting\Repositories\DepartmentRepository;
use App\Modules\Setting\Repositories\DesignationRepository;
use App\Modules\Setting\Repositories\LevelRepository;
use Illuminate\Support\Facades\DB;

class EmployeeCareerMobilityAppointmentController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->all();
        $employee = new EmployeeRepository();
        $branch = new BranchRepository();
        $data['organizationList'] = (new OrganizationRepository())->getList();
        $data['branchList'] = $branch->getList();
        $data['employeeList'] = $employee->getList();

        $data['departmentList'] = (new DepartmentRepository())->getList();
        $data['levelList'] = (new LevelRepository())->getList();
        $data['designationList']  = (new DesignationRepository())->getList();


        $data['typeList'] = EmployeeCarrierMobility::typeList();
        $data['probationStatusList'] = EmployeeCarrierMobility::probationStatusList();
        $data['payrollChangeList'] = EmployeeCarrierMobility::payrollChangeList();
        $data['contractTypeList'] = LeaveType::CONTRACT;
        unset($data['contractTypeList'][100]);

        $data['employee'] = [];
        if (!empty($filter)) {
            $data['employee'] = $employee = $employee->find($filter['employee_id']);
            $data['filteredBranchList'] = $branch->branchListOrganizationwise($employee->organization_id);
            $data['remainingDesignation'] = self::getRemainingItems($data['designationList'], $employee->designation_id);;
            $data['remainingDepartment'] = self::getRemainingItems($data['departmentList'], $employee->department_id);
            $data['remainingBranch'] = self::getRemainingItems($data['branchList'], $employee->branch_id);
        }


        return view('employee::employee.carrier-mobility.appointment.index', $data);
    }

    public function create(Request $request) {}
    public function edit(Request $request) {}
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $employeeCareerMobilityAppointmentService = new EmployeeCareerMobilityAppointmentService($request);
            $employee = Employee::findOrFail($request->employee_id);

            $appointmentData = $employeeCareerMobilityAppointmentService
                ->setAppointmentData();
            $employeeCareerMobilityAppointment =  EmployeeCareerMobilityAppointment::create($appointmentData);

            $timelineOfAppointmentData = $employeeCareerMobilityAppointmentService
                ->setTimeLineData($employee, $employeeCareerMobilityAppointment);
            NewEmployeeCareerMobilityTimeline::create($timelineOfAppointmentData);

            $employeeCareerMobilityAppointmentService->updateEmployeeDetails($employee);
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

    public static function getRemainingItems($list, $keyToExclude)
    {
        return $list->filter(function ($value, $key) use ($keyToExclude) {
            return $key !== $keyToExclude;
        });
    }
}
