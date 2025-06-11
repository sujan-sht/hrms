<?php

namespace App\Modules\OvertimeRequest\Repositories;

use App\Modules\Admin\Entities\MailSender;
use App\Modules\OvertimeRequest\Entities\OvertimeRequest;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\EmployeeApprovalFlow;
use App\Modules\Employee\Entities\EmployeeClaimRequestApprovalFlow;
use App\Modules\Notification\Entities\Notification;
use App\Modules\Setting\Entities\Setting;
use App\Modules\User\Entities\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ladumor\OneSignal\OneSignal;

class OvertimeRequestRepository implements OvertimeRequestInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $overtimeRequestModel  = OvertimeRequest::query();
        $overtimeRequestModel->when(true, function ($query) use ($filter) {
            if (isset($filter['organization_id']) && $filter['organization_id'] != '') {
                $query->whereHas('employee', function ($q) use ($filter) {
                    $q->where('organization_id', $filter['organization_id']);
                });
            }

            if (isset($filter['employee_id']) && $filter['employee_id'] != '') {
                $query = $query->where('employee_id', $filter['employee_id']);
            }

            if (isset($filter['date_range'])) {
                $filterDates = explode(' - ', $filter['date_range']);
                $query->where('date', '>=', $filterDates[0]);
                $query->where('date', '<=', $filterDates[1]);
            }

            if (isset($filter['from_date_nep']) && !empty($filter['from_date_nep'])) {
                $query->where('nepali_date', '>=', $filter['from_date_nep']);
            }

            if (isset($filter['to_date_nep']) && !empty($filter['to_date_nep'])) {
                $query->where('nepali_date', '<=', $filter['to_date_nep']);
            }

            if (isset($filter['status']) && $filter['status'] != '') {
                $query = $query->where('status', $filter['status']);
            }

            if (auth()->user()->user_type == 'employee' || auth()->user()->user_type == 'supervisor') { //supervisor logic changes
                $empId = optional(User::where('id', auth()->user()->id)->first()->userEmployer)->id;

                $query->where('employee_id', $empId);
            } elseif (auth()->user()->user_type == 'division_hr') {
                $query->whereHas('employee', function ($q) use ($filter) {
                    $q->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
                });
            } elseif (auth()->user()->user_type == 'hr' || auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'super_admin') {
                $query->where('employee_id', '!=', null);
            }
        });

        // if (isset($filter['is_export']) && $filter['is_export'] != '') { //for export
        //     $result = $overtimeRequestModel->get();
        // } else {
        $result = $overtimeRequestModel->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : config('attendance.export-length'));
        // }
        return $result;
    }

    public function find($id)
    {
        return OvertimeRequest::find($id);
    }

    public function save($data)
    {
        $model = OvertimeRequest::create($data);
        return $model;
    }

    public function update($id, $data)
    {
        return OvertimeRequest::find($id)->update($data);
    }

    public function delete($id)
    {
        return OvertimeRequest::find($id)->delete();
    }

    public function findTeamOvertimeRequests($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $statusList = $this->getStatus();
        $user = auth()->user();
        $userId = $user->id;
        $usertype = $user->user_type;

        $firstApprovalEmps = EmployeeApprovalFlow::where('first_approval_user_id', $userId)->pluck('last_approval_user_id', 'employee_id')->toArray();
        $firstApproval = OvertimeRequest::with('employee.employeeApprovalFlowRelatedDetailModel')->when(true, function ($query) use ($firstApprovalEmps, $user) {
            // $query->whereHas('employee', function ($q) use ($user) {
            //     $q->where('organization_id', optional($user->userEmployer)->organization_id);
            // });
            $query->whereIn('employee_id', array_keys($firstApprovalEmps));
        })->get()->map(function ($approvals) use ($statusList, $usertype, $userId) {
            $approvalFlow = optional($approvals->employee)->employeeApprovalFlowRelatedDetailModel;
            if ($usertype == 'supervisor') {
                if (!$approvalFlow->last_approval_user_id || $approvals->status == 1) {
                    unset($statusList[3]);
                }

                if ($approvalFlow->first_approval_user_id == $userId && $approvals->status == 2) {
                    unset($statusList[1], $statusList[3], $statusList[4]);
                }
                unset($statusList[5]);
            }
            $approvals->status_list = json_encode($statusList);
            return $approvals;
        });
        $lastApprovalEmps = EmployeeApprovalFlow::where('last_approval_user_id', $userId)->select('first_approval_user_id', 'last_approval_user_id', 'employee_id')->get()->toArray();
        $lastApproval = [];
        if (count($lastApprovalEmps) > 0) {
            $lastApproval = OvertimeRequest::when(true, function ($query) use ($lastApprovalEmps, $user) {
                // $query->whereHas('employee', function ($q) use ($user) {
                //     $q->where('organization_id', optional($user->userEmployer)->organization_id);
                // });
                $where = 'where';
                foreach ($lastApprovalEmps as $value) {

                    $query->$where(function ($query) use ($value, $where) {
                        $query->where('employee_id', $value['employee_id']);
                        if (is_null($value['first_approval_user_id'])) {
                            $query->whereIn('status', [1, 2, 3, 4]);
                            // $query->whereIn('status', [1, 2, 3, 4, 5]);
                        } else {
                            $query->whereIn('status', [2, 3, 4]);
                            // $query->whereIn('status', [2, 3, 4, 5]);
                        }
                    });
                    $where = 'orWhere';
                }
            })->get()->map(function ($approvals) use ($statusList, $usertype, $userId) {
                $approvalFlow = optional($approvals->employee)->employeeApprovalFlowRelatedDetailModel;
                if ($usertype == 'supervisor') {
                    if ($approvals->status == 1) {
                        if (isset($approvalFlow->first_approval_user_id) && $approvalFlow->first_approval_user_id == $userId) {
                            unset($statusList[3]);
                        } elseif (isset($approvalFlow->last_approval_user_id) && $approvalFlow->last_approval_user_id == $userId) {
                            unset($statusList[2]);
                        }
                    } elseif ($approvals->status == 2) {
                        if (isset($approvalFlow->first_approval_user_id) && $approvalFlow->first_approval_user_id == $userId) {
                            unset($statusList[1], $statusList[3], $statusList[4]);
                        } elseif (isset($approvalFlow->last_approval_user_id) && $approvalFlow->last_approval_user_id == $userId) {
                            unset($statusList[1]);
                        }
                    }
                    unset($statusList[5]);
                }
                $approvals->status_list = json_encode($statusList);
                return $approvals;
            });
        }
        $mergeApproval = $firstApproval->merge($lastApproval)->sortByDesc('id');
        $myCollectionObj = collect($mergeApproval);
        $result = $myCollectionObj;

        if (isset($filter['employee_id']) && $filter['employee_id'] != '') {
            $result = $result->where('employee_id', $filter['employee_id']);
        }

        if (isset($filter['date_range'])) {
            $filterDates = explode(' - ', $filter['date_range']);
            $result = $result->where('from_date', '>=', $filterDates[0]);
            $result = $result->where('to_date', '<=', $filterDates[1]);
        }

        if (isset($filter['from_date_nep']) && !empty($filter['from_date_nep'])) {
            $result = $result->where('from_date_nep', '>=', $filter['from_date_nep']);
        }

        if (isset($filter['to_date_nep']) && !empty($filter['to_date_nep'])) {
            $result = $result->where('to_date_nep', '<=', $filter['to_date_nep']);
        }

        if (isset($filter['status']) && !empty($filter['status'])) {
            $result = $result->where('status', $filter['status']);
        }

        $result = paginate($result, 20, '', ['path' => request()->url()]);
        return $result;
    }

    public function getStatus()
    {
        return OvertimeRequest::STATUS;
    }

    public function sendMailNotification($model)
    {
        $authUser = auth()->user();
        $employeeModel = Employee::find($model->employee_id);
        // $userModel = optional($employeeModel->getUser);

        //check if there is first approval or not
        if (isset(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->first_approval_user_id) && !empty(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->first_approval_user_id)) {
            $singleApproval = false;
        } else {
            $singleApproval = true;
        }
        //

        if ($authUser->user_type == 'super_admin') {
            $authorName = $authUser->first_name;
        } else {
            $authorName = optional($authUser->userEmployer)->full_name;
        }

        if ($model->status == '1') {
            $statusTitle = 'Created';
        } else {
            $statusTitle = $model->getStatus();
        }
        if(isset($model['is_claim']) && $model['is_claim'] == 11){
            $statusTitle = 'Claimed';
        }

        $mailArray = [];
        if (optional($employeeModel->getUser)->id) {
            // if ($authUser->id != optional($employeeModel->getUser)->id && ($model->status == '1' || $model->status == '3' || $model->status == '4')) {

                if ($authUser->id != optional($employeeModel->getUser)->id) {
                // create notification for employee user
                $notificationData['creator_user_id'] = $authUser->id;
                $notificationData['notified_user_id'] = optional($employeeModel->getUser)->id;
                $notificationData['message'] = "Your overtime request has been " . $statusTitle . " by " . $authorName;
                $notificationData['link'] = route('overtimeRequest.index');
                $notificationData['type'] = 'Overtime';
                $notificationData['type_id_value'] = $model->id;
                Notification::create($notificationData);

                // send email to employee
                // if(emailSetting(7) == 11 && $model->enable_mail == 11){
                    if($model->enable_mail == 11){

                    // $fields['include_player_ids'] = [optional($userModel->device)->os_player_id];
                    // $message = $notificationData['message'];
                    // $oneSignal =  OneSignal::sendPush($fields, $message);

                    $notified_user_email = User::getUserEmail(optional($employeeModel->getUser)->id);
                    if (isset($notified_user_email) && !empty($notified_user_email)) {
                        $notified_user_fullname = Employee::getName(optional($employeeModel->getUser)->id);
                        $details = array(
                            'email' => $notified_user_email,
                            'message' => "Your overtime request has been " . $statusTitle . " by " . $authorName,
                            'notified_user_fullname' => $notified_user_fullname,
                            'setting' => Setting::first(),
                            'overtime_request' => $model,
                            'subject' => 'Overtime Request Notification'
                        );
                        $mailArray[] = $details;
                    }
                }
            }
        }

        // check for first approval
        if (optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->first_approval_user_id && ($model->status == '1' || isset($model['is_claim']))) {
            // create notification for first approval
            $notificationData['creator_user_id'] = $authUser->id;
            $notificationData['notified_user_id'] = optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->first_approval_user_id;
            $notificationData['message'] = $employeeModel->full_name . "'s overtime request has been " . $statusTitle . " by " . $authorName;
            $notificationData['link'] = route('overtimeRequest.teamRequests');
            $notificationData['type'] = 'Overtime';
            $notificationData['type_id_value'] = $model->id;
            Notification::create($notificationData);

            // send email to supervisor
            // if(emailSetting(7) == 11 && $model->enable_mail == 11){
                if($model->enable_mail == 11){

                $notified_user_email = User::getUserEmail(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->first_approval_user_id);
                if (isset($notified_user_email) && !empty($notified_user_email)) {
                    $notified_user_fullname = Employee::getName(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->first_approval_user_id);
                    $details = array(
                        'email' => $notified_user_email,
                        'message' => $employeeModel->full_name . "'s overtime request has been " . $statusTitle . " by " . $authorName,
                        'notified_user_fullname' => $notified_user_fullname,
                        'setting' => Setting::first(),
                        'overtime_request' => $model,
                        'subject' => 'Overtime Request Notification'
                    );
                    $mailArray[] = $details;
                }
            }
        }

        // check for last approval
        if (optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->last_approval_user_id && ($model->status == '2' || ($singleApproval == true && $model->status == '1') || isset($model['is_claim']))) {
            // create notification for last approval
            $notificationData['creator_user_id'] = $authUser->id;
            $notificationData['notified_user_id'] = optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->last_approval_user_id;
            $notificationData['message'] = $employeeModel->full_name . "'s overtime request has been " . $statusTitle . " by " . $authorName;
            $notificationData['link'] = route('overtimeRequest.teamRequests');
            $notificationData['type'] = 'Overtime';
            $notificationData['type_id_value'] = $model->id;
            Notification::create($notificationData);

            // send email to last approval
            // if(emailSetting(7) == 11 && $model->enable_mail == 11){
                if($model->enable_mail == 11){

                $notified_user_email = User::getUserEmail(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->last_approval_user_id);
                if (isset($notified_user_email) && !empty($notified_user_email)) {
                    $notified_user_fullname = Employee::getName(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->last_approval_user_id);
                    $details = array(
                        'email' => $notified_user_email,
                        'message' => $employeeModel->full_name . "'s overtime request has been " . $statusTitle . " by " . $authorName,
                        'notified_user_fullname' => $notified_user_fullname,
                        'setting' => Setting::first(),
                        'overtime_request' => $model,
                        'subject' => 'Overtime Request Notification'
                    );
                    $mailArray[] = $details;
                }
            }
        }

        // check for all hr roles
        $hrs = User::where('user_type', 'hr')->pluck('id');
        if (isset($hrs) && !empty($hrs)) {
            foreach ($hrs as $hr) {
                // create notification for hr
                $notificationData['creator_user_id'] = $authUser->id;
                $notificationData['notified_user_id'] = $hr;
                $notificationData['message'] = $employeeModel->full_name . "'s overtime request has been " . $statusTitle . " by " . $authorName;
                $notificationData['link'] = route('overtimeRequest.index');
                $notificationData['type'] = 'Overtime';
                $notificationData['type_id_value'] = $model->id;
                Notification::create($notificationData);

                // send email to all hr
                // if(emailSetting(7) == 11 && $model->enable_mail == 11){
                    if($model->enable_mail == 11){

                    $notified_user_email = User::getUserEmail($hr);
                    if (isset($notified_user_email) && !empty($notified_user_email)) {
                        $notified_user_fullname = Employee::getName($hr);
                        $details = array(
                            'email' => $notified_user_email,
                            'message' => $employeeModel->full_name . "'s overtime request has been " . $statusTitle . " by " . $authorName,
                            'notified_user_fullname' => $notified_user_fullname,
                            'setting' => Setting::first(),
                            'overtime_request' => $model,
                            'subject' => 'Overtime Request Notification'
                        );
                        $mailArray[] = $details;
                    }
                }
            }
        }

        // check for all division hr roles
        $divisionHrs = User::when(true, function ($query) use ($employeeModel) {
            $query->whereHas('userEmployer', function ($q) use ($employeeModel) {
                $q->where('organization_id', $employeeModel->organization_id)->where('status', 1);
            });
        })->where('user_type', 'division_hr')->pluck('id');

        if (isset($divisionHrs) && !empty($divisionHrs)) {
            foreach ($divisionHrs as $divisionHr) {
                // create notification for division hr
                $notificationData['creator_user_id'] = $authUser->id;
                $notificationData['notified_user_id'] = $divisionHr;
                $notificationData['message'] = $employeeModel->full_name . "'s overtime request has been " . $statusTitle . " by " . $authorName;
                $notificationData['link'] = route('overtimeRequest.index');
                $notificationData['type'] = 'Overtime';
                $notificationData['type_id_value'] = $model->id;
                Notification::create($notificationData);

                // send email to all division hr
                // if(emailSetting(7) == 11 && $model->enable_mail == 11){
                    if($model->enable_mail == 11){

                    $notified_user_email = User::getUserEmail($divisionHr);
                    if (isset($notified_user_email) && !empty($notified_user_email)) {
                        $notified_user_fullname = Employee::getName($divisionHr);
                        $details = array(
                            'email' => $notified_user_email,
                            'message' => $employeeModel->full_name . "'s overtime request has been " . $statusTitle . " by " . $authorName,
                            'notified_user_fullname' => $notified_user_fullname,
                            'setting' => Setting::first(),
                            'overtime_request' => $model,
                            'subject' => 'Overtime Request Notification'
                        );
                        $mailArray[] = $details;
                    }
                }
            }
        }

        //  Send all email at once
        if (count($mailArray) > 0) {
            foreach ($mailArray as $mailDetail) {
                $mail = new MailSender();
                $mail->sendMail('admin::mail.overtime_request', $mailDetail);
            }
        }
        return true;
    }


    public function getEmployeeOvertimeRequests($employeeId = null, $limit = null)
    {
        $activeUserModel = Auth::user();
        $query = OvertimeRequest::query();
        $query->select('id', 'employee_id', 'from_date', 'status', "from_date as date")->where('status', 1)->addSelect(DB::raw("'overtimeRequest' as type"));



        if ($activeUserModel->user_type == 'employee') {
            $query->where('employee_id', $activeUserModel->emp_id);
        }

        if ($activeUserModel->user_type == 'supervisor') {
            $authEmpId = array(intval($activeUserModel->emp_id));
            $subordinateEmpIds = Employee::getSubordinates($activeUserModel->id);
            $empIds = array_merge($authEmpId, $subordinateEmpIds);
            $query->whereIn('employee_id', $empIds);
        }

        if (auth()->user()->user_type == 'division_hr') {
            $organizationId = optional($activeUserModel->userEmployer)->organization_id;

            if ($organizationId) {
                $employeeIds = Employee::where('organization_id', $organizationId)->pluck('id');
                $query->whereIn('employee_id', $employeeIds);
            } else {
                return collect(); // Returning an empty collection
            }
        }



        $result = $query->orderBy('id', 'DESC')->take($limit ? $limit : env('DEF_PAGE_LIMIT', 9999))->get();
        return $result;
    }
    
}
