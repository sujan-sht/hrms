<?php

namespace App\Modules\PMS\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTargetRequest extends FormRequest
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
            'kpi_id' => 'required',
            'fiscal_year_id' => 'required',
            'title' => 'required',
            'frequency' => 'required',
            'weightage' => 'required|numeric',
            'no_of_quarter' => 'required|numeric|min:1|max:4',
            // 'no_of_quarter' => 'required|numeric|min:1|max:12',
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
            'kpi_id.required' => 'Please select KPA',
            'fiscal_year_id.required' => 'Please select Fiscal Year',
            'title.required' => 'Please enter title',
            'frequency.required' => 'Please select frequency',
            'weightage.required' => 'Please enter weightage',
            'weightage.numeric' => 'Please enter number',
            'no_of_quarter.required' => 'Please enter No of Quarter',
            'no_of_quarter.numeric' => 'Please enter number',
            'no_of_quarter.min' => 'Please enter number greater than 0',
            'no_of_quarter.max' => 'Please enter number less than 5',
        ];
    }
}
