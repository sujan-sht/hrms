<?php

namespace App\Modules\Setting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeviceManagementRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'organization_id' => 'required',
            'ip_address' => 'required',
            'port' => 'required',
            'device_id' => 'required',
            'communication_password' => 'required'
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
