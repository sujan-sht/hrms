<?php

namespace App\Modules\Employee\Entities;

use App\Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;

class EmployeeAppraisalApprovalFlow extends Model
{

    protected $fillable = [
        'employee_id',
        'first_approval',
        'last_approval',
    ];

    /**
     * Relation with employee
     */
    public function employeeModel()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     * Relation with first approval
     */
    public function firstApprovalUserModel()
    {
        return $this->belongsTo(User::class, 'first_approval');
    }

    /**
     * Relation with last approval
     */
    public function lastApprovalUserModel()
    {
        return $this->belongsTo(User::class, 'last_approval');
    }

    /**
     * static save function
     */
    public static function checkAndSaveAppraisalApprovalFlow($data)
    {
        $model = Self::where('employee_id', $data['employee_id'])->first();
        if (!$model) {
            $model = new Self();
            $model->employee_id = $data['employee_id'];
        }

        $model->first_approval = $data['appraisal_first_approval'];
        $model->last_approval = $data['appraisal_last_approval'];
        $model->save();

        return true;
    }

     protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Created post: ');
        });

        static::updated(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Updated post: ');
        });

        static::deleted(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Deleted post: ');
        });
    }

}
