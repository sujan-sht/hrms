<?php

namespace App\Modules\Appraisal\Entities;

use Illuminate\Database\Eloquent\Model;

class Competency extends Model
{
    protected $fillable = ['name'];

    public function questions()
    {
        return $this->hasMany(CompetencyQuestion::class);
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
