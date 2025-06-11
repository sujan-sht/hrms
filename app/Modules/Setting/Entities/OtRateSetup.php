<?php

namespace App\Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OtRateSetup extends Model
{
    protected $fillable = [
        'organization_id',
        'ot_type',
        'ot_basis',
        'rate',
        'times_value',
        'is_min_ot_requirement',
        'min_ot_time'
    ];
    const OT_TYPE = [
        1 => 'Standard OT',
        2 => 'Public Holiday OT',
        3 => 'Festival OT'
    ];
    public static function otTypeList()
    {
        return [
            1 => 'Standard OT',
            2 => 'Public Holiday OT',
            3 => 'Festival OT'
        ];
    }
    public function getOtType()
    {
        $list = Self::otTypeList();
        return $list[$this->ot_type];
    }
    public function incomeHeadingDetail(){
        return $this->hasMany(OtRateIncomeHeading::class,'ot_rate_setup_id','id');
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
