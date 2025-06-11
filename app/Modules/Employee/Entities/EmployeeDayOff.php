<?php

namespace App\Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeDayOff extends Model
{
    // use HasFactory;

    protected $fillable = [
        'employee_id',
        'day_off'
    ];
    public $timestamps = false;


    // protected static function newFactory()
    // {
    //     return \App\Modules\Employee\Database\factories\EmployeeDayOffFactory::new();
    // }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
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
