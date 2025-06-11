<?php

namespace App\Modules\Worklog\Entities;

use App\Modules\Dropdown\Entities\Dropdown;
use App\Modules\Employee\Entities\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Worklog extends Model
{
    const STATUS = [
        '1' => 'Pending',
        '2' => 'In Progress',
        '3' => 'Todo',
        '4' => 'Done',
        '5' => 'Completed',
        '6' => 'Rejected',
    ];

    protected $fillable = ['date'];

    public function workLogDetail()
    {
        return $this->hasMany(WorklogDetail::class, 'worklog_id');
    }

    // public function employee()
    // {
    //     return $this->belongsTo(Employee::class,'employee_id');
    // }

    public function getStatus()
    {
        return Worklog::STATUS[$this->status ?? 1];
    }

    /**
     *
     */
    public function project()
    {
        return $this->belongsTo(Dropdown::class, 'project_id');
    }

    public static function getCount() {
        $authUser = auth()->user();
        if($authUser->user_type == 'division_hr') {
            $result = Worklog::whereHas('workLogDetail',function($query){
                $query->whereHas('employee',function($q){
                    $q->where('organization_id',optional(auth()->user()->userEmployer)->organization_id);
                });
            })->count();
        } else {
            $result = Worklog::count();
        }

        return $result;
    }
}
