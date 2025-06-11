<?php

namespace App\Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignedRole extends Model
{

    protected $fillable = [
        'user_id',
        'role_id'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}