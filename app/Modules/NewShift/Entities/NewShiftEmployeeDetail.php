<?php

namespace App\Modules\NewShift\Entities;

use App\Modules\Shift\Entities\Shift;
use App\Modules\Shift\Entities\ShiftGroup;
use Illuminate\Database\Eloquent\Model;

class NewShiftEmployeeDetail extends Model
{
    protected $fillable = [
        'new_shift_employee_id',
        'type',
        'shift_id',
        'shift_group_id'
    ];

    public function getShiftGroup()
    {
        return $this->belongsTo(ShiftGroup::class, 'shift_group_id', 'id');
    }

    public function getShift()
    {
        return $this->belongsTo(Shift::class, 'shift_id', 'id');
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
