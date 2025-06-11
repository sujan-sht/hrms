<?php

namespace App\Modules\NewShift\Entities;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Shift\Entities\ShiftGroup;
use Illuminate\Database\Eloquent\Model;

class NewShiftRequest extends Model
{
    const STATUS = [
        '1' => 'Pending',
        // '2' => 'Forwarded',
        '2' => 'Approved',
        '3' => 'Rejected',
        // '5' => 'Cancelled'
    ];

    protected $fillable = ['employee_id', 'shift_group_id', 'date', 'nepali_date', 'status', 'remarks', 'created_by'];

    public function getStatus()
    {
        return NewShiftRequest::STATUS[$this->status ?? 1];
    }

    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function createdBy(){
        return $this->belongsTo(Employee::class, 'created_by');
    }

    public function shiftGroup(){
        return $this->belongsTo(ShiftGroup::class, 'shift_group_id');
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
