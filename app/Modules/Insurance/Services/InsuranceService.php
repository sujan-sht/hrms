<?php

namespace App\Modules\Insurance\Services;

use App\Modules\Insurance\Entities\InsuranceType;
use Illuminate\Http\Request;

class InsuranceService
{


    const INSURANCE_TYPE_LIFE = 'Life Insurance';
    const INSURANCE_TYPE_ACCIDENT = 'Accident Insurance';
    const INSURANCE_TYPE_MEDICAL = 'Medical Insurance';


    public function typeWiseStore(Request $request)
    {
        $insuranceType = InsuranceType::find($request->insurance_type);


        if ($insuranceType->title == self::INSURANCE_TYPE_LIFE) {
            $data['insurance_type_id'] = $request->insurance_type;
            if ($request->premium_payment_by == 'sharing') {
                $data['premium_payment_by'] = $request->premium_payment_by;
                $data['total_employees'] = $request->employees;
                $data['total_employer'] = $request->employer;
            } else {
                $data['premium_payment_by'] = $request->premium_payment_by;
            }
            $data['policy_number'] = $request->policy_number;
            $data['company_name'] = $request->company_name;
            $data['policy_start_date'] = $request->policy_start_date;
            $data['policy_end_date'] = $request->policy_end_date;
            $data['policy_maturity_date'] = $request->policy_maturity_date;
            $data['sum_assured_amount'] = $request->sum_assured_amount;
            $data['premium_amount'] = $request->premium_amount;
            if (isset($request->document_upload) && !is_null($request->document_upload)  && $request->file('document_upload')) {
                $file = $request->file('document_upload');
                $fileName = time() . rand(1, 99) . '.' . $file->extension();
                $file->move(public_path('uploads/insurance/'), $fileName);
                $data['document_upload'] = $fileName;
            }
            return $data;
        } elseif ($insuranceType->title == self::INSURANCE_TYPE_ACCIDENT) {
            $data['insurance_type_id'] = $request->insurance_type;
            $data['company_name'] = $request->accident_company_name;
            $data['sum_assured_amount'] = $request->accident_sum_assured_amount;
            $data['policy_start_date'] = $request->accident_policy_start_date;
            $data['policy_end_date'] = $request->accident_policy_end_date;
            $data['premium_amount'] = $request->accident_premium_amount;

            return $data;
        } elseif ($insuranceType->title == self::INSURANCE_TYPE_MEDICAL) {
            $data['insurance_type_id'] = $request->insurance_type;
            if ($request->medical_premium_payment_by == 'sharing') {
                $data['premium_payment_by'] = $request->medical_premium_payment_by;
                $data['total_employees'] = $request->medical_employees;
                $data['total_employer'] = $request->medical_employer;
            } else {
                $data['premium_payment_by'] = $request->medical_premium_payment_by;
            }
            $data['company_name'] = $request->medical_company_name;
            $data['sum_assured_amount'] = $request->medical_sum_assured_amount;
            $data['policy_start_date'] = $request->medical_policy_start_date;
            $data['policy_end_date'] = $request->medical_policy_end_date;
            $data['premium_amount'] = $request->medical_premium_amount;

            return $data;
        }
    }
}
