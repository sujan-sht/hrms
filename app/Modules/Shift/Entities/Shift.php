<?php

namespace App\Modules\Shift\Entities;

use DateTime;

use DateInterval;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Shift\Entities\EmployeeShift;


class Shift extends Model
{
    protected $fillable = [
        'title',
        'custom_title',
        'start_time',
        'end_time',
        'created_by',
        'updated_by',
        'seasonal',
        'is_multi_day_shift',
        'default',
    ];

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

    public function getTitle($value)
    {

        return $value;
    }

    public function checkShift($employee_id, $shift_id, $days, $group_id)
    {
        $data = EmployeeShift::where('employee_id', '=', $employee_id)
            ->where('shift_id', '=', $shift_id)
            ->where('days', '=', $days)
            ->where('group_id', '=', $group_id)
            ->count();

        if ($data > 0) {
            return true;
        }
        return false;
    }

    public function shiftDayWise()
    {
        return $this->hasMany(ShiftDayWise::class, 'shift_id');
    }

    public function shiftDayWiseHasOne()
    {
        return $this->hasOne(ShiftDayWise::class, 'shift_id');
    }


    // public function getShiftDayWise($day)
    // {
    //     return $this->hasMany(ShiftDayWise::class, 'shift_id')->where('day', $day)->first();
    // }

    public function getShiftDayWise($day, $seasonal_shift_id = null)
    {
        $query = $this->shiftDayWise()->where('day', $day);

        if (!is_null($seasonal_shift_id)) {
            $query->where('shift_season_id', $seasonal_shift_id);
        }
        return $query->first();
    }

    public function shiftSeasons()
    {
        return $this->hasMany(ShiftSeason::class, 'shift_id');
    }

    public function getShiftSeasonForDate($date)
    {

        $data =  $this->shiftSeasons()
            ->where('date_from', '<=', $date)
            ->where('date_to', '>=', $date)
            ->orderBy('id', 'desc')
            ->first();
        if (isset($data) && !is_null($data)) {
            return $data;
        }
        return null;
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
