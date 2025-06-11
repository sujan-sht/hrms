<?php

namespace App\Modules\BulkUpload\Service\Import;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\ResearchAndPublicationDetail;

class ResearchDetailImport
{
    public static function import($array)
    {
        try {
            foreach ($array as $data) {

                if (!is_null($data[0])) {
                    $inputData = [
                        'emp_code' => $data[1] ?? null,
                        'org_id' => $data[2] ?? null,
                        'research_title' =>  $data[3] ?? null,
                        'note' => $data[4] ?? null,
                    ];

                    if (Employee::where('employee_code', $data[1])->exists()) {
                        $employee = Employee::where('employee_code', $data[1])->where('organization_id',$data[2])->first();
                        $inputData['employee_id'] = $employee->id;
                    } else {
                        $inputData['employee_id'] = null;
                    }
                    
                    $previousResearchDetail = ResearchAndPublicationDetail::where('employee_id',$inputData['employee_id'])->where('research_title',$data[3])->first();
                    if($previousResearchDetail){
                        $previousResearchDetail->update($inputData);
                    }
                    else{
                        $ResearchDetail = ResearchAndPublicationDetail::create($inputData);
                    }
                
                }
                
            }
            toastr()->success('Bulk Upload succesfully');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            toastr('Data Format For Excel Upload Is Invalid ', 'error');
        }
    }
}
