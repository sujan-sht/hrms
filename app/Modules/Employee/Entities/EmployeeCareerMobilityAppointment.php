<?php

namespace App\Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;

class EmployeeCareerMobilityAppointment extends Model
{

    protected $table = 'employee_career_mobility_appointments';

    protected $fillable = [
        'employee_id',
        'letter_issue_date',
        'appointment_date',
        'effective_date',
        'contract_type', // contract or probation or regular

        // it is only for probation and contract
        'from_date',
        'to_date',

        'designation_id',
        'branch_id',
        'department_id',

    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
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
