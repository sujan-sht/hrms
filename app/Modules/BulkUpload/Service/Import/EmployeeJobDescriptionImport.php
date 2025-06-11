<?php

namespace App\Modules\BulkUpload\Service\Import;

use App\Modules\Dropdown\Entities\Dropdown;
use App\Modules\Dropdown\Entities\Field;
use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Dropdown\Repositories\DropdownRepository;
use App\Modules\Dropdown\Repositories\FieldInterface;
use App\Modules\Dropdown\Repositories\FieldRepository;
use App\Modules\Employee\Entities\Employee;

class EmployeeJobDescriptionImport
{
    public static function import($array)
    {
        // dd($array);
        try {
            foreach ($array as $data) {
                // dd($data);
                $employeeModel = Employee::where('employee_code', $data[1])->where('organization_id',$data[2])->first();
                // dd($employeeModel);
                if($employeeModel) {
                    $employeeModel->job_description = $data[3];
                    $employeeModel->save();
                }
            }
            toastr()->success('Employee Job Description Uploaded Succesfully');
        } catch (\Exception $e) {
            toastr('Data Format For Excel Upload Is Invalid ', 'error');
        }
    }
}
