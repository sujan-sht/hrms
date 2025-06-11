<?php

namespace App\Modules\Appraisal\Entities;

use App\Modules\Employee\Entities\Employee;
use App\Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;

class Appraisal extends Model
{
    protected $fillable = ['appraisee', 'questionnaire_id', 'valid_date', 'type', 'enable_self_evaluation', 'self_evaluation_type', 'enable_supervisor_evaluation', 'supervisor_evaluation_type', 'enable_hod_evaluation', 'hod_evaluation_type'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'appraisee');
    }

    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class, 'questionnaire_id');
    }

    public function respondents()
    {
        return $this->hasMany(Respondent::class);
    }

    public function firstRespondent()
    {
        return $this->hasOne(Respondent::class);
    }

    public function appraisalResponses()
    {
        return $this->hasManyThrough(
            AppraisalResponse::class,
            Respondent::class,
            'appraisal_id', // Foreign key on the deployments table...
            'respondent_id', // Foreign key on the environments table...
            'id', // Local key on the projects table...
            'id' // Local key on the environments table...
        );
    }
    public function appraisalCompetanceResponses()
    {
        return $this->hasManyThrough(
            AppraisalCompetencyResponse::class,
            Respondent::class,
            'appraisal_id', // Foreign key on the deployments table...
            'respondent_id', // Foreign key on the environments table...
            'id', // Local key on the projects table...
            'id' // Local key on the environments table...
        );
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
