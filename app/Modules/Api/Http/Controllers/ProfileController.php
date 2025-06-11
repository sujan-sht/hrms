<?php

namespace App\Modules\Api\Http\Controllers;

use App\Modules\Api\Transformers\EmployeeResource;
use App\Modules\Api\Transformers\NotificationResource;
use App\Modules\Api\Transformers\UserResource;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Notification\Entities\Notification;
use App\Modules\User\Entities\User;
use BadMethodCallException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends ApiController
{

    public function profile()
    {
        try {
            $user = auth()->user();
            return  new UserResource($user);
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        } catch (BadMethodCallException $e) {
            return $this->respondNotFound();
        }
    }

    public function update(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'mobile' => 'required',
                // 'phone' => 'required',
                'personal_email' => 'required',
            ]
        );

        if ($validate->fails()) {
            return $this->respondValidatorFailed($validate);
        }

        $data = $request->all();

        if ($request->hasFile('profile_pic')) {
            $data['profile_pic'] = $this->uploadProfilePic($data['profile_pic']);
        }
      
        try {
            $user = auth()->user();
            $result = Employee::find($user->emp_id);
            $result->update($data);
            return  $this->respond([
                'status' => true,
                'message' => "User Updated Successfully",
                'data' => new EmployeeResource($result)
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    public function updatePassword(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'old_password' => 'required',
                'confirm_password' => 'required',
                'new_password' => 'required_with:confirm_password|same:confirm_password',

            ]
        );

        if ($validate->fails()) {
            return $this->respondValidatorFailed($validate);
        }

        #Match The Old Password
        if (!Hash::check($request->old_password, auth()->user()->password)) {
            return $this->respondWithError("Old Password Doesn't match!");
        }

        try {
            #Update the new Password
            $user = User::whereId(auth()->user()->id)->update([
                'password' => Hash::make($request->new_password)
            ]);
            return  $this->respond([
                'status' => true,
                'message' => "User Password Changed Successfully",
                'data' => new UserResource(auth()->user())
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }

    public function notification()
    {
        try {
            $inputParams = [
                'notified_user_id' => auth()->id(),
            ];

            $combineArray = array_merge($inputParams, ['is_read' => 0]);
            $data['count'] = Notification::where($combineArray)->count();
            $notifications = Notification::where($inputParams)->orderBy('id', 'DESC')->get();
            $data['notification'] = NotificationResource::collection($notifications);
            return  $this->respond([
                'status' => true,
                'data' => ($data)
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }
    }
    public function uploadProfilePic($file)
    {
        $imageName = $file->getClientOriginalName();
        $fileName = date('Y-m-d-h-i-s') . '-' . preg_replace('[ ]', '-', $imageName);

        $file->move(public_path() . Employee::PROFILE_PATH, $fileName);

        return $fileName;
    }
}
