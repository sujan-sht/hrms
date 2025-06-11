<?php

namespace App\Modules\OrganizationalStructure\Entities;

use App\Modules\Employee\Entities\Employee;
use Illuminate\Database\Eloquent\Model;

class OrganizationalStructure extends Model
{
    protected $fillable = [
        'title',
        'kra',
        'kpi',
        'designation',
        'job_role',
        'root_employee_id',
        'created_by',
        'updated_by'
    ];

    public function orgStructureDetail()
    {
        return $this->hasMany(OrganizationalStructureDetail::class, 'org_structure_id');
    }
    
    public function rootEmployee(){
        return $this->belongsTo(Employee::class, 'root_employee_id');
    }
}
