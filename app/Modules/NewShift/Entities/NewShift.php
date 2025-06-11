<?php

namespace App\Modules\NewShift\Entities;

use DateTime;
use DateInterval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewShift extends Model
{

    protected $guarded = [];

    public function getCheckpoint()
    {
       // return date('H:i', ((strtotime($this->start_time) + strtotime($this->end_time)) / 2));

        // Convert times to DateTime objects
        $start = new DateTime($this->start_time);
        $end = new DateTime($this->end_time);

        // If end time is less than start time, add one day to end time
        if ($end <= $start) {
            $end->modify('+1 day');
        }

        // Calculate the difference and get the midpoint
        $interval = $start->diff($end);
        $midpoint = clone $start;
        $midpoint->add(new DateInterval('PT' . floor(($interval->h * 60 * 60 + $interval->i * 60 + $interval->s) / 2) . 'S'));

        return $midpoint->format('H:i');
    }

    public function getTitleAttribute($value)
    {
        $result = $value;
        if ($value == 'Custom') {
            $result = $value . ': ' . $this->attributes['custom_title'];
        }
        return $result;
    }

    // public function checkShift($employee_id, $shift_id, $days, $group_id)
    // {
    //     $data = EmployeeShift::where('employee_id', '=', $employee_id)
    //         ->where('shift_id', '=', $shift_id)
    //         ->where('days', '=', $days)
    //         ->where('group_id', '=', $group_id)
    //         ->count();

    //     if ($data > 0) {
    //         return true;
    //     }
    //     return false;
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
