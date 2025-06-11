<?php

namespace App\Modules\Holiday\Entities;

use App\Modules\Branch\Entities\Branch;
use App\Modules\Organization\Entities\Organization;
use App\Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Holiday extends Model
{

    const GENDER =    [
        1 => "All",
        2 => "Female",
        3 => "Male"
    ];

    const RELIGION = [
        1 => "All",
        2 => "Hinduism",
        3 => "Buddhism",
        4 => "Christianity",
        5 => "Islam",
        6 => "Kirat"
    ];

    protected $fillable = ['title', 'fiscal_year_id', 'organization_id', 'branch_id', 'province_id', 'district_id','group_id','calendar_type', 'gender_type','religion_type', 'status', 'is_festival','apply_for_all', 'created_by'];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relation with organization
     */
    public function organizationModel()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    /**
     * Relation with branch
     */
    public function branchModel()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function holidayDetail()
    {
        return $this->hasMany(HolidayDetail::class, 'holiday_id');
    }

    public function getSingleHolidayDetail()
    {
        return $this->hasOne(HolidayDetail::class, 'holiday_id');
    }

    public function getGenderType()
    {
        return Holiday::GENDER[$this->gender_type ?? 1];
    }

    public function getReligionType()
    {
        return Holiday::RELIGION[$this->religion_type ?? 1];
    }

    public function scopeGetEmployeeWiseHoliday($query, $emp, $genderFlag = false, $religionFlag = false)
    {
        $query->where(function ($q) use ($emp) {
            $q->whereHas('organizationModel', function ($k) use ($emp) {
                $k->where('organization_id', $emp->organization_id);
            });
            $q->orWhereNull('organization_id');
        });

        $query->where(function ($q) use ($emp){
            $q->whereHas('branchModel', function ($k) use ($emp) {
                $k->where('branch_id', $emp->branch_id);
            });
            $q->orWhereNull('branch_id');
        });

        if ($genderFlag) {
            $genderType = optional($emp->getGender()->first())->dropvalue;
            if ($genderType == 'Male') {
                $genderType = 3;
            } elseif ($genderType == 'Female') {
                $genderType = 2;
            }
            $query->whereIn('gender_type', [1, $genderType]);
        }
        if ($religionFlag) {
            $religionType = $emp->religion;
            $query->whereIn('religion_type', [1, $religionType]);
        }
        return $query;
    }

    public static function boot()
    {
        parent::boot();

        Self::creating(function ($model) {
            $model->created_by = auth()->user()->id;
        });

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
