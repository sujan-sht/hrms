<?php

namespace App\Modules\Leave\Entities;

use Illuminate\Database\Eloquent\Model;

class EmployeeLeaveOpening extends Model
{
    protected $fillable = [
        'fiscal_year_id',
        'organization_id',
        'employee_id',
        'leave_type_id',
        'opening_leave',
        'leave_year_id'
    ];

    public static function saveData($organizationId, $employeeId, $leaveTypeId, $data)
    {
        $data['employee_id'] = $employeeId;
        $data['organization_id'] = $organizationId;
        $data['leave_type_id'] = $leaveTypeId;

        $model = EmployeeLeaveOpening::where('organization_id', $organizationId)->where('employee_id', $employeeId)->where('leave_type_id', $leaveTypeId)->first();
        if ($model) {
            $result = $model->update($data);
        } else {
            $result = EmployeeLeaveOpening::create($data);
        }

        return $result;
    }

    public static function getLeaveOpening($leave_year_id, $organization_id, $employee_id, $leave_type_id)
    {
        $result = EmployeeLeaveOpening::select('opening_leave')->where('leave_year_id', $leave_year_id)->where('organization_id', $organization_id)->where('employee_id', $employee_id)->where('leave_type_id', $leave_type_id)->first();
        if (!empty($result)) {
            $opening_leave = $result->opening_leave;
        } else {
            $opening_leave = 0;
        }
        return $opening_leave;
    }

    /**
     * Relation with leave type
     */
    public function leaveTypeModel()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id')->where('status', 11);
    }

    /**
     *
     */

      protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Created post: ' . $model);
        });

        static::updated(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Updated post: ' . $model);
        });

        static::deleted(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Deleted post: ' . $model);
        });
    }
}
