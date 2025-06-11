<?php

namespace App\Modules\ApprovalFlow\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateApprovalFlowRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'department_id' => 'required',
            'last_approval_user_id' => 'required',
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
            'department_id.required' => 'Department is required',
            'last_approval_user_id.required' => 'Last Approval User is required',
        ];
    }
}
