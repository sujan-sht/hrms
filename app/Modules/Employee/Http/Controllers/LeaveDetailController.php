<?php

namespace App\Modules\Employee\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Employee\Entities\Employee;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Leave\Repositories\LeaveInterface;
use App\Modules\Leave\Repositories\LeaveTypeInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;

class LeaveDetailController extends Controller
{
    protected $leave;
    protected $employee;

    protected $leaveType;

    public function __construct(LeaveInterface $leave, LeaveTypeInterface $leaveType, EmployeeInterface $employee)
    {
        $this->leave = $leave;
        $this->employee = $employee;

        $this->leaveType = $leaveType;
    }

    public function leaveReport(Request $request)
    {
        $data['employeeModel'] = Employee::find($request->emp_id);
        $data['leave_report'] = $this->leave->getEmployeeLeaves($request->emp_id);
        return view('employee::employee.partial.ajaxlayouts.leaveReportDetailTable', $data)->render();
    }

    public function leaveRemaining(Request $request)
    {
        $data['employee_leave_details'] = $this->employee->employeeLeaveDetails($request->emp_id);
        return view('employee::employee.partial.ajaxlayouts.leaveRemainingDetailTable', $data)->render();
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('employee::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('employee::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('employee::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
