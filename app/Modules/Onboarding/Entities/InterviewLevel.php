<?php

namespace App\Modules\Onboarding\Entities;

use Illuminate\Database\Eloquent\Model;

class InterviewLevel extends Model
{
    protected $fillable = [
        'title'
    ];

    /**
     * Relation with interview level questions
     */
    public function getQuestionModels()
    {
        return $this->hasMany(InterviewLevelQuestion::class);
    }
}
