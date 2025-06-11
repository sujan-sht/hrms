<?php

namespace App\Modules\Attendance\Entities;

use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    protected $fillable = [
        'date',
        'org_id',
        'ip_address',
        'biometric_emp_id',
        'emp_id',
        'inout_mode',
        'verifymode',
        'time',
        'punch_from',
        'location',
        'lat',
        'long',
        'source',
        'check_in',
        'check_out',
    ];
}
