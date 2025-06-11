<?php

namespace App\Modules\Payroll\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IncomeSetupReferenceSalaryType extends Model
{
    protected $fillable = [
        'income_setup_id',
        'percentage',
        'salary_type'
    ];
    public function getSalaryType($salary_type){
        switch ($salary_type) {
            case 1:
                $salary_type = "Basic Salary";
                break;

            case 2:
                $salary_type = "Gross Salary";
                break;

            case 3:
                $salary_type = "Grade";
                break;
        }
        return $salary_type;
    }

    public function getSalaryTypeCode($salary_type){
        switch ($salary_type) {
            case 1:
                $salary_type = "BS";
                break;

            case 2:
                $salary_type = "G";
                break;

            case 3:
                $salary_type = "GR";
                break;
        }
        return $salary_type;
    }

    public function getIncome(){
        return $this->hasOne(IncomeSetup::class,'id','income_setup_id');
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
