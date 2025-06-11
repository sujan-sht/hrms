<?php

namespace App\Modules\Shift\Entities;

use App\Modules\Employee\Entities\Employee;
use Illuminate\Database\Eloquent\Model;

class ShiftGroup extends Model
{
    protected $fillable = [
        'org_id',
        'group_name',
        'shift_id',
        'ot_grace',
        'ot_grace_period',
        'grace_period_checkout',
        'grace_period_checkin_for_penalty',
        'grace_period_checkout_for_penalty',
        'leave_benchmark_time_for_first_half',
        'leave_benchmark_time_for_second_half',
        'shift_season_id',
        'default',
    ];

    public function groupMembers()
    {
        return $this->hasMany(ShiftGroupMember::class, 'group_id');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }

    public function getGroupMember()
    {
        return ShiftGroupMember::where('group_id', $this->id);
    }

    public function shiftSeason_info()
    {
        return $this->belongsTo(ShiftSeason::class, 'shift_season_id');
    }
    // public function employees()
    // {
    //     return $this->belongsToMany(Employee::class, 'shift_group_members')->withPivot('group_id');;
    // }

    public function scopeIsDefault($query){
    return $query->where('default', 'yes');
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
