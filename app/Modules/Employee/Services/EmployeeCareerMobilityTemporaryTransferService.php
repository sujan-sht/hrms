<?php

namespace App\Modules\Employee\Services;

use App\Modules\Branch\Entities\Branch;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\EmployeeCarrierMobilityTemporaryTransfer;
use Illuminate\Http\Request;

class EmployeeCareerMobilityTemporaryTransferService
{


    public $request;

    public function  __construct(Request $request)
    {
        $this->request = $request;
    }


    public function setTemporaryTransferData()
    {
        $request = $this->request;
        return [
            'employee_id' => $request->employee_id,
            'branch_id' => $request->branch_id,
            'letter_issue_date' => $request->letter_issue_date  ?? null,
            'transfer_from_date' => $request->transfer_from_date  ?? null,
            'transfer_to_date' => $request->transfer_to_date  ?? null,
            'effective_date' => $request->effective_date  ?? null,
        ];
    }

    public function setTimeLineData(Employee $employee, EmployeeCarrierMobilityTemporaryTransfer $employeeCarrierMobilityTemporaryTransfer)
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

        // Combine changes into the description
        $description = empty($changes) ? null : json_encode($changes);
        // Return the timeline data
        return [
            'employee_id' => $this->request->employee_id,
            'event_type' => 'temporary_transfer',
            'title' => 'Temporary Transfer',
            'icon' => 'icon-truck',
            'color' => 'secondary',
            'career_mobility_type' => 'App\Modules\Employee\Entities\EmployeeCarrierMobilityTemporaryTransfer',
            'career_mobility_type_id' => $employeeCarrierMobilityTemporaryTransfer->id,
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
            ])
        ];
    }


    public function updateEmployeeDetails(Employee $employee)
    {
        $fieldsToUpdate = [
            'branch_id' => 'branch_id',
        ];
        foreach ($fieldsToUpdate as $requestField => $employeeField) {
            if (!is_null($this->request->$requestField)) {
                $employee->update([$employeeField => $this->request->$requestField]);
            }
        }
    }
}
