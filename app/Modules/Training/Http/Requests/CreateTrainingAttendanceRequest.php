<?php

namespace App\Modules\Training\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTrainingAttendanceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'employee_id' => 'required',
            'contact_no' => 'required|numeric',
            'email' => 'required|email',
            'remarks' => 'required',
            'feedback' => 'required',
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

    public function messages()
    {
        return [
            // 'employee_id.required' => 'Please enter participant name',
            'contact_no.required' => 'Please enter contact number',
            'contact_no.numeric' => 'Please enter number only',
            'email.required' => 'Please enter email',
            'email.email' => 'Email format not matched',
            'remarks.required' => 'Please enter remarks',
            'remarks.feedback' => 'Please enter feedback',
        ];
    }
}
