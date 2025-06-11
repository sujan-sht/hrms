<?php

namespace App\Modules\OrganizationalStructure\Entities;

use App\Modules\Employee\Entities\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrganizationalStructureDetail extends Model
{
    protected $fillable = [
        'org_structure_id',
        'employee_id',
        'parent_employee_id'
    ];

    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id');
    }
   
    public function parentEmployee(){
        return $this->belongsTo(Employee::class, 'parent_employee_id');
    }
}
