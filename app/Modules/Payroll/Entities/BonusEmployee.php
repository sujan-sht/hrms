<?php

namespace App\Modules\Payroll\Entities;

use App\Modules\Employee\Entities\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BonusEmployee extends Model
{
    protected $fillable = [
        'bonus_id',
        'employee_id',
        'total_income',
        'tds',
        'payable_salary'
    ];
    public function bonus()
    {
        return $this->belongsTo(Bonus::class, 'bonus_id');
    }

    /**
     * Relation with payroll
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    // public function ssf(){
    //     return $this->hasmany(PayrollDeduction::class)->orderBy('deduction_setup_id', 'ASC');
    // }

    /**
     * Relation with payroll income
     */
    public function incomes()
    {
        return $this->hasMany(BonusIncome::class)->orderBy('id', 'ASC');
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
