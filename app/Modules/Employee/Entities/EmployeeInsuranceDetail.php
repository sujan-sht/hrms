<?php

namespace App\Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;

class EmployeeInsuranceDetail extends Model
{
    protected $fillable = [
        'employee_id',
        'gpa_enable',
        'gpa_sum_assured',
        'medical_coverage',
        'individual',
        'spouse',
        'kid_one',
        'kid_two',
        'mom',
        'dad',
        'gmi_enable',
        'gmi_sum_assured',
        'hospitality_in_perc',
        'hospitality_in_amt',
        'domesticality_in_perc',
        'domesticality_in_amt',
        'company_name',
        'individual_or_fam'
    ];

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
