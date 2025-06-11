<?php

namespace App\Modules\Training\Entities;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Training\Entities\Training;

use Illuminate\Database\Eloquent\Model;

class TrainingParticipant extends Model
{

    protected $fillable = [
        'training_id',
        'contact_no',
        'email',
        'remarks',
        'employee_id'
    ];

    public function trainingInfo()
    {
        return $this->belongsTo(Training::class, 'training_id');
    }

    public function employeeModel()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function getAttendee()
    {
        return TrainingAttendance::where('training_id', $this->training_id)->where('employee_id', $this->employee_id)->first();
    }
}
