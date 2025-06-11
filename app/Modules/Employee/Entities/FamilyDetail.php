<?php

namespace App\Modules\Employee\Entities;

use App\Modules\Dropdown\Entities\Dropdown;
use Illuminate\Database\Eloquent\Model;

class FamilyDetail extends Model
{
    protected $fillable = [
        'employee_id',
        'name',
        'relation',
        'contact',
        'dob',
        'is_emergency_contact',
        'is_dependent',
        'include_in_medical_insurance',
        'same_as_employee',
        'family_address',
        'late_status',
        'is_nominee_detail',
        'province_id',
        'district_id',
        'municipality',
        'ward_no',
        'status',
        'approved_by'
    ];

    public function relationInfo()
    {
        return $this->belongsTo(Dropdown::class, 'relation');
    }

    public function approvedBy()
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    /**
     * Get the province that owns the FamilyDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }

    /**
     * Get the district that owns the FamilyDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }


    /**
     * Get the employeeAddress that owns the FamilyDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employeeAddress()
    {
        return $this->belongsTo(Employee::class, 'same_as_employee', 'id');
    }


    public function getRelationTypeTitleAttribute()
    {
        $title = '';

        if ($this->relation) {
            $list = Self::relationType();
            if (isset($list[$this->relation])) {
                $title = $list[$this->relation];
            } else {
                $title = '-';
            }
        }
        return $title;
    }

    public static function getRelationTypeId($string)
    {
        $id = null;

        if ($string) {
            $list = array_flip(Self::relationType());
            $id = $list[$string];
        }

        return $id;
    }

    public static function relationType()
    {
        return [
            '1' => 'Grand Father',
            '2' => 'Grand Mother',
            '3' => 'Father',
            '4' =>  'Mother',
            '5' =>  'Brother',
            '6' =>  'Sister',
            '7' =>  'Son',
            '8' =>  'Daughter',
            '9' =>  'Spouse',
            '10' => 'Wife'
        ];
    }

    public static function boot()
    {
        parent::boot();

        Self::creating(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Created post: ' . $model);
        });

        Self::updating(function ($model) {
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
