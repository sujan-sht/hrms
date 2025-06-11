<?php

namespace App\Modules\OrganizationalStructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrgStructureRequest extends FormRequest
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
            'root_employee_id' => 'required',
            // 'structure_details.employee_id.*' => 'required',
            // 'structure_details.parent_employee_id.*' => 'required',
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
            'title.required' => 'Title is required',
            'root_employee_id.required' => 'Root employee is required',
            // 'structure_details.employee_id.*.required' => 'Employee is required',
            // 'structure_details.parent_employee_id.*.required' => 'Parent Employee is required',
        ];
    }
}
