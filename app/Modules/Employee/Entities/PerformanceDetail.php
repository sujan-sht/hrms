<?php

namespace App\Modules\Employee\Entities;

use App\Modules\Branch\Entities\Branch;
use App\Modules\Dropdown\Entities\Dropdown;
use App\Modules\Organization\Entities\Organization;
use App\Modules\Setting\Entities\Department;
use App\Modules\Setting\Entities\Designation;
use App\Modules\Setting\Entities\Level;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PerformanceDetail extends Model
{
    protected $fillable = [
        'employee_id',
        'organization_id',
        'branch_id',
        'department_id',
        'level_id',
        'designation_id',
        'job_title',
        'category',
        'type_id',
        'created_by',
        'updated_by',
        'date'
    ];

    public function getTypeList()
    {
        $list = Self::typeList();
        return $list[$this->type_id];
    }

    public static function typeList()
    {
        return [
            '1' => 'Transfer',
            '2' => 'Promotion',
            '3' => 'Demotion'
        ];
    }

    public static function boot()
    {
        parent::boot();

        Self::creating(function ($model) {
            $model->created_by = Auth::user()->id;
        });
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    public static function storePerformanceDetail($data)
    {
        return PerformanceDetail::create($data);
    }
}
