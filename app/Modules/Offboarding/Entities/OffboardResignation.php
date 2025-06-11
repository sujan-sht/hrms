<?php

namespace App\Modules\Offboarding\Entities;

use App\Modules\Employee\Entities\Employee;
use Illuminate\Database\Eloquent\Model;


class OffboardResignation extends Model
{
    const FILE_PATH = 'uploads/offboard/';

    protected $fillable = [
        'employee_id',
        'last_working_date',
        'remark',
        'attachment',
        'status',
        'issued_date',
        'issued_remark',
        'received_date',
        'received_by',
        'received_remark',
    ];

    /**
     * Relation with employee
     */
    public function employeeModel()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function offboardEmployeeeClearance()
    {
        return $this->hasMany(OffboardEmployeeClearance::class, 'offboard_resignation_id');
    }

    /**
     *
     */
    public function getAttachmentFileAttribute()
    {
        return asset(Self::FILE_PATH . $this->attachment);
    }

    /**
     *
     */
    public function getStatusDetailAttribute()
    {
        switch ($this->status) {
            case 2:
                $color = 'teal';
                $title = 'Forwarded';
                break;
            case 3:
                $color = 'info';
                $title = 'Accepted';
                break;
            case 4:
                $color = 'danger';
                $title = 'Rejected';
                break;
            case 5:
                $color = 'success';
                $title = 'Completed';
                break;
            default:
                $color = 'secondary';
                $title = 'Pending';
                break;
        }

        return [
            'status' => $title,
            'color' => $color
        ];
    }

    /**
     *
     */
    public static function getStatusList()
    {
        return [
            1 => 'Pending',
            2 => 'Forward',
            3 => 'Accept',
            4 => 'Reject',
            5 => 'Complete'
        ];
    }
}
