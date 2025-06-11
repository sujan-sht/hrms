<?php

namespace App\Modules\User\Entities;

use App\Modules\Employee\Entities\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoginLogoutLog extends Model
{
    protected $fillable = ['employee_id', 'type', 'date', 'nepali_date','created_user_id','created_user_modal','action_id','action_model','route','browser_details'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }    
}
