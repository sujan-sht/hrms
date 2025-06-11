<?php

namespace App\Modules\BulkUpload\Service\Import;

use App\Modules\Dropdown\Repositories\FieldInterface;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\DocumentDetail;

class DocumentDetailImport
{
    protected $field;
    
    public function __construct(FieldInterface $field)
    {
        $this->field = $field;
    }
    public static function import($array)
    {
        try {
            foreach ($array as $data) {
                if (!is_null($data[0])) {
                    $inputData = [
                        'employee_id' => $data[1] ?? null,
                        'document_name' => $data[2] ?? null,
                        'id_number' => $data[3] ?? null,
                        'issued_date' =>  $data[4] ? date_converter()->nep_to_eng_convert($data[4]) : null,
                        'expiry_date' => $data[5] ? date_converter()->nep_to_eng_convert($data[5]) : null,
                    ];

                    if (Employee::where('employee_code', $data[1])->exists()) {
                        $employee = Employee::where('employee_code', $data[1])->first();
                        $inputData['employee_id'] = $employee->id;
                    } else {
                        $inputData['employee_id'] = null;
                    }
                   
                    // $previousVisaImmigrationDetail = VisaAndImmigrationDetail::where('employee_id',$inputData['employee_id'])->first();
                    // if($previousVisaImmigrationDetail){
                    //     $previousVisaImmigrationDetail->update($inputData);
                    // }
                    // else{
                       DocumentDetail::create($inputData);
                    // }
                
                }
                
            }
            toastr()->success('Bulk Upload succesfully');
        } catch (\Exception $e) {
            toastr('Data Format For Excel Upload Is Invalid ', 'error');
        }
    }
}
