<?php

namespace App\Modules\Payroll\Entities;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Organization\Entities\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StopPayment extends Model
{
    protected $fillable = ['organization_id','employee_id','from_date','to_date','nep_from_date','nep_to_date','exclude_ssf','notes','status'];

    public function organizationModel()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
    public function employeeModel()
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
