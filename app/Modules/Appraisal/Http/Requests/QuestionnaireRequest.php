<?php

namespace App\Modules\Appraisal\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionnaireRequest extends FormRequest
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
            'competency_ids' => 'required',
            'competency_library_id' => 'required'
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
            'title.required' => 'A nice title is required for the post.',
            'competency_ids.required' => 'You must choose 3 Competencies',
            'competency_library_id.required' => 'Please Choose Competency Library',
        ];
    }
}
