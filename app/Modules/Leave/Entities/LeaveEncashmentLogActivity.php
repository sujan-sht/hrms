<?php

namespace App\Modules\Leave\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveEncashmentLogActivity extends Model
{
    protected $fillable = [
        'leave_encashment_log_id',
        'encashed_leave_balance',
        'payroll_id',
        'employee_id'
    ];

    public function leaveEncashmentLog(){
        return $this->belongsTo(LeaveEncashmentLog::class, 'leave_encashment_log_id');
    }
}
