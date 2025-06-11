<?php

namespace App\Modules\Attendance\Entities;

use Illuminate\Database\Eloquent\Model;

class DivisionAttendanceRoleSetup extends Model
{
    protected $fillable = [
        'organization_id',
        'reviewer_emp_id'
    ];

    public static function updateOrCreateRoleSetup($data) {
        $divisionRole = DivisionAttendanceRoleSetup::where('organization_id', $data['organization_id'])->first();
        if (isset($divisionRole) && !empty($divisionRole)) {
            $divisionRole->update($data);
        } else {
            DivisionAttendanceRoleSetup::create($data);
        }
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
