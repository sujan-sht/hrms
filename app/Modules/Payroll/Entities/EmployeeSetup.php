<?php

namespace App\Modules\Payroll\Entities;

use App\Modules\Employee\Entities\Employee;
use Illuminate\Database\Eloquent\Model;

class EmployeeSetup extends Model
{
    protected $fillable = ['employee_id','organization_id','reference','reference_id','status','amount'];


    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function deduction()
    {
        return $this->belongsTo(DeductionSetup::class, 'reference_id', 'id');
    }
    public function income()
    {
        return $this->belongsTo(IncomeSetup::class, 'reference_id', 'id');
    }

     protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Created post: ' );
        });

        static::updated(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Updated post: ' );
        });

        static::deleted(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Deleted post: ' . $model);
        });
    }
}
