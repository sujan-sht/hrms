<?php

namespace App\Modules\Payroll\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArrearAdjustmentDetail extends Model
{

    protected $fillable = [
        'arrear_adjustment_id',
        'income_setup_id',
        'arrear_amount',
        'income_type',
    ];
    public function incomes()
    {
        return $this->belongsTo(IncomeSetup::class, 'income_setup_id');
    }
    public function arrearAdjustment()
    {
        return $this->belongsTo(ArrearAdjustment::class, 'arrear_adjustment_id');
    }

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
                ->log('Deleted post: ' . $model);
        });
    }

}
