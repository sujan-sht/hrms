<?php

namespace App\Modules\Api\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Modules\User\Entities\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use App\Modules\Setting\Entities\Setting;
use Illuminate\Support\Facades\Validator;
use App\Modules\Admin\Entities\MailSender;
use App\Modules\Admin\Entities\UserDevice;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Api\Transformers\UserResource;
use App\Modules\Api\Http\Requests\LoginRequest;

class AuthController extends Controller
{

    public function forgetPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);
        if ($validator->fails()) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => $validator->errors()
            ];
            return response()->json($response, 200);
        }
        DB::beginTransaction();
        try{
            $user=User::where('email',$request->email)->first();
            if(!$user){
               throw new Exception('User Not Found !!');
            }
            $user->update([
                'activation_code'=>$user->generateActivationCodeApi()
            ]);
            $details = [
                'email' => $user->email,
                'notified_user_fullname' => $user->username,
                'subject' => 'Reset Your Password',
                'setting' => Setting::first(),
                'user'=>$user
            ];
            (new MailSender())->sendMail('admin::mail.reset_password-api', $details);
           DB::commit();
           $response = [
            'error' => false,
            'data' => $user,
            'otp'=>$user->activation_code,
            'message' =>'Otp has sent to your mail !'
         ];
         return response()->json($response, 200);
        }catch(\Throwable $th){
            DB::rollBack();
            $response = [
                'error' => true,
                'data' => null,
                'message' =>$th->getMessage()
            ];
            return response()->json($response, 200);
        }
       
    }


    public function resetPasswordVerification(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp'=>'required|string',
        ]);
        if ($validator->fails()) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => $validator->errors()
            ];
            return response()->json($response, 200);
        }
        DB::beginTransaction();
        try{
            $user=User::where('email',$request->email)->first();
            if(!$user){
               throw new Exception('User Not Found !!');
            }
            if($user->activation_code != $request->otp){
                throw new Exception('Invalid Otp !!');
            }

           DB::commit();
           $response = [
            'error' => false,
            'data' => $user,
            'otp'=>$user->activation_code,
            'message' =>'Otp Verified Successfully !'
         ];
         return response()->json($response, 200);
        }catch(\Throwable $th){
            DB::rollBack();
            $response = [
                'error' => true,
                'data' => null,
                'message' =>$th->getMessage()
            ];
            return response()->json($response, 200);
        }
       
    }


    
    public function resetPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp'=>'required|string',
            'new_password'=>'required|min:6'
        ]);
        if ($validator->fails()) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => $validator->errors()
            ];
            return response()->json($response, 200);
        }
        DB::beginTransaction();
        try{
            $user=User::where('email',$request->email)->first();
            if(!$user){
               throw new Exception('User Not Found !!');
            }
            if($user->activation_code != $request->otp){
                throw new Exception('Invalid Otp !!');
            }

            
            $user->update([
                'password'=>bcrypt($request->new_password)
            ]);
           DB::commit();
           $response = [
            'error' => false,
            'data' => $user,
            'otp'=>$user->activation_code,
            'message' =>'Password Updated Successfuly !'
         ];
         return response()->json($response, 200);
        }catch(\Throwable $th){
            DB::rollBack();
            $response = [
                'error' => true,
                'data' => null,
                'message' =>$th->getMessage()
            ];
            return response()->json($response, 200);
        }
       
    }


    
    
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'mobile_number' => 'required',
        ]);
        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        $success['token'] =  $user->createToken('authToken')->accessToken;
        $success['name'] =  $user->name;
        return response()->json(['success' => $success]);
    }

    public function login(Request $request)
    {
        try {
            //Check App access or not
            $appAccess = Config::get('api.app_access');
            if(!$appAccess){
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have App Access.',
                ], 401);
            }
            //

            $validateUser = Validator::make(
                $request->all(),
                [
                    'username' => 'required',
                    'password' => 'required',
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if (!auth()->attempt(['username' => $request['username'], 'password' => $request['password'], 'active' => 1])) {

                return response()->json([
                    'status' => false,
                    'message' => 'Username & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('username', $request->username)->first();


            
            $userTypes = ['employee', 'supervisor', 'division_hr', 'hr', 'super_admin'];
            if (!in_array($user->user_type, $userTypes)) {
                return response()->json([
                    'status' => false,
                    'message' => "User Access Denied!"
                ], 401);
            }

            //login access for one device at a time
            if($user->imei && $user->imei != $request['imei']){
                $data['error'] = true;
                $data['status_code'] = 418;
                $data['message'] = "Device Data linked in other device. Please contact administrator to reset your device data.";
                // Auth::logout();
                return response()->json($data, $data['status_code']);
            }
            $user->update(['imei'=>$request['imei']]);
            //

            UserDevice::where('os_player_id',$request['device_id'])->delete();

            // 735e9529-d185-476a-9544-3155fa100a58
            $inputUserDevice = [
                'device_type' => $request['device_type'],
                'os_player_id' => $request['device_id'],
                'is_active' => 11,
            ];
            $user->device()->updateOrCreate([], $inputUserDevice);

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API-TOKEN")->accessToken,
                'user' => new UserResource($user)
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $userModel = $request->user();
        if($userModel->device){
            $userModel->device->delete();
        }
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }

    public function appVersionCheck(Request $request){
        $request->validate([
            'app_version' => 'required',
            'device_type' => 'required'
        ]);

        $setting = Setting::first();

        if($setting && $setting->force_app_update == "11"){
            // Convert app versions to arrays
            $currentVersion = explode('.', $request->app_version);
            $storeVersion = $request->device_type == 'android' ? explode('.', $setting->play_store_app_version) : explode('.', $setting->apple_store_app_version);

            // Compare each segment of the version numbers
            $isNewerVersion = true;
            for ($i = 0; $i < count($currentVersion); $i++) {
                if (intval($currentVersion[$i]) < intval($storeVersion[$i])) {
                    $isNewerVersion = false;
                    break;
                }
            }

            if(!$isNewerVersion) {
                return response()->json([
                    'force_update_app' => true,
                    'app_description' => $setting->app_update_description
                ], 200);
            }
        }

        return response()->json([
            'force_update_app' => false
        ], 200);
    }
}
