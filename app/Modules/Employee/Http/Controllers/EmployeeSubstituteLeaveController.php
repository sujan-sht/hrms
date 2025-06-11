<?php

namespace App\Modules\Employee\Http\Controllers;

use App\Traits\LogTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\User\Entities\User;
use App\Modules\Leave\Entities\Leave;
use App\Modules\Leave\Entities\LeaveType;
use App\Modules\Setting\Entities\Setting;
use App\Modules\Admin\Entities\MailSender;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Attendance\Entities\Attendance;
use App\Modules\Employee\Entities\EmployeeLeave;
use App\Modules\Notification\Entities\Notification;
use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Leave\Entities\EmployeeLeaveOpening;
use App\Modules\Leave\Repositories\LeaveTypeInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Employee\Entities\EmployeeSubstituteLeave;
use App\Modules\Employee\Entities\EmployeeSubstituteLeaveClaim;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Employee\Repositories\EmployeeSubstituteLeaveInterface;

class EmployeeSubstituteLeaveController extends Controller
{
    use LogTrait;
    private $organizationObj;
    private $employeeObj;
    private $employeeSubstituteLeaveObj;
    private $leaveTypeObj;
    private $branch;

    public function __construct(
        OrganizationInterface $organizationObj,
        EmployeeInterface $employeeObj,
        EmployeeSubstituteLeaveInterface $employeeSubstituteLeaveObj,
        LeaveTypeInterface $leaveTypeObj,
        BranchInterface $branch
    ) {
        $this->organizationObj = $organizationObj;
        $this->employeeObj = $employeeObj;
        $this->employeeSubstituteLeaveObj = $employeeSubstituteLeaveObj;
        $this->leaveTypeObj = $leaveTypeObj;
        $this->branch = $branch;
    }

    public function index(Request $request)
    {
        $filter = $request->all();

        $data['employeeSubstituteLeaveModels'] = $this->employeeSubstituteLeaveObj->findAll(20, $filter);
        $data['employeeList'] = $this->employeeObj->getList();
        $data['statusList'] = EmployeeSubstituteLeave::statusList();
        if (in_array(auth()->user()->user_type, ['super_admin', 'hr', 'division_hr'])) {
            unset($data['statusList'][2]);
        }
        $data['organizationList'] = $this->organizationObj->getList();
        $data['branchList'] = $this->branch->getList();

        return view('employee::employee-substitute-leave.index', $data);
    }

    public function claimedSubstituteLeaves(Request $request)
    {
        $filter = $request->all();
        $filter['status'] = 3;
        $userInfo = auth()->user();
        $data['user_type'] = $user_type = $userInfo->user_type;
        $id = (($user_type == 'super_admin' || $user_type == 'hr')) ? '' : $userInfo->emp_id;
        $data['emp_id'] = $id;
        $data['user_id'] = $userInfo->id;

        $data['employeeSubstituteLeaveModels'] = $this->employeeSubstituteLeaveObj->findTeamClaimedleaves(20, $filter);
        $data['employeeList'] = $this->employeeObj->getList();
        $data['claimStatusList'] = EmployeeSubstituteLeaveClaim::claimStatusList();
        if (in_array(auth()->user()->user_type, ['super_admin', 'hr', 'division_hr'])) {
            unset($data['claimStatusList'][2]);
        }
        $data['organizationList'] = $this->organizationObj->getList();
        $data['branchList'] = $this->branch->getList();

        return view('employee::employee-substitute-leave.claimed-leaves', $data);
    }
    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data['isEdit'] = false;
        $data['employeeList'] = $this->employeeObj->getList();
        $data['statusList'] = EmployeeSubstituteLeave::statusList();
        $data['leaveKindList'] = Leave::leaveKindList();

        // $data['maxSubstituteDays'] = 0;
        // $leaveTypeModel = LeaveType::where('organization_id', optional(auth()->user()->userEmployer)->organization_id)->where('leave_year_id', getCurrentLeaveYearId())->where('code', 'SUBLV')->where('status', 11)->first();
        // if(isset($leaveTypeModel) && isset($leaveTypeModel->max_substitute_days)){
        //     $data['maxSubstituteDays'] = $leaveTypeModel->max_substitute_days;
        // }

        return view('employee::employee-substitute-leave.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        // dd($data);
        try {
            $data['nepali_date'] = setting('calendar_type') == "AD" ? date_converter()->eng_to_nep_convert($data['date']) : $data['nepali_date'];
            $data['date'] = setting('calendar_type') == 'BS' ? date_converter()->nep_to_eng_convert($data['nepali_date']) : $data['date'];

            $exists = EmployeeSubstituteLeave::where('employee_id', $data['employee_id'])->where('date', $data['date'])->where('status', '!=', '4')->exists();
            if ($exists) {
                toastr()->error('Substitute Leave for this date and employee already allocated !!!');
            } else {
                $employee = $this->employeeObj->find($data['employee_id']);
                $leaveTypeModel = LeaveType::where([
                    'organization_id' => $employee->organization_id,
                    'leave_year_id' => getCurrentLeaveYearId(),
                    'code' => 'SUBLV',
                    'status' => '11',
                ])->orderBy('id', 'desc')->first();

                if (!$leaveTypeModel) {
                    toastr()->error('Leave Not found !');
                    return back();
                }

                $data['leave_type_id'] = $leaveTypeModel->id;
                $model = $this->employeeSubstituteLeaveObj->create($data);
                if ($model) {
                    $model['enable_mail'] = setting('enable_mail');
                    $model['req_name'] = 'Substitute Leave Request';

                    $this->sendMailNotification($model);
                    $logData = [
                        'title' => 'Substitute Leave Claim',
                        'action_id' => $model->id,
                        'action_model' => get_class($model),
                        'route' => route('substituteLeave.index')
                    ];
                    $this->setActivityLog($logData);
                    toastr()->success('Data Created Successfully');
                }
            }
        } catch (\Throwable $e) {
            dd($e);
            toastr()->error('Something Went Wrong !!!');
            return back();
        }

        return redirect(route('substituteLeave.index'));
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        try {
            // $user_id = auth()->user()->id;
            $data['employeeSubstituteLeaveModel'] = $this->employeeSubstituteLeaveObj->findOne($id);

            if (!$data['employeeSubstituteLeaveModel']) {
                toastr()->error('Substitute Leave not found!');
                return redirect(route('substituteLeave.index'));
            }

            // $leaveTypeModel = LeaveType::where('organization_id', optional(auth()->user()->userEmployer)->organization_id)->where('fiscal_year_id', getCurrentFiscalYearId())->where('code', 'SUBLV')->where('status', 11)->first();
            // $data['leave_type_id'] = $leaveTypeModel->name;
            $data['employee'] = $this->employeeObj->find($data['employeeSubstituteLeaveModel']->employee_id);
            $data['statusList'] = EmployeeSubstituteLeave::statusList();

            return view('employee::employee-substitute-leave.show', $data);
        } catch (\Throwable $e) {
            dd($e);
            toastr()->error('Something Went Wrong !!!');
            return redirect(route('substituteLeave.index'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $data['isEdit'] = true;
        $data['employeeSubstituteLeaveModel'] = $this->employeeSubstituteLeaveObj->findOne($id);
        $data['employeeList'] = $this->employeeObj->getList();
        $data['statusList'] = EmployeeSubstituteLeave::statusList();
        $data['leaveKindList'] = Leave::leaveKindList();


        return view('employee::employee-substitute-leave.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();

        try {
            $data['nepali_date'] = setting('calendar_type') == "AD" ? date_converter()->eng_to_nep_convert($data['date']) : $data['nepali_date'];
            $data['date'] = setting('calendar_type') == 'BS' ? date_converter()->nep_to_eng_convert($data['nepali_date']) : $data['date'];

            $exists = EmployeeSubstituteLeave::where('employee_id', $data['employee_id'])->where('date', $data['date'])->where('status', '!=', '4')->where('id', '!=', $id)->exists();
            if ($exists) {
                toastr()->error('Substitute Leave for this date and employee already allocated !!!');
            } else {
                $employee = $this->employeeObj->find($data['employee_id']);
                $leaveTypeModel = LeaveType::where([
                    'organization_id' => $employee->organization_id,
                    'leave_year_id' => getCurrentLeaveYearId(),
                    'code' => 'SUBLV',
                    'status' => '11',
                ])->orderBy('id', 'desc')->first();

                $data['leave_type_id'] = $leaveTypeModel->id;
                $this->employeeSubstituteLeaveObj->update($id, $data);
                $logData = [
                    'title' => 'Substitute Leave Claim Updated',
                    'action_id' => $leaveTypeModel->id,
                    'action_model' => get_class($leaveTypeModel),
                    'route' => route('substituteLeave.index')
                ];
                $this->setActivityLog($logData);
                toastr()->success('Data Updated Successfully');
            }
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('substituteLeave.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $this->employeeSubstituteLeaveObj->delete($id);
            $logData = [
                'title' => 'Substitute Leave Claim Deleted',
                'action_id' => null,
                'action_model' => null,
                'route' => null
            ];
            $this->setActivityLog($logData);
            toastr()->success('Data Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }

    /**
     *
     */

    public function updateStatus(Request $request)
    {
        $inputData = $request->all();
        $model = $this->employeeSubstituteLeaveObj->findOne($inputData['id']);
        $leaveTypeModel = null;
        try {
            if ($inputData['status'] == '2') {
                $inputData['forwarded_remarks'] = $inputData['status_message'];
                $inputData['forwarded_by'] = auth()->user()->id;
            } elseif ($inputData['status'] == '4') {
                $inputData['rejected_remarks'] = $inputData['status_message'];
                $inputData['rejected_by'] = auth()->user()->id;
            } elseif ($inputData['status'] == '3') {
                $inputData['accepted_by'] = auth()->user()->id;
            }
            $result = $this->employeeSubstituteLeaveObj->update($model->id, $inputData);
            if ($result) {
                $updatedModel = $this->employeeSubstituteLeaveObj->findOne($inputData['id']);
                if (setting('two_step_substitute_leave') !== 11) {

                    if ($inputData['status'] == '3') {

                        $leaveTypeModel = $this->leaveTypeObj->findOne($model->leave_type_id);
                        if ($leaveTypeModel) {
                            $employeeLeaveModel = EmployeeLeave::where(['leave_year_id' => $leaveTypeModel->leave_year_id, 'employee_id' => $model->employee_id, 'leave_type_id' => $leaveTypeModel->id])->first();

                            if($leaveTypeModel->code == 'SUBLV'){
                               $counter = 1;
                            }else{
                            $counter = $model->leave_kind == 1 ? 0.5 : 1;
                            }

                            if ($employeeLeaveModel) {
                                $employeeLeaveModel->leave_remaining += $counter;
                                $employeeLeaveModel->save();

                            }

                            $employeeLeaveOpeningModel = EmployeeLeaveOpening::where(['organization_id' => $leaveTypeModel->organization_id, 'leave_year_id' => $leaveTypeModel->leave_year_id, 'employee_id' => $model->employee_id, 'leave_type_id' => $leaveTypeModel->id])->first();
                            if ($employeeLeaveOpeningModel) {
                                $employeeLeaveOpeningModel->opening_leave += $counter;
                                $employeeLeaveOpeningModel->save();
                            }
                        }
                    }
                }

                $updatedModel['enable_mail'] = setting('enable_mail');
                $updatedModel['req_name'] = 'Substitute Leave Request';

                $this->sendMailNotification($updatedModel);
                $logData = [
                    'title' => 'Substitute Leave Claim Status Updated',
                    'action_id' => $inputData['id'],
                    'action_model' => $leaveTypeModel ? get_class($leaveTypeModel) : null,
                    'route' => route('substituteLeave.index')
                ];
                $this->setActivityLog($logData);
            }
            toastr()->success('Status Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }

    /**
     *
     */
    public function getTeamLeaves(Request $request)
    {
        $filter = $request->all();

        $userInfo = auth()->user();
        $data['user_type'] = $user_type = $userInfo->user_type;
        $id = (($user_type == 'super_admin' || $user_type == 'hr')) ? '' : $userInfo->emp_id;
        $data['emp_id'] = $id;
        $data['user_id'] = $userInfo->id;
        $data['teamLeaveModels'] = $this->employeeSubstituteLeaveObj->findTeamleaves(20, $filter);
        $data['organizationList'] = $this->organizationObj->getList();
        $data['employeeList'] = $this->employeeObj->getList();
        $data['statusList'] = EmployeeSubstituteLeave::statusList();

        return view('employee::employee-substitute-leave.team-leave', $data);
    }

    /**
     *
     */
    public static function sendMailNotification($model)
    {
        $authUser = auth()->user();
        $employeeModel = Employee::find($model->employee_id);

        //check if there is first approval or not
        if (isset(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->first_approval_user_id) && !empty(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->first_approval_user_id)) {
            $singleApproval = false;
        } else {
            $singleApproval = true;
        }

        if ($authUser->user_type == 'super_admin') {
            $authorName = $authUser->first_name;
        } else {
            $authorName = optional($authUser->userEmployer)->full_name;
        }

        if ($model->status == '1') {
            $statusTitle = 'Requested';
        } else {
            $statusTitle = $model->getStatus();
        }
        $mailArray = [];

        if ($authUser->id != optional($employeeModel->getUser)->id) {
            // create notification for employee user
            $notificationData['creator_user_id'] = $authUser->id;
            $notificationData['notified_user_id'] = optional($employeeModel->getUser)->id;
            $notificationData['message'] = "Your substitute leave request has been "
                . $statusTitle . " by " . $authorName
                . ". Check-in: " . ($model->checkin ?? '-')
                . ", Check-out: " . ($model->checkout ?? '-')
                . ", Total Working Hours: " . ($model->total_working_hr ?? '-');

            $notificationData['link'] = route('substituteLeave.index');
            $notificationData['type'] = 'employee_substitute_leave';
            $notificationData['type_id_value'] = $model->id;
            Notification::create($notificationData);

            // send email to employee who needs leave
            $notified_user_email = User::getUserEmail(optional($employeeModel->getUser)->id);
            if (isset($notified_user_email) && !empty($notified_user_email) && $model->enable_mail == 11) {
                $notified_user_fullname = Employee::getName(optional($employeeModel->getUser)->id);
                $details = array(
                    'email' => $notified_user_email,
                    'message' => "Your substitute leave request has been " . $statusTitle . " by " . $authorName,
                    'notified_user_fullname' => $notified_user_fullname,
                    'setting' => Setting::first(),
                    'leave' => $model,
                    'req_name' => $model['req_name']
                );
                $mailArray[] = $details;
            }
        }

        // check for first approval
        if (optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->first_approval_user_id && $model->status == '1') {
            // create notification for first approval
            $notificationData['creator_user_id'] = $authUser->id;
            $notificationData['notified_user_id'] = optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->first_approval_user_id;
            $notificationData['message'] = $employeeModel->full_name . "'s substitute leave request has been " . $statusTitle . " by " . $authorName;
            $notificationData['link'] = route('substituteLeave.teamRequest');
            $notificationData['type'] = 'employee_substitute_leave';
            $notificationData['type_id_value'] = $model->id;
            Notification::create($notificationData);

            // send email to supervisor
            $notified_user_email = User::getUserEmail(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->first_approval_user_id);
            if (isset($notified_user_email) && !empty($notified_user_email) && $model->enable_mail == 11) {
                $notified_user_fullname = Employee::getName(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->first_approval_user_id);
                $details = array(
                    'email' => $notified_user_email,
                    'message' => $employeeModel->full_name . "'s substitute leave request has been " . $statusTitle . " by " . $authorName,
                    'notified_user_fullname' => $notified_user_fullname,
                    'setting' => Setting::first(),
                    'leave' => $model,
                    'req_name' => $model['req_name']
                );
                $mailArray[] = $details;
            }
        }

        // check for last approval
        if (optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->last_approval_user_id && ($model->status == '2' || ($singleApproval == true && $model->status == '1'))) {
            // create notification for last approval
            $notificationData['creator_user_id'] = $authUser->id;
            $notificationData['notified_user_id'] = optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->last_approval_user_id;
            $notificationData['message'] = $employeeModel->full_name . "'s substitute leave request has been " . $statusTitle . " by " . $authorName;
            $notificationData['link'] = route('substituteLeave.teamRequest');
            $notificationData['type'] = 'employee_substitute_leave';
            $notificationData['type_id_value'] = $model->id;
            Notification::create($notificationData);

            // send email to last approval
            $notified_user_email = User::getUserEmail(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->last_approval_user_id);
            if (isset($notified_user_email) && !empty($notified_user_email) && $model->enable_mail == 11) {
                $notified_user_fullname = Employee::getName(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->last_approval_user_id);
                $details = array(
                    'email' => $notified_user_email,
                    'message' => $employeeModel->full_name . "'s substitute leave request has been " . $statusTitle . " by " . $authorName,
                    'notified_user_fullname' => $notified_user_fullname,
                    'setting' => Setting::first(),
                    'leave' => $model,
                    'req_name' => $model['req_name']
                );
                $mailArray[] = $details;
            }
        }

        // check for all hr roles
        $hrs = User::where('user_type', 'hr')->pluck('id');
        if (isset($hrs) && !empty($hrs)) {
            foreach ($hrs as $hr) {
                // create notification for hr
                $notificationData['creator_user_id'] = $authUser->id;
                $notificationData['notified_user_id'] = $hr;
                $notificationData['message'] = $employeeModel->full_name . "'s substitute leave request has been " . $statusTitle . " by " . $authorName;
                $notificationData['link'] = route('substituteLeave.index');
                $notificationData['type'] = 'employee_substitute_leave';
                $notificationData['type_id_value'] = $model->id;
                Notification::create($notificationData);

                // send email to all hr
                $notified_user_email = User::getUserEmail($hr);
                if (isset($notified_user_email) && !empty($notified_user_email) && $model->enable_mail == 11) {
                    $notified_user_fullname = Employee::getName($hr);
                    $details = array(
                        'email' => $notified_user_email,
                        'message' => $employeeModel->full_name . "'s substitute leave request has been " . $statusTitle . " by " . $authorName,
                        'notified_user_fullname' => $notified_user_fullname,
                        'setting' => Setting::first(),
                        'leave' => $model,
                        'req_name' => $model['req_name']
                    );
                    $mailArray[] = $details;
                }
            }
        }

        // Send all email at once
        if (count($mailArray) > 0) {
            foreach ($mailArray as $mailDetail) {
                $mail = new MailSender();
                $mail->sendMail('admin::mail.substitute-leave', $mailDetail);
            }
        }

        return true;
    }

    /**
     *
     */
    public static function sendClaimedMailNotification($model)
    {
        $authUser = auth()->user();
        $employeeModel = Employee::find($model->employeeSubstituteLeave->employee_id);
        $leaveClaim = $model;

        //check if there is first approval or not
        if (isset(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->first_approval_user_id) && !empty(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->first_approval_user_id)) {
            $singleApproval = false;
        } else {
            $singleApproval = true;
        }

        if ($authUser->user_type == 'super_admin') {
            $authorName = $authUser->first_name;
        } else {
            $authorName = optional($authUser->userEmployer)->full_name;
        }

        if ($leaveClaim->claim_status == 1) {
            $statusTitle = 'Claimed';
        } else {
            $statusTitle = $leaveClaim->getClaimStatus();
        }
        $mailArray = [];

        if ($authUser->id != optional($employeeModel->getUser)->id) {
            // create notification for employee user
            $notificationData['creator_user_id'] = $authUser->id;
            $notificationData['notified_user_id'] = optional($employeeModel->getUser)->id;
            $notificationData['message'] = "Your substitute leave has been " . $statusTitle . " by " . $authorName;
            $notificationData['link'] = route('substituteLeave.index');
            $notificationData['type'] = 'employee_substitute_leave';
            $notificationData['type_id_value'] = $model->id;
            Notification::create($notificationData);

            // send email to employee who needs leave
            $notified_user_email = User::getUserEmail(optional($employeeModel->getUser)->id);
            if (isset($notified_user_email) && !empty($notified_user_email) && $model->enable_mail == 11) {
                $notified_user_fullname = Employee::getName(optional($employeeModel->getUser)->id);
                $details = array(
                    'email' => $notified_user_email,
                    'message' => "Your substitute leave has been " . $statusTitle . " by " . $authorName,
                    'notified_user_fullname' => $notified_user_fullname,
                    'setting' => Setting::first(),
                    'leave' => $model,
                    'req_name' => $model['req_name']
                );
                $mailArray[] = $details;
            }
        }

        // check for first approval
        if (optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->first_approval_user_id && $leaveClaim->claim_status == '1') {
            // create notification for first approval
            $notificationData['creator_user_id'] = $authUser->id;
            $notificationData['notified_user_id'] = optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->first_approval_user_id;
            $notificationData['message'] = $employeeModel->full_name . "'s substitute leave has been " . $statusTitle . " by " . $authorName;
            $notificationData['link'] = route('substituteLeave.claimedSubstituteLeaves');
            $notificationData['type'] = 'employee_substitute_leave';
            $notificationData['type_id_value'] = $model->id;
            Notification::create($notificationData);

            // send email to supervisor
            $notified_user_email = User::getUserEmail(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->first_approval_user_id);
            if (isset($notified_user_email) && !empty($notified_user_email) && $model->enable_mail == 11) {
                $notified_user_fullname = Employee::getName(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->first_approval_user_id);
                $details = array(
                    'email' => $notified_user_email,
                    'message' => $employeeModel->full_name . "'s substitute leave has been " . $statusTitle . " by " . $authorName,
                    'notified_user_fullname' => $notified_user_fullname,
                    'setting' => Setting::first(),
                    'leave' => $model,
                    'req_name' => $model['req_name']
                );
                $mailArray[] = $details;
            }
        }

        // check for last approval
        if (optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->last_approval_user_id && ($leaveClaim->claim_status == '2' || ($singleApproval == true && $leaveClaim->claim_status == '1'))) {
            // create notification for last approval
            $notificationData['creator_user_id'] = $authUser->id;
            $notificationData['notified_user_id'] = optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->last_approval_user_id;
            $notificationData['message'] = $employeeModel->full_name . "'s substitute leave has been " . $statusTitle . " by " . $authorName;
            $notificationData['link'] = route('substituteLeave.claimedSubstituteLeaves');
            $notificationData['type'] = 'employee_substitute_leave';
            $notificationData['type_id_value'] = $model->id;
            Notification::create($notificationData);

            // send email to last approval
            $notified_user_email = User::getUserEmail(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->last_approval_user_id);
            if (isset($notified_user_email) && !empty($notified_user_email) && $model->enable_mail == 11) {
                $notified_user_fullname = Employee::getName(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->last_approval_user_id);
                $details = array(
                    'email' => $notified_user_email,
                    'message' => $employeeModel->full_name . "'s substitute leave has been " . $statusTitle . " by " . $authorName,
                    'notified_user_fullname' => $notified_user_fullname,
                    'setting' => Setting::first(),
                    'leave' => $model,
                    'req_name' => $model['req_name']
                );
                $mailArray[] = $details;
            }
        }

        // check for all hr roles
        $hrs = User::where('user_type', 'hr')->pluck('id');
        if (isset($hrs) && !empty($hrs)) {
            foreach ($hrs as $hr) {
                // create notification for hr
                $notificationData['creator_user_id'] = $authUser->id;
                $notificationData['notified_user_id'] = $hr;
                $notificationData['message'] = $employeeModel->full_name . "'s substitute leave has been " . $statusTitle . " by " . $authorName;
                $notificationData['link'] = route('substituteLeave.claimedSubstituteLeaves');
                $notificationData['type'] = 'employee_substitute_leave';
                $notificationData['type_id_value'] = $model->id;
                Notification::create($notificationData);

                // send email to all hr
                $notified_user_email = User::getUserEmail($hr);
                if (isset($notified_user_email) && !empty($notified_user_email) && $model->enable_mail == 11) {
                    $notified_user_fullname = Employee::getName($hr);
                    $details = array(
                        'email' => $notified_user_email,
                        'message' => $employeeModel->full_name . "'s substitute leave has been " . $statusTitle . " by " . $authorName,
                        'notified_user_fullname' => $notified_user_fullname,
                        'setting' => Setting::first(),
                        'leave' => $model,
                        'req_name' => $model['req_name']
                    );
                    $mailArray[] = $details;
                }
            }
        }

        // Send all email at once
        if (count($mailArray) > 0) {
            foreach ($mailArray as $mailDetail) {
                $mail = new MailSender();
                $mail->sendMail('admin::mail.substitute-leave-claim', $mailDetail);
            }
        }

        return true;
    }

    public function minSubstituteDate(Request $request)
    {
        try {
            if ($request->ajax()) {
                $employee = $this->employeeObj->find($request->employee_id);

                if (!$employee) {
                    return response()->json(['error' => 'Employee not found'], 404);
                }

                $organizationId = $employee->organization_id ?? 1;

                $data = $this->employeeSubstituteLeaveObj->getMinDate($organizationId);

                return response()->json($data);
            }

            return response()->json(['error' => 'Invalid request type'], 400);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An unexpected error occurred',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function getAttendance(Request $request)
    {
        try {

            $attendance = Attendance::where('emp_id', $request->employee_id)
                ->where('date', $request->date)
                ->select('id', 'date', 'checkin', 'checkout', 'total_working_hr')
                ->first();

            if ($attendance) {
                return response()->json($attendance);
            } else {
                return response()->json(['message' => 'No attendance found for this date.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An unexpected error occurred',
            ], 500);
        }
    }



    public function claim($id)
    {
        try {
            EmployeeSubstituteLeaveClaim::create([
                'employee_substitute_leave_id' => $id,
                'claim_status' => 1
            ]);
            $substituteLeaveModel = $this->employeeSubstituteLeaveObj->findOne($id);
            $claim_model = EmployeeSubstituteLeaveClaim::where('employee_substitute_leave_id', $substituteLeaveModel->id)->first();

            $claim_model['enable_mail'] = setting('enable_mail');
            $claim_model['req_name'] = 'Substitute Leave Claim';

            $this->sendClaimedMailNotification($claim_model);
            toastr()->success('Claimed Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('substituteLeave.index'));
    }

    public function updateClaimStatus(Request $request)
    {
        $inputData = $request->all();
        $model = EmployeeSubstituteLeaveClaim::find($inputData['id']);
        try {
            if ($inputData['claim_status'] == '2') {
                $inputData['forwarded_remarks'] = $inputData['status_message'];
                unset($inputData['status_message']);
                $inputData['forwarded_by'] = auth()->user()->id;
            } elseif ($inputData['claim_status'] == '4') {
                $inputData['rejected_remarks'] = $inputData['status_message'];
                unset($inputData['status_message']);
                $inputData['rejected_by'] = auth()->user()->id;
            } elseif ($inputData['claim_status'] == '3') {
                unset($inputData['status_message']);
                $inputData['accepted_by'] = auth()->user()->id;
            }
            $result = $model->update($inputData);
            if ($result) {
                $substituteLeaveModel = $this->employeeSubstituteLeaveObj->findOne($model->employee_substitute_leave_id);
                $claim_model = EmployeeSubstituteLeaveClaim::where('employee_substitute_leave_id', $substituteLeaveModel->id)->first();
                if ($inputData['claim_status'] == '3') {
                    $leaveTypeModel = $this->leaveTypeObj->findOne($substituteLeaveModel->leave_type_id);
                    if ($leaveTypeModel) {
                        $employeeLeaveModel = EmployeeLeave::where(['leave_year_id' => $leaveTypeModel->leave_year_id, 'employee_id' => $substituteLeaveModel->employee_id, 'leave_type_id' => $leaveTypeModel->id])->first();

                        $counter = $substituteLeaveModel->leave_kind == 1 ? 0.5 : 1;

                        if ($employeeLeaveModel) {
                            $employeeLeaveModel->leave_remaining += $counter;
                            $employeeLeaveModel->save();
                        }

                        $employeeLeaveOpeningModel = EmployeeLeaveOpening::where(['organization_id' => $leaveTypeModel->organization_id, 'leave_year_id' => $leaveTypeModel->leave_year_id, 'employee_id' => $substituteLeaveModel->employee_id, 'leave_type_id' => $leaveTypeModel->id])->first();
                        if ($employeeLeaveOpeningModel) {
                            $employeeLeaveOpeningModel->opening_leave += $counter;
                            $employeeLeaveOpeningModel->save();
                        }
                    }
                }

                $claim_model['enable_mail'] = setting('enable_mail');
                $claim_model['req_name'] = 'Substitute Leave Claim';

                $this->sendClaimedMailNotification($claim_model);
            }
            toastr()->success('Status Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }

    public function cancelSubstituteLeaveRequest(Request $request)
    {
        try {
            $inputData = $request->except('_token');
            $inputData['cancelled_by'] = auth()->user()->id;
            $model = $this->employeeSubstituteLeaveObj->update($inputData['id'], $inputData);

            // $this->sendMailNotification($model);

            toastr('Substitute Leave Request Status Cancelled Successfully', 'success');
        } catch (\Throwable $e) {
            toastr('Error While Updating Substitute Leave Request Status', 'error');
        }
        return redirect()->back();
    }
}
