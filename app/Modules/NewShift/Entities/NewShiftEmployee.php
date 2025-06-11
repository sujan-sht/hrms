<?php

namespace App\Modules\NewShift\Entities;

use App\Modules\Shift\Entities\Shift;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Shift\Entities\ShiftGroup;
use App\Modules\NewShift\Entities\NewShift;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewShiftEmployee extends Model
{

    protected $guarded = [];

    public function getShift()
    {
        return $this->belongsTo(Shift::class, 'shift_id', 'id');
    }

    public function newShiftEmployeeDetails()
    {
        return $this->hasMany(NewShiftEmployeeDetail::class, 'new_shift_employee_id');
    }
  public function newShiftEmployeeDetailOne()
    {
        return $this->hasOne(NewShiftEmployeeDetail::class, 'new_shift_employee_id');
    }
    public static function getShiftEmployee($empId, $date){
        return NewShiftEmployee::where('emp_id', $empId)->where('eng_date', $date)->first();
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
