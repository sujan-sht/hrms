<?php

namespace App\Modules\Onboarding\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InterviewRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'applicant_id' => 'required',
            'interview_level_id' => 'required',
            'date' => 'required'
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
}
