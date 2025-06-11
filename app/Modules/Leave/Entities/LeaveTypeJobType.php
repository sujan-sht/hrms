<?php

namespace App\Modules\Leave\Entities;

use Illuminate\Database\Eloquent\Model;

class LeaveTypeJobType extends Model
{

    protected $fillable = [
        'leave_type_id',
        'job_type_id'
    ];

    public function leaveTypeModel()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
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
