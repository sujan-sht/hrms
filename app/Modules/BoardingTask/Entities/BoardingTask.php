<?php

namespace App\Modules\BoardingTask\Entities;

use App\Modules\Onboarding\Entities\Onboard;
use Illuminate\Database\Eloquent\Model;

class BoardingTask extends Model
{
    protected $fillable = [
        'title',
        'category',
        'description',
        'created_by',
        'updated_by'
    ];

    /**
     * Get category
     */
    public function getCategory()
    {
        $list = Self::getCategoryList();
        return $list[$this->category];
    }
    /**
     * Get category list
     */
    public static function getCategoryList()
    {
        return [
            // 1 => 'Pre-Onboarding',
            2 => 'Onboarding',
            // 3 => 'Post-Onboarding',
            // 4 => 'Pre-Offboarding',
            5 => 'Offboarding',
            // 6 => 'Post-Offboarding'
        ];
    } 

    /**
     * 
     */
    public function getStatusDetail($applicantId, $mrfId)
    {
        $model = Onboard::where([
            'manpower_requisition_form_id' => $mrfId,
            'applicant_id' => $applicantId,
            'boarding_task_id' => $this->id
        ])->first();

        if($model) {
            return $model->status;
        }

        return false;
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
