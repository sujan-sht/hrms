<?php

namespace App\Modules\Payroll\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BonusIncome extends Model
{
    protected $fillable = [
        'bonus_id',
        'bonus_employee_id',
        'bonus_setup_id',
        'value'
    ];

    /**
     * Relation with payroll
     */
    public function bonus()
    {
        return $this->belongsTo(Bonus::class, 'bonus_id');
    }

    /**
     * Relation with payroll employee
     */
    public function bonusEmployee()
    {
        return $this->belongsTo(BonusEmployee::class, 'bonus_employee_id')->orderBy('bonus_setup_id','ASC');
    }
    public function bonusSetup()
    {
        return $this->belongsTo(BonusSetup::class, 'bonus_setup_id');
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
