<?php

namespace App\Modules\Setting\Entities;

use App\Modules\Leave\Entities\LeaveType;
use App\Modules\Organization\Entities\Organization;
use Illuminate\Database\Eloquent\Model;

class LeaveDeductionSetup extends Model
{
    protected $fillable = [
        'organization_id',
        'method',
        'type',
        'max_late_days',
        'deduct_leave_number',
        'leave_type_id',
        'unpaid_leave_type'
    ];

    /**
     * Relation with organization
     */
    public function organizationModel()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    /**
     * Relation with leave type
     */
    public function leaveTypeModel()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }

    /**
     *
     */
    public function getMethodTitleAttribute()
    {
        $list = Self::getMethods();
        if (isset($this->method)) {
            return $list[$this->method];
        }
    }

    /**
     *
     */
    public static function getMethods()
    {
        return [
            '1' => 'Monthly',
        ];
    }
    /**
     *
     */
    public function getTypeTitleAttribute()
    {
        $list = Self::getTypes();
        return $list[$this->type];
    }

    public static function getTypes()
    {
        return [
            1 => 'Missed Check In',
            2 => 'Missed Check Out',
            3 => 'Early Departure',
            4 => 'Late Arrival',
            5 => 'Grace for Penalty for Check Out',
            6 => 'Grace for Penalty for Check In'

            // '1' => 'Grace for Penalty',
            // '2' => 'Missed Check In',
            // '3' => 'Missed Check Out',
            // '4' => 'Late Arrival',
            // '5' => 'Early Departure',
        ];
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
