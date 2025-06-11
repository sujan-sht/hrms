<?php

namespace App\Modules\Api\Http\Controllers\Leave;

use App\Modules\Api\Http\Controllers\ApiController;
use App\Modules\Api\Service\Leave\LeaveService;
use App\Modules\Api\Transformers\OrganizationResource;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\EmployeeLeave;
use App\Modules\LeaveYearSetup\Entities\LeaveYearSetup;
use App\Modules\LeaveYearSetup\Repositories\LeaveYearSetupRepository;
use App\Modules\Leave\Entities\EmployeeLeaveOpening;
use App\Modules\Leave\Entities\Leave;
use App\Modules\Leave\Entities\LeaveType;
use App\Modules\Leave\Repositories\LeaveTypeRepository;
use App\Modules\Organization\Entities\Organization;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LeaveSummaryController extends ApiController
{
    public function getOrganizationList()
    {
        try {
            $userModel = auth()->user();
            $organizationId = optional($userModel->userEmployer)->organization_id;
            $data['organization'] = new OrganizationResource(Organization::find($organizationId));

            return  $this->respond([
                'status' => true,
                'data' => $data
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    public function getLeaveYearList()
    {
        try {
            $leaveYearList = (new LeaveYearSetupRepository)->getLeaveYearList();
            $data['leaveYearList'] = setObjectIdAndName($leaveYearList);

            return  $this->respond([
                'status' => true,
                'data' => $data
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }
    
    public function index(Request $request)
    {
        $filter = $request->all();
        $userModel = auth()->user();
       
        // $data['employeeLeaveSummaries'] = $employees;
        try {
            $id = optional($userModel->userEmployer)->organization_id;
            $leave_year_id = getCurrentLeaveYearId();
            if(isset($filter['leave_year_id'])){
                $leave_year_id = $filter['leave_year_id'];
            }
            $leaveService = new LeaveService();
            $data['leaveTypes'] = $leaveType = $leaveService->leaveTypeListWithFilter($id,$leave_year_id);
    
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
    
            $employees = $query->select('id','first_name','middle_name','last_name')->paginate($limit = 10);
            $result = $employees->setCollection($employees->getCollection()->map(function ($emp) use ($leave_year_id, $id,$leaveType) {
                // $leaveTypeQuery = LeaveType::query();
                // $leaveType = $leaveTypeQuery->get();
                $leaveOpening = [];
                $leaveRemaining = [];
    
                foreach ($leaveType as $lType) {
                    $empLeaveOpening[$lType->id] = EmployeeLeaveOpening::getLeaveOpening($leave_year_id, $id, $emp->id, $lType->id) ?? 0;
                    $empLeaveRemaining[$lType->id] = optional(EmployeeLeave::getLeaveRemaining($leave_year_id, $emp->id, $lType->id))->leave_remaining ?? 0;
                    $leaveDetail[$lType->id] = [
                        'leaveOpening' => $empLeaveOpening[$lType->id],
                        'leaveRemaining' => $empLeaveRemaining[$lType->id]
                    ];
                    // $leaveOpening[$lType->id] = EmployeeLeaveOpening::getLeaveOpening($leave_year_id, $id, $emp->id, $lType->id) ?? 0;
                    // $leaveRemaining[$lType->id] = optional(EmployeeLeave::getLeaveRemaining($leave_year_id, $emp->id, $lType->id))->leave_remaining ?? 0;
                }
                $emp->leaveDetail =  setObjectIdAndName($leaveDetail);
    
                // $emp->leaveOpening = setObjectIdAndName($leaveOpening);
                // $emp->leaveRemaining = setObjectIdAndName($leaveRemaining);
                return $emp;
            }));
            // dd($employees);
            $data['employeeLeaveSummaries'] = $employees;

            return  $this->respond([
                'status' => true,
                'data' => $data
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    public function getLeaveCount(Request $request){
        try {
            $userModel = auth()->user();
            $leave_year_id = getCurrentLeaveYearId();
            if(isset($request->leave_year_id)){
                $leave_year_id = $request->leave_year_id;
            }
            $data['leaveRequest'] = Leave::whereHas('leaveTypeModel', function ($qry) use ($leave_year_id) {
                $qry->where('leave_year_id', $leave_year_id);
            })->where('employee_id',$userModel->emp_id)->where('organization_id',optional($userModel->userEmployer)->organization_id)->count();
            $data['pendingLeave'] = Leave::whereHas('leaveTypeModel', function ($qry) use ($leave_year_id) {
                $qry->where('leave_year_id', $leave_year_id);
            })->where('employee_id',$userModel->emp_id)->where('organization_id',optional($userModel->userEmployer)->organization_id)->where('status',1)->count();
            $data['approvedLeave'] = Leave::whereHas('leaveTypeModel', function ($qry) use ($leave_year_id) {
                $qry->where('leave_year_id', $leave_year_id);
            })->where('employee_id',$userModel->emp_id)->where('organization_id',optional($userModel->userEmployer)->organization_id)->where('status',3)->count();
            $data['declinedLeave'] = Leave::whereHas('leaveTypeModel', function ($qry) use ($leave_year_id) {
                $qry->where('leave_year_id', $leave_year_id);
            })->where('employee_id',$userModel->emp_id)->where('organization_id',optional($userModel->userEmployer)->organization_id)->where('status',4)->count();
            return  $this->respond([
                'status' => true,
                'data' => $data
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }
}
