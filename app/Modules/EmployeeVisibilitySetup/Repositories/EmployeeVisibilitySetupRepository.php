<?php

namespace App\Modules\EmployeeVisibilitySetup\Repositories;

use Illuminate\Support\Facades\DB;
use App\Modules\EmployeeVisibilitySetup\Entities\EmployeeVisibilitySetup;

class EmployeeVisibilitySetupRepository implements EmployeeVisibilitySetupInterface
{
    public function store($request)
    {
        $attendance = $request->input('attendance');
        $leave = $request->input('leave');
        $payroll = $request->input('payroll');
        $approval_flow = $request->input('approval_flow');

        DB::beginTransaction();

        try {
            foreach ($request->employee_id as $employeeId) {
                EmployeeVisibilitySetup::updateOrCreate(
                    [
                        'user_id' => $employeeId
                    ],
                    [
                        'attendance' => $attendance[$employeeId],
                        'leave' => $leave[$employeeId],
                        'payroll' => $payroll[$employeeId],
                        'approval_flow' => $approval_flow[$employeeId]
                    ]
                );
            }

            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            return false;
        }
    }
}
