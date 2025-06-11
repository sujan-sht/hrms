<?php

namespace App\Modules\Setting\Entities;

use App\Modules\Organization\Entities\Organization;
use Illuminate\Database\Eloquent\Model;

class HierarchySetup extends Model
{
    protected $fillable = [
        'organization_id'
    ];

    public function getOrganization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }

    public function getOrganizationDepartments()
    {
        return $this->hasMany(OrganizationDepartment::class, 'hierarchy_setup_id', 'id');
    }

    public function getOrganizationDesignations()
    {
        return $this->hasMany(OrganizationDesignation::class, 'hierarchy_setup_id', 'id');
    }

    public function getOrganizationLevels()
    {
        return $this->hasMany(OrganizationLevel::class, 'hierarchy_setup_id', 'id');
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
