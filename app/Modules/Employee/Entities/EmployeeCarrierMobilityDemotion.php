<?php

namespace App\Modules\Employee\Entities;


use Illuminate\Database\Eloquent\Model;

class EmployeeCarrierMobilityDemotion extends Model
{

    protected $table = 'employee_career_mobility_demotions';

    protected $fillable = [
        'employee_id',
        'contract_type',
        'letter_issue_date',
        'demotion_date',
        'demotion_to',
        'location',
        'department_id',
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

      public static function boot()
    {
        parent::boot();

        Self::creating(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Created post: ' . $model);
        });

        Self::updating(function ($model) {
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
