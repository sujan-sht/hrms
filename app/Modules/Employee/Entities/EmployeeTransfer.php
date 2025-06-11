<?php

namespace App\Modules\Employee\Entities;

use App\Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Organization\Entities\Organization;

class EmployeeTransfer extends Model
{
    protected $fillable = [
        'employee_id',
        'transfer_date',
        'from_org_id',
        'to_org_id',
        'remarks',
        'created_by',
        'updated_by',
        'status'
    ];

    /**
     * Realtion with employee
     */
    public function employeeModel() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     * Realtion with organization
     */
    public function fromOrganizationModel() {
        return $this->belongsTo(Organization::class, 'from_org_id');
    }

    /**
     * Realtion with organization
     */
    public function toOrganizationModel() {
        return $this->belongsTo(Organization::class, 'to_org_id');
    }

    /**
     * Realtion with user
     */
    public function creatorModel() {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get status with detail
     */
    public function getStatusDetailAttribute()
    {
        $list = Self::getStatusList();

        switch ($this->status) {
            case '11':
                $color = 'primary';
                $title = 'Forwarded';
            break;
            case '12':
                $color = 'success';
                $title = 'Accepted';
            break;
            case '13':
                $color = 'danger';
                $title = 'Rejected';
            break;
            default:
                $color = 'secondary';
                $title = 'Pending';
            break;
        }

        return [
            'color' => $color,
            'title' => $title
        ];
    }

    /**
     * Status list
     */
    public static function getStatusList()
    {
        return [
            '10' => 'Pending',
            // '11' => 'Forward',
            '12' => 'Accept',
            '13' => 'Reject'
        ];
    }

    /**
     * 
     */
    public static function boot() {
        parent::boot();

        Self::creating(function ($model) {
            $model->created_by = auth()->user()->id;
        });

        Self::updating(function ($model) {
            $model->updated_by = auth()->user()->id;
        });
    }
}
