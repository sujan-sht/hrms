<?php

namespace App\Modules\Attendance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Attendance\Entities\AttendanceSummaryVerification;

class AttendanceOrganizationLock extends Model
{
    protected $fillable = [
        'organization_id',
        'calender_type',
        'year',
        'month',
        'created_np_datetime',
        'created_eng_datetime',
        'lock_type'
    ];

    public function getAttendanceSummaryVerification()
    {
        return $this->hasMany(AttendanceSummaryVerification::class, 'attendance_organization_lock_id', 'id');
    }



    public function getLockAttribute()
    {
        return $this->hasMany(AttendanceLockAttribute::class, 'attendance_organization_lock_id', 'id');
    }
}
