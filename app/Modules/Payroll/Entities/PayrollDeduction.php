<?php

namespace App\Modules\Payroll\Entities;

use Illuminate\Database\Eloquent\Model;

class PayrollDeduction extends Model
{
    protected $fillable = [
        'payroll_id',
        'payroll_employee_id',
        'deduction_setup_id',
        'value'
    ];

    /**
     * Relation with payroll
     */
    public function payroll()
    {
        return $this->belongsTo(Payroll::class, 'payroll_id');
    }

    /**
     * Relation with payroll employee
     */
    public function payrollEmployee()
    {
        return $this->belongsTo(PayrollEmployee::class, 'payroll_employee_id');
    }

    /**
     * Relation with deduction setup
     */
    public function deductionSetup()
    {
        return $this->belongsTo(DeductionSetup::class, 'deduction_setup_id');
    }

     protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Created post: ');
        });

        static::updated(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Updated post: ');
        });

        static::deleted(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Deleted post: ' . $model);
        });
    }
}
