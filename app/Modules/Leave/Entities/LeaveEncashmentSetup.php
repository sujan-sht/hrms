<?php

namespace App\Modules\Leave\Entities;

use App\Modules\Organization\Entities\Organization;
use App\Modules\Payroll\Entities\IncomeSetup;
use Illuminate\Database\Eloquent\Model;

class LeaveEncashmentSetup extends Model
{
    protected $fillable = [
        'organization_id',
        'income_type',
        'month'
    ];

    public function organization(){
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function incomeSetup(){
        return $this->belongsTo(IncomeSetup::class, 'income_type');
    }

     protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Created post: ' . $model);
        });

        static::updated(function ($model) {
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
