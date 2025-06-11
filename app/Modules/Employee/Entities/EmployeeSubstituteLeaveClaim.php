<?php

namespace App\Modules\Employee\Entities;

use App\Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;

class EmployeeSubstituteLeaveClaim extends Model
{
    protected $guarded = [];

    public function employeeSubstituteLeave()
    {
        return $this->belongsTo(EmployeeSubstituteLeave::class,'employee_substitute_leave_id');
    }
     /**
     * Claim Status list
     */
    public static function claimStatusList()
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
    public function getClaimStatus()
    {
        $list = Self::claimStatusList();
        return $list[$this->claim_status];
    }


    public function getClaimStatusWithColor()
    {
        $list = Self::claimStatusList();

        switch ($this->claim_status) {
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
            case '1' :
                $color = 'secondary';
                $title = 'Pending';
                break;
            default:
                $color = 'warning';
                $title = 'N/A';
                break;
        }

        return [
            'claim_status' => $title,
            'color' => $color
        ];
    }


    public function forwardedUser() {
        return $this->belongsTo(User::class, 'forwarded_by');
    }

    public function rejectedUser() {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function acceptedUser() {
        return $this->belongsTo(User::class, 'accepted_by');
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
