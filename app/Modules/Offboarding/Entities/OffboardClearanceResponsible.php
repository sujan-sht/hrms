<?php

namespace App\Modules\Offboarding\Entities;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Organization\Entities\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OffboardClearanceResponsible extends Model
{

    protected $fillable = [
        'offboard_clearance_id',
        'organization_id',
        'employee_id',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
    public function employeeClearance(){
        return $this->hasMany(OffboardEmployeeClearance::class,'offboard_clearance_responsible_id');
    }
    public function clearance(){
        return $this->belongsTo(OffboardClearance::class,'offboard_clearance_id');
    }


}
