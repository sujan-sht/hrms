<?php

namespace App\Modules\Setting\Entities;

use App\Modules\Dropdown\Entities\Dropdown;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'title',
        'short_code',
        'display_short_code',
        'category',
        'description',
        'function_id'
    ];

    public function organizations()
    {
        return $this->hasMany(DepartmentOrganization::class, 'department_id');
    }

    public function getCategoryInfo()
    {
        return $this->belongsTo(Dropdown::class, 'category', 'id');
    }

    public function getFunction()
    {
        return $this->belongsTo(Functional::class, 'function_id', 'id');
    }

     protected static function boot()
    {
        parent::boot();

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
}
