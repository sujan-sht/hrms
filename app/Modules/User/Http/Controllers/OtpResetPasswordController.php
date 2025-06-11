<?php

namespace App\Modules\User\Http\Controllers;

use Carbon\Carbon;
use Bidhee\Otp\Facades\Otp;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\User\Entities\User;
use Bidhee\Otp\Models\Otp as OtpModel;
use Illuminate\Support\Facades\Hash;

class OtpResetPasswordController extends Controller
{
    public function getResetPasswordUser()
    {
        return view('user::reset-password.username');
    }

    public function grabResetPasswordUser(Request $request)
    {
        $request->validate([
            'username' => 'required|exists:users,username',
        ]);
        $user = User::where('username', $request->username)->first();

        if ($user) {
            $otp = Otp::generate($user);
            return redirect()->route('otp-reset-password.otp.show', ['otp' => $otp->id]);
        } else {
            return redirect()->route('otp-reset-password.get-user')->with('error', 'User not found');
        }
    }

    public function chooseModeOtp(OtpModel $otp, Request $request)
    {
        $request->validate([
            'mode' => 'required'
        ]);
        $data = $otp->data;
        $data['channels'] = [$request->mode];
        $otp->update(['data' => $data]);

        Otp::sendNotification($otp, $otp->user, route('otp-reset-password.otp.show', ['otp' => $otp->id]));
        return redirect()->route('otp-reset-password.otp.show', ['otp' => $otp->id]);
    }

    public function chooseDifferentModeOtp(OtpModel $otp)
    {
        $data = $otp->data;
        $data['channels'] = null;
        $otp->update(['data' => $data]);
        return redirect()->route('otp-reset-password.otp.show', ['otp' => $otp->id]);
    }

    public function showOtp(OtpModel $otp)
    {
        $user = $otp->user;
        return view('user::reset-password.otp.show', compact('otp', 'user'));
    }

    public function verifyOtp(OtpModel $otp, Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);
        $user = $otp->user;
        if (Otp::isNotExpired($otp)) {
            if ($otp->code == $request->code) {
                // Updating session verified at
                $otp->update(['verified_at' => Carbon::now(), 'expires_at' => null]);
                return redirect()->route('otp-reset-password.change-password', ['otp' => $otp]);
            } else {
                return back()->withErrors(['otp' => 'Invalid OTP entered']);
            }
        } else {
            $user->onVerifyFailed();
            return back()->withErrors(['otp' => 'OTP has been expired']);
        }
    }

    public function regenerateOtp(OtpModel $otp)
    {
        $user = $otp->user;

        if (Otp::isNotExpired($otp)) {
            Otp::sendNotification($otp, $user);
        } else {
            return back()->withErrors(['otp' => 'OTP has been expired']);
        }
        return redirect()->route('otp-reset-password.otp.show', ['otp' => $otp->id]);
    }

    public function resetPassword(OtpModel $otp)
    {
        return view('user::reset-password.change-password', compact('otp'));
    }

    public function updateResetPassword(OtpModel $otp, Request $request)
    {
        $request->validate([
            'password' => [
                'required',
                'confirmed',
                'min:10',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
            ],
        ]);
        $user = User::find($otp->user_id);

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Also making user verified
        $otp->user->onVerify();

        toastr()->success('Password Successfully Reset. Please Login Again!');
        return redirect('/');
    }
}
