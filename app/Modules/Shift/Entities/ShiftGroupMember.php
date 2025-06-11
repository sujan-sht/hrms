<?php

namespace App\Modules\Shift\Entities;

use App\Modules\Employee\Entities\Employee;
use Illuminate\Database\Eloquent\Model;

class ShiftGroupMember extends Model
{
    protected $fillable = [
        'group_id',
        'group_member'
    ];

    public function group()
    {
        return $this->belongsTo(ShiftGroup::class, 'group_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'group_member');
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
