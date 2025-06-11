<?php

namespace App\Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;

class PreviousJobDetail extends Model
{

    protected $fillable = [
        'employee_id',
        'company_name',
        'address',
        'from_date',
        'to_date',
        'job_title',
        'designation_on_joining',
        'designation_on_leaving',
        'industry_type',
        'break_in_career',
        'reason_for_leaving',
        'role_key',
        'approved_by_hr'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
