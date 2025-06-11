<?php

namespace App\Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class UserRole extends Model
{
    protected $table = "user_roles";

    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'role_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function userRole()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    // Forget cache on updating or saving and deleting
    public static function boot()
    {
        parent::boot();

        static::saving(function () {
            self::cacheKey();
        });

        static::deleting(function () {
            self::cacheKey();
        });
    }

    // Cache Keys
    private static function cacheKey()
    {
        Cache::has('user_with_roles_and_permissions') ? Cache::forget('user_with_roles_and_permissions') : '';
    }
}
