<?php

namespace App\Modules\Appraisal\Entities;

use Illuminate\Database\Eloquent\Model;

class AppraisalResponse extends Model
{
    protected $fillable = ['appraisal_id', 'respondent_id', 'question_id', 'comment', 'created_by', 'score'];

    public function respondent()
    {
        return $this->belongsTo(Respondent::class, 'respondent_id');
    }

    public function competenceQuestion()
    {
        return $this->belongsTo(CompetencyQuestion::class, 'question_id');
    }

    public static function getList($appraisal_id = 24)
    {
        // dd($appraisal_id);
        return AppraisalResponse::where('appraisal_id', $appraisal_id)->get()->groupBy('respondent_id');
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
