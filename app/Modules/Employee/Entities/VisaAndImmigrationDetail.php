<?php

namespace App\Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;

class VisaAndImmigrationDetail extends Model
{
    protected $fillable = [
        'employee_id',
        'country',
        'visa_type',
        'visa_expiry_date',
        'passport_number',
        'note',
        'issued_date'
    ];

    /**
     * Relation with employee
     */
    public function employeeModel()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    
    public function countryInfo()
    {
        return $this->belongsTo(Country::class, 'country');
    }
}
