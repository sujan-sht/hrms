<?php

namespace App\Modules\PMS\Entities;

use Illuminate\Database\Eloquent\Model;

class TargetQuarter extends Model
{

    protected $fillable = [
        'target_id',
        'quarter',
        'target_value',
    ];
}
