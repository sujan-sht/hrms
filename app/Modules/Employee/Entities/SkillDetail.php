<?php

namespace App\Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;

class SkillDetail extends Model
{
    protected $table = "employee_skill_details";

    protected $fillable = [
        'employee_id',
        'skill_name',
        'rating',
    ];





    public static function getSkillMetric($rating)
    {
        switch ($rating) {
            case '1':
                $text = 'Beginner';
                break;

            case '2':
                $text = 'Intermediate';
                break;

            case '3':
                $text = 'Semi-Professional';
                break;

            case '4':
                $text = 'Professional';
                break;

            case '5':
                $text = 'Excellent';
                break;

            default:

                break;
        }

        return $text;
    }


    /**
     * Get the employee that owns the AwardDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

     public static function boot()
    {
        parent::boot();

        Self::creating(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Created post: ' . $model);
        });

        Self::updating(function ($model) {
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
