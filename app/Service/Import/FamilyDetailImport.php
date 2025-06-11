<?php

namespace App\Service\Import;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\FamilyDetail;
use Illuminate\Support\Facades\DB;
use Yoeunes\Toastr\Facades\Toastr;

class FamilyDetailImport implements ImportInterface
{
    // protected $dropdown;

    // public function __construct(
    //     DropdownInterface $dropdown
    // ) {
    //     $this->dropdown = $dropdown;
    // }

    public function import($array)
    {
        // dd($array);
        try {
            foreach ($array as $index => $data) {
                $rowNumber = $index + 2;

                if (!is_null($data[0])) {
                    $inputData = [
                        'emp_code' => $data[1] ?? null,
                        'org_id' => $data[2] ?? null,
                        'name' => $data[3] ?? null,
                        'relation' =>  $data[4] ?? null,
                        'contact' => $data[5] ?? null,
                    ];

                    $employee = Employee::where('employee_code', $data[1])->where('organization_id', $data[2])->first();
                    if (!$employee) {
                        return [
                            'success' => false,
                            'message' => "Error at Row $rowNumber, Column 'Employee': Employee does not exist. Uploaded successfully up to Row " . ($rowNumber - 1) . "!!"
                        ];
                    }
                    $inputData['employee_id'] = $employee->id;
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
                    $previousFamilyDetail = FamilyDetail::where('employee_id', $inputData['employee_id'])->where('relation', $inputData['relation'])->first();
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
                'message' => "Error at Row $rowNumber, Branch cannot create. Uploaded successfull upto $rowNumber-1. !!"
            ];
            return true;
        } catch (\Exception $e) {
            // dd($e->getMessage() . ' ' . $e->getLine());
            // toastr('Data Format For Excel Upload Is Invalid ', 'error');
            return false;
        }
        return true;
    }
}
