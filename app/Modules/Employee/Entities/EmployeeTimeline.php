<?php

namespace App\Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;

class EmployeeTimeline extends Model
{
    protected $fillable = [
        'employee_id',
        'date',
        'title',
        'description',
        'icon',
        'color',
        'reference',
        'reference_id',
        'carrier_mobility_id'
    ];

    /**
     * Relation with employee
     */
    public function employeeModel()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     * Get the careerMobility that owns the EmployeeTimeline
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function careerMobility()
    {
        return $this->belongsTo(NewEmployeeCareerMobilityTimeline::class, 'carrier_mobility_id', 'career_mobility_type_id');
    }
}
