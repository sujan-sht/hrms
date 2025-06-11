<?php

namespace App\Modules\Attendance\Entities;

use Illuminate\Database\Eloquent\Model;

class AttendanceRequestLink extends Model
{

    protected $fillable = [
        'attendance_id',
        'attendance_request_id'
    ];
}
