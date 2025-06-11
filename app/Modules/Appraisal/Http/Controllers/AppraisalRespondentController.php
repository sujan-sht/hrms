<?php

namespace App\Modules\Appraisal\Http\Controllers;

use App\Modules\Appraisal\Entities\AppraisalResponse;
use App\Modules\Appraisal\Repositories\AppraisalRepository;
use App\Modules\Appraisal\Repositories\ScoreInterface;
use App\Modules\Employee\Entities\EmployeeAppraisalApprovalFlow;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Setting\Entities\Setting;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;

class AppraisalRespondentController extends Controller
{


    protected $appraisal;
    protected $employee;
    protected $score;

    public function __construct(AppraisalRepository $appraisal, EmployeeInterface $employee, ScoreInterface $score)
    {
        $this->appraisal = $appraisal;
        $this->employee = $employee;
        $this->score = $score;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($id)
    {
        $data['appraisal'] = $this->appraisal->findOne($id);
        $data['employees'] = $this->employee->findAll()->pluck('full_name', 'id');
        return view('appraisal::appraisal-respondent.index', $data);
    }

    public function show($id)
    {
        $data['respondent'] = $this->appraisal->findResponseById($id);
        $data['employee_approval_flow']= EmployeeAppraisalApprovalFlow::where('employee_id',optional($data['respondent']->appraisal)->appraisee)->first();
        // dd($data['employee_approval_flow']);

        // $data['appraisal_response'] = AppraisalResponse::where('appraisal_id',optional($data['respondent']->appraisal)->id)->get();
        // dd($data['appraisal_response']);
        // $data['respondent'] = $this->appraisal->invitationCodeExist($request->invitation_code);
        return view('appraisal::appraisal-respondent.show', $data);
    }

    public function resendEmail(Request $request)
    {
        $respondent = $this->appraisal->findResponseById($request->id);
        $details = [
            'invitation_code' => $respondent->invitation_code,
            'name' => optional(optional($respondent->appraisal)->employee)->full_name,
            'email' => $respondent->email,
            'respondent' => $respondent->name,
            'setting' => Setting::first()
        ];
        Mail::send('appraisal::mails.appraisal-respondent-mail', $details, function ($message) use ($respondent) {
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $message->to($respondent->email);
            $message->subject('Appraisal Questionnaire Invitation');
        });

        toastr('Invitation resent Successfully', 'success');
        return back();
    }

    public function addRespondent(Request $request, $appraisalId)
    {
        $data = $request->all();
        $appraisal = $this->appraisal->findOne($appraisalId);
        $appraisee = $this->employee->find($appraisal->appraisee)->full_name;
        if ($data['type'] == 'external') {
            if ($data['email'] != null && $data['name'] != null) {
                $resData = [
                    'appraisal_id' => $appraisalId,
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'invitation_code' => mt_rand(99, 999999) . strtotime(now()->format('d-m-Y h:i:s a'))
                ];
                $this->appraisal->saveRespondents($resData);

                $details = [
                    'invitation_code' => $resData['invitation_code'],
                    'name' =>  $appraisee,
                    'email' => $data['email'],
                    'respondent' => $data['name'],
                    'setting' => Setting::first()
                ];

                Mail::send('appraisal::mails.appraisal-respondent-mail', $details, function ($message) use ($data) {
                    $message->from(config('mail.from.address'));
                    $message->to($data['email']);
                    $message->subject('Appraisal Questionnaire Invitation');
                });
            }
        }

        if ($data['type'] == 'internal') {
            $employee = $this->employee->find($request->respondent_id);
            $email = $employee->personal_email ?? $employee->official_email;
            $name = $employee->full_name;

            if ($email != null && $name != null) {
                $resData = [
                    'appraisal_id' => $appraisalId,
                    'employee_id' => $request->respondent_id,
                    'name' => $name,
                    'email' => $email,
                    'invitation_code' => mt_rand(99, 999999) . strtotime(now()->format('d-m-Y h:i:s a'))
                ];
                $this->appraisal->saveRespondents($resData);

                $details = [
                    'invitation_code' => $resData['invitation_code'],
                    'name' =>  $appraisee,
                    'email' => $email,
                    'respondent' => $name,
                    'setting' => Setting::first()
                ];

                Mail::send('appraisal::mails.appraisal-respondent-mail', $details, function ($message) use ($email) {
                    $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                    $message->to($email);
                    $message->subject('Appraisal Questionnaire Invitation');
                });
            }
        }
        toastr('Respondents Invited Successfully', 'success');
        return back();
    }

    public function print($id)
    {
        $data['fields'] = ['Frequency', 'Ability', 'Effectiveness'];
        $data['frequencies'] = $this->score->findAll()->pluck('frequency');
        $data['abilities'] = $this->score->findAll()->pluck('ability');
        $data['effectiveness'] = $this->score->findAll()->pluck('effectiveness');
        $data['respondent'] = $this->appraisal->findResponseById($id);
        return view('appraisal::appraisal-respondent.print', $data);
    }
}
