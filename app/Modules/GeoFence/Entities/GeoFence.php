<?php

namespace App\Modules\GeoFence\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Malhal\Geographical\Geographical;

class GeoFence extends Model
{
    use Geographical;
    protected $guarded = [];

    protected $table='geofences';

    protected static $kilometers = true;

    public function geofenceAllocation() {
        return $this->hasMany(GeofenceAllocation::class, 'geofence_id');
        
    }
}
