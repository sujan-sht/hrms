<?php

namespace App\Modules\Employee\Entities;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class EmployeePayrollRelatedDetail extends Model
{
    protected $fillable = [
        'employee_id',
        'join_date',
        'basic_salary',
        'dearness_allowance',
        'lunch_allowance',
        'dashain_allowance',
        'insurance_premium',
        'contract_type',
        'contract_start_date',
        'contract_end_date',
        'probation_status',
        'payroll_change',
        'ot',
        'probation_period_days',
        'probation_start_date',
        'probation_end_date',
        'account_no',
        'created_by',
        'updated_by'
    ];
    const JOB_TYPE = [
        100 => 'All',
        10 => 'Regular',
        11 => 'Contract'
    ];
    /**
     * Relation with employee
     */
    public function employeeModel()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     *
     */
    public static function saveData($employeeId, $data)
    {
        // if (isset($data['join_date']) && isset($data['probation_period_days'])) {
        //     $data['probation_end_date'] = date('Y-m-d', strtotime("+" . $data['probation_period_days'] . " days", strtotime($data['join_date'])));
        // }
        if ($data['contract_type'] == 10 && $data['probation_status'] == 11 && $data['probation_start_date'] != null) {
            $data['contract_start_date'] = $data['contract_nep_start_date'] = null;
            $data['contract_end_date'] = $data['contract_nep_end_date'] = null;
        }

        $model = EmployeePayrollRelatedDetail::where('employee_id', $employeeId)->first();
        if ($model) {
            ($model->update($data));
            $result = EmployeePayrollRelatedDetail::find($model->id);
        } else {
            $data['employee_id'] = $employeeId;
            $result = EmployeePayrollRelatedDetail::create($data);
        }

        return $result;
    }

    public  function getJobType()
    {
        return $this->job_type ? self::JOB_TYPE[$this->job_type] : null;
    }

    /**
     *
     */
    public static function boot()
    {
        parent::boot();

        Self::creating(function ($model) {
            $authUser = Auth::user();
            if ($authUser) {
                $model->created_by = $authUser->id;
            } else {
                $model->created_by = 1;
            }

            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Created post: ' . $model);
        });

        Self::updating(function ($model) {
            $authUser = Auth::user();
            if ($authUser) {
                $model->updated_by = $authUser->id;
            } else {
                $model->created_by = 1;
            }

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
