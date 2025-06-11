<?php

namespace App\Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Employee\Entities\Province;

class District extends Model
{
    protected $fillable = [
        'district_name',
        'province_id'
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
                ->log('Deleted post: ' );
        });
    }



}
