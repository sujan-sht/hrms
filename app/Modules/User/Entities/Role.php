<?php

namespace App\Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Modules\User\Entities\Permission;
use Illuminate\Support\Facades\Cache;

class Role extends Model
{
      protected $fillable = [

        'name',
        'user_type',
        'status'
    ];
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

        static::created(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Created post: ' . $model);
        });

        static::updated(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Updated post: ' . $model);
        });

        static::deleted(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Deleted post: ' . $model);
        });
    }

    // Cache Keys
    private static function cacheKey()
    {
        Cache::has('user_with_roles_and_permissions') ? Cache::forget('user_with_roles_and_permissions') : '';
    }


    public function permission()
    {
        return $this->hasMany(Permission::class);
    }
}
