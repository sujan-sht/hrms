<?php

namespace App\Modules\PMS\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateKraRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'department_id' => 'required',
            'division_id' => 'required',
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
            'title.required' => 'KRA title is required',
            'department_id.required' => 'Department is required',
            'division_id.required' => 'Division is required',
        ];
    }
}
