<?php

namespace App\Modules\Employee\Entities;

use Carbon\Carbon;
use App\Filters\EmployeeFilter;
use App\Modules\Unit\Entities\Unit;
use App\Modules\User\Entities\User;
use App\Modules\Event\Entities\Event;
use App\Modules\Leave\Entities\Leave;

use App\Modules\Branch\Entities\Branch;
use App\Modules\Setting\Entities\Level;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\Shift\Entities\ShiftGroup;
use App\Modules\Dropdown\Entities\Dropdown;
use App\Modules\Employee\Entities\District;
use App\Modules\Employee\Entities\Province;
use App\Modules\User\Entities\AssignedRole;
use App\Modules\Employee\Scopes\ActiveScope;
use App\Modules\Setting\Entities\Department;
use App\Modules\Setting\Entities\Designation;
use App\Modules\Shift\Entities\EmployeeShift;
use App\Modules\Attendance\Entities\Attendance;
use App\Modules\Leave\Entities\LeaveEncashable;
use App\Modules\Payroll\Entities\EmployeeSetup;
use App\Modules\Setting\Entities\HierarchySetup;
use App\Modules\Shift\Entities\ShiftGroupMember;
use App\Modules\Employee\Entities\ArchivedDetail;
use App\Modules\Attendance\Entities\AttendanceLog;
use App\Modules\Leave\Entities\LeaveEncashmentLog;
use App\Modules\Payroll\Entities\GrossSalarySetup;
use App\Modules\Setting\Entities\LevelDesignation;
use App\Modules\NewShift\Entities\NewShiftEmployee;
use App\Modules\Organization\Entities\Organization;
use App\Modules\Setting\Entities\LevelOrganization;
use App\Modules\Leave\Entities\EmployeeLeaveOpening;
use App\Modules\Attendance\Entities\AttendanceRequest;
use App\Modules\Setting\Entities\DepartmentOrganization;
use App\Modules\Setting\Entities\OrganizationDepartment;
use App\Modules\Setting\Entities\DesignationOrganization;
use App\Modules\Attendance\Entities\DivisionAttendanceReport;
use App\Modules\Employee\Entities\EmployeeOffboardApprovalFlow;
use App\Modules\BusinessTrip\Entities\BusinessTripAllowanceSetup;
use App\Modules\Employee\Entities\EmployeeAttendanceApprovalFlow;
use App\Modules\Employee\Entities\EmployeeClaimRequestApprovalFlow;
use App\Modules\EmployeeVisibilitySetup\Traits\HasPermissionsTrait;
use App\Modules\EmployeeVisibilitySetup\Entities\EmployeeVisibilitySetup;

class   Employee extends Model
{
    use HasPermissionsTrait;
    protected $appends = array('full_name');

    const FILE_PATH = '/uploads/employee/';
    const PROFILE_PATH = '/uploads/employee/profile_pic/';
    const CITIZEN_PATH = '/uploads/employee/citizen/';
    const DOC_PATH = '/uploads/employee/document/';
    const SIGNATURE_PATH = '/uploads/employee/signature/';

    const IMAGE_PATH = 'uploads/employee/profile_pic';

    const RESUME_PATH = '/uploads/employee/resume/';

    const PASSPORT_PATH = '/uploads/employee/passport/';
    const NATIONALID_PATH = '/uploads/employee/nationalid/';
    const MARITAL_PATH = '/uploads/employee/marital/';


    protected $fillable = [
        'id',
        'organization_id',
        'employee_id',
        'biometric_id',
        'age',
        'national_id',
        'employee_code',
        'first_name',
        'middle_name',
        'last_name',
        'dayoff',
        'citizenship_no',
        'blood_group',

        'profile_pic',
        'citizen_pic',
        'document_pic',
        'signature',
        'resume',

        'phone',
        'mobile',
        'personal_email',
        'official_email',

        'designation_id',
        'level_id',
        'department_id',
        'branch_id',
        'join_date',
        'nepali_join_date',
        'job_title',

        'gender',
        'dob',
        'pan_no',
        'pf_no',
        'ssf_no',
        'cit_no',
        'tax_calculation',
        'total_tds_paid',
        'effective_fiscal_year',
        'total_previous_income',
        'total_previous_deduction',
        'grade_applicable_date',
        'grade_applicable_nep_date',
        'nep_dob',
        'marital_status',

        'status',
        'is_user_access',
        'is_parent_link',

        'temporaryprovince',
        'temporarydistrict',
        'temporarymunicipality_vdc',
        'temporaryaddress',
        'temporaryward',

        'permanentprovince',
        'permanentdistrict',
        'permanentmunicipality_vdc',
        'permanentaddress',
        'country',
        'permanentward',
        'archived_type',
        'archive_reason',
        'archived_date',
        'nep_archived_date',
        'nationality',
        'job_description',
        'job_status',
        'religion',
        'not_affect_on_payroll',
        'end_date',
        'nep_end_date',
        'ethnicity',
        'passport_no',
        'gratuity_fund_account_no',
        'telephone',
        'languages',
        'signature',
        'initial_signature',
        'permanent_latitude',
        'temporary_latitude',
        'permanent_longitude',
        'temporary_longitude',
        'national_pic',
        'passport_pic',
        'marital_pic',
        'retirement_age',
        'manager_id',
        'function_id',
        // 'group_id'
    ];

    const ACTION_TYPE = [
        10 => 'Activate User'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];


    /**
     * @param Builder $query
     * @param array   $filters
     *
     * @return Builder
     */
    public function scopeFilterBy(Builder $query, array $filters): Builder
    {
        // dd($filters);
        return (new EmployeeFilter($query, $filters))->apply();
    }

    public static function getFilterColumnList()
    {
        return [
            'address' => 'Address',
            'mobile' => 'Mobile',
            'phone' => ' CUG Number',
            'official_email' => 'Official Email',
            'dob' => 'DOB',
            'level' => 'Level',
            'join_date' => 'Join Date',
            'group' => 'Group',
            'designation' => 'Designation'
        ];
    }

    public function getUnit()
    {
        return $this->hasOne(Unit::class, 'id', 'unit_id_value');
    }

    /**
     * Get the user associated with the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function previousJobDetail()
    {
        return $this->hasOne(PreviousJobDetail::class, 'employee_id', 'id');
    }







    // public function setNepDobAttribute($value)
    // {
    //     $this->attributes['nep_dob'] = date('Y-m-d', strtotime($value));
    // }

    // public function shiftGroups()
    // {
    //     return $this->belongsToMany(ShiftGroup::class, 'shift_group_members');
    // }
    public function shiftGroups()
    {
        return $this->hasOne(ShiftGroupMember::class, 'group_member', 'id');
        return $this->hasMany(ShiftGroupMember::class, 'group_member', 'id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'emp_id');
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class)->select('first_name', 'middle_name', 'last_name', 'id');
    }


    public function setNepJoinDateAttribute($value)
    {
        $this->attributes['nepali_join_date'] = date('Y-m-d', strtotime($value));
    }

    public function getFullName()
    {
        if (isset($this->middle_name)) {
            $fullName = $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name;
        } else {
            $fullName = $this->first_name . ' ' . $this->last_name;
        }

        return $fullName;
    }

    protected function getFullNameAttribute()
    {
        if (isset($this->middle_name)) {
            $fullName = $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name;
        } else {
            $fullName = $this->first_name . ' ' . $this->last_name;
        }

        return $fullName;
    }

    public function getImage()
    {
        return $this->profile_pic ? asset(Self::IMAGE_PATH . '/' . $this->profile_pic) : asset('admin/default.png');
    }

    // public function getSignature()
    // {
    //     return asset(Self::SIGNATURE_PATH . '/' . $this->signature);
    // }

    public function getFileFullPathAttribute()
    {
        return self::FILE_PATH . $this->file_name;
    }

    public function branchModel()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function organizationModel()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function temporaryDistrictModel()
    {
        return $this->belongsTo(District::class, 'temporarydistrict');
    }

    public function temporaryProvinceModel()
    {
        return $this->belongsTo(Province::class, 'temporaryprovince');
    }

    public function permanentDistrictModel()
    {
        return $this->belongsTo(District::class, 'permanentdistrict');
    }

    public function permanentProvinceModel()
    {
        return $this->belongsTo(Province::class, 'permanentprovince');
    }

    public function getGender()
    {
        return $this->belongsTo(Dropdown::class, 'gender');
    }

    public function getMaritalStatus()
    {
        return $this->belongsTo(Dropdown::class, 'marital_status');
    }

    public function getSalutation()
    {
        return $this->belongsTo(Dropdown::class, 'salutation_title', 'id');
    }

    public function getBloodGroup()
    {
        return $this->belongsTo(Dropdown::class, 'blood_group', 'id');
    }

    public function religiontype()
    {
        return $this->belongsTo(Dropdown::class, 'religion', 'id');
    }

    public function ethnic()
    {
        return $this->belongsTo(Dropdown::class, 'cast_ethnic', 'id');
    }

    public function districts()
    {
        return $this->belongsTo(District::class, 'district', 'id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province', 'id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    public function jobStatus()
    {
        return $this->belongsTo(Dropdown::class, 'job_status');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function payrollRelatedDetailModel()
    {
        return $this->hasOne(EmployeePayrollRelatedDetail::class);
    }

    /**
     * Get the educationDetail associated with the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function educationDetail()
    {
        return $this->hasOne(EducationDetail::class, 'employee_id', 'id');
    }

    public function leave()
    {
        return $this->hasMany(Leave::class, 'employee_id');
    }

    public function employeeleave()
    {
        return $this->hasMany(EmployeeLeave::class, 'employee_id');
    }

    public function leaveEncashmentLogs()
    {
        return $this->hasMany(LeaveEncashmentLog::class, 'employee_id');
    }

    public function employeeLeaveOpening()
    {
        return $this->hasMany(EmployeeLeaveOpening::class, 'employee_id');
    }

    public function employeeIncomeSetup()
    {
        return $this->hasMany(EmployeeSetup::class, 'employee_id')->where('reference', 'income');
    }

    public function employeeDeductionSetup()
    {
        return $this->hasMany(EmployeeSetup::class, 'employee_id')->where('reference', 'deduction');
    }
    public function employeeAllowanceSetup($type_id)
    {
        return $this->hasOne(BusinessTripAllowanceSetup::class, 'employee_id')->where('type_id', $type_id)->first();
    }
    public function employeeGrossSalarySetup()
    {
        return $this->hasOne(GrossSalarySetup::class, 'employee_id', 'id')->where('organization_id', $this->organization_id);;
    }

    public function employeeApprovalFlowRelatedDetailModel()
    {
        return $this->hasOne(EmployeeApprovalFlow::class);
    }

    public function employeeAttendanceApprovalFlow()
    {
        return $this->hasOne(EmployeeAttendanceApprovalFlow::class, 'employee_id', 'id');
    }

    public function employeeDayOff()
    {
        return $this->hasMany(EmployeeDayOff::class, 'employee_id', 'id');
    }
    public function employeeThresholdDetail()
    {
        return $this->hasMany(EmployeeThresholdRelatedDetail::class, 'employee_id', 'id')->orderby('deduction_setup_id', 'asc');
    }

    public function employeeClaimRequestApprovalDetailModel()
    {
        return $this->hasOne(EmployeeClaimRequestApprovalFlow::class);
    }

    public function employeeOffboardApprovalDetailModel()
    {
        return $this->hasOne(EmployeeOffboardApprovalFlow::class);
    }

    public function employeeAppraisalApprovalDetailModel()
    {
        return $this->hasOne(EmployeeAppraisalApprovalFlow::class);
    }
    public function employeeAdvanceApprovalDetailModel()
    {
        return $this->hasOne(EmployeeAdvanceApprovalFlow::class);
    }
    public function employeeBusinessTripApprovalDetailModel()
    {
        return $this->hasOne(EmployeeBusinessTripApprovalFlow::class);
    }
    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_participants', 'employee_id', 'event_id');
    }

    public static function getHeadDept()
    {
        return User::where('user_type', '!=', 'super_admin')->get();
    }

    public function getUser()
    {
        return $this->hasOne(User::class, 'emp_id')->withoutGlobalScopes();
    }

    public static function getUserInfoByEmp($emp_id)
    {
        return User::where('emp_id', '=', $emp_id)->first();
    }

    public static function getDetail($id)
    {
        return Employee::where('id', $id)->first();
    }

    public function attendanceLog()
    {
        return $this->hasMany(AttendanceLog::class, 'emp_id', 'id');
    }

    public function getCurrentAttendanceLog()
    {
        return $this->attendanceLog()->where('date', Carbon::now()->toDateString())->orderBy('id', 'desc')->get();
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'emp_id', 'id');
    }

    public function getSingleAttendance($field, $date)
    {
        return $this->hasOne(Attendance::class, 'emp_id', 'id')->where($field, $date)->first();
    }

    public function getSingleAttendance1()
    {
        return $this->hasOne(Attendance::class, 'emp_id', 'id');
    }

    public function attendanceRequest()
    {
        return $this->hasMany(AttendanceRequest::class, 'employee_id', 'id');
    }

    public function getAtttendanceRequestByDate($field, $date)
    {
        return $this->hasMany(AttendanceRequest::class, 'employee_id', 'id')->where($field, $date);
    }

    public function leaveEncashable()
    {
        return $this->hasMany(LeaveEncashable::class, 'employee_id');
    }

    public function newShift()
    {
        return $this->hasMany(NewShiftEmployee::class, 'emp_id');
    }

    public function getSingleNewShift()
    {
        return $this->hasOne(NewShiftEmployee::class, 'emp_id');
    }

    // public function currentLeaveYearleaveEncashable()
    // {
    //     return $this->leaveEncashable->where('leave_year_id',);
    // }


    /**
     *
     */
    public static function getCount()
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            $result = Employee::select('organization_id', 'status', 'id')->where('organization_id', optional($authUser->userEmployer)->organization_id)->where('status', 1)->count();
        } else {
            $result = Employee::select('organization_id', 'status', 'id')->where('status', 1)->count();
        }

        return $result;
    }

    public static function getMaleCount($male_id)
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            $result = Employee::where('organization_id', optional($authUser->userEmployer)->organization_id)->where('status', 1)->where('gender', $male_id)->count();
        } else {
            $result = Employee::where('status', 1)->where('gender', $male_id)->count();
        }

        return $result;
    }

    public static function getFemaleCount($female_id)
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            $result = Employee::where('organization_id', optional($authUser->userEmployer)->organization_id)->where('status', 1)->where('gender', $female_id)->count();
        } else {
            $result = Employee::where('status', 1)->where('gender', $female_id)->count();
        }

        return $result;
    }

    public static function getSingleCount($single_id)
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            $result = Employee::where('organization_id', optional($authUser->userEmployer)->organization_id)->where('status', 1)->where('marital_status', $single_id)->count();
        } else {
            $result = Employee::where('status', 1)->where('marital_status', $single_id)->count();
        }

        return $result;
    }

    public static function getMarriedCount($married_id)
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            $result = Employee::where('organization_id', optional($authUser->userEmployer)->organization_id)->where('status', 1)->where('marital_status', $married_id)->count();
        } else {
            $result = Employee::where('status', 1)->where('marital_status', $married_id)->count();
        }

        return $result;
    }

    public static function getLevelCount($levelId)
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            $result = Employee::where('organization_id', optional($authUser->userEmployer)->organization_id)->where('status', 1)->where('level_id', $levelId)->count();
        } else {
            $result = Employee::where('status', 1)->where('level_id', $levelId)->count();
        }

        return $result;
    }

    /**
     * Provide list of branches of specific organization
     */
    public static function getOrganizationwiseBranches($organizationId)
    {
        $list = [];
        $models = Branch::where('organization_id', $organizationId)->get();
        if (count($models) > 0) {
            foreach ($models as $model) {
                $list[$model->id] = $model->name;
            }
        }
        return $list;
    }

    /**
     * Provide list of departments of specific organization
     */
    public static function getOrganizationwiseDepartments($organizationId)
    {
        $list = [];
        $models = DepartmentOrganization::where('organization_id', $organizationId)->get();
        if (count($models) > 0) {
            foreach ($models as $model) {
                $list[$model->department_id] = optional($model->department)->title;
            }
        }
        return $list;
    }

    /**
     * Provide list of designations of specific organization
     */
    public static function getOrganizationwiseDesignations($organizationId)
    {
        $list = [];
        $models = DesignationOrganization::where('organization_id', $organizationId)->get();
        if (count($models) > 0) {
            foreach ($models as $model) {
                $list[$model->designation_id] = optional($model->designation)->title;
            }
        }
        return $list;
    }

    public static function getDesignationwiseLevels($organizationId, $designationId)
    {
        $list = [];
        $levels = Level::whereHas('designations', function ($query) use ($designationId) {
            $query->where('designation_id', $designationId);
        })->whereHas('organizations', function ($query) use ($organizationId) {
            $query->where('organization_id', $organizationId);
        })->get();
        if (!empty($levels)) {
            foreach ($levels as $level) {
                $list[$level['id']] = $level['title'];
            }
        }
        return $list;
    }

    /**
     * Provide list of levels of specific organization
     */
    public static function getOrganizationwiseLevels($organizationId)
    {
        $list = [];
        $models = LevelOrganization::where('organization_id', $organizationId)->get();
        if (count($models) > 0) {
            foreach ($models as $model) {
                $list[$model->level_id] = optional($model->level)->title;
            }
        }
        return $list;
    }

    /**
     * Provide list of employees of specific organization
     */
    public static function getOrganizationwiseEmployees($filter)
    {
        $employees = [];

        $models = Employee::when(true, function ($query) use ($filter) {
            $query->where('organization_id', $filter['organization_id'])->where('status', 1);

            if (isset($filter['department_id']) && !empty($filter['department_id'])) {
                $query->where('department_id', $filter['department_id']);
            }

            if (isset($filter['designation_id']) && !empty($filter['designation_id'])) {
                $query->where('designation_id', $filter['designation_id']);
            }
        })->get();
        if ($models) {
            foreach ($models as $model) {
                $employees[$model->id] = $model->full_name . ' :: ' . $model->employee_code;
            }
        }

        return $employees;
    }

    public static function getOrganizationEmployeeConfirmations($filter)
    {
        $employees = [];

        $models = Employee::where('contract_type', 11) // contract
            ->where('probation_status', 11) // yes
            ->when(true, function ($query) use ($filter) {
                $query->where('organization_id', $filter['organization_id'])
                    ->where('status', 1);

                if (isset($filter['department_id']) && !empty($filter['department_id'])) {
                    $query->where('department_id', $filter['department_id']);
                }

                if (isset($filter['designation_id']) && !empty($filter['designation_id'])) {
                    $query->where('designation_id', $filter['designation_id']);
                }
            })->get();


        if ($models) {
            foreach ($models as $model) {
                $employees[$model->id] = $model->full_name . ' :: ' . $model->employee_code;
            }
        }

        return $employees;
    }


    public static function getOrganizationWisePermanentEmployees($filter)
    {
        $employees = [];

        $models = Employee::whereHas('payrollRelatedDetailModel', function ($query) {
            $query->where('contract_type', 12); // permanent
        })->when(true, function ($query) use ($filter) {
            if (!empty($filter)) {
                $query->where('organization_id', $filter['organization_id'] ?? $filter['org_id'])
                    ->where('status', 1);
            }
        })->get();

        if ($models) {
            foreach ($models as $model) {
                $employees[$model->id] = $model->full_name . ' :: ' . $model->employee_code;
            }
        }

        return $employees;
    }
    /**
     * Get subordinates of that employee
     * Provide list of employees
     */
    public static function getSubordinates($userId)
    {
        $employeeIds = [];
        $models = EmployeeApprovalFlow::where('first_approval_user_id', $userId)->orWhere('last_approval_user_id', $userId)->get();
        if ($models->count() > 0) {
            foreach ($models as $model) {
                $employeeIds[] = $model->employee_id;
            }
        }

        return $employeeIds;
    }

    // public static function getSubordinateUserIds($userId)
    // {
    //     $userIds = [];
    //     $models = EmployeeApprovalFlow::where('first_approval_user_id', $userId)->orWhere('last_approval_user_id', $userId)->get();
    //     if ($models->count() > 0) {
    //         foreach ($models as $model) {
    //             $userIds[] = optional(optional($model->employee)->getUser)->id;
    //         }
    //     }
    //     return $userIds;
    // }

    public static function boot()
    {
        parent::boot();
        // static::addGlobalScope(new ActiveScope);

        self::saved(function ($model) {
            Employee::where('id', $model->id)->update(['employee_id' => $model->id]);
        });
    }

    public function appendPayrollRetatedDetailAttributes($data)
    {
        if ($this->payrollRelatedDetailModel) {
            $attributes = $this->payrollRelatedDetailModel->getAttributes();
            $ignoreAttributes = ['id', 'employee_id', 'created_at', 'updated_at'];
            foreach ($attributes as $key => $value) {
                if (in_array($key, $ignoreAttributes)) {
                    continue;
                }
                $data[$key] = $value;
            }
        }

        return $data;
    }

    public function appendEmployeeApprovalFlowDetailAttributes($data)
    {
        if ($this->employeeApprovalFlowRelatedDetailModel) {
            $attributes = $this->employeeApprovalFlowRelatedDetailModel->getAttributes();
            $ignoreAttributes = ['id', 'employee_id', 'created_by', 'updated_by', 'created_at', 'updated_at'];
            foreach ($attributes as $key => $value) {
                if (in_array($key, $ignoreAttributes)) {
                    continue;
                }
                $data[$key] = $value;
            }
        }
        return $data;
    }

    public function appendEmployeeClaimRequestApprovalFlowDetailAttributes($data)
    {
        if ($this->employeeClaimRequestApprovalDetailModel) {
            $attributes = $this->employeeClaimRequestApprovalDetailModel->getAttributes();
            $ignoreAttributes = ['id', 'employee_id', 'created_by', 'updated_by', 'created_at', 'updated_at'];
            foreach ($attributes as $key => $value) {
                if (in_array($key, $ignoreAttributes)) {
                    continue;
                }
                $data[$key] = $value;
            }
        }
        return $data;
    }

    public function appendEmployeeOffboardApprovalFlowDetailAttributes($data)
    {
        if ($this->employeeOffboardApprovalDetailModel) {
            $attributes = $this->employeeOffboardApprovalDetailModel->getAttributes();
            $ignoreAttributes = ['id', 'employee_id', 'created_by', 'updated_by', 'created_at', 'updated_at'];
            foreach ($attributes as $key => $value) {
                if (in_array($key, $ignoreAttributes)) {
                    continue;
                }
                if ($key == 'first_approval') {
                    $data['offboard_first_approval'] = $value;
                } else if ($key == 'last_approval') {
                    $data['offboard_last_approval'] = $value;
                } else {
                    $data[$key] = $value;
                }
            }
        }
        return $data;
    }

    public function appendEmployeeAppraisalApprovalFlowDetailAttributes($data)
    {
        if ($this->employeeAppraisalApprovalDetailModel) {
            $attributes = $this->employeeAppraisalApprovalDetailModel->getAttributes();
            $ignoreAttributes = ['id', 'employee_id', 'created_by', 'updated_by', 'created_at', 'updated_at'];
            foreach ($attributes as $key => $value) {
                if (in_array($key, $ignoreAttributes)) {
                    continue;
                }
                if ($key == 'first_approval') {
                    $data['appraisal_first_approval'] = $value;
                } else if ($key == 'last_approval') {
                    $data['appraisal_last_approval'] = $value;
                } else {
                    $data[$key] = $value;
                }
            }
        }
        return $data;
    }
    public function appendEmployeeAdvanceApprovalFlowDetailAttributes($data)
    {
        if ($this->employeeAdvanceApprovalDetailModel) {
            $attributes = $this->employeeAdvanceApprovalDetailModel->getAttributes();
            $ignoreAttributes = ['id', 'employee_id', 'created_by', 'updated_by', 'created_at', 'updated_at'];
            foreach ($attributes as $key => $value) {
                if (in_array($key, $ignoreAttributes)) {
                    continue;
                }
                if ($key == 'first_approval') {
                    $data['advance_first_approval'] = $value;
                } else if ($key == 'last_approval') {
                    $data['advance_last_approval'] = $value;
                } else {
                    $data[$key] = $value;
                }
            }
        }
        return $data;
    }

    public function appendEmployeeBusinessTripApprovalFlowDetailAttributes($data)
    {
        if ($this->employeeBusinessTripApprovalDetailModel) {
            $attributes = $this->employeeBusinessTripApprovalDetailModel->getAttributes();
            $ignoreAttributes = ['id', 'employee_id', 'created_by', 'updated_by', 'created_at', 'updated_at'];
            foreach ($attributes as $key => $value) {
                if (in_array($key, $ignoreAttributes)) {
                    continue;
                }
                if ($key == 'first_approval') {
                    $data['business_trip_first_approval'] = $value;
                } else if ($key == 'last_approval') {
                    $data['business_trip_last_approval'] = $value;
                } else {
                    $data[$key] = $value;
                }
            }
        }
        return $data;
    }

    public function getEmployeeDayList()
    {
        return $this->employeeDayOff->pluck('day_off', 'id')->toArray();
    }

    // public function appendEmployeeDayOffAttributes($data)
    // {
    //     if ($this->employeeDayOff) {
    //         foreach ($this->employeeDayOff as $key => $value) {
    //             $attributes = $value->getAttributes();
    //             $ignoreAttributes = ['id', 'employee_id', 'created_at', 'updated_at'];
    //             foreach ($attributes as $key => $value) {
    //                 if (in_array($key, $ignoreAttributes)) {
    //                     continue;
    //                 }
    //                 $data[$key] = $value;
    //             }
    //         }
    //     }
    //     return $data;
    // }

    /**
     * Provide list of employees of specific organization and department
     */
    public static function getEmployeesOrganizationDepartmentwise($organizationId, $departmentId, $branchId)
    {
        $employees = [];
        $qry = Employee::query();
        $qry->where('organization_id', $organizationId)->where('department_id', $departmentId)->where('id', '!=', auth()->user()->emp_id)->where('status', 1);
        if (isset($branchId)) {
            $qry->where('branch_id', $branchId);
        }
        $models = $qry->get();
        if ($models) {
            foreach ($models as $model) {
                $employees[$model->id] = $model->full_name;
            }
        }
        return $employees;
    }

    public static function saveEmployeeTimelineData($employeeId, $params)
    {
        $params['employee_id'] = $employeeId;

        return EmployeeTimeline::create($params);
    }

    public static function getEmployeeDepartmentWise($departmentId)
    {
        return Employee::where('department_id', $departmentId)->where('status', 1)->where('id', '!=', auth()->user()->emp_id)->pluck('id');
    }

    /**
     *
     */
    public static function findAlternativeEmployees($params)
    {
        $result = [];

        if (isset($params['employee_id'])) {
            $employeeId = $params['employee_id'];
            $mainEmployeeModel = Employee::find($employeeId);
            if ($mainEmployeeModel) {
                $models = Employee::where([
                    'department_id' => $mainEmployeeModel->department_id,
                    'organization_id' => $mainEmployeeModel->organization_id,
                    'status' => 1
                ])->get();
                if ($models) {
                    foreach ($models as $model) {
                        if ($model->employee_id == $employeeId) {
                            // no nothing
                        } else {
                            $result[$model->id] = $model->full_name;
                        }
                    }
                }
            }
        }

        return $result;
    }

    public static function getCountry($id)
    {
        return Country::where('id', $id)->first();
    }

    public function bankDetail()
    {
        return $this->hasone(BankDetail::class, 'employee_id', 'id');
    }

    public static function getSupervisorSubordinates($userId)
    {
        $employees = [];
        $models = EmployeeAppraisalApprovalFlow::where('first_approval', $userId)->get();
        if ($models->count() > 0) {
            foreach ($models as $model) {
                if ($model->employee_id && optional($model->employeeModel)->full_name) {
                    $employees[$model->employee_id] = optional($model->employeeModel)->full_name;
                }
            }
        }
        return $employees;
    }
    public function findOtByType($employee_id, $type)
    {
        return EmployeeOtDetail::where('employee_id', $employee_id)->where('ot_type', $type)->first();
    }

    public static function getName($user_id)
    {
        $user = User::find($user_id);
        if (!empty(optional($user->userEmployer)->middle_name)) {
            $full_name = optional($user->userEmployer)->first_name . ' ' . optional($user->userEmployer)->middle_name . ' ' . optional($user->userEmployer)->last_name;
        } else {
            $full_name = optional($user->userEmployer)->first_name . ' ' . optional($user->userEmployer)->last_name;
        }

        return $full_name;
    }

    public function getSingleSiteAttendance($date)
    {
        return $this->hasOne(DivisionAttendanceReport::class, 'employee_id', 'id')->where('date', $date)->first();
    }

    public function insuranceDetail()
    {
        return $this->hasOne(EmployeeInsuranceDetail::class, 'employee_id');
    }

    public function visibilitySetup()
    {

        return $this->hasOne(EmployeeVisibilitySetup::class, 'user_id');
    }

    public function archivedDetails()
    {
        return $this->hasMany(ArchivedDetail::class, 'employee_id', 'id');
    }

    public static function findByEmployeeCode($empCode)
    {
        return Employee::where('employee_code', $empCode)->first();
    }

    public function EmpShiftGroup()
    {
        return $this->hasMany(ShiftGroupMember::class, 'group_member');
    }
    public function assignedRoles()
    {
        $user_id = $this->user->id;
        return AssignedRole::where('user_id', $user_id)->get();
    }

    public static function contractTypeList($contract = null)
    {
        return self::where('status', 1)
            ->whereHas('payrollRelatedDetailModel', function ($query) {
                return $query->where('contract_type', "!=", '12');
            })
            ->orderBy('first_name', 'asc')->get()
            ->mapWithKeys(function ($emp) {
                return [$emp->id => $emp->full_name . ' :: ' . $emp->employee_code];
            })
            ->toArray();
    }
}