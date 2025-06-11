<?php

namespace App\Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Employee\Entities\EmployeeCareerMobilityTransfer;

class NewEmployeeCareerMobilityTimeline extends Model
{

    protected $table = 'new_employee_career_mobility_timelines';

    protected $fillable = [
        'employee_id',
        'event_type',
        'title',
        'icon',
        'color',
        'career_mobility_type',
        'career_mobility_type_id',
        'event_date',
        'description',
        'remarks',
        'contract_type_log',
        'designation_log',
        'branch_log',
        'department_log',
        'job_title_log'
    ];
    protected $appends = ['event'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function careerMobilityType()
    {
        return $this->morphTo();
    }

    public function getEventAttribute()
    {
        $class = $this->career_mobility_type;

        if (class_exists($class)) {
            return $class::find($this->career_mobility_type_id);
        }

        return null;
    }
}
