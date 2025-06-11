<?php

namespace App\Modules\Employee\Entities;

use App\Modules\Dropdown\Entities\Dropdown;
use Illuminate\Database\Eloquent\Model;

class BenefitDetail extends Model
{

    protected $fillable = [
        'employee_id',
        'benefit_type_id',
        'plan',
        'coverage',
        'effective_date',
        'employee_contribution',
        'company_contribution',
    ];

    public function benefitTypeInfo()
    {
        return $this->belongsTo(Dropdown::class, 'benefit_type_id');
    }

    // public function getBenefitTypeTitleArttribute()
    // {
    //     $title = '';

    //     if($this->relation) {
    //         $list = Self::benefitType();
    //         $title = $list[$this->relation];
    //     }

    //     return $title;
    // }

    // public static function getBenefitTypeId($string)
    // {
    //     // dd($string);
    //     $id = null;

    //     if($string) {
    //         $list = array_flip(Self::benefitType());
    //         $id = $list[$string];
    //     }
    //     // dd($id);
    //     return $id;
    // }

    // public static function benefitType()
    // {
    //     return [
    //         '1' => 'Insurance',
    //     ];
    // }

    public function getCoverageTitleAttribute()
    {
        $title = '';

        if($this->coverage) {
            $list = Self::coverage();
            $title = $list[$this->coverage];
        }

        return $title;
    }

    public static function getCoverageId($string)
    {
        // dd($string);
        $id = null;

        if($string) {
            $list = array_flip(Self::coverage());
            $id = $list[$string];
        }
        // dd($id);
        return $id;
    }

    public static function coverage()
    {
        return [
            '1' => 'Fully',
            '2' => 'Partially',
        ];
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
