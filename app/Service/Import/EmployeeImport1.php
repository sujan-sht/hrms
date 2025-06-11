<?php

namespace App\Service\Import;

use App\Modules\Branch\Entities\Branch;
use App\Modules\Dropdown\Entities\Dropdown;
use App\Modules\Dropdown\Entities\Field;
use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\EmployeeDayOff;
use App\Modules\Organization\Entities\Organization;
use Illuminate\Support\Facades\DB;
use Yoeunes\Toastr\Facades\Toastr;

class EmployeeImport1 implements ImportInterface
{
    // protected $dropdown;

    // public function __construct(
    //     DropdownInterface $dropdown
    // ) {
    //     $this->dropdown = $dropdown;
    // }

    public function import($array)
    {
        try {
            foreach ($array as $data) {
                $employeeModel = Employee::where('employee_code', $data[2])->first();
                if($employeeModel) {
                    $employeeModel->profile_pic = $data[3];
                    $employeeModel->save();
                }
            }
        } catch (\Exception $e) {
            //
        }
    }
}
