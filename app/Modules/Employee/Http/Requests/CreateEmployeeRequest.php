<?php

namespace App\Modules\Employee\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEmployeeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'employee_code' => 'required|unique:employees,employee_code,'.$this->id,
            // 'biometric_id' => 'unique:employees,biometric_id,'.$this->id,
            'first_name' => 'required',
            'last_name' => 'required',
            // 'dayoff[]' => 'required',
            'nepali_join_date' => 'required',
            'nep_dob' => 'required',
            // 'mobile' => 'required',
            // 'permanentprovince' => 'required',
            // 'permanentdistrict' => 'required',
            // 'permanentmunicipality_vdc' => 'required',
            // 'permanentaddress' => 'required',
            // 'designation_id' => 'required',
            // 'department_id' => 'required',
            // 'job_title' => 'required',
            // 'official_email' => 'required',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return [
            'employee_code.required' => 'Employee Code is required',
            'first_name.required' => 'First Name is required',
            'last_name.required' => 'Last Name is required',
            'dayoff.required' => 'Dayoff is required',
            'nepali_join_date.required' => 'join Date is required',
            'dob.required' => 'DOB is required',
            'mobile.required' => 'Mobile is required',
            'permanentprovince.required' => 'Province is required',
            'permanentdistrict.required' => 'District is required',
            'permanentmunicipality_vdc.required' => 'Municipality/VDC is required',
            'permanentaddress.required' => 'Address is required',
            'designation_id.required' => 'Designation is required',
            'department_id.required' => 'Department is required',
            'job_title.required' => 'Functional Title is required',
            'official_email.required' => 'Official Email is required',
        ];
    }
}
