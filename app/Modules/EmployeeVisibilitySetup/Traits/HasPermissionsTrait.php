<?php

namespace App\Modules\EmployeeVisibilitySetup\Traits;

use App\Modules\EmployeeVisibilitySetup\Entities\EmployeeVisibilitySetup;

trait HasPermissionsTrait
{
    public function isAttendance()
    {
        $visibility = EmployeeVisibilitySetup::where('user_id', $this->id)->first();
        return $visibility ? $visibility->attendance == 1 : false;
    }

    public function isLeave()
    {
        $visibility = EmployeeVisibilitySetup::where('user_id', $this->id)->first();
        return $visibility ? $visibility->leave == 1 : false;
    }

    public function isPayroll()
    {
        $visibility = EmployeeVisibilitySetup::where('user_id', $this->id)->first();
        return $visibility ? $visibility->payroll == 1 : false;
    }

    public function isApprovalFlow()
    {
        $visibility = EmployeeVisibilitySetup::where('user_id', $this->id)->first();
        return $visibility ? $visibility->approval_flow == 1 : false;
    }
}
