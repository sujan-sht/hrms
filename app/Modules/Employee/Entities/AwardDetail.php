<?php

namespace App\Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;

class AwardDetail extends Model
{
    protected $table = "employee_awards_details";

    protected $fillable = [
        'employee_id',
        'title',
        'date',
        'attachment',
    ];


    /**
     * Get the employee that owns the AwardDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

     protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Created post: ');
        });

        static::updated(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Updated post: ');
        });

        static::deleted(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Deleted post: ');
        });
    }

}
