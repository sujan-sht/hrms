<?php

namespace App\Modules\Branch\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Organization\Entities\Organization;
use App\Modules\Setting\Entities\District;
use App\Modules\Setting\Entities\ProvincesDistrict;

class Branch extends Model
{
    protected $fillable = [
        'organization_id',
        'name',
        'provinces_districts_id',
        'district_id',
        'location',
        'contact',
        'email',
        'manager_id',
        'branche_code',
        'created_by',
        'updated_by',
        'remote_allowance'
    ];

    /**
     * Relation with organization
     */
    public function organizationModel()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    /**
     * Relation with employee
     */
    public function managerEmployeeModel()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function districtModel()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function ProvincesModel()
    {
        return $this->belongsTo(ProvincesDistrict::class, 'provinces_districts_id');
    }

    public function branchDayOff()
    {
        return $this->hasMany(BranchDayOff::class, 'branch_id', 'id');
    }

    public function getBranchDayList()
    {
        return $this->branchDayOff->pluck('day_off', 'id')->toArray();
    }
    /**
     * boot function for user tracking
     */
    public static function boot()
    {
        parent::boot();

        Self::creating(function ($model) {
            $model->created_by = auth()->user()->id;
        });

        Self::updating(function ($model) {
            $model->updated_by = auth()->user()->id;
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
