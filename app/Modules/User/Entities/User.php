<?php

namespace App\Modules\User\Entities;

use Bidhee\Otp\Facades\Otp;
use Illuminate\Support\Str;
use App\Mail\LoginDetailMail;
use Bidhee\Otp\Traits\HasOtp;
use Bidhee\Otp\Services\Sparrow;
use Laravel\Passport\HasApiTokens;
use App\Modules\User\Entities\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Bidhee\Otp\Contracts\ImplementsOtp;
use Illuminate\Database\Eloquent\Model;
use App\Modules\User\Scopes\ActiveScope;
use App\Modules\Admin\Entities\UserDevice;
use App\Modules\Employee\Entities\Employee;
use App\Modules\User\Entities\AssignedRole;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Modules\EmployeeVisibilitySetup\Traits\HasPermissionsTrait;

class User extends Authenticatable implements ImplementsOtp
{
    use HasApiTokens, HasOtp;
    protected $fillable = [

        'ip_address',
        'username',
        'password',
        'email',
        'user_type',
        'activation_code',
        'last_login',
        'active',
        'first_name',
        'middle_name',
        'last_name',
        'company',
        'phone',
        'emp_id',
        'remember_token',
        'parent_id',
        'imei',
        'verified',
        'verified_at',
        'password_updated_date',
    ];

    protected $hidden = [
        'ip_address',
        'password',
        'activation_code',
        'last_login',
        'remember_token',
        'created_at',
        'updated_at'

    ];

    protected $appends = ['full_name'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ActiveScope);
        static::saving(function () {
            self::cacheKey();
        });

        static::deleting(function () {
            self::cacheKey();
        });
    }
    // Cache Keys
    private static function cacheKey()
    {
        Cache::has('user_with_roles_and_permissions') ? Cache::forget('user_with_roles_and_permissions') : '';
    }

    public function generateToken()
    {
        $str = rand();
        $this->api_token = md5($str);
        $this->save();

        return $this->api_token;
    }

    public function device()
    {
        return $this->hasOne(UserDevice::class, 'user_id');
    }

    public function role()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }


    public function userEmployer()
    {
        return $this->belongsTo(Employee::class, 'emp_id', 'id');
    }

    // public function getEmployeeOrganizationId()
    // {
    //     return $this->userEmployer->organization_id;
    // }

    public function generateActivationCode()
    {
        $alphabet = Str::uuid()->toString();
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 10; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    public function generateActivationCodeApi()
    {
        $string = Str::random(6) . rand(111, 999);
        return $string;
    }

    public function getFullNameAttribute()
    {
        if (!empty($this->middle_name)) {
            $fullName = $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name;
        } else {
            $fullName = $this->first_name . ' ' . $this->last_name;
        }

        return $fullName;
    }

    public function getChild()
    {
        return $this->hasMany(User::class, 'parent_id', 'id');
    }

    public function assignedRoles()
    {
        return $this->hasMany(AssignedRole::class, 'user_id', 'id');
    }



    public function getParent()
    {
        return $this->belongsTo(User::class, 'parent_id', 'id');
    }

    public static function getUserEmail($user_id)
    {
        $email = env('MAIL_TO_ADDRESS');

        $userModel = User::where('id', $user_id)->first();
        if ($userModel) {
            $employee_email = Employee::select('official_email')->where('id', $userModel->emp_id)->first();
            if ($employee_email) {
                $email = $employee_email->official_email;
            }
        }

        return $email;
    }

    public static function getName($user_id)
    {
        $user = User::find($user_id);
        if (!empty($user->middle_name)) {
            $full_name = $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name;
        } else {
            $full_name = $user->first_name . ' ' . $user->last_name;
        }

        return $full_name;
    }

    public static function getAllActiveUserListExpectEmployeeOrgWise($organizationId)
    {
        $users = User::where(function ($query) use ($organizationId) {
            $query->where('active', 1);
            $query->where('user_type', '!=', 'employee');
            if (isset($organizationId)) {
                $query->whereHas('userEmployer', function ($q) use ($organizationId) {
                    $q->where('organization_id', $organizationId);
                });
            }
        })->get();

        $user_data = array();
        foreach ($users as $user) {
            if (!empty($user->middle_name)) {
                $full_name = $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name;
            } else {
                $full_name = $user->first_name . ' ' . $user->last_name;
            }
            $user_data += array(
                $user->id => $full_name
            );
        }
        return $user_data;
    }

    /**
     *
     */
    public function getModuleDetailsAttribute()
    {
        $result = [];

        $authUser = auth()->user();
        switch ($authUser->user_type) {
            case 'hr':
                $result = [
                    'Dashboard' => true,
                    'Organization' => true,
                    'WorkLog' => true,
                    'Leave' => true,
                    'Attendance' => true,
                    'ClaimAndRequest' => true,
                    'Grievance' => true,
                    'Poll' => true,
                    'Payroll' => true,
                    'Organization' => true,
                    'Notice' => true,
                    'EventAndHoliday' => true
                ];
                break;
            default:
                $result = [
                    'Dashboard' => true,
                    'Organization' => true,
                    'WorkLog' => true,
                    'Leave' => true,
                    'Attendance' => true,
                    'ClaimAndRequest' => true,
                    'Grievance' => true,
                    'Poll' => true,
                    'Payroll' => true,
                    'Organization' => true,
                    'Notice' => true,
                    'EventAndHoliday' => true
                ];
                break;
        }

        return $result;
    }

    public function getOtpName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }


    public function getOtpEmail()
    {
        return $this->userEmployer->official_email ?? null;
    }

    public function getOtpPhone()
    {
        return $this->userEmployer->phone ?? $this->userEmployer->mobile ?? null;
    }



    public function demandsOtpIf(): bool
    {
        if ($this->user_type == "super_admin") {
            return false;
        }

        if (is_null($this->getOtpEmail()) && is_null($this->getOtpPhone())) {
            return false;
        }
        return !($this->verified ?? false);
    }

    public function onVerify(): void
    {
        $this->update([
            'verified' => true,
            'verified_at' => now()
        ]);
    }

    public function sendSMS($message)
    {
        $phone = $this->getOtpPhone();
        if ($phone) {
            $sms_credits = Otp::sparrowCredits();
            if (($sms_credits ?? 0) > 0) {
                return (new Sparrow)->send([$phone], $message);
            } else {
                throw new \Exception('No SMS credits available');
            }
        } else {
            throw new \InvalidArgumentException('Phone is required');
        }
    }

    public function activateUserAccess()
    {


        $email = $this->getOtpEmail();
        $name = $this->getOtpName();
        $password = env('DEFAULT_USER_PASSWORD', "Cocacola@123");
        $this->update([
            'active' => true,
            'password' => Hash::make($password)
        ]);

        $employee = $this->userEmployer;

        if (!is_null($employee)) {
            $employee->update([
                'is_user_access' => true,
            ]);
        }

        // Send email with login details
        if (!is_null($name) && !is_null($email)) {

            $receiver =
                (object) [
                    'email' => $email,
                    'name' => $name,
                ];
            Mail::to($receiver)->send(new LoginDetailMail($this, $password));
        }
        // Sending SMS with login details
        $message = "Dear $name, your login details are as follows: \nUsername: $this->username \nPassword: $password";
        if (env('LOGIN_ACTIVATE_SMS', false)) {
            $message = "Dear $name, your login details are as follows: \nUsername: $this->username \nPassword: $password" . ' - ' . env('APP_NAME', 'Bidhee') . ', Purpose: Login Details';
            $this->sendSMS($message);
        }
    }
}
