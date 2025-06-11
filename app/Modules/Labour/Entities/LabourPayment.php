<?php

namespace App\Modules\Labour\Entities;


use Illuminate\Database\Eloquent\Model;

class LabourPayment extends Model
{

    protected $guarded = [];


    public function labour()
    {
        return $this->belongsTo(Labour::class,'employee_id','id');
    }

     public static function boot()
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
