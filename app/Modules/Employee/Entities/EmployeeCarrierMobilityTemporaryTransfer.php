<?php

namespace App\Modules\Employee\Entities;


use Illuminate\Database\Eloquent\Model;

class EmployeeCarrierMobilityTemporaryTransfer extends Model
{

    protected $table = 'employee_career_mobility_temporary_transfers';

    protected $fillable = [
        'employee_id',
        'branch_id',
        'letter_issue_date',
        'transfer_from_date',
        'transfer_to_date',
        'effective_date'
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
