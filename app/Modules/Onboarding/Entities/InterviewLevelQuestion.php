<?php

namespace App\Modules\Onboarding\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InterviewLevelQuestion extends Model
{
    protected $fillable = [
        'interview_level_id',
        'question'
    ];

    /**
     * Relation with interview level
     */
    public function interviewLevelModel()
    {
        return $this->belongsTo(InterviewLevel::class, 'interview_level_id');
    }
}
