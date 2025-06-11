<?php

namespace App\Modules\Employee\Entities;

use App\Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Employee\Entities\Employee;

class EmployeeAttendanceApprovalFlow extends Model
{
       protected $fillable = [
        'employee_id',
        'first_approval_user_id',
        'second_approval_user_id',
        'third_approval_user_id',
        'last_approval_user_id',
        'created_by',
        'updated_by'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function userFirstApproval()
    {
        return $this->belongsTo(User::class, 'first_approval_user_id');
    }

    public function userSecondApproval()
    {
        return $this->belongsTo(User::class, 'second_approval_user_id');
    }

    public function userThirdApproval()
    {
        return $this->belongsTo(User::class, 'third_approval_user_id');
    }

    public function userLastApproval()
    {
        return $this->belongsTo(User::class, 'last_approval_user_id');
    }

    public function firstApproval()
    {
        return $this->belongsTo(User::class, 'first_approval');
    }

    public function lastApproval()
    {
        return $this->belongsTo(User::class, 'last_approval');
    }

  public static function saveOrUpdate($employeeId, $data)
{
    $emp = EmployeeAttendanceApprovalFlow::updateOrCreate(
        ['employee_id' => $employeeId],
        $data
    );

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
