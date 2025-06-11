<?php

namespace App\Modules\BusinessTrip\Entities;

use Illuminate\Database\Eloquent\Model;
class BusinessTripAllowanceSetup extends Model
{
    protected $fillable = [
        'employee_id',
        'type_id',
        'per_day_allowance'
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
