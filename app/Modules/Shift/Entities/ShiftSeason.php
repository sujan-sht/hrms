<?php

namespace App\Modules\Shift\Entities;

use Illuminate\Database\Eloquent\Model;

class ShiftSeason extends Model
{
    protected $fillable = [
        'shift_id',
        'date_from',
        'date_to',
        'is_multi_day_shift'
    ];

    public function seasonShiftDayWise()
    {
        return $this->hasMany(ShiftDayWise::class, 'shift_season_id');
    }

    public function shift(){
        return $this->belongsTo(Shift::class, 'shift_id');
    }

    // public static function getSeasonalShift($eng_date){
    //     return ShiftSeason::where('date_from', '<=', $eng_date)->where('date_to', '>=', $eng_date)->orderby('id', 'desc')->first();
    // }

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
