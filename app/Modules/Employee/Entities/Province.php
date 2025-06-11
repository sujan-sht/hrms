<?php

namespace App\Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Employees\Entities\District;

class Province extends Model
{
    protected $fillable = ['province_name'];

    public function getDistricts()
    {
        return $this->hasMany(District::class, 'province_id', 'id');
    }
}
