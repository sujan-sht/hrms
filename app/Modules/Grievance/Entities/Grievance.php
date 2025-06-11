<?php

namespace App\Modules\Grievance\Entities;

use App\Modules\Employee\Entities\Employee;
use App\Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grievance extends Model
{
    const MISCONDUCT_TYPE = [1 => 'Theft', 2 => 'Harassment', 3 => 'Legal Compliance', 4 => 'Others'];
    const STATUS = [1 => 'Resolved', 2 => 'Under Process', 3 => 'Discarded', 4 => 'Unresolved'];
    const FILE_PATH = '/uploads/grievance/';
    const SUBJECT_TYPE = [1 => 'Grievances', 2 => 'Disciplinary Action', 3 => 'Suggestions', 4 => 'Others'];
    const SUBJECT_FIELD = [
        1 => [
            'related_grievances' => 'Subject related to grievances',
            'detail' => 'Grievances Details',
        ],
        2 => [
            'emp_name' => 'Employee involved in Misconduct',
            'dept' => 'Department',
            'date' => 'Date of Misconduct',
            'time' => 'Time of Misconduct',
            'location' => 'Location of Misconduct',
            'witness_name' => 'Witness Name',
            'detail' => 'Details of Misconduct',
        ],
        3 => [
            'detail' => 'Suggestion Details',
        ],
        4 => [
            'detail' => 'Other Details'
        ]
    ];

    protected $fillable = ['is_anonymous', 'subject_type', 'created_by', 'updated_by', 'attachment', 'status', 'resolved_date', 'remark'];

    // public function employee()
    // {
    //     return $this->belongsTo(Employee::class, 'created_by');
    // }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function grievanceEmployee()
    {
        return $this->hasOne(GrievanceEmployee::class, 'grievance_id');
    }

    public function grievanceMetas()
    {
        return $this->hasMany(GrievanceMeta::class, 'grievance_id');
    }

    public function getSubjectType()
    {
        $subjecType = self::SUBJECT_TYPE;
        return $subjecType[$this->subject_type];
    }

    public function getMisconductType()
    {
        $misconductType = self::MISCONDUCT_TYPE;
        return $misconductType[$this->misconduct_type];
    }

    public function getStatus()
    {
        $status = self::STATUS;
        return $this->status ? $status[$this->status] : '';
    }

    public function getSingleGrievanceMeta($key)
    {
        $meta = $this->grievanceMetas->where('key', $key)->first();
        return $meta ? $meta->value : "";
    }

    public function pluckGrievanceMetas()
    {
        return $this->grievanceMetas->pluck('value', 'key');
    }
}
