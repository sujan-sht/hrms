<?php

namespace App\Modules\Employee\Entities;


use Illuminate\Database\Eloquent\Model;

class EmployeeCarrierMobilityProbationaryPeriod extends Model
{

    protected $table = 'employee_career_mobility_probationary_periods';

    protected $fillable = [
        'employee_id',
        'contract_type',
        'letter_issue_date',
        'extension_from_date',
        'extension_till_date',
        'attachment',
        'remarks'
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
