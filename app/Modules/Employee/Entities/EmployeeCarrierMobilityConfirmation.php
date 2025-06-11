<?php

namespace App\Modules\Employee\Entities;


use Illuminate\Database\Eloquent\Model;

class EmployeeCarrierMobilityConfirmation extends Model
{

    protected $table = 'employee_career_mobility_confirmations';

    protected $fillable = [
        'employee_id',
        'letter_issue_date',
        'confirmation_date',
        'contract_type',
        'contract_start_date',
        'contract_end_date',
        'designation_id',
        'branch_id',
        'department_id'
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
