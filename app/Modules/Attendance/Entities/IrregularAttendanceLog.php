<?php

namespace App\Modules\Attendance\Entities;

use Illuminate\Database\Eloquent\Model;

class IrregularAttendanceLog extends Model
{

    protected $fillable = [
        'employee_id',
        'total_late_arrival_days',
        'total_early_departure_days'
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
