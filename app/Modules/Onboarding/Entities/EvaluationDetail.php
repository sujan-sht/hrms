<?php

namespace App\Modules\Onboarding\Entities;

use Illuminate\Database\Eloquent\Model;

class EvaluationDetail extends Model
{
    protected $fillable = [
        'evaluation_id',
        'question',
        'score'
    ];

    /**
     * Relation with evaluation
     */
    public function evaluationModel()
    {
        return $this->belongsTo(Evaluation::class, 'evaluation_id');
    }

    /**
     * Relation with question
     */
    public function questionModel()
    {
        return $this->belongsTo(InterviewLevelQuestion::class, 'question');
    }
}
