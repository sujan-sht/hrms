<?php

namespace App\Modules\Appraisal\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppraisalRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'appraisee' => 'required',
            'questionnaire_id' => 'required',
            // 'type' => 'required',
            'enable_self_evaluation' => 'required',
            'enable_supervisor_evaluation' => 'required',
            // 'enable_hod_evaluation' => 'required',
            'valid_date' => 'required'
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
            'questionnaire_id.required' => 'Please Choose Questionnaire'
        ];
    }
}
