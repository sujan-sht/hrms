<?php

namespace App\Modules\Survey\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SurveyAllocateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'organization_ids' => 'required',
            // 'level_ids' => 'required',
            // 'department_ids' => 'required',
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
            'organization_ids.required' => 'Please Select Organization',
            // 'level_ids.required' => 'Please Select Level',
            // 'department_ids.required' => 'Please Select Sub-Function',
        ];
    }
}
