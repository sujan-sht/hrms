<?php

namespace App\Modules\User\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\User\Entities\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use App\Modules\Setting\Entities\Setting;
use App\Modules\Admin\Entities\MailSender;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\User\Repositories\UserInterface;
use App\Modules\User\Http\Requests\LoginFormRequest;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;

class LoginController extends Controller
{
    protected $user;
    protected $employee;
    protected $organization;


    public function __construct(UserInterface $user, EmployeeInterface $employee, OrganizationInterface $organization)
    {
        $this->user = $user;
        $this->employee = $employee;
        $this->organization = $organization;
    }

    public function login()
    {
        $data['setting'] = Setting::first();

        if (Auth::check()) {
            return redirect()->intended(route('dashboard'));
        } else {
            return view('user::login.login', $data);
        }
    }

    public function authenticate(Request $request)
    {
        $data = $request->all('username', 'password');
        $request->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
            ],
        ]);
        if ($data) {
            $user = User::where('username', $data['username'] ?? null)->first();
            if (!is_null($user)) {


                if (auth()->attempt(['username' => $data['username'], 'password' => $data['password'], 'active' => 1])) {

                    $password_updated_date = config('login_attempts.password_reset_days_enable', true) ? ($user->password_updated_date ?? null) : null;
                    if (!is_null($password_updated_date) || !config('login_attempts.reset_user_on_first_login', true)) {
                        if (is_null($password_updated_date)) {
                            $password_updated_date = now();
                            $user->update([
                                'password_updated_date' => $password_updated_date
                            ]);
                        }
                        $password_expiry_date = Carbon::parse($password_updated_date)->addDays(30);
                        if (config('login_attempts.password_reset_days_enable', true) ? $password_expiry_date > now() : true) {
                            $loginData = [
                                'employee_id' => auth()->user()->emp_id,
                                'type' => 'login',
                                'date' => date('Y-m-d'),
                                'nepali_date' => date_converter()->eng_to_nep_convert(date('Y-m-d'))
                            ];
                            $this->user->storeActivityLog($loginData);
                            return redirect()->intended(route('dashboard'));
                        } else {
                            // Logging Out User
                            Auth::logout();
                            // Randomize the password
                            $user->update([
                                'password' => bcrypt(Str::random(8))
                            ]);
                            toastr()->warning('Your password has expired. Please reset your password.');
                            return redirect('/');
                        }
                    } else {
                        // Resetting the first time logged in user if turned on
                        return $this->resetOnFirstLogin();
                    }
                } else {
                    // $this->loginAttempt($request);
                    toastr()->warning('Please Enter Correct Username/Password');
                    return redirect('/');
                }
            } else {
                toastr()->warning('No user found with provided information.');
                return redirect('/');
            }
        }
    }

    public function changePassword()
    {
        return view('user::login.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => [
                'required',
                'confirmed',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
            ],
        ]);

        $oldPassword = $request->get('old_password');
        $newPassword = $request->get('password');
        $id = Auth::user()->id;
        $users = Auth::user()->find($id);
        if (!(Hash::check($oldPassword, $users->password))) {
            toastr()->warning('Old Password Do Not Match !');
            return redirect(route('change-password'));
        } else {
            $data['password'] = Hash::make($newPassword);
            $this->user->update($id, $data);
            toastr()->success('Password Successfully Updated. Please Login Again!');
        }
        Auth::logout();
        return redirect('/');
    }

    public function changeUsername(Request $request)
    {
        $usernames = $this->user->findAllExceptOne($request->id)->pluck('username')->toArray();

        if (!in_array($request->username, $usernames)) {
            if ($request->has('password')) {

                $user = $this->user->find($request->id);
                $user->fill(['username' => $request->username, 'password' => Hash::make($request->password)]);
                $user->save();
                toastr()->success('Credentails Successfully Updated. !');
            } else {
                $user = $this->user->find($request->id);
                $user->fill(['username' => $request->username]);
                $user->save();
                toastr()->success('Credentails Successfully Updated. !');
            }
        } else {
            toastr()->error('Username Already exist!. Please Try Again!');
        }
        return back();
    }

    public function permissionDenied()
    {
        return view('user::authPermission.permission-denied');
    }

    public function logout()
    {
        $logoutData = [
            'employee_id' => auth()->user()->emp_id,
            'type' => 'logout',
            'date' => date('Y-m-d'),
            'nepali_date' => date_converter()->eng_to_nep_convert(date('Y-m-d'))
        ];
        $this->user->storeActivityLog($logoutData);
        Auth::logout();
        toastr()->success('You are now signed out.');
        return redirect('/');
    }

    public function activityLogReport(Request $request)
    {
        $filter = $request->all();
        $data['activityLogs'] = $this->user->activityLogs(10, $filter);

        if (auth()->user()->user_type == 'super_admin' || auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'hr' || auth()->user()->user_type == 'division_hr') {
            $data['employeeList'] = $this->employee->getList();
            $data['organizationList'] = $this->organization->getList();
        }
        return view('user::activityLog.index', $data);
    }

    public function resetPassword()
    {
        return view('user::login.forgot-password');
    }

    public function setPasswordView($activation_code)
    {
        $data['user'] = User::where('activation_code', $activation_code)->first();
        if ($data['user'] != null) {
            return view('user::login.set-password-view', $data);
        } else {
            toastr()->error('Your password has already been reset.');
            return redirect('/');
        }
    }

    public function resetPasswordLink(Request $request)
    {
        $username = $request->username;

        if (User::where('username', $username)->exists()) {
            $user = User::where('username', $username)->first();
            $user->update([
                'activation_code' => $user->generateActivationCode()
            ]);
            $details = [
                'email' => $user->userEmployer->official_email,
                'notified_user_fullname' => $username,
                'setting' => Setting::first(),
                'subject' => 'Reset Your Password',
                'url' => route('set-password-view', $user->activation_code)
            ];
            (new MailSender())->sendMail('admin::mail.reset_password', $details);
            return redirect('/');
        } else {
            toastr()->error('Please enter correct username!');
            return redirect()->route('forgot-password');
        }
    }

    public function setPassword(Request $request)
    {
        $request->validate([
            'password' => [
                'required',
                'confirmed',
                'string',
                'min:10',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
            ],
        ]);

        $user = User::where('username', $request->username)->first();
        $user->update([
            'password' => Hash::make($request->password),
            'activation_code' => null
        ]);
        // Updating password_updated_date
        $user->update([
            'password_updated_date' => date('Y-m-d')
        ]);
        toastr()->success('Password Successfully Reset. Please Login Again!');
        return redirect('/');
    }

    private function resetOnFirstLogin()
    {
        if (config('login_attempts.reset_user_on_first_login', true) && Auth::check()) {
            $user = Auth::user();
            if ($user->user_type == 'super_admin' || $user->user_type == 'admin') {
                $loginData = [
                    'employee_id' => auth()->user()->emp_id,
                    'type' => 'login',
                    'date' => date('Y-m-d'),
                    'nepali_date' => date_converter()->eng_to_nep_convert(date('Y-m-d'))
                ];
                $this->user->storeActivityLog($loginData);
                return redirect()->intended(route('dashboard'));
            } else {
                if (is_null($user->password_updated_date)) {
                    // Logging Out User
                    Auth::logout();

                    toastr()->info('First time login success. Please set your new password');

                    return redirect()->route('forgot-password');
                } else {
                    $loginData = [
                        'employee_id' => auth()->user()->emp_id,
                        'type' => 'login',
                        'date' => date('Y-m-d'),
                        'nepali_date' => date_converter()->eng_to_nep_convert(date('Y-m-d'))
                    ];
                    $this->user->storeActivityLog($loginData);
                    return redirect()->intended(route('dashboard'));
                }
            }
        }
    }
}
