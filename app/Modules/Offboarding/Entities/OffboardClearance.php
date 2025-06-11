<?php

namespace App\Modules\Offboarding\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OffboardClearance extends Model
{

    protected $fillable = [
        'title',
        'description',
        'order',
        'status'
    ];


    public function clearanceResponsible()
    {
        return $this->hasMany(OffboardClearanceResponsible::class, 'offboard_clearance_id');
    }
}
