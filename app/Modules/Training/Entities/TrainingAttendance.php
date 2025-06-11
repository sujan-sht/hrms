<?php

namespace App\Modules\Training\Entities;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Training\Entities\Training;
use Illuminate\Database\Eloquent\Model;

class TrainingAttendance extends Model
{
    protected $fillable = [
        'training_id',
        'contact_no',
        'email',
        'remarks',
        'feedback',
        'employee_id',
        'marks_obtained',
        'rating',
        'status'
    ];

    const STATUS = [
        11 => 'Present',
        10 => 'Absent'
    ];


    public function trainingInfo()
    {
        return $this->belongsTo(Training::class, 'training_id');
    }

    public function employeeModel()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function getStatusWithColor()
    {
        $list = Self::STATUS;

        switch ($this->status) {
            case '11':
                $color = 'primary';
                break;
            case '10':
                $color = 'danger';
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
}
