<?php

namespace App\Modules\Onboarding\Entities;

use App\Modules\Employee\Entities\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'parent_id',
        'applicant_id',
        'interview_id',
        'interview_level_id',
        'employee_id',
        'total_score',
        'percentage',
        'created_by',
        'updated_by'
    ];

    /**
     * Relation with applicant
     */
    public function applicantModel()
    {
        return $this->belongsTo(Applicant::class, 'applicant_id');
    }


    /**
     * Relation with Offer letter
     */
    public function offerLetter()
    {
        return $this->hasOne(OfferLetter::class);
    }

    /**
     * Relation with interview level
     */
    public function interviewLevelModel()
    {
        return $this->belongsTo(InterviewLevel::class, 'interview_level_id');
    }

    /**
     * Relation with interview level
     */
    public function employeeModel()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     * Relation with evaluation details
     */
    public function evaluationDetailModels()
    {
        return $this->hasMany(EvaluationDetail::class);
    }

    /**
     *
     */
    public static function GetScoreList()
    {
        return [
            0 => "Don't Know",
            1 => "1",
            2 => "2",
            3 => "3",
            4 => "4",
            5 => "5"
        ];
    }

    /**
     * 
     */
    public function getScore($questionId)
    {
        $score = 0;

        $model = EvaluationDetail::where('evaluation_id', $this->id)->where('question', $questionId)->first();
        // $model = EvaluationDetail::where('evaluation_id', $evaluationId)->where('question', $questionId)->first();

        if($model) {
            $score = $model->score;
        }

        return $score;
    }

    /**
     * boot function for user tracking
     */
    public static function boot()
    {
        parent::boot();

        Self::creating(function ($model) {
            $model->created_by = Auth::user()->id;
        });

        Self::updating(function ($model) {
            $model->updated_by = Auth::user()->id;
        });
    }
}
