<?php

namespace App\Modules\Employee\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEmployerUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required',
           // 'email' => 'required',
            'password' => 'required',
            
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return [
            'username.required' => 'User Name is required',
            //'email.required' => 'Email is required',
            'password.required' => 'Password is required',
        ];
    }
}
