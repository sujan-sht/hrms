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

class EmployeeImport implements ImportInterface
{
    // protected $dropdown;

    // public function __construct(
    //     DropdownInterface $dropdown
    // ) {
    //     $this->dropdown = $dropdown;
    // }

    public function import($array)
    {
        $filteredArray = array_filter($array, function ($subArray) {
            return !empty(array_filter($subArray, function ($value) {
                return !is_null($value);
            }));
        });

        try {
            foreach ($filteredArray as $rowIndex => $data) {
                $rowNumber = $rowIndex + 2;

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
                ];

                $branchData = Branch::where('name', $data['17'])->first();
                if (!$branchData) {
                    continue;
                }

                $inputData['organization_id'] = optional(Organization::where('name', $data[15])->first())->id;
                if ($inputData['organization_id']) {
                    // $inputData['branch_id'] = optional(Branch::where('name', $data[17])->where('organization_id', $inputData['organization_id'])->first())->id;
                    $inputData['branch_id'] = optional(Branch::where('name', $data[17])->first())->id;
                }
                // dd($inputData);
                if (Employee::where('employee_code', $data[0])->exists()) {
                    $employee = Employee::where('employee_code', $data[1])->first();
                    if ($employee) {
                        return [
                            'success' => false,
                            'message' => "Error at Row $rowNumber, Employee code already exist. Uploaded successfully up to Row " . ($rowNumber - 1) . "!!"
                        ];
                    }
                } else {

                    if (!is_null($data[0])) {

                        if (Employee::where('biometric_id', $data[16])->whereNotNull('biometric_id')->exists()) {
                            $employeecode = Employee::where('biometric_id', $data[16])->whereNotNull('biometric_id')->first();
                            if ($employeecode) {
                                return [
                                    'success' => false,
                                    'message' => "Error at Row $rowNumber, Biometric Id already exist. Uploaded successfully up to Row " . ($rowNumber - 1) . "!!"
                                ];
                            }
                        }
                        $inputData['employee_code'] = $data[0];
                        // $inputData['gender'] = optional(Dropdown::whereRaw('LOWER(`dropvalue`) LIKE ?', [strtolower($data[4])])->first())->id??null;
                        $inputData['join_date'] =  $data[6] ?? null;
                        $inputData['nepali_join_date'] =  date_converter()->eng_to_nep_convert($inputData['join_date']);
                        $inputData['biometric_id'] =  $data[16] ?? null;
                        $inputData['dob'] =  ($data[7]) ?? null;
                        $inputData['nep_dob'] =  date_converter()->eng_to_nep_convert($inputData['dob']);
                        $inputData['branch_id'] = $branchData->id;

                        $dropdownArray = [
                            'gender' => ['type' => 'gender', 'value' => $data[4] ?? null],
                            // 'department' => ['type' => 'department_id', 'value' => $data[18] ?? null],
                            // 'level' => ['type' => 'level_id', 'value' => $data[19] ?? null],
                            // 'designation' => ['type' => 'designation_id', 'value' => $data[20] ?? null],
                            'marital_status' => ['type' => 'marital_status', 'value' => $data[21] ?? null],
                        ];

                        if (isset($data[18])) {
                            $department = Department::where('title', trim($data[18]))->first();
                            if (isset($department)) {
                                $inputData['department_id'] = $department->id;
                                $inputData['function_id'] = $department->function_id;
                            }
                        }

                        if (isset($data[19])) {
                            $level = Level::where('title', trim($data[19]))->first();
                            if (isset($level)) {
                                $inputData['level_id'] = $level->id;
                            }
                            if (!$level) {
                                return [
                                    'success' => false,
                                    'message' => "Error at Row $rowNumber, Level does not exist. Uploaded successfully up to Row " . ($rowNumber - 1) . "!!"
                                ];
                            }
                        }

                        if (isset($data[20])) {
                            $designation = Designation::where('title', trim($data[20]))->first();
                            if (isset($designation)) {
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


                        $success = $employee = Employee::create($inputData);
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
            if ($success) {
                return [
                    'success' => true,
                    'message' => "Bulk Upload Completed Successfully!"
                ];
            }
            return [
                'success' => false,
                'message' => "Error at Row $rowNumber, Bulk upload not completed. Uploaded successfull upto $rowNumber-1. !!"
            ];
            return true;
        } catch (\Exception $e) {
            // dd($e->getMessage());
            // toastr($message, 'error');
            return false;
        }
        return true;
    }
}