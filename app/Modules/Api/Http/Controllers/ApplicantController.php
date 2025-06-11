<?php

namespace App\Modules\Api\Http\Controllers;

use App\Modules\Admin\Entities\MailSender;
use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Onboarding\Entities\Applicant;
use App\Modules\Onboarding\Repositories\ApplicantInterface;
use App\Modules\Setting\Entities\Setting;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApplicantController extends ApiController
{
    private $applicantObj;
    private $dropdown;
    
    public function __construct(
        ApplicantInterface $applicantObj,
        DropdownInterface $dropdown
    ) {
        $this->applicantObj = $applicantObj;
        $this->dropdown = $dropdown;
    }

    public function store(Request $request) {
        
        $inputData = $request->all();
        try {
            $validate = Validator::make(
                $request->all(),
                [
                    'manpower_requisition_form_id' => 'required',
                    'first_name' => 'required',
                    'last_name' => 'required',
                    "mobile" => "required",
                    // "academic_qualification" =>"required",
                    // "current_organization" => "required",
                    // "current_designation" => "required",
                    // "reference_name" => "required",
                    // "reference_position" => "required",
                    // "reference_contact_number" => "required|integer",
                    "reference_contact_number" => "integer",
                    'gender' => 'required',
                    'source' => 'required',
                    'experience' => 'integer',
                    'expected_salary' => 'integer'
                ]
            );
            if ($validate->fails()) {
                return $this->respondValidatorFailed($validate);
            }

            if($inputData['gender'] == 'Male'){
                $inputData['gender'] = 1;
            }elseif($inputData['gender'] == 'Female'){
                $inputData['gender'] = 2;
            }else{
                $inputData['gender'] = null;
            }

            if($inputData['source'] == 'Internal'){
                $inputData['source'] = 1;
            }elseif($inputData['source'] == 'Referer'){
                $inputData['source'] = 2;
            }elseif($inputData['source'] == 'LinkedIn'){
                $inputData['source'] = 3;
            }elseif($inputData['source'] == 'Mero Jobs'){
                $inputData['source'] = 4;
            }elseif($inputData['source'] == 'Website'){
                $inputData['source'] = 5;
            }elseif($inputData['source'] == 'Others'){
                $inputData['source'] = 6;
            }else{
                $inputData['source'] = null;
            }

            if ($request->hasFile('resume')) {
                $inputData['resume'] = $this->applicantObj->uploadResume($inputData['resume']);
            }
            if ($request->hasFile('cover_letter')) {
                $inputData['cover_letter'] = $this->applicantObj->uploadResume($inputData['cover_letter']);
            }
            $inputData['status'] = 1;
            $model = $this->applicantObj->create($inputData);
            if($model) {
                // send email to all hr
                $notified_user_email = $model->email;
                if (isset($notified_user_email) && !empty($notified_user_email)) {
                    $mailDetail = array(
                        'email' => $notified_user_email,
                        'subject' => 'Welcome to '.setting('company_name'),
                        'notified_user_fullname' => $model->full_name,
                        'setting' => Setting::first()
                    );
                    $mail = new MailSender();
                    $mail->sendMail('admin::mail.mrf_reply', $mailDetail);
                }
            }
            return $this->respond([
                'status' => true,
                'message' => 'Application has been submitted Successfully',
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery($e->getMessage());
        }

    }

    public function getDropdown()
    {
        try {
            $data['genderList'] = setObjectIdAndName($this->dropdown->getFieldBySlug('gender'));
            $data['sourceList'] = setObjectIdAndName(Applicant::sourceList());
            return  $this->respond(['data' => $data]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }
  
}
