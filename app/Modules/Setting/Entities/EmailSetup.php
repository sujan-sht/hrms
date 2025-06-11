<?php

namespace App\Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmailSetup extends Model
{
    protected $fillable = [
        'module_id',
        'status'
    ];

    public static function moduleList(){
        return [
            1 => 'Leave',
            2 => 'Attendance',
            3 => 'Claim',
            4 => 'Request',
            5 => 'Job/Contract/Probation',
            6 => 'Attendance Data Verification',
            7 => 'Business Trip',
            8 => 'Grace Time'
        ];
    }

    public static function statusList(){
        return [
            10 => 'No',
            11 => 'Yes',
        ];
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
