<?php

namespace App\Modules\Employee\Entities;


use Illuminate\Database\Eloquent\Model;

class EmployeeCarrierMobilityAppointment extends Model
{

    protected $table = 'employee_career_mobility_appointments';

    protected $fillable = [
        'employee_id',
        'created_by',
        'updated_by',
        'letter_issue_date',
        'appointment_date',
        'effective_date',
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }


    public static function boot()
    {
        parent::boot();

        Self::creating(function ($model) {
            $model->created_by = auth()->user()->id;
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Created post: ' . $model);
        });

        Self::updating(function ($model) {
            $model->updated_by = auth()->user()->id;
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
