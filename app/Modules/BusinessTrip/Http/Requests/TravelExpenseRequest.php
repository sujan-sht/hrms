<?php

namespace App\Modules\BusinessTrip\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TravelExpenseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
           'employee_id'   => 'required|string|max:255',
           'employee_name'   => 'nullable|string|max:255',
            'department'      => 'required|string|max:255',
            'designation'     => 'required|string|max:255',
            'expenses_type'   => 'required|string',
            'from_date'       => 'required|date',
            'to_date'         => 'required|date|after_or_equal:from_date',
            'departure'       => 'required|string|max:255',
            'destination'     => 'nullable|string|max:255',
            'purpose'         => 'nullable|string|max:500',
            'total_amount'    => 'required|numeric|min:0',
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
