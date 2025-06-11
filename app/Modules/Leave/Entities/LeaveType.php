<?php

namespace App\Modules\Leave\Entities;

use App\Modules\Dropdown\Entities\Dropdown;
use App\Modules\Employee\Entities\EmployeeLeave;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Organization\Entities\Organization;

class LeaveType extends Model
{
    protected $casts = [
        'employee_ids' => 'array',
    ];

    protected $fillable = [
        'organization_id',
        'fiscal_year_id',
        'name',
        'code',
        'leave_type',
        'gender',
        'marital_status',
        'number_of_days',
        'description',
        'show_on_employee',
        'prorata_status',
        'encashable_status',
        'max_encashable_days',
        'half_leave_status',
        'half_leave_type',
        'carry_forward_status',
        'sandwitch_rule_status',
        'pre_inform_days',
        'max_per_day_leave',
        'status',
        'job_type',
        'contract_type',
        'fixed_remaining_leave',
        'max_substitute_days',
        'created_by',
        'updated_by',
        'leave_year_id',
        'employee_ids',
        'advance_allocation'
    ];

    const CONTRACT = [
        100 => 'All',
        10 => 'Regular',
        11 => 'Contract',
        12 => 'Permanent'
    ];

    // const JOB_TYPE = [
    //     100 => 'All',
    //     10 => 'Permanent',
    //     11 => 'Probation',
    //     12 => 'Contract',
    //     // 13 => 'CONTRACT',
    //     14 => 'Trainee',
    //     15 => 'Outsource'
    // ];

    const JOB_TYPE = [
        100 => 'All',
        10 => 'Regular',
        11 => 'Contract'
    ];

     public  function getJobType()
    {
        return $this->contract_type ? self::JOB_TYPE[$this->contract_type] : null;
    }
    /**
     * Relation with organization
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    /**
     * Relation with department
     */
    public function departments()
    {
        return $this->hasMany(LeaveTypeDepartment::class);
    }

    public function employeeLeave()
    {
        return $this->hasMany(EmployeeLeave::class, 'leave_type_id');
    }

    public function employeeLeaveLatest()
    {
        return $this->hasMany(EmployeeLeave::class, 'leave_type_id')->where('leave_year_id', getCurrentLeaveYearId());
    }

    public function leaveEncashmentLogs()
    {
        return $this->hasMany(LeaveEncashmentLog::class, 'leave_type_id');
    }
    public function employeeLeaveOpening()
    {
        return $this->hasMany(EmployeeLeaveOpening::class, 'leave_type_id');
    }

    public function getSingleDepartment()
    {
        return $this->hasOne(LeaveTypeDepartment::class);
    }

    /**
     * Relation with level
     */
    public function levels()
    {
        return $this->hasMany(LeaveTypeLevel::class);
    }

    public function jobTypes()
    {
        return $this->hasMany(LeaveTypeJobType::class, 'leave_type_id');
    }

    public function getLeaveType()
    {
        $list = Self::leaveTypeList();
        return $list[$this->leave_type];
    }

    public function getGender()
    {
        $list = Self::genderList();
        return $list[$this->gender];
    }

    public function getMaritalStatus()
    {
        $list = Self::maritalStatusList();
        return $list[$this->marital_status];
    }

    public function getGenderInfo()
    {
        return $this->belongsTo(Dropdown::class, 'gender', 'id');
    }

    public function getMaritalStatusInfo()
    {
        return $this->belongsTo(Dropdown::class, 'marital_status', 'id');
    }

    /**
     * Provide list of leave types of specific organization
     */
    public static function getOrganizationwiseLeaveTypes($inputData)
    {
        $result = [];
        $query = LeaveType::query();
        if (isset($inputData['leave_type'])) {
            $query->where('leave_type', $inputData['leave_type']);
        }
        $models = $query->where('organization_id', $inputData['organization_id'])->where('leave_year_id', $inputData['leave_year_id'])->where('status', 11)->get();
        if ($models) {
            foreach ($models as $model) {
                $result[$model->id] = $model->name;
            }
        }
        return $result;
    }

    /**
     *
     */
    public static function leaveTypeList()
    {
        return [
            '10' => 'Paid',
            '11' => 'Unpaid'
        ];
    }

    /**
     *
     */
    public static function genderList()
    {
        return [
            '10' => 'All',
            '11' => 'Male',
            '12' => 'Female'
        ];
    }

    /**
     *
     */
    public static function maritalStatusList()
    {
        return [
            '10' => 'All',
            '11' => 'Unmarried',
            '12' => 'Married'
        ];
    }

    /**
     *
     */
    public function getStatusDetailAttribute()
    {
        $list = Self::statusList();

        switch ($this->status) {
            case '11':
                $color = 'success';
                break;
            default:
                $color = 'danger';
                break;
        }

        return [
            'title' => $list[$this->status],
            'color' => $color
        ];
    }

    /**
     * Status list
     */
    public static function statusList()
    {
        return [
            '11' => 'Active',
            '10' => 'Inactive'
        ];
    }

    /**
     * boot function for user tracking
     */
    public static function boot()
    {
        parent::boot();

        Self::creating(function ($model) {
            $model->created_by = Auth::user()->id;
        });

        Self::updating(function ($model) {
            $model->updated_by = Auth::user()->id;
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
