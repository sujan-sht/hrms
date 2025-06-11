<?php

namespace App\Modules\Employee\Services;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\EmployeeCarrierMobilityProbationaryPeriod;
use Illuminate\Http\Request;

class EmployeeCareerMobilityProbationaryPeriodService
{


    public $request;

    public function  __construct(Request $request)
    {
        $this->request = $request;
    }


    public function setProbationaryPeriodData()
    {
        $request = $this->request;
        $contract_type = optional(Employee::with('payrollRelatedDetailModel')->find($request->employee_id)->payrollRelatedDetailModel)->contract_type;
        return [
            'employee_id' => $request->employee_id,
            'letter_issue_date' => $request->letter_issue_date  ?? null,
            'contract_type' => $request->contract_type ?? $contract_type,
            'letter_issue_date' => $request->letter_issue_date  ?? null,
            'extension_from_date' => $request->extension_from_date  ?? null,
            'extension_till_date' => $request->extension_till_date  ?? null,
            'remarks' => $request->remarks ?? null
        ];
    }

    public function setTimeLineData(EmployeeCarrierMobilityProbationaryPeriod $employeeCarrierMobilityProbationaryPeriod)
    {
        $changes = [];
        // Combine changes into the description
        $description = empty($changes) ? null : json_encode($changes);
        // Return the timeline data
        return [
            'employee_id' => $this->request->employee_id,
            'event_type' => 'probationary_period',
            'title' => 'Probationary Period',
            'icon' => 'icon-truck',
            'color' => 'secondary',
            'career_mobility_type' => 'App\Modules\Employee\Entities\EmployeeCarrierMobilityProbationaryPeriod',
            'career_mobility_type_id' => $employeeCarrierMobilityProbationaryPeriod->id,
            'event_date' => now()->format('Y-m-d'),
            'description' => $description,
            'remarks' => $this->request->remarks
        ];
    }

    public function updateEmployeeExtensionDate(Employee $employee)
    {
        // dd($this->request);
        // dd($employee->payrollRelatedDetailModel, $this->request);
        if ($employee->payrollRelatedDetailModel->contract_type == '11') {
            return optional($employee->payrollRelatedDetailModel)->update([
                'contract_start_date' => $this->request->extension_from_date,
                'contract_end_date' => $this->request->extension_till_date,
            ]);
        } else {
            return optional($employee->payrollRelatedDetailModel)->update([
                'probation_start_date' => $this->request->extension_from_date,
                'probation_end_date' => $this->request->extension_till_date,
            ]);
        }
    }
}