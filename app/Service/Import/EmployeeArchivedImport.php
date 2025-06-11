<?php

namespace App\Service\Import;

use App\Modules\Branch\Entities\Branch;
use App\Modules\Dropdown\Entities\Dropdown;
use App\Modules\Dropdown\Entities\Field;
use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\EmployeeDayOff;
use App\Modules\Employee\Entities\EmployeePayrollRelatedDetail;
use App\Modules\Organization\Entities\Organization;
use App\Modules\Setting\Entities\Department;
use App\Modules\Setting\Entities\Designation;
use App\Modules\Setting\Entities\Level;
use Illuminate\Support\Facades\DB;
use Yoeunes\Toastr\Facades\Toastr;

class EmployeeArchivedImport implements ImportInterface
{
    // protected $dropdown;

    // public function __construct(
    //     DropdownInterface $dropdown
    // ) {
    //     $this->dropdown = $dropdown;
    // }

    public function import($array)
    {
        try {
            foreach ($array as $data) {
                $inputData = [
                    'first_name' => $data[1] ?? null,
                    'middle_name' => $data[2] ?? null,
                    'last_name' => $data[3] ?? null,
                    'dayoff' => 'Saturday',
                    'mobile' => $data[8] ?? null,
                    'phone' => $data[9] ?? null,
                    'permanentaddress' => $data[10] ?? null,
                    'permanentmunicipality_vdc' => $data[11] ?? null,
                    'job_title' => $data[12] ?? null,
                    'personal_email' => $data[13] ?? null,
                    'official_email' => $data[14] ?? null,
                    'citizenship_no' => $data[22] ?? null,
                    'probation_end_date' => $data[23] ?? null,
                    'status'=>0
                ];
                
                $branchData = Branch::where('name', $data['17'])->first();
                if (!$branchData) {
                    continue;
                }

                $inputData['organization_id'] = optional(Organization::where('name', $data[15])->first())->id;
                if($inputData['organization_id']){
                    $inputData['branch_id'] = optional(Branch::where('name', $data[17])->where('organization_id', $inputData['organization_id'])->first())->id;
                }
                if (Employee::where('employee_code', $data[0])->exists()) {
                    // $employee = Employee::where('employee_code', $data[0])->first();
                    // if (!Employee::where('biometric_id', $data[16])->exists()) {
                    //     $inputData['biometric_id'] =  $data[16] ?? null;
                    // }
                    // $employee->update($inputData);
                    // Employee::where('employee_code', $data[0])->update($inputData);
                    continue;
                } else {

                    if (!is_null($data[0])) {

                        if (Employee::where('biometric_id', $data[16])->whereNotNull('biometric_id')->exists()) {
                            continue;
                        }
                        $inputData['employee_code'] = $data[0] ?? null;
                        // $inputData['gender'] = optional(Dropdown::whereRaw('LOWER(`dropvalue`) LIKE ?', [strtolower($data[4])])->first())->id??null;
                        $inputData['nepali_join_date'] =  $data[6] ?? null;
                        $inputData['join_date'] =  date_converter()->nep_to_eng_convert($inputData['nepali_join_date']);
                        $inputData['biometric_id'] =  $data[16] ?? null;
                        $inputData['nep_dob'] =  ($data[7]) ?? null;
                        $inputData['dob'] =  date_converter()->nep_to_eng_convert($inputData['nep_dob']);
                        $inputData['branch_id'] = $branchData->id;

                        $dropdownArray = [
                            'gender' => ['type' => 'gender', 'value' => $data[4] ?? null],
                            // 'department' => ['type' => 'department_id', 'value' => $data[18] ?? null],
                            // 'level' => ['type' => 'level_id', 'value' => $data[19] ?? null],
                            // 'designation' => ['type' => 'designation_id', 'value' => $data[20] ?? null],
                            'marital_status' => ['type' => 'marital_status', 'value' => $data[21] ?? null],
                        ];

                        if(isset($data[18])){
                            $department = Department::where('title', trim($data[18]))->first();
                            if(isset($department)){
                                $inputData['department_id'] = $department->id;
                            }
                        }

                        if(isset($data[19])){
                            $level = Level::where('title', trim($data[19]))->first();
                            if(isset($level)){
                                $inputData['level_id'] = $level->id;
                            }
                        }

                        if(isset($data[20])){
                            $designation = Designation::where('title', trim($data[20]))->first();
                            if(isset($designation)){
                                $inputData['designation_id'] = $designation->id;
                            }
                        }
        

                        foreach ($dropdownArray as $key => $dropdown) {
                            $inputData[$dropdown['type']] = optional(Dropdown::whereRaw('LOWER(`dropvalue`) LIKE ?', [strtolower(trim($dropdown['value'], ' '))])->first())->id;

                            if (is_null($inputData[$dropdown['type']])) {
                                $fieldId = optional(Field::where('slug', '=', $key)->first())->id;
                                if ($fieldId) {
                                    $dropDownModel = Dropdown::create([
                                        'fid' => $fieldId,
                                        'dropvalue' => $dropdown['value']
                                    ]);
                                    $inputData[$dropdown['type']] = $dropDownModel->id;
                                }
                            }
                        }


                        $employee = Employee::create($inputData);
                        if ($employee) {
                            // save employee timeline
                            $timelineData['employee_id'] = $employee->id;
                            $timelineData['date'] = $employee->join_date;
                            $timelineData['title'] = "New Join";
                            $timelineData['description'] = "Join " . optional($employee->organizationModel)->name;
                            $timelineData['icon'] = "icon-user";
                            $timelineData['color'] = "primary";
                            $timelineData['reference'] = "employee";
                            $timelineData['reference_id'] = $employee->id;
                            Employee::saveEmployeeTimelineData($employee->id, $timelineData);
                        }

                        if (isset($data[5]) && !empty($data[5])) {
                            $dayOff = explode(',', $data[5]);
                            foreach ($dayOff as $key => $value) {
                                $employeDayOff = [
                                    'day_off' => preg_replace('/\s+/', '', $value),
                                    'employee_id' => $employee->id
                                ];
                                EmployeeDayOff::create($employeDayOff);
                            }
                        }
                        if (isset($data[23]) && !empty($data[23])) {
                            $employeePayrollRelatedDetail = [
                                'probation_end_date' => $data[23],
                                'employee_id' => $employee->id
                            ];
                            EmployeePayrollRelatedDetail::create($employeePayrollRelatedDetail);
                        }
                    }
                }
            }
            toastr()->success('Bulk Upload succesfully');
        } catch (\Exception $e) {
            toastr()->error('Something went wrong!!');
        }
    }
}
