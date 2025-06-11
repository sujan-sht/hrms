<?php

namespace App\Modules\Setting\Http\Requests;

use App\Modules\Setting\Entities\Darbandi;
use Illuminate\Foundation\Http\FormRequest;

class DarbandiRequest extends FormRequest
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
            'designation_id' => 'required',
            'no' => 'required'
        ];
    }


    // public function prepareForValidation()
    // {
    //     $sortingOrder = $this->input('sorting_order');
    //     $organizationId = $this->input('organization_id');
    //     if (!is_null($sortingOrder)) {
    //         $darbandi = Darbandi::where('organization_id', $organizationId)->where('sorting_order', $sortingOrder)->first();
    //         if ($darbandi) {
    //             $darbandi_order = $darbandi->sorting_order;
    //             if ($darbandi_order > 0) {
    //                 $defaultNo = $darbandi_order + 1;
    //             } else {
    //                 $defaultNo = $sortingOrder;
    //             }
    //         } else {
    //             $defaultNo = Darbandi::where('organization_id', $organizationId)
    //                 ->max('sorting_order') + 1;
    //         }
    //     } else {
    //         $defaultNo = Darbandi::where('organization_id', $organizationId)
    //             ->max('sorting_order') + 1;
    //     }

    //     $this->merge(['sorting_order' => $defaultNo]);
    // }
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
