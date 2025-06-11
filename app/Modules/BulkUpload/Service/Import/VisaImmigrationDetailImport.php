<?php

namespace App\Modules\BulkUpload\Service\Import;

use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Dropdown\Repositories\FieldInterface;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\VisaAndImmigrationDetail;
use App\Modules\Employee\Repositories\EmployeeRepository;

class VisaImmigrationDetailImport
{
    protected $dropdown;
    protected $field;
    
    public function __construct(DropdownInterface $dropdown, FieldInterface $field)
    {
        $this->dropdown = $dropdown;
        $this->field = $field;
    }
    public static function import($array)
    {
        try {
            foreach ($array as $data) {
                if (!is_null($data[0])) {
                    $inputData = [
                        'emp_code' => $data[1] ?? null,
                        'org_id' => $data[2] ?? null,
                        'country' => $data[3] ?? null,
                        'visa_type' =>  $data[4] ?? null,
                        'issued_date' => $data[5] ?? null,
                        'visa_expiry_date' => $data[6] ?? null,
                        'passport_number' => $data[7] ?? null,
                        'note' => $data[8] ?? null,
                    ];

                    if (Employee::where('employee_code', $data[1])->exists()) {
                        $employee = Employee::where('employee_code', $data[1])->where('organization_id',$data[2])->first();
                        $inputData['employee_id'] = $employee->id;
                    } else {
                        $inputData['employee_id'] = null;
                    }
                    if(!is_null($data[3])){
                        $empObj = new EmployeeRepository();
                        $country = $empObj->findCountry($data[3]);
                        $inputData['country'] = $country->id;
                        // $dropdownObj = new DropdownRepository();
                        // $field = $fieldObj->findByTitle('Country');
                        // $dropvalue = $dropdownObj->getModel($field[0]->id,$data[3]);
                        // if($dropvalue){
                        //     $inputData['country'] = $dropvalue->dropvalue;
                        // }
                        // else{
                        //     $dropvalue = Dropdown::create([
                        //         'fid' => $field[0]->id,
                        //         'dropvalue' =>  $data[3]
                        //     ]);
                        //     $inputData['country'] = $dropvalue->dropvalue;
                        // }
                        
                    }
                    // dd($inputData);
                    $previousVisaImmigrationDetail = VisaAndImmigrationDetail::where('employee_id',$inputData['employee_id'])->first();
                    if($previousVisaImmigrationDetail){
                        $previousVisaImmigrationDetail->update($inputData);
                    }
                    else{
                        $vsaAndImmigrationDetail = VisaAndImmigrationDetail::create($inputData);
                    }
                
                }
                
            }
            toastr()->success('Bulk Upload succesfully');
        } catch (\Exception $e) {
            toastr('Data Format For Excel Upload Is Invalid ', 'error');
        }
    }
}
