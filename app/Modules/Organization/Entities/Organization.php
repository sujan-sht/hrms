<?php

namespace App\Modules\Organization\Entities;

use App\Modules\Branch\Entities\Branch;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Leave\Entities\LeaveEncashmentSetup;
use App\Modules\Setting\Entities\Designation;
use App\Modules\Setting\Entities\Level;
use App\Modules\Setting\Entities\PayrollCalenderTypeSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    const IMAGE_PATH = 'uploads/organization/';
    const LETTER_HEAD_PATH = 'uploads/organization-letterhead/';

    protected $fillable = [
        'id',
        'image',
        'name',
        'organisation_code',
        'address',
        'contact',
        'mobile',
        'email',
        'fax',
        'vision',
        'mission',
        'code_of_conduct',
        'letter_head',
        'created_by',
        'updated_by'
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
    public function payrollCalender()
    {
        return $this->hasOne(PayrollCalenderTypeSetting::class);
    }


    /**
     * Get image full path
     */
    public function getImage()
    {
        if ($this->image) {
            $imageName = asset(Self::IMAGE_PATH . $this->image);
        } else {
            $imageName = asset('admin/clientLogo.png');
        }

        return $imageName;
    }

    /**
     * Get letter head full path
     */
    public function getLetterHeadImage()
    {
        if ($this->letter_head) {
            $imageName = asset(Self::LETTER_HEAD_PATH . $this->letter_head);
        } else {
            $imageName = asset('admin/clientLogo.png');
        }

        return $imageName;
    }

    public function branch()
    {
        return $this->hasMany(Branch::class, 'organization_id', 'id');
    }

    /**
     *
     */
    public static function getCount() {
        $authUser = auth()->user();
        if($authUser->user_type == 'division_hr') {
            $result = 1;
        } else {
            $result = Organization::count();
        }

        return $result;
    }

    /**
     * boot function for user tracking
     */
    public static function boot()
    {
        parent::boot();

        Self::creating(function ($model) {
            $authUser = Auth::user();
            if ($authUser) {
                $model->created_by = $authUser->id;
            } else {
                $model->created_by = 1;
            }
        });

        Self::updating(function ($model) {
            $authUser = Auth::user();
            if ($authUser) {
                $model->updated_by = $authUser->id;
            } else {
                $model->created_by = 1;
            }
        });
    }
    public function levels(){
        return $this->belongsToMany(Level::class,'level_organizations','organization_id','level_id');
    }

    public function designations(){
        return $this->belongsToMany(Designation::class,'designation_organizations','organization_id','designation_id');
    }

    public function leaveEncashmentSetup(){
        return $this->hasOne(LeaveEncashmentSetup::class,'organization_id');
    }
}
