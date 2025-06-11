<?php

namespace App\Modules\Appraisal\Http\Controllers;

use App\Modules\Appraisal\Emails\AppraisalRespondentMail;
use App\Modules\Appraisal\Entities\AppraisalCompetancyResponse;
use App\Modules\Appraisal\Entities\AppraisalCompetencyResponse;
use App\Modules\Appraisal\Entities\AppraisalDevelopmentPlan;
use App\Modules\Appraisal\Entities\AppraisalResponse;
use App\Modules\Appraisal\Http\Requests\AppraisalRequest;
use App\Modules\Appraisal\Http\Requests\QuestionnaireRequest;
use App\Modules\Appraisal\Repositories\AppraisalInterface;
use App\Modules\Appraisal\Repositories\QuestionnaireInterface;
use App\Modules\Appraisal\Repositories\ScoreInterface;
use App\Modules\Employee\Entities\EmployeeAppraisalApprovalFlow;
use App\Modules\Employee\Entities\EmployeeApprovalFlow;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\FiscalYearSetup\Repositories\FiscalYearSetupInterface;
use App\Modules\Notification\Entities\Notification;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Setting\Entities\Setting;
use App\Modules\Setting\Repositories\SettingInterface;
use App\Modules\User\Entities\User;
use PDF;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AppraisalController extends Controller
{

    protected $appraisal;
    protected $employee;
    protected $questionnaire;
    protected $score;
    protected $setting;
    protected $organization;
    protected $fiscalYear;

    public function __construct(AppraisalInterface $appraisal, EmployeeInterface $employee, QuestionnaireInterface $questionnaire, ScoreInterface $score, SettingInterface $setting,OrganizationInterface $organization, FiscalYearSetupInterface $fiscalYear)
    {
        $this->appraisal = $appraisal;
        $this->employee = $employee;
        $this->questionnaire = $questionnaire;
        $this->score = $score;
        $this->setting = $setting;
        $this->organization = $organization;
        $this->fiscalYear = $fiscalYear;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $data['filter']  = $request->all();
        $data['employee'] = $this->employee->findAll()->pluck('full_name', 'id');
        $data['questionnaires'] = $this->questionnaire->findAll()->pluck('title', 'id');
        $data['appraisals'] = $this->appraisal->findAll($limit = 50, $data['filter']);
        return view('appraisal::appraisal-management.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['is_edit'] = false;
        $data['organizationModel'] = $this->organization->getList();
        $data['questionnaires'] = $this->questionnaire->findAll()->pluck('title', 'id');
        $data['employee'] = $this->employee->findAll()->pluck('full_name', 'id');
        $data['noYesList'] = array('10' => 'No', '11' => 'Yes');
        // $data['evaluationTypeList'] = array('1' => 'Rating', '2' => 'Comment', '3' => 'Both');
        $data['evaluationTypeList'] = array('1' => 'Rating');
        return view('appraisal::appraisal-management.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(AppraisalRequest $request)
    {
        $data = $request->all();
        // dd($data);
        $authUser = auth()->user();
        if ($authUser->user_type == 'super_admin') {
            $authorName = $authUser->first_name;
        } else {
            $authorName = optional($authUser->userEmployer)->full_name;
        }
        // dd($data);
        try {
            if (isset($data['appraisee']) && !empty($data['appraisee'])) {
                $employee = $this->employee->find($data['appraisee']);
                $hr_type = User::where('user_type','hr')->first();
                foreach ($data['appraisee'] as $appraisee_id) {
                    $appraisalData['appraisee'] = $appraisee_id;
                    $appraisalData['questionnaire_id'] = $data['questionnaire_id'];
                    if (setting('calendar_type') == 'BS'){
                        if(!is_null($data['valid_date'])){
                            $appraisalData['valid_date'] = date_converter()->nep_to_eng_convert($data['valid_date']);
                        }
                    }else{
                        $appraisalData['valid_date'] = $data['valid_date'];
                    }
                    $appraisalData['type'] = 'internal';
                    $appraisalData['enable_self_evaluation'] = $data['enable_self_evaluation'];
                    $appraisalData['self_evaluation_type'] = $data['self_evaluation_type'];
                    $appraisalData['enable_supervisor_evaluation'] = $data['enable_supervisor_evaluation'];
                    $appraisalData['supervisor_evaluation_type'] = $data['supervisor_evaluation_type'];
                    $appraisalData['enable_hod_evaluation'] = $data['enable_hod_evaluation'];
                    $appraisalData['hod_evaluation_type'] = $data['hod_evaluation_type'];
                    

                    $appraisal = $this->appraisal->save($appraisalData);

                    //For Appraisee
                    $appraisee = $this->employee->find($appraisee_id);

                    if ($appraisee->official_email != null && $appraisee->full_name != null && $appraisalData['enable_self_evaluation'] == 11) {
                        $appraiseeEmail = $appraisee->official_email;
                        $appraiseeData = [
                            'appraisal_id' => $appraisal->id,
                            'name' => $appraisee->full_name,
                            'email' => $appraiseeEmail,
                            'invitation_code' => mt_rand(99, 999999) . strtotime(now()->format('d-m-Y h:i:s a')),
                            'employee_id' => $appraisee_id
                        ];
                        // dd($appraiseeData);
                        $this->appraisal->saveRespondents($appraiseeData);

                        $appraiseeDetails = [
                            'invitation_code' => $appraiseeData['invitation_code'],
                            'name' =>  $appraisee->full_name,
                            'email' => $appraiseeEmail,
                            'respondent' => $appraisee->full_name,
                            'setting' => Setting::first()
                        ];
                        // dd( $appraiseeDetails);
                        // dd($appraiseeEmail);
                        Mail::send('appraisal::mails.appraisal-respondent-mail', $appraiseeDetails, function ($message) use ($appraiseeEmail) {
                            $message->from(config('mail.from.address'));
                            $message->to($appraiseeEmail);
                            $message->subject('Appraisal Questionnaire Invitation');
                        });
                    }

                    $notificationData['creator_user_id'] = $authUser->id;
                    $notificationData['notified_user_id'] = optional($appraisee->getUser)->id;
                    $notificationData['message'] = "Your " . "Appraisal" . " has been send"  . " by " . $authorName;
                    $notificationData['link'] = route('appraisal.viewThroughInvitation') . '?invitation_code=' . $appraiseeData['invitation_code'];
                    Notification::create($notificationData);
                    // $appraisalApprovalFlow = $this->employee->employeeAppraisalApprovalFlow($appraisee_id);
                    // if ($appraisalApprovalFlow) {
                    //     //For First Supervisor
                    //     if ($appraisalApprovalFlow->first_approval) {
                    //         if (optional(optional($appraisalApprovalFlow->firstApprovalUserModel)->userEmployer)->official_email != null && optional(optional($appraisalApprovalFlow->firstApprovalUserModel)->userEmployer)->full_name != null && $appraisalData['enable_supervisor_evaluation'] == 11) {

                    //             $firstSupervisorEmail = optional(optional($appraisalApprovalFlow->firstApprovalUserModel)->userEmployer)->official_email;
                    //             $firstSupervisorData = [
                    //                 'appraisal_id' => $appraisal->id,
                    //                 'name' => optional(optional($appraisalApprovalFlow->firstApprovalUserModel)->userEmployer)->full_name,
                    //                 'email' => $firstSupervisorEmail,
                    //                 'invitation_code' => mt_rand(99, 999999) . strtotime(now()->format('d-m-Y h:i:s a')),
                    //                 'employee_id' => optional(optional($appraisalApprovalFlow->firstApprovalUserModel)->userEmployer)->id
                    //             ];
                    //             $this->appraisal->saveRespondents($firstSupervisorData);

                    //             $details = [
                    //                 'invitation_code' => $firstSupervisorData['invitation_code'],
                    //                 'name' =>  $firstSupervisorData['name'],
                    //                 'email' => $firstSupervisorEmail,
                    //                 'respondent' => $this->employee->find($appraisee_id)->full_name,
                    //                 'setting' => Setting::first()
                    //             ];

                    //             Mail::send('appraisal::mails.appraisal-respondent-mail', $details, function ($message) use ($firstSupervisorEmail) {
                    //                 $message->from(config('mail.from.address'));
                    //                 $message->to($firstSupervisorEmail);
                    //                 $message->subject('Appraisal Questionnaire Invitation');
                    //             });
                    //         }
                    //     }
                    //     //

                    //     //For Last Supervisor
                    //     if ($appraisalApprovalFlow->last_approval) {
                    //         if (optional(optional($appraisalApprovalFlow->lastApprovalUserModel)->userEmployer)->official_email != null && optional(optional($appraisalApprovalFlow->lastApprovalUserModel)->userEmployer)->full_name != null && $appraisalData['enable_hod_evaluation'] == 11) {

                    //             $lastSupervisorEmail = optional(optional($appraisalApprovalFlow->lastApprovalUserModel)->userEmployer)->official_email;
                    //             $lastSupervisorData = [
                    //                 'appraisal_id' => $appraisal->id,
                    //                 'name' => optional(optional($appraisalApprovalFlow->lastApprovalUserModel)->userEmployer)->full_name,
                    //                 'email' => $lastSupervisorEmail,
                    //                 'invitation_code' => mt_rand(99, 999999) . strtotime(now()->format('d-m-Y h:i:s a')),
                    //                 'employee_id' => optional(optional($appraisalApprovalFlow->lastApprovalUserModel)->userEmployer)->id
                    //             ];
                    //             $this->appraisal->saveRespondents($lastSupervisorData);

                    //             $details = [
                    //                 'invitation_code' => $lastSupervisorData['invitation_code'],
                    //                 'name' =>  $lastSupervisorData['name'],
                    //                 'email' => $lastSupervisorEmail,
                    //                 'respondent' => $this->employee->find($appraisee_id)->full_name,
                    //                 'setting' => Setting::first()
                    //             ];

                    //             Mail::send('appraisal::mails.appraisal-respondent-mail', $details, function ($message) use ($lastSupervisorEmail) {
                    //                 $message->from(config('mail.from.address'));
                    //                 $message->to($lastSupervisorEmail);
                    //                 $message->subject('Appraisal Questionnaire Invitation');
                    //             });
                    //         }
                    //     }
                    //     //
                    // }
                    // if (count($hr_types) > 0) {
                    //     foreach ($hr_types as $hr_type) {
                    //         if (optional($hr_type->userEmployer)->official_email != null && optional($hr_type->userEmployer)->full_name != null) {
                    //             $hrEmail = optional($hr_type->userEmployer)->official_email;
                    //             $hrData = [
                    //                 'appraisal_id' => $appraisal->id,
                    //                 'name' => optional($hr_type->userEmployer)->full_name,
                    //                 'email' => $hrEmail,
                    //                 'invitation_code' => mt_rand(99, 999999) . strtotime(now()->format('d-m-Y h:i:s a')),
                    //                 'employee_id' => optional($hr_type->userEmployer)->id,
                    //             ];
                    //             $this->appraisal->saveRespondents($hrData);

                    //             $hrDetails = [
                    //                 'invitation_code' => $hrData['invitation_code'],
                    //                 'name' => optional($hr_type->userEmployer)->full_name,
                    //                 'email' => $hrEmail,
                    //                 'respondent' => optional($hr_type->userEmployer)->full_name,
                    //                 'setting' => Setting::first()
                    //             ];

                    //             Mail::send('appraisal::mails.appraisal-respondent-mail', $hrDetails, function ($message) use ($hrEmail) {
                    //                 $message->from(config('mail.from.address'));
                    //                 $message->to($hrEmail);
                    //                 $message->subject('Appraisal Questionnaire Invitation');
                    //             });
                    //         }
                    //     }
                    // }
                    // if ($hr_type) {
                    //         if (optional($hr_type->userEmployer)->official_email != null && optional($hr_type->userEmployer)->full_name != null) {
                    //             $hrEmail = optional($hr_type->userEmployer)->official_email;
                    //             $hrData = [
                    //                 'appraisal_id' => $appraisal->id,
                    //                 'name' => optional($hr_type->userEmployer)->full_name,
                    //                 'email' => $hrEmail,
                    //                 'invitation_code' => mt_rand(99, 999999) . strtotime(now()->format('d-m-Y h:i:s a')),
                    //                 'employee_id' => optional($hr_type->userEmployer)->id,
                    //             ];
                    //             $this->appraisal->saveRespondents($hrData);

                    //             $hrDetails = [
                    //                 'invitation_code' => $hrData['invitation_code'],
                    //                 'name' => optional($hr_type->userEmployer)->full_name,
                    //                 'email' => $hrEmail,
                    //                 'respondent' => optional($hr_type->userEmployer)->full_name,
                    //                 'setting' => Setting::first()
                    //             ];

                    //             Mail::send('appraisal::mails.appraisal-respondent-mail', $hrDetails, function ($message) use ($hrEmail) {
                    //                 $message->from(config('mail.from.address'));
                    //                 $message->to($hrEmail);
                    //                 $message->subject('Appraisal Questionnaire Invitation');
                    //             });
                    //         }
                    // }
                }
            }


            // if (isset($data['type']) && $data['type'] == 'external') {
            //     if ((isset($data['ext_email']) && $data['ext_email'][0] == null) || (isset($data['ext_name']) && $data['ext_name'][0] == null)) {
            //         toastr('Please set External Name/Email', 'error');
            //         return back();
            //     }
            // }

            // if (isset($data['type']) && $data['type'] == 'internal') {
            //     if ((isset($data['int_email']) && $data['int_email'][0] == null) ||  (isset($data['int_name']) && $data['int_name'][0] == null)) {
            //         toastr('Please set Internal Name/Email', 'error');
            //         return back();
            //     }
            // }

            // $appraisal = $this->appraisal->save($data);

            // if ($data['type'] == 'external') {
            //     foreach ($data['ext_email'] as $key => $email) {
            //         if ($email != null && $data['ext_name'] != null) {
            //             $resData = [
            //                 'appraisal_id' => $appraisal->id,
            //                 'name' => $data['ext_name'][$key],
            //                 'email' => $email,
            //                 'invitation_code' => mt_rand(99, 999999) . strtotime(now()->format('d-m-Y h:i:s a'))
            //             ];
            //             $this->appraisal->saveRespondents($resData);

            //             $details = [
            //                 'invitation_code' => $resData['invitation_code'],
            //                 'name' =>  $data['ext_name'][$key],
            //                 'email' => $email,
            //                 'respondent' => $this->employee->find($request->appraisee)->full_name,
            //                 'setting' => Setting::first()
            //             ];

            //             Mail::send('appraisal::mails.appraisal-respondent-mail', $details, function ($message) use ($email) {
            //                 $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            //                 $message->to($email);
            //                 $message->subject('Appraisal Questionnaire Invitation');
            //             });
            //         }
            //     }
            // }

            // if ($data['type'] == 'internal') {

            //     foreach ($data['int_email'] as $key => $email) {
            //         if ($email != null && $data['int_name'] != null) {
            //             $resData = [
            //                 'appraisal_id' => $appraisal->id,
            //                 'employee_id' => $request->appraisee,
            //                 'name' => $data['int_name'][$key],
            //                 'email' => $email,
            //                 'invitation_code' => mt_rand(99, 999999) . strtotime(now()->format('d-m-Y h:i:s a'))
            //             ];
            //             $this->appraisal->saveRespondents($resData);

            //             $details = [
            //                 'invitation_code' => $resData['invitation_code'],
            //                 'name' =>  $data['int_name'][$key],
            //                 'email' => $email,
            //                 'respondent' => $this->employee->find($request->appraisee)->full_name,
            //                 'setting' => Setting::first()
            //             ];

            //             Mail::send('appraisal::mails.appraisal-respondent-mail', $details, function ($message) use ($email) {
            //                 $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            //                 $message->to($email);
            //                 $message->subject('Appraisal Questionnaire Invitation');
            //             });
            //         }
            //     }
            // }

            toastr('Appraisal Added Successfully', 'success');
            return redirect()->route('appraisal.index');
        } catch (Exception $e) {
            // dd($e);
            toastr('Error While Adding Appraisal', 'error');
            return redirect()->route('appraisal.index');
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('appraisal::appraisal-management.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['is_edit'] = true;
        $data['organizationModel'] = $this->organization->getList();
        $data['appraisal'] = $this->appraisal->findOne($id);
        $data['employee'] = $this->employee->findAll()->pluck('full_name', 'id');
        $data['questionnaires'] = $this->questionnaire->findAll()->pluck('title', 'id');

        return view('appraisal::appraisal-management.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->appraisal->delete($id);
            toastr('Appraisal Deleted Successfully', 'success');
            return redirect()->route('appraisal.index');
        } catch (Exception $e) {
            toastr('Error While Deleting Appraisal', 'error');
            return redirect()->route('appraisal.index');
        }
    }

    /**
     *
     */
    public function report($id)
    {
        $data['fields'] = ['Frequency', 'Ability', 'Effectiveness'];
        $data['frequencies'] = $this->score->findAll()->pluck('frequency');
        $data['abilities'] = $this->score->findAll()->pluck('ability');
        $data['effectiveness'] = $this->score->findAll()->pluck('effectiveness');
        $data['fiscalYear'] = $this->fiscalYear->getFiscalYear();
        $data['appraisalModel'] = $appraisalModel = $this->appraisal->findOne($id);
        $data['employeeApproval'] = $this->appraisal->findEmployeeApproval($appraisalModel->appraisee);
        $first_approval = optional(optional($data['employeeApproval']->firstApprovalUserModel)->userEmployer)->id;
        $hr_type = User::where('user_type','hr')->first();
        $data['firstApprovalComment'] = AppraisalDevelopmentPlan::where('appraisal_id',$appraisalModel->id)->where('created_by', optional(optional($data['employeeApproval']->firstApprovalUserModel)->userEmployer)->id )->first();
        $data['lastApprovalComment'] = AppraisalDevelopmentPlan::where('appraisal_id',$appraisalModel->id)->where('created_by', optional(optional($data['employeeApproval']->lastApprovalUserModel)->userEmployer)->id )->first();
        $data['hrComment'] = AppraisalDevelopmentPlan::where('appraisal_id',$appraisalModel->id)->where('created_by',$hr_type->emp_id)->first();
        $data['developmentPlan']=AppraisalDevelopmentPlan::where('appraisal_id',$appraisalModel->id)->where('created_by',$first_approval)->first();
        $reportData = [];
        foreach ($appraisalModel->appraisalCompetanceResponses as $model) {
            $reportData[$model->competency_id]['competency'] = optional($model->competency)->name;
            $reportData[$model->competency_id]['answer'][] = [
                'score' => $model->score,
                'comment' => $model->comment,
            ];
        }
        // dd($reportData );

        $data['reportData'] = $reportData;
        return view('appraisal::appraisal.report', $data);
    }

    public function downloadReport($id)
    {
        $data['fields'] = ['Frequency', 'Ability', 'Effectiveness'];
        $data['frequencies'] = $this->score->findAll()->pluck('frequency');
        $data['abilities'] = $this->score->findAll()->pluck('ability');
        $data['effectiveness'] = $this->score->findAll()->pluck('effectiveness');
        $data['fiscalYear'] = $this->fiscalYear->getFiscalYear();
        $data['appraisalModel'] = $appraisalModel = $this->appraisal->findOne($id);
        $data['employeeApproval'] = $this->appraisal->findEmployeeApproval($appraisalModel->appraisee);
        $first_approval = optional(optional($data['employeeApproval']->firstApprovalUserModel)->userEmployer)->id;
        $hr_type = User::where('user_type','hr')->first();
        $data['firstApprovalComment'] = AppraisalDevelopmentPlan::where('appraisal_id',$appraisalModel->id)->where('created_by', optional(optional($data['employeeApproval']->firstApprovalUserModel)->userEmployer)->id )->first();
        $data['lastApprovalComment'] = AppraisalDevelopmentPlan::where('appraisal_id',$appraisalModel->id)->where('created_by', optional(optional($data['employeeApproval']->lastApprovalUserModel)->userEmployer)->id )->first();
        $data['hrComment'] = AppraisalDevelopmentPlan::where('appraisal_id',$appraisalModel->id)->where('created_by',$hr_type->emp_id)->first();
        $data['developmentPlan']=AppraisalDevelopmentPlan::where('appraisal_id',$appraisalModel->id)->where('created_by',$first_approval)->first();
        $reportData = [];
        foreach ($appraisalModel->appraisalCompetanceResponses as $model) {
            $reportData[$model->competency_id]['competency'] = optional($model->competency)->name;
            $reportData[$model->competency_id]['answer'][] = [
                'score' => $model->score,
                'comment' => $model->comment,
            ];
        }
        $data['reportData'] = $reportData;

        $pdf = PDF::loadView('exports.appraisal-report-pdf', $data)->setPaper('a4');
        return $pdf->download('appraisal-report.pdf');

        // return view('exports.appraisal-report-pdf', $data);
        // return $pdf->stream('appraisal-report.pdf');
    }


    public function appendRespondent(Request $request)
    {
        $data['employee'] = $this->employee->findAll()->pluck('full_name', 'id');

        if ($request->type == 'internal') {
            $view = view('appraisal::appraisal-management.partial.add-more-internal-employee', $data)->render();
        }

        if ($request->type == 'external') {
            $view = view('appraisal::appraisal-management.partial.add-more-external-employee', $data)->render();
        }
        return response()->json(['result' => $view]);
    }

    public function viewThroughInvitation(Request $request)
    {
        $codeExists = $this->appraisal->invitationCodeExist($request->invitation_code);
        if (!$codeExists) {
            abort(404);
        }
        try {
            $data['respondent'] = $this->appraisal->findAppraisalByInvitationCode($request->invitation_code);
            $data['employeeApproval']=EmployeeAppraisalApprovalFlow::where('employee_id',$data['respondent']->appraisal->appraisee)->first();
            $data['appraisal_response'] = AppraisalCompetencyResponse::where('created_by', optional($data['respondent']->appraisal)->appraisee)->where('appraisal_id', optional($data['respondent']->appraisal)->id)->get();
            $data['firstApprovalResponse'] = AppraisalCompetencyResponse::where('created_by', optional(optional($data['employeeApproval']->firstApprovalUserModel)->userEmployer)->id )->where('appraisal_id', optional($data['respondent']->appraisal)->id)->get();
            $data['employeeApproval'] = $this->appraisal->findEmployeeApproval(optional($data['respondent']->appraisal)->appraisee);
            $data['firstApprovalComment'] = AppraisalDevelopmentPlan::where('appraisal_id', optional($data['respondent']->appraisal)->id)->where('created_by', optional(optional($data['employeeApproval']->firstApprovalUserModel)->userEmployer)->id )->first();
            $data['lastApprovalComment'] = AppraisalDevelopmentPlan::where('appraisal_id', optional($data['respondent']->appraisal)->id)->where('created_by', optional(optional($data['employeeApproval']->lastApprovalUserModel)->userEmployer)->id )->first();
            $data['setting'] = $this->setting->getdata();
            $data['fields'] = ['Frequency', 'Ability', 'Effectiveness'];
            $data['frequencies'] = $this->score->findAll()->pluck('frequency');
            $data['abilities'] = $this->score->findAll()->pluck('ability');
            $data['effectiveness'] = $this->score->findAll()->pluck('effectiveness');
            $data['competencies'] = $this->appraisal->findByInvitationCode($request->invitation_code);
            $data['respondent'] = $codeExists;
            return view('appraisal::appraisal-management.view-by-guest', $data);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function appraiseeDetail(){
        $userModel = User::where('id', Auth::user()->id)->first();
        $authUser = auth()->user()->id;
        $data['appraisalDatas'] = AppraisalDevelopmentPlan::whereHas('employee.getUser',function($query){
            $query->where('user_type','hr');
        })->where('appraisee',optional($userModel->userEmployer)->id)->get();
        // dd($data['appraisalData']);
        return view('appraisal::appraisal-management.appraisee-detail',$data);
    }
}
