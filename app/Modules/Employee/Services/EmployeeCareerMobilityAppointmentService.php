<?php

namespace App\Modules\Employee\Services;

use App\Modules\Branch\Entities\Branch;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\EmployeeCareerMobilityAppointment;
use App\Modules\Leave\Entities\LeaveType;
use App\Modules\Setting\Entities\Department;
use App\Modules\Setting\Entities\Designation;
use Illuminate\Http\Request;

class EmployeeCareerMobilityAppointmentService
{


    public $request;

    public function  __construct(Request $request)
    {
        $this->request = $request;
    }


    public function setAppointmentData()
    {
        $request = $this->request;
        return [
            'employee_id' => $request->employee_id,
            'letter_issue_date' => $request->letter_issue_date ?? null,
            'appointment_date' => $request->appointment_date ?? null,
            'effective_date' => $request->effective_date ?? null,
            'contract_type' => $request->contract_type ?? null,
            'from_date' => $request->contract_start_date ?? null,
            'to_date' => $request->contract_end_date ?? null,
            'designation_id' => $request->designation_id ?? null,
            'branch_id' => $request->branch_id ?? null,
            'department_id' => $request->department_id ?? null,
        ];
    }

    public function setTimeLineData(Employee $employee, EmployeeCareerMobilityAppointment $employeeCareerMobilityAppointment)
    {
        $changes = [];
        // Track Contract Type Changes
        if (
            optional($employee->payrollRelatedDetailModel)->contract_type !== $this->request->contract_type ||
            optional($employee->payrollRelatedDetailModel)->contract_start_date !== $this->request->contract_start_date ||
            optional($employee->payrollRelatedDetailModel)->contract_end_date !== $this->request->contract_end_date
        ) {
            $changes[] = sprintf(
                "Contract updated: Type changed from '%s' to '%s', Start Date from '%s' to '%s', End Date from '%s' to '%s'.",
                LeaveType::CONTRACT[optional($employee->payrollRelatedDetailModel)->contract_type],
                !is_null($this->request->contract_type) ? LeaveType::CONTRACT[$this->request->contract_type] : null,
                optional($employee->payrollRelatedDetailModel)->contract_start_date,
                !is_null($this->request->contract_start_date) ? $this->request->contract_start_date : null,
                optional($employee->payrollRelatedDetailModel)->contract_end_date,
                !is_null($this->request->contract_end_date) ? $this->request->contract_end_date : null
            );
        }

        // Track Designation Changes
        if ($employee->designation_id !== !is_null($this->request->designation_id)) {
            $changes[] = sprintf(
                "Designation changed from '%s' to '%s'.",
                optional($employee->designation)->title,
                optional(Designation::find($this->request->designation_id))->title ?? null
            );
        }

        // Track Branch Changes
        if ($employee->branch_id !==  !is_null($this->request->branch_id)) {
            $changes[] = sprintf(
                "Branch changed from '%s' to '%s'.",
                optional($employee->branchModel)->name,
                optional(Branch::find($this->request->branch_id))->name
            );
        }

        // Track Sub-Function Changes
        if ($employee->department_id !==  !is_null($this->request->department_id)) {
            $changes[] = sprintf(
                "Sub-Function changed from '%s' to '%s'.",
                optional($employee->department)->title,
                optional(Department::find($this->request->department_id))->title
            );
        }



        // Combine changes into the description
        $description = empty($changes) ? null : json_encode($changes);
        // Return the timeline data
        return [
            'employee_id' => $this->request->employee_id,
            'event_type' => 'appointment',
            'title' => 'Appointment',
            'icon' => 'icon-truck',
            'color' => 'secondary',
            'career_mobility_type' => 'App\Modules\Employee\Entities\EmployeeCareerMobilityAppointment',
            'career_mobility_type_id' => $employeeCareerMobilityAppointment->id,
            'event_date' => now()->format('Y-m-d'),
            'description' => $description,
            'remarks' => $this->request->remarks,
            'contract_type_log' => json_encode([
                'old' => [
                    'contract_type' => optional($employee->payrollRelatedDetailModel)->contract_type,
                    'contract_start_date' => optional($employee->payrollRelatedDetailModel)->contract_start_date,
                    'contract_end_date' => optional($employee->payrollRelatedDetailModel)->contract_end_date,
                ],
                'new' => [
                    'contract_type' => $this->request->contract_type ?? null,
                    'contract_start_date' => $this->request->contract_start_date ?? null,
                    'contract_end_date' => $this->request->contract_end_date ?? null,
                ]
            ]),
            'designation_log' => json_encode([
                'old' => [
                    'designation_id' => $employee->designation_id,
                ],
                'new' => [
                    'designation_id' => $this->request->designation_id ?? null,
                ]
            ]),
            'branch_log' => json_encode([
                'old' => [
                    'branch_id' => $employee->branch_id,
                ],
                'new' => [
                    'branch_id' => $this->request->branch_id ?? null,
                ]
            ]),
            'department_log' => json_encode([
                'old' => [
                    'department_id' => $employee->department_id,
                ],
                'new' => [
                    'department_id' => $this->request->department_id ?? null,
                ]
            ])
        ];
    }


    public function updateEmployeeDetails(Employee $employee)
    {
        $fieldsToUpdate = [
            'designation_id' => 'designation_id',
            'branch_id' => 'branch_id',
            'department_id' => 'department_id',
        ];
        foreach ($fieldsToUpdate as $requestField => $employeeField) {
            if (!is_null($this->request->$requestField)) {
                $employee->update([$employeeField => $this->request->$requestField]);
            }
        }
        $payrollFieldsToUpdate = [
            'contract_type' => 'contract_type',
            'contract_start_date' => 'contract_start_date',
            'contract_end_date' => 'contract_end_date',
        ];
        foreach ($payrollFieldsToUpdate as $requestField => $payrollField) {
            if (!is_null($this->request->$requestField)) {
                $employee->payrollRelatedDetailModel->update([$payrollField => $this->request->$requestField]);
            }
        }
    }
}
