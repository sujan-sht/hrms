<?php

namespace App\Modules\BulkUpload\Service\Import;

use App\Modules\Dropdown\Entities\Dropdown;
use App\Modules\Dropdown\Entities\Field;
use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Dropdown\Repositories\DropdownRepository;
use App\Modules\Dropdown\Repositories\FieldInterface;
use App\Modules\Dropdown\Repositories\FieldRepository;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\BenefitDetail;

class BenefitDetailImport
{
    public static function import($array)
    {
        try {
            foreach ($array as $data) {

                if (!is_null($data[0])) {
                    $inputData = [
                        'emp_code' => $data[1] ?? null,
                        'org_id' => $data[2] ?? null,
                        'benefit_type' => $data[3] ?? null,
                        'plan' =>  $data[4] ?? null,
                        'coverage' => $data[5] ?? null,
                        'effective_date' => $data[6] ?? null,
                        'employee_contribution' => $data[7] ?? null,
                        'company_contribution' => $data[8] ?? null,
                    ];

                    // dd($inputData);

                    if (Employee::where('employee_code', $data[1])->exists()) {
                        $employee = Employee::where('employee_code', $data[1])->where('organization_id',$data[2])->first();
                        $inputData['employee_id'] = $employee->id;
                    } else {
                        $inputData['employee_id'] = null;
                    }
                    if(!is_null($data[4])){
                        $fieldObj = new FieldRepository();
                        $dropdownObj = new DropdownRepository();
                        $field = $fieldObj->findByTitle('Benefit Type');
                        $dropvalue = $dropdownObj->getModel($field[0]->id,$data[3]);
                        if($dropvalue){
                            $inputData['benefit_type_id'] = $dropvalue->id;
                        }
                        else{
                            $dropvalue = Dropdown::create([
                                'fid' => $field[0]->id,
                                'dropvalue' =>  $data[3]
                            ]);
                            $inputData['benefit_type_id'] = $dropvalue->id;
                        }
                        
                    }
                    if(!is_null($data[5])){
                        $inputData['coverage'] =BenefitDetail::getCoverageId($data[5]);
                    }
                    $previousBenefitDetail = BenefitDetail::where('employee_id',$inputData['employee_id'])->where('benefit_type_id',$inputData['benefit_type_id'])->first();
                    if($previousBenefitDetail){
                        $previousBenefitDetail->update($inputData);
                    }
                    else{
                        $BenefitDetail = BenefitDetail::create($inputData);
                    }
                
                }
                
            }
            toastr()->success('Bulk Upload succesfully');
        } catch (\Exception $e) {
            // dd($e);
            toastr('Data Format For Excel Upload Is Invalid ', 'error');;
        }
    }
}
