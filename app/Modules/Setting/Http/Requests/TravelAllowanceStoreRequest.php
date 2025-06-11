<?php

namespace App\Modules\Setting\Http\Requests;

use App\Modules\Setting\Enum\TravelTypeEnum;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class TravelAllowanceStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'allowance_type'=>['required',Rule::in(TravelTypeEnum::Employee,TravelTypeEnum::Level,TravelTypeEnum::Designation)]
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
