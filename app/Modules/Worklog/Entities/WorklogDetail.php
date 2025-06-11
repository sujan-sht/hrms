<?php

namespace App\Modules\Worklog\Entities;

use App\Modules\Employee\Entities\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorklogDetail extends Model
{
    const STATUS = [
        '1' => 'Pending',
        '2' => 'In Progress',
        '3' => 'Todo',
        '4' => 'Done',
        '5' => 'Completed',
        '6' => 'Rejected',
    ];
    protected $fillable = ['worklog_id', 'title', 'employee_id', 'hours', 'status', 'status', 'detail', 'priority', 'assigned_to'];

    public function getStatus()
    {
        return Worklog::STATUS[$this->status ?? 1];
    }

    public function workLog()
    {
        return $this->belongsTo(Worklog::class, 'worklog_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function getArrayDataAttribute() {
        return [
            'id' => $this->id,
            'date' => optional($this->workLog)->date,
            'title' => $this->title,
            'hours' => $this->hours,
            'status' => $this->status,
            'statusTitle' => $this->getStatus(),
            'detail' => $this->detail
        ];
    }
}
