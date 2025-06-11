<?php

namespace App\Modules\OvertimeRequest\Entities;

use App\Modules\Employee\Entities\Employee;
use App\Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OvertimeRequest extends Model
{
    const STATUS = [
        '1' => 'Pending',
        '2' => 'Forwarded',
        '3' => 'Approved',
        '4' => 'Rejected',
        '5' => 'Cancelled'
    ];

    const CLAIM_STATUS = [
        '1' => 'Pending',
        '2' => 'Claimed'
    ];

    protected $fillable = [
        'employee_id',
        'date',
        'nepali_date',
        'ot_time',
        'status',
        'eligible_ot_time',
        'claim_status',
        'reject_note',
        'remarks',
        'start_time',
        'end_time',
        'forwarded_remarks',
        'forwarded_by',
        'forwarded_date',
        'approved_remarks',
        'approved_by',
        'approved_date',
        'rejected_remarks',
        'rejected_by',
        'rejected_date'
    ];

    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function getStatus()
    {
        return OvertimeRequest::STATUS[$this->status ?? 1];
    }

    public function getClaimStatus()
    {
        return OvertimeRequest::CLAIM_STATUS[$this->claim_status ?? 1];
    }

    public function forwardUserModel()
    {
        return $this->belongsTo(User::class, 'forwarded_by');
    }

    public function approvedUserModel()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedUserModel()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }
}
