<?php

namespace App\Modules\Advance\Entities;

use App\Modules\Employee\Entities\Employee;
use App\Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;

class Advance extends Model
{
    protected $fillable = [
        'organization_id',
        'employee_id',
        'advance_amount',
        'from_date',
        'settlement_type',
        'remaining_amount',
        'created_by',
        'updated_by',
        'status',
        'approval_status'
    ];

    /**
     * Relation with settlement
     */
    public function settlementModels()
    {
        return $this->hasMany(AdvanceSettlement::class);
    }

    /**
     * Relation with settlement
     */
    public function settlementPaymentModels()
    {
        return $this->hasMany(AdvanceSettlementPayment::class);
    }

    /**
     * Relation with employee
     */
    public function employeeModel()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     * Relation with user
     */
    public function creatorUserModel()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     *
     */
    public function getSettlementTypeTitleAttribute()
    {
        switch ($this->settlement_type) {
            case '1':
                $title = 'One-Time Pay';
                break;
            case '2':
                $title = 'Partially Pay';
                break;
            default:
                $title = 'Monthly EMI Pay';
                break;
        }

        return $title;
    }

    /**
     *
     */
    public function getStatusDetailAttribute()
    {
        $list = Self::statusList();

        switch ($this->status) {
            case '2':
                $color = 'primary';
                break;
            case '3':
                $color = 'success';
                break;
            default:
                $color = 'secondary';
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
            '1' => 'Pending',
            '2' => 'Partially Settled',
            '3' => 'Fully Settled'
        ];
    }
    public static function approvalStatusList()
    {
        return [
            '1' => 'Pending',
            '2' => 'Forwarded',
            '3' => 'Accepted',
            '4' => 'Rejected'
        ];
    }
    public function getApprovalStatus()
    {
        $list = Self::approvalStatusList();
        return $list[$this->approval_status];
    }
    public function getApprovalStatusDetailAttribute()
    {
        $list = Self::approvalStatusList();

        switch ($this->approval_status) {
            case '2':
                $color = 'primary';
                break;
            case '3':
                $color = 'success';
                break;
            case '4':
                $color = 'danger';
                break;
            default:
                $color = 'secondary';
                break;
        }
        if ($this->approval_status) {
            return [
                'title' => $list[$this->approval_status],
                'color' => $color
            ];
        } else {
            return [
                'title' => 'Pending',
                'color' => $color
            ];
        }
    }

    /**
     * Boot function
     */
    public static function boot()
    {
        parent::boot();

        Self::creating(function ($model) {
            $model->created_by = auth()->user()->id;
        });

        Self::updating(function ($model) {
            $model->updated_by = auth()->user()->id;
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
