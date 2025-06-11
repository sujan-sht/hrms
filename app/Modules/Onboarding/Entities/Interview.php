<?php

namespace App\Modules\Onboarding\Entities;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    protected $fillable = [
        'applicant_id',
        'interview_level_id',
        'date',
        'time',
        'venue',
        'status',
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
     * Relation with interview level
     */
    public function interviewLevelModel()
    {
        return $this->belongsTo(InterviewLevel::class, 'interview_level_id');
    }

    /**
     * 
     */
    public function getStatusWithColor()
    {
        $list = Self::statusList();

        switch ($this->status) {
            case '2':
                $color = 'success';
            break;
            default:
                $color = 'secondary';
            break;
        }

        return [
            'status' => $list[$this->status],
            'color' => $color
        ];
    }

    /**
     * Stage list
     */
    public static function statusList()
    {
        return [
            '1' => 'Pending',
            '2' => 'Completed'
        ];
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
