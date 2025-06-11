<?php

namespace App\Modules\Leave\Entities;

use App\Modules\Employee\Entities\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveEncashmentLog extends Model
{
    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'encashment_threshold',
        'leave_remaining',
        'exceeded_balance',
        'total_balance',
        'eligible_encashment',
        'encashed_date',
        'encashed_amount',
        'status',
        'is_valid'
    ];

    const STATUS = [
        '1' => 'Pending',
        '2' => 'Encashed',
    ];

    public function getStatus()
    {
        return LeaveEncashmentLog::STATUS[$this->status ?? 1];
    }

    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function leaveType(){
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }

    public function leaveEncashmentLogActivity(){
        return $this->hasOne(LeaveEncashmentLogActivity::class,'leave_encashment_log_id','id');
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
