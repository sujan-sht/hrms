<?php

namespace App\Modules\Attendance\Entities;

use App\Modules\Employee\Entities\Employee;
use App\Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttendanceRequest extends Model
{
    const Types = [
        '1' => 'Missed Check In',
        '2' => 'Missed Check Out',
        '3' => 'Early Departure Request',
        '4' => 'Late Arrival Request',
        '5' => 'Force Attendance Request',
        '6' => 'Out Door Duty Request',
        '7' =>  'Work From Home Request'
    ];
    const Kinds = [
        '1' => 'First Half',
        '2' => 'Second Half',
        '3' => 'Full Day',
    ];
    const STATUS = [
        '1' => 'Pending',
        '2' => 'Recommended',
        '3' => 'Approved',
        '4' => 'Rejected',
        '5' => 'Cancelled',
    ];

    const PRE_STATUS = [
        '1' => 'Pending',
        '2' => 'Recommended',
        '3' => 'Approve',
        '4' => 'Reject',
        '5' => 'Cancel',
    ];


    protected $fillable = ['employee_id', 'date', 'nepali_date', 'time', 'type', 'kind', 'status', 'detail', 'forwarded_remarks', 'rejected_remarks', 'approved_by', 'created_by', 'parent_id', 'approved_date', 'forwarded_date', 'rejected_date', 'cancelled_date', 'forwarded_by', 'rejected_by', 'cancelled_by'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function userModel()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedByModel()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function forwardedByModel()
    {
        return $this->belongsTo(User::class, 'forwarded_by');
    }

    public function rejectedByModel()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }
    public function cancelledByModel()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }


    public function getStatus()
    {
        return AttendanceRequest::STATUS[$this->status ?? 1];
    }

    public function childs()
    {
        return $this->hasMany(AttendanceRequest::class, 'parent_id', 'id');
    }

    public function getType()
    {
        // return AttendanceRequest::Types[$this->type ?? 1];
        $type = $this->type ?? 1;

        // Check if the type is present in the Types array
        if (isset(AttendanceRequest::Types[$type])) {
            return AttendanceRequest::Types[$type];
        } else {
            // If type is 8, return 'Mark as Read'
            return $type == 8 ? 'Mark as Absent' : 'Unknown Type';
        }
    }
    public function getKind()
    {
        return AttendanceRequest::Kinds[$this->kind];
    }

    public function getDateRangeWithCount()
    {
        $result = [
            'range' => '-',
            'count' => '0',
        ];

        $parentModel = AttendanceRequest::select('date', 'nepali_date', 'kind')->where('id', $this->id)->first();
        $models = AttendanceRequest::select('date', 'nepali_date', 'kind')->where('parent_id', $this->id)->get();
        if (count($models) > 0) {
            if (setting('calendar_type') == 'BS') {
                $firstDate = $parentModel->nepali_date;
                $lastDate = $models->last()->nepali_date;
            } else {
                $firstDate = date('M d, Y', strtotime($parentModel->date));
                $lastDate = date('M d, Y', strtotime($models->last()->date));
            }
            $noOfDays = 0;
            foreach ($models as $model) {
                $noOfDays += isset($model->kind) && ($model->kind == 1 || $model->kind == 2) ? 1 : 1;
            }
            $result = [
                'range' => $firstDate . ' - ' . $lastDate,
                'count' => $noOfDays + (isset($parentModel->kind) && ($parentModel->kind == 1 || $parentModel->kind == 2) ? 1 : 1)
            ];
        } else {
            $result = [
                'range' => setting('calendar_type') == 'BS' ? $this->nepali_date : date('M d, Y', strtotime($this->date)),
                'count' => isset($parentModel->kind) && ($parentModel->kind == 1 || $parentModel->kind == 2) ? 0.5 : 1
            ];
        }
        return $result;
    }

     protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Created post: ' . $model);
        });

        static::updated(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Updated post: ' . $model);
        });

        static::deleted(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Deleted post: ' . $model);
        });
    }
}
