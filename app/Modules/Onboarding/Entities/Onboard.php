<?php

namespace App\Modules\Onboarding\Entities;

use App\Modules\BoardingTask\Entities\BoardingTask;
use Illuminate\Database\Eloquent\Model;

class Onboard extends Model
{
    protected $fillable = [
        'manpower_requisition_form_id',
        'applicant_id',
        'boarding_task_id',
        'onboard_date',
        'status',
        'created_by',
        'updated_by'
    ];

    /**
     * Relation with mrf
     */
    public function mrfModel()
    {
        return $this->belongsTo(ManpowerRequisitionForm::class, 'manpower_requisition_form_id');
    }

    /**
     * Relation with applicant
     */
    public function applicantModel()
    {
        return $this->belongsTo(Applicant::class, 'applicant_id');
    }

    /**
     * Relation with applicant
     */
    public function boardingTaskModel()
    {
        return $this->belongsTo(BoardingTask::class, 'boarding_task_id');
    }

    /**
     * User tracking
     */
    public static function boot()
    {
        parent::boot();

        Self::creating(function ($model) {
            $model->created_by = auth()->user()->id;
        });

        Self::creating(function ($model) {
            $model->updated_by = auth()->user()->id;
        });
    }
}
