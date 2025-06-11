<?php

namespace App\Modules\Labour\Entities;

use App\Modules\Attendance\Entities\LabourAttendanceMonthly;
use App\Modules\Organization\Entities\Organization;
use Illuminate\Database\Eloquent\Model;

class Labour extends Model
{

    protected $guarded = [];
    protected $appends = array('full_name');
    const FILE_PATH = '/uploads/labour/';
    public function getSkillType($skill_type)
    {
        return SkillSetup::find($skill_type)->first()->category;
    }

    public function skillType()
    {
        return $this->belongsTo(SkillSetup::class,'skill_type','id');
    }

    public function organizationModel()
    {
        return $this->belongsTo(Organization::class,'organization','id');
    }

    public function getLabourSingleAttendance($field, $date)
    {
        return $this->hasOne(LabourAttendanceMonthly::class, 'employee_id', 'id')->where($field, $date)->first();
    }

    public function countPresentDays($labour_id,$startDate, $endDate)
    {
        return LabourAttendanceMonthly::where('employee_id',$labour_id)->where('date','>=',$startDate)->where('date','<=',$endDate)->where('is_present','=',11)->count();

    }
    public function checkIfPaymentExists($labour_id,$year, $month)
    {
        return LabourPayment::where('employee_id',$labour_id)->where('nep_year',$year)->where('nep_month',$month)->exists();

    }

    protected function getFullNameAttribute()
    {
        if (isset($this->middle_name)) {
            $fullName = $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name;
        } else {
            $fullName = $this->first_name . ' ' . $this->last_name;
        }

        return $fullName;
    }

    public static function getLabourList()
    {
        $data = [];

        $labourModels = Labour::get();

        if ($labourModels->count() > 0) {
            foreach ($labourModels as $employeeModel) {
                $data[$employeeModel->id] = $employeeModel->full_name;
            }
        }
        return $data;
    }

    public static function getOrganizationwiseLabour($organization_id)
    {
        $employees = [];

        $models = Labour::when(true, function ($query) use ($organization_id) {
            $query->where('organization', $organization_id);
        })->get();
        if ($models) {
            foreach ($models as $model) {
                $employees[$model->id] = $model->full_name;
            }
        }

        return $employees;
    }

     public static function boot()
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
