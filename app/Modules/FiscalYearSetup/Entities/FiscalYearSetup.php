<?php

namespace App\Modules\FiscalYearSetup\Entities;

use Illuminate\Database\Eloquent\Model;

class FiscalYearSetup extends Model
{
    protected $fillable = [
        'fiscal_year',
        'fiscal_year_english',
        'start_date',
        'end_date',
        'start_date_english',
        'end_date_english',
        'status',
        'is_sync'
    ];

    public static function currentFiscalYear()
    {
        return FiscalYearSetup::where('status', 1)->first();
    }

    public static function previousFiscalYear()
    {
        return FiscalYearSetup::where('id', '<', getCurrentFiscalYearId())->orderBy('id', 'desc')->first();
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
