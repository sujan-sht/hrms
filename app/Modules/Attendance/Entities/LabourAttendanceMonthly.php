<?php

namespace App\Modules\Attendance\Entities;

use Illuminate\Database\Eloquent\Model;

class LabourAttendanceMonthly extends Model
{
    protected $fillable = [
        'employee_id',
        'date',
        'nepali_date',
        'is_present'
    ];

     protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Created post: ' . $model);
        });

        static::updated(function ($model) {
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
