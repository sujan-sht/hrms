<?php

namespace App\Modules\Setting\Entities;

use App\Modules\Attendance\Entities\AttendanceLog;
use App\Modules\Organization\Entities\Organization;
use Illuminate\Database\Eloquent\Model;

class DeviceManagement extends Model
{
    protected $fillable = ['organization_id', 'ip_address', 'port', 'device_id', 'communication_password', 'location', 'status'];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class,'ip_address','ip_address');
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
