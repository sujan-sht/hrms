<?php

namespace App\Modules\EmployeeMassIncrement\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Organization\Entities\Organization;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeMassIncrement extends Model
{
    protected $fillable = [
        'organization_id',
        'employee_id',
    ];

    public function organizationModel()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function details(){
        return $this->hasMany(EmployeeMassIncrementDetail::class,'employee_mass_increment_id','id');
    }

}
