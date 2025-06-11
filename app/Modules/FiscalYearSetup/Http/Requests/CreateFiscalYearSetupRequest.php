<?php

namespace App\Modules\FiscalYearSetup\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateFiscalYearSetupRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'fiscal_year' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'status' => 'required',
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
            'fiscal_year.required' => 'Fiscal Year is required',
            'start_date.required' => 'Start Date is required',
            'end_date.required' => 'End Date is required',
            'status.required' => 'Status is required',
        ];
    }
}
