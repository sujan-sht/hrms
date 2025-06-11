<?php

namespace App\Modules\PMS\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateKpiRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'kra_id' => 'required',
            'title' => 'required',
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
            'kra_id.required' => 'Please select KRA',
            'title.required' => 'Please enter title',
        ];
    }
}
