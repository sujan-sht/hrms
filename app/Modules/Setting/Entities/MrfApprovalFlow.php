<?php

namespace App\Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Organization\Entities\Organization;

class MrfApprovalFlow extends Model
{
    protected $fillable = [
        'organization_id',
        'first_approval_emp_id',
        'second_approval_emp_id',
        'third_approval_emp_id',
        'fourth_approval_emp_id'
    ];

    /**
     * Relation with organization
     */
    public function organizationModel() {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    /**
     * Relation with employee
     */
    public function firstApprovalEmployeeModel() {
        return $this->belongsTo(Employee::class, 'first_approval_emp_id');
    }

    /**
     * Relation with employee
     */
    public function secondApprovalEmployeeModel() {
        return $this->belongsTo(Employee::class, 'second_approval_emp_id');
    }

    /**
     * Relation with employee
     */
    public function thirdApprovalEmployeeModel() {
        return $this->belongsTo(Employee::class, 'third_approval_emp_id');
    }

    /**
     * Relation with employee
     */
    public function fourthApprovalEmployeeModel() {
        return $this->belongsTo(Employee::class, 'fourth_approval_emp_id');
    }

    /**
     *
     */
    public static function getOrganizationwiseApprovalFlow($organizationId)
    {
        return MrfApprovalFlow::where('organization_id', $organizationId)->first();
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
