<?php

namespace App\Modules\Survey\Entities;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{

    protected $fillable = [
        'title',
        'description',
        'created_by',
        'updated_by'
    ];

    public function surveyQuestions()
    {
        return $this->hasMany(SurveyQuestion::class, 'survey_id');
    }

    public function surveyParticipants()
    {
        return $this->hasMany(SurveyParticipant::class, 'survey_id');
    }

}
