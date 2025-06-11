<?php

namespace App\Modules\PMS\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TargetValueRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'employee_ids' => 'required',
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
            'employee_ids.required' => 'Please Choose Employee'
        ];
    }
}
