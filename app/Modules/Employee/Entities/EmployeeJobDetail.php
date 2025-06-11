<?php

namespace App\Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;

class EmployeeJobDetail extends Model
{
    protected $fillable = [
        'employee_id',
        'type_id',
        'job_type_id',
        'probation_period_days',
        'contract_start_date',
        'contract_end_date',
        'extend_end_date',
        'created_by'
    ];

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
