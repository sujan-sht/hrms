<?php

namespace App\Modules\Employee\Entities;

use App\Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;

class EmployeeClaimRequestApprovalFlow extends Model
{
    protected $fillable = [
        'employee_id',
        'first_claim_approval_user_id',
        'last_claim_approval_user_id',
        'created_by',
        'updated_by'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public static function saveData($employeeId, $data)
    {
        $model = EmployeeClaimRequestApprovalFlow::where('employee_id', $employeeId)->first();
        if ($model) {
            $model->update($data);
            $result = EmployeeClaimRequestApprovalFlow::find($model->id);
        } else {
            $data['employee_id'] = $employeeId;
            $result = EmployeeClaimRequestApprovalFlow::create($data);
        }
        return $result;
    }

    public function firstApproval()
    {
        return $this->belongsTo(User::class, 'first_claim_approval_user_id');
    }

    public function lastApproval()
    {
        return $this->belongsTo(User::class, 'last_claim_approval_user_id');
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
