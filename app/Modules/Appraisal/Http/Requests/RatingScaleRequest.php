<?php

namespace App\Modules\Appraisal\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RatingScaleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'score' => 'required',
            'indication' => 'required',
            'explanation' => 'required'
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
            'score.required' => 'Score is Required.',
            'indication.required' => 'Indication is Required',
            'explanation.required' => 'Explanation is Required',
        ];
    }
}
