<?php

namespace App\Modules\Employee\Entities;

use App\Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;

class EmployeeSubstituteLeave extends Model
{
    protected $fillable = [
        'leave_type_id',
        'employee_id',
        'date',
        'nepali_date',
        'leave_kind',
        'remark',
        'status',
        'is_expired',
        'forwarded_remarks',
        'rejected_remarks',
        'forwarded_by',
        'rejected_by',
        'accepted_by',
        'cancelled_by',
        'checkin',
        'checkout',
        'total_working_hr'
    ];

    /**
     * Relation with employee
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function employeeSubstituteLeaveClaim()
    {
        return $this->belongsTo(EmployeeSubstituteLeaveClaim::class, 'id', 'employee_substitute_leave_id');
    }

    /**
     * Status list
     */
    public static function statusList()
    {
        return [
            '1' => 'Pending',
            '2' => 'Forwarded',
            '3' => 'Accepted',
            '4' => 'Rejected'
        ];
    }

    /**
     *
     */
    public function getStatus()
    {
        $list = Self::statusList();
        return $list[$this->status];
    }

    /**
     *
     */
    public function getStatusWithColor()
    {
        $list = Self::statusList();

        switch ($this->status) {
            case '2':
                $color = 'primary';
                $title = 'Forwarded';
                break;
            case '3':
                $color = 'success';
                $title = 'Accepted';
                break;
            case '4':
                $color = 'danger';
                $title = 'Rejected';
                break;
            default:
                $color = 'secondary';
                $title = 'Pending';
                break;
        }

        return [
            'status' => $title,
            'color' => $color
        ];
    }

    public function forwardedUser()
    {
        return $this->belongsTo(User::class, 'forwarded_by');
    }

    public function rejectedUser()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function acceptedUser()
    {
        return $this->belongsTo(User::class, 'accepted_by');
    }

    public function cancelledUser()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
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
