<?php

namespace App\Modules\Leave\Entities;

use Carbon\Carbon;
use App\Modules\User\Entities\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Organization\Entities\Organization;

class Leave extends Model
{
    protected $fillable = [
        'parent_id',
        'organization_id',
        'employee_id',
        'leave_kind',
        'leave_type_id',
        'half_type',
        'date',
        'nepali_date',
        'substitute_date',
        'reason',
        'alt_employee_id',
        'alt_employee_message',
        'status',
        'forward_by',
        'forward_message',
        'reject_by',
        'reject_message',
        'accept_by',
        'accept_message',
        'generated_by',
        'generated_leave_type',
        'generated_no_of_days',
        'created_by',
        'updated_by',
        'cancelled_by',
        'approved_date',
        'forwarded_date',
        'rejected_date',
        'cancelled_date',
        'created_at',
    ];

    protected $appends = ['day'];

    public function getDayAttribute()
    {
        return $this->leave_kind == 1 ? 0.5 : 1;
    }
    /**
     * Relation with organization
     */
    public function organizationModel()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    /**
     * Relation with organization
     */
    public function employeeModel()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function rejectUserModel()
    {
        return $this->belongsTo(User::class, 'reject_by');
    }

    public function forwardUserModel()
    {
        return $this->belongsTo(User::class, 'forward_by');
    }
    /**
     * Relation with organization
     */
    public function altEmployeeModel()
    {
        return $this->belongsTo(Employee::class, 'alt_employee_id');
    }

    /**
     * Relation with organization
     */
    public function leaveTypeModel()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }

    /**
     * Relation with attachment
     */
    public function attachments()
    {
        return $this->hasMany(LeaveAttachment::class);
    }

    /**
     * Relation with attachment
     */
    public function childs()
    {
        return $this->hasMany(Leave::class, 'parent_id', 'id');
    }

    public function userModel()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function acceptModel()
    {
        return $this->belongsTo(User::class, 'accept_by');
    }

    public function cancelModel()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }
    /**
     * Relation with organization
     */
    public function statusBy()
    {
        switch ($this->status) {
            case '2':
                $reference = 'forward_by';
                break;
            case '3':
                $reference = 'accept_by';
                break;
            case '4':
                $reference = 'reject_by';
                break;
            case '5':
                $reference = 'cancelled_by';
                break;
            default:
                $reference = 'created_by';
                break;
        }

        return $this->belongsTo(User::class, $reference);
    }

    /**
     *
     */
    public function getDateRangeWithCount()
    {
        $result = [
            'range' => '-',
            'count' => '0',
        ];

        $parentModel = Leave::select('date', 'nepali_date', 'leave_kind')->where('id', $this->id)->first();
        $models = Leave::select('date', 'nepali_date', 'leave_kind')->where('parent_id', $this->id)->get();
        if ($models->count() > 0) {
            if (setting('calendar_type') == 'BS') {
                $firstDate = $parentModel->nepali_date;
                $lastDate = $models->last()->nepali_date;
            } else {
                $firstDate = date('M d, Y', strtotime($parentModel->date));
                $lastDate = date('M d, Y', strtotime($models->last()->date));
            }
            $result = [
                'range' => $firstDate . ' - ' . $lastDate,
                'count' => count($models) + ($parentModel->leave_kind == 1 ? 0.5 : 1)
            ];
        } else {
            if (setting('calendar_type') == 'BS') {

                $date = $this->nepali_date;
            } else {
                $date = date('M d, Y', strtotime($this->date));
            }
            $result = [
                'range' => $date,
                'count' => $parentModel->leave_kind == 1 ? 0.5 : 1
            ];
        }

        return $result;
    }

    /**
     *
     */
    public function getLeaveKind()
    {
        $list = Self::leaveKindList();
        if (isset($list[$this->leave_kind])) {
            return $list[$this->leave_kind];
        }
    }

    public function getHalfType()
    {
        $list = Self::halfTypeList();
        if (isset($list[$this->half_type])) {
            return $list[$this->half_type];
        }
    }

    /**
     *
     */
    public function getStatus()
    {
        $list = Self::statusList();
        return $list[$this->status];
    }

    /**
     *
     */
    public function getStatusWithColor()
    {
        $list = Self::statusList();

        switch ($this->status) {
            case '2':
                $color = 'primary';
                break;
            case '3':
                $color = 'success';
                break;
            case '4':
                $color = 'danger';
                break;
            case '5':
                $color = 'warning';
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

    /**
     *
     */
    public static function getCount()
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {

            $result = Leave::where([
                'parent_id' => null,
                'status' => 1,
                'organization_id' => optional($authUser->userEmployer)->organization_id,
            ])
                ->whereHas('leaveTypeModel', function ($query) {
                    $query->where('leave_year_id', getCurrentLeaveYearId());
                })->count();
        } else {
            $result = Leave::where([
                'parent_id' => null,
                'status' => 1
            ])->whereHas('leaveTypeModel', function ($query) {
                $query->where('leave_year_id', getCurrentLeaveYearId());
            })->count();
        }

        return $result;
    }

    public static function getChildren($parent)
    {
        $tree = array();
        $tree = Leave::with(['childs' => function ($query) {
            $query->select('id', 'parent_id', 'date');
        }])
            ->select('id', 'parent_id', 'date')->where('id', $parent)->get();
        return $tree;
    }

    /**
     * kinds of leave list
     */
    public static function leaveKindList()
    {
        return [
            '2' => 'Full Leave',
            '1' => 'Half Leave'
            // '3' => 'Custom Leave',
            // '4' => 'Substitute Leave'
        ];
    }

    /**
     * Half type list
     */
    public static function halfTypeList()
    {
        return [
            '1' => 'First',
            '2' => 'Second'
        ];
    }

    /**
     * Status list
     */
    public static function statusList()
    {
        return [
            '1' => 'Pending',
            '2' => 'Recommended',
            '3' => 'Approved',
            '4' => 'Rejected',
            '5' => 'Cancelled'
        ];
    }

    /**
     * Generated By list
     */
    public static function generatedByList()
    {
        return [
            '10' => 'Generated By Human',
            '11' => 'Generated By System'
        ];
    }

    /**
     * Generated Leave Type
     */
    public static function generatedLeaveTypeList()
    {
        return [
            1 => 'Missed Check In',
            2 => 'Missed Check Out',
            3 => 'Early Departure Request',
            4 => 'Late Arrival Request',
            5 => 'Grace for Penalty for Check Out',
            6 => 'Grace for Penalty for Check In'
        ];
    }

    /**
     * boot function for user tracking
     */
    public static function boot()
    {
        parent::boot();

        Self::creating(function ($model) {
            $model->created_by = Auth::user()->id;
            $model->reason = self::sanitizeReason($model->reason);

            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Created post: ' . $model);
        });

        Self::updating(function ($model) {
            $model->updated_by = Auth::user()->id;
            $model->reason = self::sanitizeReason($model->reason);

             activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Updated post: ' . $model);
        });

        static::creating(function ($model) {
            if (empty($model->created_at)) {
                $model->created_at = Carbon::now()->toDateTimeString();
            }
        });

        static::updating(function ($model) {
            if (empty($model->created_at)) {
                $model->created_at = Carbon::now()->toDateTimeString();
            }
        });

         static::deleted(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Deleted post: ' . $model);
        });
    }


    public function getReasonAttribute($value)
    {
        return self::sanitizeReason($value);
    }

    // Helper function to clean special characters
    protected static function sanitizeReason($text)
    {
        // Keep only letters, numbers, spaces and basic punctuation
        return preg_replace('/[^a-zA-Z0-9\s\.\,\-\_]/', '', $text);
    }
}
