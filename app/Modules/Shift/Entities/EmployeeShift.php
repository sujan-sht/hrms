<?php

namespace App\Modules\Shift\Entities;

use Illuminate\Database\Eloquent\Model;

class EmployeeShift extends Model
{
    protected $fillable = [
    	'employee_id',
    	'shift_id',
    	'group_id',
        'days',
    	'created_by',
    	'updated_by'
    ];

    CONST DAYS = [
        'Sun' => 'Sunday',
        'Mon' => 'Monday',
        'Tue' => 'Tuesday',
        'Wed' => 'Wednesday',
        'Thu' => 'Thursday',
        'Fri' => 'Friday',
        'Sat' => 'Saturday',
    ];

    public function getShift()
    {
        return $this->belongsTo(Shift::class,'shift_id','id');
    }
    public function getGroup()
    {
        return $this->belongsTo(Group::class,'group_id','id');
    }

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
