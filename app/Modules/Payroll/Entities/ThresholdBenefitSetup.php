<?php

namespace App\Modules\Payroll\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ThresholdBenefitSetup extends Model
{

    protected $fillable = [
        'organization_id',
        'deduction_setup_id',
        'amount',
    ];

     protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Created post: ');
        });

        static::updated(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Updated post: ');
        });

        static::deleted(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Deleted post: ');
        });
    }


}
