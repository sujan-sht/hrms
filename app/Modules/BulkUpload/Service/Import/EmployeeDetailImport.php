<?php

namespace App\Modules\BulkUpload\Service\Import;

use App\Modules\Dropdown\Repositories\DropdownRepository;
use App\Modules\Dropdown\Repositories\FieldRepository;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\EmployeeDayOff;
use App\Modules\Employee\Entities\EmployeePayrollRelatedDetail;
use App\Modules\Employee\Entities\FamilyDetail;
use App\Modules\Holiday\Entities\Holiday;
use App\Modules\Setting\Entities\Department;
use App\Modules\Setting\Entities\Designation;
use App\Modules\Setting\Entities\Level;
use PhpOffice\PhpSpreadsheet\Shared\Date;


class EmployeeDetailImport
{
    public static function import($array)
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
                    'employee_code' => $data[1] ?? null,
                    'first_name' => $data[2] ?? null,
                    'middle_name' => $data[3] ?? null,
                    'last_name' =>  $data[4] ?? null,
                    // 'gender' => $data[5] ?? null,
                    // 'join_date' => $data[6] ?? null,
                    // 'dob' => $data[7] ?? null,
                    // 'blood_group' => $data[8] ?? null,
                    'phone' => $data[9] ?? null,
                    'mobile' => $data[10] ?? null,
                    'personal_email' => $data[11] ?? null,
                    'official_email' => $data[12] ?? null,
                    'citizenship_no' => $data[13] ?? null,
                    'nationality' => $data[14] ?? null,
                    // 'marital_status' => $data[15] ?? null,
                    // 'religion' => $data[16] ?? null,
                    'pan_no' => $data[22] ?? null,
                    'pf_no' => $data[24] ?? null,
                    'ssf_no' => $data[25] ?? null,
                    'cit_no' => $data[26] ?? null,
                ];

                $fieldObj = new FieldRepository();
                $dropdownObj = new DropdownRepository();


                if (isset($data[5])) {
                    $field = $fieldObj->findByTitle('Gender');
                    $dropvalue = $dropdownObj->getModel($field[0]->id, $data[5]);
                    if ($dropvalue) {
                        $inputData['gender'] =  $dropvalue->id;
                    } else {
                        $inputData['gender'] = null;
                    }
                }

                if (!is_null($data[6])) {
                    // $date = Date::excelToDateTimeObject($data[6])->format('Y-m-d');
                    $inputData['join_date'] = $data[6];
                    $inputData['nepali_join_date'] = date_converter()->eng_to_nep_convert($data[6]);
                }

                if (!is_null($data[7])) {
                    // $dob = Date::excelToDateTimeObject($data[7])->format('Y-m-d');
                    $inputData['nep_dob'] = $data[7];
                    $inputData['dob'] = date_converter()->eng_to_nep_convert($data[7]);
                }

                if (!is_null($data[18])) {
                    // $date = Date::excelToDateTimeObject($data[18])->format('Y-m-d');
                    $inputData['nep_end_date'] = $data[18];
                    $inputData['end_date'] = date_converter()->eng_to_nep_convert($data[18]);
                }

                if (isset($data[8])) {
                    $field = $fieldObj->findByTitle('Blood Group');
                    $dropvalue = $dropdownObj->getModel($field[0]->id, $data[8]);
                    if ($dropvalue) {
                        $inputData['blood_group'] =  $dropvalue->id;
                    } else {
                        $inputData['blood_group'] = null;
                    }
                }

                if (isset($data[15])) {
                    $field = $fieldObj->findByTitle('Marital Status');
                    $dropvalue = $dropdownObj->getModel($field[0]->id, $data[15]);
                    if ($dropvalue) {
                        $inputData['marital_status'] =  $dropvalue->id;
                    } else {
                        $inputData['marital_status'] = null;
                    }
                }

                if (!is_null($data[16])) {
                    $religionArray = Holiday::RELIGION;
                    $key = array_search($data[16], $religionArray);
                    if (isset($key)) {
                        $inputData['religion'] = $key;
                    } else {
                        $inputData['religion'] = null;
                    }
                }

                if (isset($data[19])) {
                    $designation = Designation::where('title', trim($data[19]))->first();
                    if (isset($designation)) {
                        $inputData['designation_id'] = $designation->id;
                    }

                    // $field = $fieldObj->findByTitle('Designation');
                    // $dropvalue = $dropdownObj->getModel($field[0]->id,$data[19]);
                    // if(isset($dropvalue) && !empty($dropvalue)){
                    //     $inputData['designation_id'] = $dropvalue->id;
                    // }else{
                    //     $dropValData = ['fid'=> $field[0]->id, 'dropvalue' => $data[19]];
                    //     $dropval = $dropdownObj->save($dropValData);
                    //     $inputData['designation_id'] = $dropval->id;
                    // }
                }

                if (isset($data[20])) {
                    $level = Level::where('title', trim($data[20]))->first();
                    if (isset($level)) {
                        $inputData['level_id'] = $level->id;
                    }

                    // $field = $fieldObj->findByTitle('Level');
                    // $dropvalue = $dropdownObj->getModel($field[0]->id,$data[20]);
                    // if(isset($dropvalue) && !empty($dropvalue)){
                    //     $inputData['level_id'] = $dropvalue->id;
                    // }else{
                    //     $dropValData = ['fid'=> $field[0]->id, 'dropvalue' => $data[20]];
                    //     $dropval = $dropdownObj->save($dropValData);
                    //     $inputData['level_id'] = $dropval->id;

                    // }
                }

                if (isset($data[21])) {
                    $inputData['job_title'] = $data[21];
                } else {
                    $inputData['job_title'] = null;
                }



                $employee = Employee::where('employee_code', $data[1])->first();
                if (!$employee) {
                    return [
                        'success' => false,
                        'message' => "Error at Row $rowNumber, Column 'Employee': Employee does not exist. Uploaded successfully up to Row " . ($rowNumber - 1) . "!!"
                    ];
                }

                if (!is_null($data[23])) {
                    EmployeePayrollRelatedDetail::updateOrCreate(['employee_id' => $employee->id], ['account_no' => $data[23]]);
                }

                if (!is_null($data[17])) {
                    $dayOff = $data[17];
                    $array = explode(',', $dayOff);
                    $dayOffArray = array_map('ucfirst', $array);
                    EmployeeDayOff::where('employee_id', $employee['id'])->delete();
                    foreach ($dayOffArray as $key => $value) {
                        $employeDayOff = [
                            'day_off' => $value,
                            'employee_id' => $employee['id']
                        ];
                        EmployeeDayOff::create($employeDayOff);
                    }
                }
                if (isset($data[27])) {
                    $department = Department::where('title', trim($data[27]))->first();
                    if (isset($department)) {
                        $inputData['department_id'] = $department->id;
                        $inputData['function_id'] = $department->function_id;
                    }

                    // $field = $fieldObj->findByTitle('Designation');
                    // $dropvalue = $dropdownObj->getModel($field[0]->id,$data[19]);
                    // if(isset($dropvalue) && !empty($dropvalue)){
                    //     $inputData['designation_id'] = $dropvalue->id;
                    // }else{
                    //     $dropValData = ['fid'=> $field[0]->id, 'dropvalue' => $data[19]];
                    //     $dropval = $dropdownObj->save($dropValData);
                    //     $inputData['designation_id'] = $dropval->id;
                    // }
                }
                $filteredData = array_filter($inputData, function ($value) {
                    return !is_null($value);
                });
                // dd($filteredData);
                if (!is_null($employee) && !empty($filteredData)) {
                    $success = $employee->update($filteredData);
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