<?php

namespace App\Modules\Onboarding\Http\Controllers;

use App\Modules\Onboarding\Entities\OfferLetter;
use App\Modules\Onboarding\Repositories\ApplicantInterface;
use App\Modules\Onboarding\Repositories\EvaluationInterface;
use App\Modules\Onboarding\Repositories\InterviewInterface;
use App\Modules\Onboarding\Repositories\InterviewLevelInterface;
use App\Modules\Onboarding\Repositories\OfferLetterInterface;
use App\Modules\Setting\Repositories\SettingInterface;
use App\Modules\Template\Entities\Template;
use App\Modules\Template\Repositories\TemplateInterface;
use App\Modules\Template\Repositories\TemplateTypeInterface;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;

class EvaluationOfferLetterController extends Controller
{

    private $offerLetterObj;
    private $templateObj;
    private $settingObj;
    private $evaluationObj;
    private $templateType;

    /**
     * Constructor
     */
    public function __construct(
        OfferLetterInterface $offerLetterObj,
        TemplateInterface $templateObj,
        SettingInterface $settingObj,
        EvaluationInterface $evaluationObj,
        TemplateTypeInterface $templateType
    ) {
        $this->offerLetterObj = $offerLetterObj;
        $this->templateObj = $templateObj;
        $this->settingObj = $settingObj;
        $this->evaluationObj = $evaluationObj;
        $this->templateType = $templateType;
    }

    public function create(Request $request)
    {
        $data['statusList'] = $this->offerLetterObj->getStatusList();
        return view('onboarding::evaluation.bulk-offer-letter.create', $data);
    }

    public function store(Request $request)
    {
        try {
            $evaluations = json_decode($request->data);
            $html = '';
            $templateType = $this->templateType->findBySlug('OL');
            if ($templateType) {
                $html = $this->templateObj->findByTemplateType($templateType->id)->text;
            }
            foreach ($evaluations as $key => $eval) {
                $vars = [];
                $evaluation = $this->evaluationObj->findOne($eval);
                $applicant = $evaluation->applicantModel;
                $mrf = $applicant->mrfModel;

                $vars = array(
                    "[CURRENT_DATE]" => date('M d, Y'),
                    "[APPLICANT_NAME]" => $applicant->full_name,
                    "[APPLICANT_ADDRESS]" => $applicant->full_address,
                    "[APPLICANT_CONTACT]" => $applicant->mobile,
                    "[POSITION]" => $mrf->position,
                    "[STARTING_SALARY]" => $request->salary,
                    "[START_DATE]" => $request->join_date,
                    "[BENEFITS]" => "Lunch, SSF and other benefits",
                    "[COMPANY_NAME]" => "Bidhee Pvt. Ltd.",
                    "[ACCEPTANCE_DATE]" => $request->expiry_date
                );

                $html = strtr($html, $vars);
                Mail::send(array(), array(), function ($message) use ($html, $applicant) {
                    $message->to($applicant->email)
                        ->subject('Employment Offer Letter')
                        ->from(config('mail.from.address'))
                        ->setBody($html, 'text/html');
                });

                $offerLetterData = [
                    'evaluation_id' => $eval,
                    'join_date' => setting('calendar_type') == 'BS' ? date_converter()->nep_to_eng_convert($request->join_date) : $request->join_date,
                    'salary' => $request->salary,
                    'expiry_date' => setting('calendar_type') == 'BS' ? date_converter()->nep_to_eng_convert($request->expiry_date) : $request->expiry_date,
                    'status' => $request->status,
                ];

                $this->offerLetterObj->create($offerLetterData);
            }

            toastr()->success('Offer Letter Send Successfully');
        } catch (Exception $e) {
            dd($e);
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->route('evaluation.index');
    }
}
