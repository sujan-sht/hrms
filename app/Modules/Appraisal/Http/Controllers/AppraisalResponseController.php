<?php

namespace App\Modules\Appraisal\Http\Controllers;

use App\Modules\Appraisal\Entities\AppraisalCompetancyResponse;
use App\Modules\Appraisal\Entities\AppraisalCompetencyResponse;
use App\Modules\Appraisal\Entities\AppraisalDevelopmentPlan;
use App\Modules\Appraisal\Entities\AppraisalResponse;
use App\Modules\Appraisal\Repositories\AppraisalInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Notification\Entities\Notification;
use App\Modules\Setting\Entities\Setting;
use App\Modules\User\Entities\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;

class AppraisalResponseController extends Controller
{
    protected $appraisal;
    protected $employee;

    public function __construct(AppraisalInterface $appraisal, EmployeeInterface $employee)
    {
        $this->appraisal = $appraisal;
        $this->employee = $employee;
    }

    //    public function store(Request $request)
    //    {
    //     // dd($request);
    //         $respondent = $this->appraisal->invitationCodeExist($request->invitation_code);

    //         foreach($request->question_ids as $key => $question_id)
    //         {
    //             AppraisalResponse::create([
    //                 'appraisal_id' => $request->appraisal_id,
    //                 'respondent_id' => $respondent->id,
    //                 'question_id' => $question_id,
    //                 'comment' => $request->comment[$key] ?? null,
    //                 'created_by' => $request->created_by,
    //                 'score' => $request->score[$key] ?? $request->average_score
    //             ]);
    //         }
    //         AppraisalDevelopmentPlan::create([
    //             'strength' => $request->strength,
    //             'development' => $request->development,
    //             'support' => $request->support,
    //             'reviewer_comment' => $request->reviewer_comment,
    //             'appraisee' => $request->appraisee,
    //             'appraisal_id' => $request->appraisal_id,
    //             'created_by' => $request->created_by,
    //         ]);
    //         $respondent->fill(['already_responded' => 1]);
    //         $respondent->save();

    //         toastr('Appraisal Detail Saved Successfully', 'success');
    //         return redirect()->route('/');
    //    }

    public function store(Request $request)
    {
        // dd($request);
        $respondent = $this->appraisal->invitationCodeExist($request->invitation_code);
        // dd($respondent);
        $authorName = optional($respondent->employee)->getFullName();
        $hr_type = User::where('user_type', 'hr')->first();
        // dd($hr_type);
        foreach ($request->competency_ids as $key => $competency_id) {
            AppraisalCompetencyResponse::create([
                'appraisal_id' => $request->appraisal_id,
                'respondent_id' => $respondent->id,
                'competency_id' => $competency_id,
                'comment' => $request->comment[$key] ?? null,
                'created_by' => $request->created_by,
                'score' => $request->score[$key] ?? null
            ]);
        }
        AppraisalDevelopmentPlan::create([
            'strength' => $request->strength,
            'development' => $request->development,
            'support' => $request->support,
            'reviewer_comment' => $request->reviewer_comment,
            'average_score' => $request->average_score,
            'appraisee' => $request->appraisee,
            'appraisal_id' => $request->appraisal_id,
            'created_by' => $request->created_by,
        ]);

        $appraisalApprovalFlow = $this->employee->employeeAppraisalApprovalFlow(optional(optional($respondent->appraisal)->employee)->id);
        // dd(optional(optional($appraisalApprovalFlow->lastApprovalUserModel)->userEmployer)->id);
        if ($appraisalApprovalFlow) {
            //For First Approval
            if ($appraisalApprovalFlow->first_approval && $respondent->employee_id == $respondent->appraisal->appraisee) {
                if (optional(optional($appraisalApprovalFlow->firstApprovalUserModel)->userEmployer)->official_email != null && optional(optional($appraisalApprovalFlow->firstApprovalUserModel)->userEmployer)->full_name != null) {

                    $firstSupervisorEmail = optional(optional($appraisalApprovalFlow->firstApprovalUserModel)->userEmployer)->official_email;
                    $firstSupervisorData = [
                        'appraisal_id' => $respondent->appraisal_id,
                        'name' => optional(optional($appraisalApprovalFlow->firstApprovalUserModel)->userEmployer)->full_name,
                        'email' => $firstSupervisorEmail,
                        'invitation_code' => mt_rand(99, 999999) . strtotime(now()->format('d-m-Y h:i:s a')),
                        'employee_id' => optional(optional($appraisalApprovalFlow->firstApprovalUserModel)->userEmployer)->id
                    ];
                    $this->appraisal->saveRespondents($firstSupervisorData);

                    $details = [
                        'invitation_code' => $firstSupervisorData['invitation_code'],
                        'name' =>  $firstSupervisorData['name'],
                        'email' => $firstSupervisorEmail,
                        'respondent' => $respondent->appraisal->employee->getFullName(),
                        'setting' => Setting::first()
                    ];

                    Mail::send('appraisal::mails.appraisal-respondent-mail', $details, function ($message) use ($firstSupervisorEmail) {
                        $message->from(config('mail.from.address'));
                        $message->to($firstSupervisorEmail);
                        $message->subject('Appraisal Questionnaire Invitation');
                    });
                }

                $notificationData['creator_user_id'] = optional(optional($respondent->employee)->getUser)->id;
                $notificationData['notified_user_id'] = $appraisalApprovalFlow->first_approval;
                $notificationData['message'] = optional(optional($respondent->appraisal)->employee)->getFullName() . "Appraisal" . " has been Forwarded"  . " by " . $authorName;
                $notificationData['link'] = route('appraisal.viewThroughInvitation') . '?invitation_code=' . $details['invitation_code'];
                Notification::create($notificationData);
            }

            //For Second approval

            if ($appraisalApprovalFlow->last_approval && $respondent->employee_id == optional(optional($appraisalApprovalFlow->firstApprovalUserModel)->userEmployer)->id) {
                if (optional(optional($appraisalApprovalFlow->lastApprovalUserModel)->userEmployer)->official_email != null && optional(optional($appraisalApprovalFlow->lastApprovalUserModel)->userEmployer)->full_name != null) {

                    $secondSupervisorEmail = optional(optional($appraisalApprovalFlow->lastApprovalUserModel)->userEmployer)->official_email;
                    $secondSupervisorData = [
                        'appraisal_id' => $respondent->appraisal_id,
                        'name' => optional(optional($appraisalApprovalFlow->lastApprovalUserModel)->userEmployer)->full_name,
                        'email' => $secondSupervisorEmail,
                        'invitation_code' => mt_rand(99, 999999) . strtotime(now()->format('d-m-Y h:i:s a')),
                        'employee_id' => optional(optional($appraisalApprovalFlow->lastApprovalUserModel)->userEmployer)->id
                    ];
                    $this->appraisal->saveRespondents($secondSupervisorData);

                    $details = [
                        'invitation_code' => $secondSupervisorData['invitation_code'],
                        'name' =>  $secondSupervisorData['name'],
                        'email' => $secondSupervisorEmail,
                        'respondent' => $respondent->appraisal->employee->getFullName(),
                        'setting' => Setting::first()
                    ];

                    Mail::send('appraisal::mails.appraisal-respondent-mail', $details, function ($message) use ($secondSupervisorEmail) {
                        $message->from(config('mail.from.address'));
                        $message->to($secondSupervisorEmail);
                        $message->subject('Appraisal Questionnaire Invitation');
                    });
                }

                $notificationData['creator_user_id'] = optional(optional($respondent->employee)->getUser)->id;
                // dd($notificationData['creator_user_id']);
                $notificationData['notified_user_id'] = $appraisalApprovalFlow->last_approval;
                $notificationData['message'] = optional(optional($respondent->appraisal)->employee)->getFullName() . "Appraisal" . " has been Forwared"  . " by " . $authorName;
                $notificationData['link'] = route('appraisal.viewThroughInvitation') . '?invitation_code=' . $details['invitation_code'];
                // dd($notificationData);
                Notification::create($notificationData);
            }
        }

        if ($hr_type) {
            if (optional($hr_type->userEmployer)->official_email != null && optional($hr_type->userEmployer)->full_name != null && $respondent->employee_id == optional(optional($appraisalApprovalFlow->lastApprovalUserModel)->userEmployer)->id) {
                // dd('ggg');
                $hrEmail = optional($hr_type->userEmployer)->official_email;
                $hrData = [
                    'appraisal_id' => $respondent->appraisal_id,
                    'name' => optional($hr_type->userEmployer)->full_name,
                    'email' => $hrEmail,
                    'invitation_code' => mt_rand(99, 999999) . strtotime(now()->format('d-m-Y h:i:s a')),
                    'employee_id' => optional($hr_type->userEmployer)->id,
                ];
                $this->appraisal->saveRespondents($hrData);

                $hrDetails = [
                    'invitation_code' => $hrData['invitation_code'],
                    'name' => optional($hr_type->userEmployer)->full_name,
                    'email' => $hrEmail,
                    'respondent' => optional($hr_type->userEmployer)->full_name,
                    'setting' => Setting::first()
                ];


                Mail::send('appraisal::mails.appraisal-respondent-mail', $hrDetails, function ($message) use ($hrEmail) {
                    $message->from(config('mail.from.address'));
                    $message->to($hrEmail);
                    $message->subject('Appraisal Questionnaire Invitation');
                });
                $notificationData['creator_user_id'] = optional(optional($respondent->employee)->getUser)->id;
                // dd($notificationData['creator_user_id']);
                $notificationData['notified_user_id'] = $hr_type->id;
                $notificationData['message'] = optional(optional($respondent->appraisal)->employee)->getFullName() . ' ' . "Appraisal" . " has been Forwared"  . " by " . $authorName;
                $notificationData['link'] = route('appraisal.viewThroughInvitation') . '?invitation_code=' . $hrDetails['invitation_code'];
                // dd($notificationData);
                Notification::create($notificationData);
            }
            
        }


        $respondent->fill(['already_responded' => 1]);
        $respondent->save();

        toastr('Appraisal Detail Saved Successfully', 'success');
        return redirect()->route('/');
    }
}
