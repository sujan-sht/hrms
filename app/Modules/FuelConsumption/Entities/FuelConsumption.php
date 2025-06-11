<?php

namespace App\Modules\FuelConsumption\Entities;

use App\Modules\Employee\Entities\Employee;
use App\Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;

class FuelConsumption extends Model
{
    protected $table = "fuel_consumptions";

    protected $fillable = [
        'starting_place',
        'destination_place',
        'vehicle_no',
        'start_km',
        'end_km',
        'km_travelled',
        'purpose',
        'parking_cost',
        'status',
        'created_by',
        'verified_by',
        'verified_at',
        'approved_by',
        'approved_at',
        'fuel_consump_created_date',
        'verified_status',
        'emp_id'
    ];

    public function employeeInfo()
    {
        return $this->belongsTo(Employee::class, 'emp_id');
    }

    public function userInfo(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function verifyInfo(){
        return $this->belongsTo(User::class,'verified_by');
    }
    public function approvedUserInfo(){
        return $this->belongsTo(User::class,'approved_by');
    }
}
