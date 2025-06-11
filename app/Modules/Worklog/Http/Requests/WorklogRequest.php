<?php

namespace App\Modules\Worklog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorklogRequest extends FormRequest
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
            'project_id' => 'required',
            'hours' => 'required',
            'status' => 'required',
            'date' => 'required',
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
            'project_id.required' => 'Project is required',
            'hours.required' => 'Hours is required',
            'status.required' => 'Status is required',
            'date.required' => 'Date is required'
        ];
    }
}
