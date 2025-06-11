<?php

namespace App\Modules\BulkUpload\Service\Import;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\FamilyDetail;
use App\Modules\Employee\Entities\Province;
use App\Modules\Setting\Entities\District;

class FamilyDetailImport
{
    public static function import($array)
    {
        try {

            foreach ($array as $rowIndex => $data) {
                $rowNumber = $rowIndex + 2;
                if (!is_null($data[0])) {
                    $inputData = [
                        'name' => $data[3] ?? null,
                        'relation' =>  $data[4] ?? null,
                        'contact' => $data[5] ?? null,
                        'dob' => $data[6] ? date_converter()->eng_to_nep_convert($data[6]) : null,
                        'is_nominee_detail' => $data[7] === "Yes" ? 1 : 0,
                        'is_emergency_contact' => $data[8] === "Yes" ? 1 : 0,
                        'is_dependent' => $data[9] === "Yes" ? 1 : 0,
                        'include_in_medical_insurance' => $data[10] === "Yes" ? 1 : 0,
                        'late_status' => $data[17] === "Yes" ? 1 : 0,
                    ];
                    $employee = Employee::where('employee_code', $data[1])->first();
                    if (!$employee) {
                        return [
                            'success' => false,
                            'message' => "Error at Row $rowNumber, Column 'Employee': Employee does not exist. Uploaded successfully up to Row " . ($rowNumber - 1) . "!!"
                        ];
                    }
                    $inputData['employee_id'] = $employee->id;

                    if ($data[16] === "No") {
                        $inputData['family_address'] = $data[11] ?? null;
                        $inputData['province_id'] =  Province::where('province_name', $data[12])->first()->id ?? null;
                        $inputData['district_id'] =  District::where('district_name', $data[13])->first()->id ?? null;
                        $inputData['municipality'] =  $data[14] ?? null;
                        $inputData['ward_no'] =  $data[15] ?? null;
                    } else {
                        $inputData['same_as_employee'] = $data[16] === "Yes" ?  $employee->id : null;
                    }

                    if (!is_null($data[4])) {
                        $relation = FamilyDetail::getRelationTypeId($data[4]);
                        if (!$relation) {
                            return [
                                'success' => false,
                                'message' => "Error at Row $rowNumber, Relation does not exist. Uploaded successfully up to Row " . ($rowNumber - 1) . "!!"
                            ];
                        }
                        $inputData['relation'] = $relation;
                    }
                    $previousFamilyDetail = FamilyDetail::where('employee_id', $employee->id)->where('relation', $inputData['relation'])->first();
                    if ($previousFamilyDetail) {
                        $success = $previousFamilyDetail->update($inputData);
                    } else {
                        $success = FamilyDetail::create($inputData);
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
                'message' => "Error at Row $rowNumber, Bulk upload cannot complete. Uploaded successfull upto $rowNumber-1. !!"
            ];
            return true;
        } catch (\Exception $e) {
            dd($e);
            // dd($e->getMessage() . ' ' . $e->getLine());
            // toastr('Data Format For Excel Upload Is Invalid ', 'error');
            return false;
        }
        return true;
    }
}
