<?php

namespace App\Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PayrollCalenderTypeSetting extends Model
{
    protected $fillable = [
        'organization_id',
        'calendar_type'
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
