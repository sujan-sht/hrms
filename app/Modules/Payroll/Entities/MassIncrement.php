<?php

namespace App\Modules\Payroll\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MassIncrement extends Model
{
    protected $fillable = [
        'emp_id',
        'name',
        'organization_id',
        'branch_id',
        'designation_id',
        'emp_status',
        'existing_income',
        'increased_by',
        'new_income',
        'arrear_amt',
        'effective_date',
        'nep_effective_date',
        'status',
        'created_by',
        'updated_by'
    ];

     protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Created post: ' );
        });

        static::updated(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Updated post: ' );
        });

        static::deleted(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Deleted post: ' . $model);
        });
    }

}
