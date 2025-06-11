<?php

namespace App\Modules\Offboarding\Entities;

use App\Modules\Employee\Entities\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OffboardEmployeeClearance extends Model
{
    protected $fillable = [
        'employee_id',
        'offboard_clearance_id',
        'offboard_clearance_responsible_id',
        'offboard_resignation_id',
        'status'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function offboardClearance()
    {
        return $this->belongsTo(OffboardClearance::class, 'offboard_clearance_id');
    }

}
