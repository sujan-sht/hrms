<?php

namespace App\Modules\Training\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTrainingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'division_id' => 'required',
            'department_id' => 'required',
            'type' => 'required',
            'title' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
            'no_of_days' => 'required|numeric|min:1',
            'location' => 'required',
            'facilitator' => 'required',
            'facilitator_name' => 'required',
            'month' => 'required',
            'planned_budget' => 'required|numeric|min:1',
            // 'actual_expense_incurred' => 'required|numeric|min:1',
            'no_of_participants' => 'required|numeric|min:1',
            'no_of_mandays' => 'required|numeric|min:1',
            // 'no_of_employee' => 'required|numeric|min:1',
            // 'status' => 'required',
            'training_for' => 'required',
            'functional_type' => 'required',
            'fiscal_year_id' => 'required'

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
            'division_id.required' => 'Please Select Division',
            'type.required' => 'Please Select Type',
            'title.required' => 'Please Enter Title',
            'from_date.required' => 'Please Choose Start Date',
            'to_date.required' => 'Please Choose End Date',

            'no_of_days.required' => 'Please Enter No of Days',
            'no_of_days.numeric' => 'Please Enter Number Only',
            'no_of_days.min' => 'Please Enter Number Greater Than 1',

            'location.required' => 'Please Select Location',
            'facilitator.required' => 'Please Select Facilitator',
            'facilitator_name.required' => 'Please enter Facilitator Name',
            'month.required' => 'Please Enter Month',

            'planned_budget.required' => 'Please Enter Planned Budget',
            'planned_budget.numeric' => 'Please Enter Number Only',
            'planned_budget.min' => 'Please Enter Number Greater Than 1',

            // 'actual_expense_incurred.required' => 'Please Enter Expense Incurred',
            // 'actual_expense_incurred.numeric' => 'Please Enter Number Only',
            // 'actual_expense_incurred.min' => 'Please Enter Number Greater Than 1',

            'no_of_participants.required' => 'Please enter No of Participants',
            'no_of_participants.numeric' => 'Please Enter Number Only',
            'no_of_participants.min' => 'Please Enter Number Greater Than 1',

            'no_of_mandays.required' => 'Please Enter No of Mandays',
            'no_of_mandays.numeric' => 'Please Enter Number Only',
            'no_of_mandays.min' => 'Please Enter Number Greater Than 1',

            // 'no_of_employee.required' => 'Please Enter No of Employees',
            // 'no_of_employee.numeric' => 'Please Enter Number Only',
            // 'no_of_employee.min' => 'Please Enter Number Greater Than 1',

            // 'status.required' => 'Please Enter Status',
            'training_for' => 'Please select Training For',
            'functional_type' => 'Please select Functional Type',
            'fiscal_year_id' => 'Please select Fiscal year'
        ];
    }
}
