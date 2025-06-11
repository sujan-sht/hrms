<?php

namespace App\Modules\Employee\Entities;

use App\Helpers\DateTimeHelper;
use App\Modules\Branch\Entities\Branch;
use App\Modules\Dropdown\Entities\Dropdown;
use App\Modules\Organization\Entities\Organization;
use App\Modules\Setting\Entities\Department;
use App\Modules\Setting\Entities\Designation;
use App\Modules\Setting\Entities\Level;
use Illuminate\Database\Eloquent\Model;

class EmployeeCarrierMobility extends Model
{

    protected $fillable = [
        'employee_id',
        'date',
        'type_id',
        'organization_id',
        'branch_id',
        'department_id',
        'level_id',
        'designation_id',
        'job_title',
        'probation_status',
        'payroll_change',
        'created_by',
        'updated_by',
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }
    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    public static function typeList()
    {
        return [
            'Appointment' => 'Appointment',
            'Promotion' => 'Promotion',
            'Demotion' => 'Demotion',
            'Confirmation' => 'Confirmation',
            'Extension of Probationary Period' => 'Extension of Probationary Period',
            'Transfer' => 'Transfer',
            'Temporary Transfer' => 'Temporary Transfer'
        ];
    }

    public function getProbationStatusList()
    {
        $list = Self::probationStatusList();
        return $list[$this->probation_status];
    }

    public static function probationStatusList()
    {
        return [
            10 => 'No',
            11 => 'Yes',
        ];
    }

    public function getPayrollChangeList()
    {
        $list = Self::payrollChangeList();
        return $list[$this->payroll_change];
    }

    public static function payrollChangeList()
    {
        return [
            1 => 'Increment',
            2 => 'Decrement',
        ];
    }
    public static function getTypewiseName($mobilityData)
    {

        switch ($mobilityData['type_id']) {
            case 1:
                $description = ' to ' . optional($mobilityData->organization)->name;
                break;
            case 2:
                $description = ' to ' . optional($mobilityData->branch)->name;
                break;
            case 3:
                $description = ' to ' . optional($mobilityData->department)->title;
                break;
            case 4:
                $description = ' to ' . optional($mobilityData->level)->title;
                break;
            case 5:
                $description =  ' to ' . optional($mobilityData->designation)->title;
                break;
            case 6:
                $description = ' to ' . $mobilityData->job_title ?? '';
                break;
            case 7:
                $description = ' to ' . $mobilityData->probation_status ? $mobilityData->getProbationStatusList() : '';
                break;
            case 8:
                $description = ' to ' . $mobilityData->payroll_change ? $mobilityData->getPayrollChangeList() : '';
                break;
            default:
                $description = '';
                break;
        }
        return $description;
    }

    public static function boot()
    {
        parent::boot();

        Self::creating(function ($model) {
            $model->created_by = auth()->user()->id;
             activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Created post: ' . $model->job_title);
        });

        Self::updating(function ($model) {
            $model->updated_by = auth()->user()->id;
             activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Updated post: ' . $model->job_title);
        });

         static::deleted(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Deleted post: ' . $model->job_title);
        });
    }




}
