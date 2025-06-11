<?php

namespace App\Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;

class EmployeeLeaveArchive extends Model
{
    protected $fillable = [
        'employee_id',
        'organization_id',
        'fiscal_year_id',
        'leave_type_id',
        'opening_leave',
        'leave_remaining',
        'leave_year_id'
    ];

    /**
     * Relation with employee
     */
    public function employeeModel() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     *
     */
    public static function saveData($data)
    {
        EmployeeLeaveArchive::create($data);
    }

    public static function boot()
    {
        parent::boot();

        Self::creating(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Created post: ' . $model);
        });

        Self::updating(function ($model) {
             activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Updated post: ' . $model);
        });

        static::deleted(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Deleted post: ' . $model);
        });
    }
}
