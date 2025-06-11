<?php

namespace App\Modules\Attendance\Http\Controllers;

use App\Helpers\DateTimeHelper;
use App\Modules\Attendance\Entities\DivisionAttendanceReport;
use App\Modules\Attendance\Entities\DivisionAttendanceRoleSetup;
use App\Modules\Attendance\Repositories\AttendanceInterface;
use App\Modules\Attendance\Repositories\AttendanceLogInterface;
use App\Modules\Attendance\Repositories\AttendanceReportInterface;
use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Setting\Repositories\DepartmentInterface;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class WebAttendanceAllocationController extends Controller
{
    protected $attendance;
    protected $organization;
    protected $dropdown;
    protected $branch;
    protected $employee;
    protected $department;





    public function __construct(
        AttendanceLogInterface $attendance,
        OrganizationInterface $organization,
        DropdownInterface $dropdown,
        BranchInterface $branch,
        EmployeeInterface $employee,
        DepartmentInterface $department
    ) {
        $this->attendance = $attendance;
        $this->organization = $organization;
        $this->dropdown = $dropdown;
        $this->branch = $branch;
        $this->employee = $employee;
        $this->department = $department;
    }

    public function allocationList()
    {
        $data['webAtdAllocations'] = $this->attendance->allocationList();
        return view('attendance::web-attendance-allocation.index', $data);
    }    
    public function allocateForm()
    {
        $data['isEdit'] = false;
        $data['organizationList'] = $this->organization->getList();
        $data['branchList'] = $this->branch->getList();
        $data['departmentList'] = $this->department->getList();
        $data['employeeList'] = [];
        return view('attendance::web-attendance-allocation.create', $data);
    }

    public function filterOrgDepartmentwise(Request $request) {
        try {
            $employees = Employee::getEmployeesOrganizationDepartmentwise($request->organization_id, $request->department_id, $request->branch_id);
            return json_encode($employees);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function checkExists(Request $request)
    {
        $data = $request->all();
        try {
            return $this->attendance->checkAllocationExists($data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function allocate(Request $request)
    {
        $inputData = $request->except('_token');
        try {
            $this->attendance->webAtdAllocation($inputData);
            toastr()->success('Web Attendance Allocated Successfully !!!');
        } catch (\Throwable $th) {
            toastr()->error('Something went wrong !!!');
        }
        return redirect()->route('webAttendance.allocationList');
    }

    public function editAllocation($id)
    {
        $data['webAtdAllocation'] = $this->attendance->findAllocation($id);
        $data['isEdit'] = true;

        $data['organizationList'] = $this->organization->getList();
        $data['branchList'] = $this->branch->getList();
        $data['departmentList'] = $this->department->getList();
        $data['employeeList'] = $this->employee->getList();
        return view('attendance::web-attendance-allocation.edit', $data);
    }

    public function updateAllocation(Request $request, $id)
    {
        $data = $request->except('_token');
        try {
            $this->attendance->updateAllocation($id,$data);
            toastr('Web Attendance Allocation updated successfully!', 'success');
        } catch (Exception $e) {
            toastr('Something went wrong!', 'error');
        }
        return redirect()->route('webAttendance.allocationList');
    }

    public function destroyAllocation($id) {
        try {
            $this->attendance->destroyAllocation($id);
            toastr()->success('Web Attendance Allocation Deleted Successfully');
        } catch (\Throwable $th) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect()->back();
    }

    public function cloneDay(Request $request)
    {
        $data = $request->all();
        $count = $data['count'] + 1;
        $employeeList = [];
        $departmentList = $this->department->getList();

        return response()->json([
            'data' => view('attendance::web-attendance-allocation.partial.clone', compact(['count', 'departmentList', 'employeeList']))->render(),
        ]);
    }

}
