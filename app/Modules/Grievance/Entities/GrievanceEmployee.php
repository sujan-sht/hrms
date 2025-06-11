<?php

namespace App\Modules\Grievance\Entities;

use App\Modules\Dropdown\Entities\Dropdown;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Organization\Entities\Organization;
use App\Modules\Setting\Entities\Department;
use App\Modules\Setting\Entities\Designation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GrievanceEmployee extends Model
{
    protected $fillable = ['grievance_id', 'emp_id', 'division_id', 'department_id', 'designation_id'];

    public function grievance()
    {
        return $this->belongsTo(Grievance::class, 'grievance_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    public function division()
    {
        return $this->belongsTo(Organization::class, 'division_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
