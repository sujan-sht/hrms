<?php

namespace App\Modules\Onboarding\Entities;

use App\Modules\User\Entities\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Dropdown\Entities\Dropdown;
use App\Modules\Organization\Entities\Organization;
use App\Modules\Setting\Entities\Department;
use App\Modules\Setting\Entities\Designation;

class ManpowerRequisitionForm extends Model
{
    protected $fillable = [
        'organization_id',
        'reference_number',
        'title',
        'description',
        'specification',
        'start_date',
        'end_date',
        'division',
        'department',
        'designation',
        'type',
        'position',
        'reporting_to',
        'age',
        'salary',
        'experience',
        'two_wheeler_status',
        'four_wheeler_status',
        'prepared_by',
        'first_recommended_by',
        'second_recommended_by',
        'third_recommended_by',
        'fourth_recommended_by',
        'approved_by',
        'reject_by',
        'reject_remark',
        'status',
        'created_by',
        'updated_by'
    ];

    /**
     * Relation with organization
     */
    public function organizationModel()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    /**
     * Relation with division
     */
    public function getDivision()
    {
        return $this->belongsTo(Dropdown::class, 'division');
    }

    /**
     * Relation with department
     */
    public function getDepartment()
    {
        return $this->belongsTo(Department::class, 'department');
    }

    /**
     * Relation with designation
     */
    public function getDesignation()
    {
        return $this->belongsTo(Designation::class, 'designation');
    }

    /**
     * Relation with designation
     */
    public function rejectUser()
    {
        return $this->belongsTo(User::class, 'reject_by');
    }

    /**
     * Relation with designation
     */
    public function createrUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relation with mrf status detail
     */
    public function statusDetailModels()
    {
        return $this->hasMany(MrfStatusDetail::class, 'mrf_id', 'id');
    }

    /**
     * Get type
     */
    public function getType()
    {
        $list = Self::typeList();
        return $list[$this->type];
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
        switch ($this->status) {
            case '2':
                $color = 'primary';
                $title = 'Forwarded';
                break;
            case '3':
                $color = 'success';
                $title = 'Published';
                break;
            case '4':
                $color = 'danger';
                $title = 'Rejected';
                break;
            case '5':
                $color = 'primary';
                $title = 'Forwarded by Division HR';
                break;
            case '6':
                $color = 'primary';
                $title = 'Forwarded by Business Head';
                break;
            case '7':
                $color = 'primary';
                $title = 'Forwarded by HR Head';
                break;
            case '8':
                $color = 'success';
                $title = 'Accepted';
                break;
            case '9':
                $color = 'danger';
                $title = 'Cancelled';
                break;
            case '10':
                $color = 'info';
                $title = 'Closed';
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
     * Half type list
     */
    public static function typeList()
    {
        return [
            '1' => 'Internal',
            '2' => 'External'
        ];
    }

    /**
     * Status list
     */
    public static function statusList()
    {
        return [
            '1' => 'Pending',
            '2' => 'Forward',
            // '3' => 'Publish',
            '4' => 'Reject',
            '5' => 'Forward',
            '6' => 'Forward',
            '7' => 'Forward',
            '8' => 'Approve',
            '9' => 'Cancel',
            '10' => 'Close'
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
            $model->reference_number = self::generateUniqueRefCode();
        });

        Self::updating(function ($model) {
            $model->updated_by = Auth::user()->id;
        });
    }

    public static function generateUniqueRefCode()
    {
        // do {
        //     $code = random_int(10000, 99999);
        // } while (self::where("reference_number", "=", $code)->first());

        // return $code;

        $latestRefNumber = self::latest()->first();
        if($latestRefNumber){
            return $latestRefNumber->reference_number + (int)1;
        }else{
            return random_int(10000, 99999);
        }

    }
}
