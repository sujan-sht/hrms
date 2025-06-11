<?php

namespace App\Modules\Survey\Entities;

use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{

    protected $fillable = [
        'survey_id',
        'employee_id',
        'survey_question_id',
        'answer'
    ];

    public function surveyQuestion()
    {
        return $this->belongsTo(SurveyQuestion::class, 'survey_question_id');
    }

}
