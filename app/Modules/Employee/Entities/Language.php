<?php

namespace App\Modules\Employee\Entities;

use App\Modules\Dropdown\Entities\Dropdown;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = [
        'name',
    ];
}
