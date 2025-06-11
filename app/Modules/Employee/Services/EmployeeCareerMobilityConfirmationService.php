<?php

namespace App\Modules\Employee\Services;

use App\Modules\Branch\Entities\Branch;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\EmployeeCarrierMobilityConfirmation;
use App\Modules\Leave\Entities\LeaveType;
use App\Modules\Setting\Entities\Department;
use App\Modules\Setting\Entities\Designation;
use Illuminate\Http\Request;

class EmployeeCareerMobilityConfirmationService
{


    public $request;

    public function  __construct(Request $request)
    {
        $this->request = $request;
    }


    public function setConfirmationData(Employee $employee)
    {
        $request = $this->request;
        return [
            'employee_id' => $request->employee_id,
            'letter_issue_date' => $request->letter_issue_date,
            'confirmation_date' => $request->confirmation_date,
            'contract_type' => $request->contract_type,
            'contract_start_date' => $request->contract_start_date ?? null,
            'contract_end_date' => $request->contract_end_date ?? null,
            'designation_id' => $employee->designation_id,
            'branch_id' => $employee->branch_id,
            'department_id' => $employee->department_id
        ];
    }

    public function setTimeLineData(Employee $employee, EmployeeCarrierMobilityConfirmation $employeeCareerMobilityConfirmation)
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
                !is_null(optional($employee->payrollRelatedDetailModel)->contract_type) ? LeaveType::CONTRACT[optional($employee->payrollRelatedDetailModel)->contract_type] : null,
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

        // Combine changes into the description
        $description = empty($changes) ? null : json_encode($changes);
        // Return the timeline data
        return [
            'employee_id' => $this->request->employee_id,
            'event_type' => 'confirmation',
            'title' => 'Confirmation',
            'icon' => 'icon-truck',
            'color' => 'secondary',
            'career_mobility_type' => 'App\Modules\Employee\Entities\EmployeeCarrierMobilityConfirmation',
            'career_mobility_type_id' => $employeeCareerMobilityConfirmation->id,
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
            ])
        ];
    }


    public function updateEmployeeDetails(Employee $employee)
    {
        $fieldsToUpdate = [
            'designation_id' => 'designation_id',
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
