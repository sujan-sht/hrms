<?php

namespace App\Modules\Employee\Services;

use App\Modules\Branch\Entities\Branch;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\EmployeeCareerMobilityTransfer;
use App\Modules\Leave\Entities\LeaveType;
use App\Modules\Setting\Entities\Department;
use App\Modules\Setting\Entities\Designation;
use Illuminate\Http\Request;

class EmployeeCareerMobilityTransferService
{


    public $request;

    public function  __construct(Request $request)
    {
        $this->request = $request;
    }


    public function setTransferData(Employee $employee)
    {
        $request = $this->request;
        return [
            'employee_id' => $request->employee_id,
            'letter_issue_date' => $request->letter_issue_date  ?? null,
            'transfer_date' => $request->transfer_date  ?? null,
            'job_title' => $request->job_title  ?? null,
            'effective_date' => $request->effective_date  ?? null,
            'branch_transfer_id' => $request->branch_transfer_id  ?? null
        ];
    }

    public function setTimeLineData(Employee $employee, EmployeeCareerMobilityTransfer $employeeCareerMobilityTransfer)
    {
        $changes = [];

        // Track Branch Changes
        if ($employee->branch_id !==  !is_null($this->request->branch_id)) {
            $changes[] = sprintf(
                "Branch changed from '%s' to '%s'.",
                optional($employee->branchModel)->name,
                optional(Branch::find($this->request->branch_id))->name
            );
        }

        // Track Branch Changes
        if (!is_null($employee->job_title) !==  !is_null($this->request->job_title)) {
            $changes[] = sprintf(
                "Job title changed from '%s' to '%s'.",
                !is_null($employee->job_title) ? $employee->job_title : null,
                !is_null($this->request->job_title) ? $this->request->job_title : null
            );
        }

        // Combine changes into the description
        $description = empty($changes) ? null : json_encode($changes);
        // Return the timeline data
        return [
            'employee_id' => $this->request->employee_id,
            'event_type' => 'transfer',
            'title' => 'Transfer',
            'icon' => 'icon-truck',
            'color' => 'secondary',
            'career_mobility_type' => 'App\Modules\Employee\Entities\EmployeeCareerMobilityTransfer',
            'career_mobility_type_id' => $employeeCareerMobilityTransfer->id,
            'event_date' => now()->format('Y-m-d'),
            'description' => $description,
            'remarks' => $this->request->remarks,
            'branch_log' => json_encode([
                'old' => [
                    'branch_id' => $employee->branch_id,
                ],
                'new' => [
                    'branch_id' => $this->request->branch_id ?? null,
                ]
            ]),
            'job_title_log' => json_encode([
                'old' => [
                    'job_title' => $employee->job_title,
                ],
                'new' => [
                    'job_title' => $this->request->job_title ?? null,
                ]
            ])

        ];
    }


    public function updateEmployeeDetails(Employee $employee)
    {
        $fieldsToUpdate = [
            'branch_id' => 'branch_id',
            'job_title' => 'job_title',
        ];
        foreach ($fieldsToUpdate as $requestField => $employeeField) {
            if (!is_null($this->request->$requestField)) {
                $employee->update([$employeeField => $this->request->$requestField]);
            }
        }
    }
}
