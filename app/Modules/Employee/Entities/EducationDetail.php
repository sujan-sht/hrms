<?php

namespace App\Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;

class EducationDetail extends Model
{

    protected $fillable = [
        'employee_id',
        'course_name',
        'score',
        'division',
        'faculty',
        'specialization',
        'university_name',
        'equivalent_certificates',
        'major_subject',
        'degree_certificates',
        'type_of_institution',
        'institution_name',
        'affiliated_to',
        'attended_from',
        'attended_to',
        'passed_year',
        'level',
        'note',
    ];

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
