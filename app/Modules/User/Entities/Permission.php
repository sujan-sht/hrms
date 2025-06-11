<?php

namespace App\Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        
        'role_id',
        'route_name'
        
    ];
}
