<?php

namespace App\Modules\Onboarding\Entities;

use App\Modules\Employee\Entities\Employee;
use Illuminate\Database\Eloquent\Model;

class MrfStatusDetail extends Model
{
    protected $fillable = [
        'mrf_id',
        'status',
        'action_by',
        'action_datetime',
        'action_remark'
    ];

    /**
     * Relation with Mrf
     */
    public function mrfModel() {
        return $this->belongsTo(ManpowerRequisitionForm::class, 'mrf_id');
    }

    /**
     * Relation with employee
     */
    public function actionByEmployeeModel() {
        return $this->belongsTo(Employee::class, 'action_by');
    }
}
