<?php

namespace App\Modules\Attendance\Entities;

use App\Modules\Employee\Entities\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttendanceSummaryVerification extends Model
{
    protected $fillable = [
        'attendance_organization_lock_id',
        'employee_id',
        'organization_id',
        'calender_type',
        'total_days',
        'working_days',
        'dayoffs',
        'public_holiday',
        'working_hour',
        'worked_days',
        'worked_hour',
        'unworked_hour',
        'leave_taken',
        'paid_leave_taken',
        'unpaid_leave_taken',
        'absent_days',
        'over_stay',
        'ot_value',
        'lock_type'
    ];

    public function employee()
    {
        return $this->hasOne(Employee::class, 'id', 'employee_id');
    }

    public function getLockAttribute()
    {
        return $this->hasMany(AttendanceLockAttribute::class, 'attendance_summary_verification_id', 'id')->where('status', 1);
    }
}
