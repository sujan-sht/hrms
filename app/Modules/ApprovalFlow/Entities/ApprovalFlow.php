<?php

namespace App\Modules\ApprovalFlow\Entities;

use App\Modules\Dropdown\Entities\Dropdown;
use App\Modules\Setting\Entities\Department;
use App\Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;

class ApprovalFlow extends Model
{

    protected $fillable = [
        'department_id',
        'first_approval_user_id',
        'last_approval_user_id',
        'created_by',
        'updated_by'
    ];

    public function dropdownInfo()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function firstApprovalInfo()
    {
        return $this->belongsTo(User::class, 'first_approval_user_id', 'id');
    }

    public function lastApprovalInfo()
    {
        return $this->belongsTo(User::class, 'last_approval_user_id', 'id');
    }


    
}
