<?php

namespace App\Modules\EmployeeRequest\Entities;

use App\Modules\Dropdown\Entities\Dropdown;
use App\Modules\Employee\Entities\Employee;
use App\Modules\EmployeeRequest\Entities\EmployeeRequestType;
use App\Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeRequest extends Model
{
    use SoftDeletes;

    const FILE_PATH = '/uploads/employee/request';

    protected $fillable = [
        'type_id',
        'title',
        'description',
        'status',
        'employee_id',
        'dropdown_id',
        'cost',
        'pay_type',
        'bank_name',
        'account_number',
        'travel_date',
        'market_visit_location',
        'night_halt',
        'transport_cost',
        'local_DA',
        'DA',
        'telephone',
        'motor_cycle_expenses',
        'PR',
        'lodging',
        'fooding',
        'bill',
        'created_by',
        'updated_by',
        'first_approval_id',
        'forwarded_to',
        'approved_by',
        'approved_date',
    ];

    public function getFileFullPathAttribute()
    {
        return self::FILE_PATH . $this->file_name;
    }

    public function requestType()
    {
        return $this->belongsTo(EmployeeRequestType::class, 'type_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function dropdown()
    {
        return $this->belongsTo(Dropdown::class, 'dropdown_id');
    }

    public function getApprovedBy()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    public function forwardedBy()
    {
        return $this->belongsTo(User::class, 'first_approval_id', 'id');
    }

}
