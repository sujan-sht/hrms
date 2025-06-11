<?php

namespace App\Modules\Setting\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Setting\Enum\TravelTypeEnum;

class GrossSallarySettingStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'gross_salary_type'=>['required',Rule::in(TravelTypeEnum::Employee,TravelTypeEnum::Level,TravelTypeEnum::Designation)]
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
