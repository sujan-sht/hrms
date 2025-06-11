<?php

namespace App\Modules\BulkUpload\Service\Import;

use App\Modules\Dropdown\Entities\Dropdown;
use App\Modules\Dropdown\Entities\Field;
use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Dropdown\Repositories\DropdownRepository;
use App\Modules\Dropdown\Repositories\FieldInterface;
use App\Modules\Dropdown\Repositories\FieldRepository;
use App\Modules\Employee\Entities\BankDetail;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\BenefitDetail;

use function PHPUnit\Framework\isEmpty;

class BankDetailImport
{
    public static function import($array)
    {
        try {
            foreach ($array as $rowIndex => $data) {
                $rowNumber = $rowIndex + 2;
                if (!is_null($data[0])) {
                    $inputData = [
                        'emp_code' => $data[1] ?? null,
                        'org_id' => $data[2] ?? null,
                        'bank_name' => $data[3] ?? null,
                        'bank_code' => $data[4] ?? null,
                        'bank_address' =>  $data[5] ?? null,
                        'bank_branch' => $data[6] ?? null,
                        'account_type' => $data[7] ?? null,
                        'account_number' => $data[8] ?? null,

                    ];

                    $employee = Employee::where('employee_code', $data[1])->where('organization_id', $data[2])->first();
                    // dd($employee);
                    if (!$employee) {
                        return [
                            'success' => false,
                            'message' => "Error at Row $rowNumber, Column 'Employee': Employee does not exist. Uploaded successfully up to Row " . ($rowNumber - 1) . "!!"
                        ];
                    }
                    $inputData['employee_id'] = $employee->id;

                    if (!is_null($data[3])) {
                        $fieldObj = new FieldRepository();
                        $dropdownObj = new DropdownRepository();
                        $field = $fieldObj->findByTitle('bank_name');
                        if ($field->isEmpty()) {
                            $field = $fieldObj->findByTitle('Bank Name');
                        }

                        if (($field->isEmpty())) {
                            return [
                                'success' => false,
                                'message' => "Error at Row $rowNumber, Column 'Bank Name': Bank Name does not exist. Uploaded successfully up to Row " . ($rowNumber - 1) . "!!"
                            ];
                        } else {
                            $dropvalue = $dropdownObj->getModel($field[0]->id, $data[3]);
                            if ($dropvalue) {
                                $inputData['bank_name'] = $dropvalue->dropvalue;
                            } else {
                                $dropvalue = Dropdown::create([
                                    'fid' => $field[0]->id,
                                    'dropvalue' =>  $data[3]
                                ]);
                                $inputData['bank_name'] = $dropvalue->dropvalue;
                            }
                        }
                        if (!is_null($data[7])) {
                            $fieldObj = new FieldRepository();
                            $dropdownObj = new DropdownRepository();
                            $fieldType = $fieldObj->findByTitle('account_type');
                            if (isEmpty($fieldType)) {
                                $fieldType = $fieldObj->findByTitle('Account Type');
                            }
                            if (($fieldType->isEmpty())) {
                                return [
                                    'success' => false,
                                    'message' => "Error at Row $rowNumber, Column 'Account Type': Account Type does not exist. Uploaded successfully up to Row " . ($rowNumber - 1) . "!!"
                                ];
                            } else {

                                $dropvalue = $dropdownObj->getModel($fieldType[0]->id, $data[7]);
                                if ($dropvalue) {
                                    $inputData['account_type'] = $dropvalue->dropvalue;
                                } else {
                                    $dropvalue = Dropdown::create([
                                        'fid' => $fieldType[0]->id,
                                        'dropvalue' =>  $data[7]
                                    ]);
                                    $inputData['account_type'] = $dropvalue->dropvalue;
                                }
                            }
                        }
                    }
                    $previousBenefitDetail = BankDetail::where('employee_id', $inputData['employee_id'])->where('account_number', $inputData['account_number'])->first();
                    if ($previousBenefitDetail) {
                        $success = $previousBenefitDetail->update($inputData);
                    } else {
                        $success = BankDetail::create($inputData);
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
            dd($e);
            // dd($e->getMessage());
            // toastr($message, 'error');
            return false;
        }
        return true;
    }
}
