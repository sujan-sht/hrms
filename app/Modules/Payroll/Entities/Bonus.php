<?php

namespace App\Modules\Payroll\Entities;

use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Organization\Entities\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bonus extends Model
{
    protected $fillable = [
        'organization_id',
        'calendar_type',
        'year',
        'month'
    ];

    /**
     *
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
    public function bonusEmployees()
    {
        return $this->hasMany(BonusEmployee::class)->orderBy('employee_id', 'ASC');
    }

    public function bonusEmployee()
    {
        return $this->hasOne(BonusEmployee::class);
    }

    /**
     *
     */
    public function getIncomes()
    {
        $result = [];

        $bonusIncomeModels = BonusIncome::where('bonus_id', $this->id)->where('bonus_employee_id', $this->bonusEmployee->id)->orderBy('id', 'ASC')->get();
        if($bonusIncomeModels) {
            foreach ($bonusIncomeModels as $bonusIncomeModel) {
                $result[$bonusIncomeModel->bonus_setup_id] = optional($bonusIncomeModel->bonusSetup)->title;
            }
        }

        return $result;
    }

    public function getMonthTitleAttribute()
    {
        $title = '';
        $dateConverter = new DateConverter();

        if($this->calendar_type == 'eng') {
            $title = $dateConverter->_get_english_month($this->month);
        } else {
            $title = $dateConverter->_get_nepali_month($this->month);
        }

        return $title;
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
                ->log('Deleted post: ');
        });
    }
}
