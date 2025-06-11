<?php

namespace App\Modules\Api\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // dd($this->user);
        return [
            'username' => 'required',
            'password' => 'required',
            // 'device_id'=>['required','unique.user_devices'],
            // 'device_id' => 'required|exists:user_devices,os_player_id'.$this->user
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function failedValidation(Validator $validator)

    {

        throw new HttpResponseException(

            response()->json([
                'status'   => false,
                'message'   => 'validation error',
                'data'      => $validator->errors()

            ])
        );
    }
}
