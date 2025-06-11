<?php

namespace App\Modules\Attendance\Repositories;

use App\Helpers\DateTimeHelper;
use Ladumor\OneSignal\OneSignal;
use Illuminate\Support\Facades\DB;
use App\Modules\User\Entities\User;
use Illuminate\Support\Facades\Auth;
use App\Modules\Leave\Entities\Leave;
use App\Modules\Setting\Entities\Setting;
use App\Modules\Admin\Entities\MailSender;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Attendance\Entities\Attendance;
use App\Modules\Employee\Entities\EmployeeLeave;
use App\Modules\Notification\Entities\Notification;
use App\Modules\Attendance\Entities\AttendanceRequest;
use App\Modules\Employee\Entities\EmployeeApprovalFlow;
use App\Modules\Employee\Entities\EmployeeAttendanceApprovalFlow;

class AttendanceRequestRepository implements AttendanceRequestInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        // $accessibleEmployees = getEmployeeIds(); //Helper Function

        // $authUser = auth()->user();
        // if ($authUser->user_type == 'division_hr') {
        //     $filter['organization_id'] = optional($authUser->userEmployer)->organization_id;
        // }

        $attendanceRequestModel  = AttendanceRequest::query();
        $attendanceRequestModel->when(true, function ($query) use ($filter) {
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

            if (isset($filter['from_nep_date']) && !empty($filter['from_nep_date'])) {
                $query->where('nepali_date', '>=', $filter['from_nep_date']);
            }

            if (isset($filter['to_nep_date']) && !empty($filter['to_nep_date'])) {
                $query->where('nepali_date', '<=', $filter['to_nep_date']);
            }

            if (isset($filter['type']) && $filter['type'] != '') {
                $query = $query->where('type', $filter['type']);
            }

            if (isset($filter['status']) && $filter['status'] != '') {
                $query = $query->where('status', $filter['status']);
            }

            if ($filter['authUser']->user_type == 'employee' || $filter['authUser']->user_type == 'supervisor') { //supervisor logic changes
                $empId = optional(User::where('id', $filter['authUser']->id)->first()->userEmployer)->id;
                $query->where('employee_id', $empId);
            } elseif ($filter['authUser']->user_type == 'division_hr') {
                $query->whereHas('employee', function ($q) use ($filter) {
                    $q->where('organization_id', optional($filter['authUser']->userEmployer)->organization_id);
                });
            } elseif ($filter['authUser']->user_type == 'hr' || $filter['authUser']->user_type == 'admin' || $filter['authUser']->user_type == 'super_admin') {
                $query->where('employee_id', '!=', null);
            }
        });

        if (isset($filter['is_export']) && $filter['is_export'] != '') { //for export
            $result = $attendanceRequestModel->get();
        } else {
            $result = $attendanceRequestModel->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : config('attendance.export-length'));
        }

        return $result;
    }

    public function find($id)
    {
        return AttendanceRequest::find($id);
    }

    public function approvedAtdRequestExist($date, $emp_id, $type)
    {
        return AttendanceRequest::where('date', $date)->where('employee_id', $emp_id)->whereIn('type', $type)->where('status', 3)->orderBy('id', 'DESC')->first();
    }

    public function getEmployeeAttendanceRequest($limit = null)
    {
        $activeUserModel = Auth::user();
        $query = AttendanceRequest::query();
        // $query->select('id', 'employee_id', 'date', 'status', 'type')
        // ->addSelect(DB::raw("'attendance' as type"));
        $query->where('status', 1);

        if ($activeUserModel->user_type == 'employee') {
            $query->where('employee_id', $activeUserModel->emp_id);
        }

        if ($activeUserModel->user_type == 'supervisor') {
            $authEmpId = array(intval($activeUserModel->emp_id));
            $subordinateEmpIds = Employee::getSubordinates($activeUserModel->id);
            $empIds = array_merge($authEmpId, $subordinateEmpIds);
            $query->whereIn('employee_id', $empIds);
        }

        if ($activeUserModel->user_type == 'division_hr') {
            $query->whereHas('employee', function ($q) {
                $q->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
            });
        }

        $result = $query->orderBy('created_at', 'DESC')->take($limit ? $limit : env('DEF_PAGE_LIMIT', 9999))->get()->map(function ($atd) {
            $atd->title = ($atd->getType());
            $atd->type = 'attendance';
            return $atd;
        });
        return $result;
    }


    public function getTypes()
    {
        return AttendanceRequest::Types;
    }

    public function getStatus()
    {
        return AttendanceRequest::STATUS;
    }
    public function getKinds()
    {
        return AttendanceRequest::Kinds;
    }

    public function save($data)
    {
        if (!isset($data['status'])) {
            $data['status'] = '1';
        }
        $model = AttendanceRequest::create($data);
        // $setting = Setting::first();
        // $model['enable_mail'] = $setting->enable_mail;
        // if ($model) {
        //     $this->sendMailNotification($model);
        // }

        return $model;
    }

    public function update($id, $data)
    {
        return AttendanceRequest::find($id)->update($data);
    }

    public function delete($id)
    {
        $atdRequest = (AttendanceRequest::find($id));
        if ($atdRequest->status == 3) {

            $checkinType = ['Missed Check In', 'Late Arrival Request'];
            $checkoutType = ['Missed Check Out', 'Early Departure Request'];
            if (in_array($atdRequest->getType(), $checkinType)) {
                $attendanceModel =   Attendance::where([
                    'emp_id' => $atdRequest->employee_id,
                    'date' => $atdRequest->date,
                ])->first();
                $attendanceModel->checkin = $attendanceModel->checkin_original;
                $attendanceModel->total_working_hr = DateTimeHelper::getTimeDiff(date('H:i', strtotime($attendanceModel->checkin)), date('H:i', strtotime($attendanceModel->checkout)));
                $attendanceModel->save();
            }

            //checkout and Approved
            if (in_array($atdRequest->getType(), $checkoutType)) {
                $attendanceModel =   Attendance::where([
                    'emp_id' => $atdRequest->employee_id,
                    'date' => $atdRequest->date,
                ])->first();
                $attendanceModel->checkout = $attendanceModel->checkout_original;
                $attendanceModel->total_working_hr = DateTimeHelper::getTimeDiff(date('H:i', strtotime($attendanceModel->checkin)), date('H:i', strtotime($attendanceModel->checkout)));
                $attendanceModel->save();
            }
        }

        return AttendanceRequest::find($id)->delete();
    }

    /**
     * For internal use
     */
    public function sendMailNotification($model)
    {
        $authUser = auth()->user();
        $employeeModel = Employee::find($model->employee_id);
        $userModel = optional($employeeModel->getUser);

        //check if there is first approval or not
        if (isset(optional($employeeModel->employeeAttendanceApprovalFlow)->first_approval_user_id) && !empty(optional($employeeModel->employeeAttendanceApprovalFlow)->first_approval_user_id)) {
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
        $subject = 'Attendance Request ' . $statusTitle . ' for ' . $employeeModel->full_name . ' (' . $employeeModel->employee_code . ')';

        $mailArray = [];

        if (optional($employeeModel->getUser)->id) {
            if ($authUser->id != optional($employeeModel->getUser)->id && ($model->status == '1' || $model->status == '3' || $model->status == '4')) {

                // create notification for employee user
                $notificationData['creator_user_id'] = $authUser->id;
                $notificationData['notified_user_id'] = optional($employeeModel->getUser)->id;
                $notificationData['message'] = "Your " . $model->getType() . " request has been " . $statusTitle . " by " . $authorName;
                $notificationData['link'] = route('attendanceRequest.index');
                $notificationData['type'] = 'Attendance';
                $notificationData['type_id_value'] = $model->id;
                Notification::create($notificationData);

                if (isset($userModel->device)) {
                    $fields['include_player_ids'] = [optional($userModel->device)->os_player_id];
                    $fields['isIos'] = true;
                    $fields['isAndroid'] = true;
                    $message = $notificationData['message'];
                    OneSignal::sendPush($fields, $message);
                }

                // send email to employee
                if (emailSetting(2) == 11) {
                    $notified_user_email = User::getUserEmail(optional($employeeModel->getUser)->id);
                    if (isset($notified_user_email) && !empty($notified_user_email) && $model->enable_mail == 11) {
                        $notified_user_fullname = Employee::getName(optional($employeeModel->getUser)->id);
                        $details = array(
                            'email' => $notified_user_email,
                            'message' => "Your " . $model->getType() . " request has been " . $statusTitle . " by " . $authorName,
                            'notified_user_fullname' => $notified_user_fullname,
                            'setting' => Setting::first(),
                            'subject' => $subject,
                            'attendance_request' => $model
                        );
                        $mailArray[] = $details;
                    }
                }
            }
        }

        // check for first approval
        if (optional($employeeModel->employeeAttendanceApprovalFlow)->first_approval_user_id && $model->status == '1') {
            // create notification for first approval
            $notificationData['creator_user_id'] = $authUser->id;
            $notificationData['notified_user_id'] = optional($employeeModel->employeeAttendanceApprovalFlow)->first_approval_user_id;
            $notificationData['message'] = $employeeModel->full_name . "'s " . $model->getType() . " request has been " . $statusTitle . " by " . $authorName;
            $notificationData['link'] = route('attendanceRequest.showTeamAttendance');
            $notificationData['type'] = 'Attendance';
            $notificationData['type_id_value'] = $model->id;
            Notification::create($notificationData);

            // send notification in phone
            if (optional(optional($employeeModel->employeeAttendanceApprovalFlow)->userFirstApproval)->device) {
                $fields['include_player_ids'] = [optional(optional(optional($employeeModel->employeeAttendanceApprovalFlow)->userFirstApproval)->device)->os_player_id];
                $fields['isIos'] = true;
                $fields['isAndroid'] = true;
                $message = $notificationData['message'];
                OneSignal::sendPush($fields, $message);
            }
            // send email to supervisor
            if (emailSetting(2) == 11) {
                $notified_user_email = User::getUserEmail(optional($employeeModel->employeeAttendanceApprovalFlow)->first_approval_user_id);
                if (isset($notified_user_email) && !empty($notified_user_email) && $model->enable_mail == 11) {
                    $notified_user_fullname = Employee::getName(optional($employeeModel->employeeAttendanceApprovalFlow)->first_approval_user_id);
                    $details = array(
                        'email' => $notified_user_email,
                        'message' => $employeeModel->full_name . "'s " . $model->getType() . " request has been " . $statusTitle . " by " . $authorName,
                        'notified_user_fullname' => $notified_user_fullname,
                        'setting' => Setting::first(),
                        'subject' => $subject,
                        'attendance_request' => $model
                    );
                    $mailArray[] = $details;
                }
            }
        }

        // check for last approval
        if (optional($employeeModel->employeeAttendanceApprovalFlow)->last_approval_user_id && ($model->status == '2' || ($singleApproval == true && $model->status == '1'))) {
            // create notification for last approval
            $notificationData['creator_user_id'] = $authUser->id;
            $notificationData['notified_user_id'] = optional($employeeModel->employeeAttendanceApprovalFlow)->last_approval_user_id;
            $notificationData['message'] = $employeeModel->full_name . "'s " . $model->getType() . " request has been " . $statusTitle . " by " . $authorName;
            $notificationData['link'] = route('attendanceRequest.showTeamAttendance');
            $notificationData['type'] = 'Attendance';
            $notificationData['type_id_value'] = $model->id;
            Notification::create($notificationData);

            // send notification in phone
            if (optional(optional($employeeModel->employeeAttendanceApprovalFlow)->userLastApproval)->device) {
                $fields['include_player_ids'] = [optional(optional(optional($employeeModel->employeeAttendanceApprovalFlow)->userLastApproval)->device)->os_player_id];
                $fields['isIos'] = true;
                $fields['isAndroid'] = true;
                $message = $notificationData['message'];
                OneSignal::sendPush($fields, $message);
            }

            // send email to last approval
            if (emailSetting(2) == 11) {
                $notified_user_email = User::getUserEmail(optional($employeeModel->employeeAttendanceApprovalFlow)->last_approval_user_id);
                if (isset($notified_user_email) && !empty($notified_user_email) && $model->enable_mail == 11) {
                    $notified_user_fullname = Employee::getName(optional($employeeModel->employeeAttendanceApprovalFlow)->last_approval_user_id);
                    $details = array(
                        'email' => $notified_user_email,
                        'message' => $employeeModel->full_name . "'s " . $model->getType() . " request has been " . $statusTitle . " by " . $authorName,
                        'notified_user_fullname' => $notified_user_fullname,
                        'setting' => Setting::first(),
                        'subject' => $subject,
                        'attendance_request' => $model
                    );
                    $mailArray[] = $details;
                }
            }
        }

        // check for all hr roles
        $hrs = User::where('user_type', 'hr')->get();
        if (isset($hrs) && !empty($hrs)) {
            foreach ($hrs as $hr) {
                // create notification for hr
                $notificationData['creator_user_id'] = $authUser->id;
                $notificationData['notified_user_id'] = $hr->id;
                $notificationData['message'] = $employeeModel->full_name . "'s " . $model->getType() . " request has been " . $statusTitle . " by " . $authorName;
                $notificationData['link'] = route('attendanceRequest.index');
                $notificationData['type'] = 'Attendance';
                $notificationData['type_id_value'] = $model->id;
                Notification::create($notificationData);

                // send notification in phone
                if ($hr->device) {
                    $fields['include_player_ids'] = [optional($hr->device)->os_player_id];
                    $fields['isIos'] = true;
                    $fields['isAndroid'] = true;
                    $message = $notificationData['message'];
                    OneSignal::sendPush($fields, $message);
                }

                // send email to all hr
                if (emailSetting(2) == 11) {
                    $notified_user_email = User::getUserEmail($hr->id);
                    if (isset($notified_user_email) && !empty($notified_user_email) && $model->enable_mail == 11) {
                        $notified_user_fullname = Employee::getName($hr->id);
                        $details = array(
                            'email' => $notified_user_email,
                            'message' => $employeeModel->full_name . "'s " . $model->getType() . " request has been " . $statusTitle . " by " . $authorName,
                            'notified_user_fullname' => $notified_user_fullname,
                            'setting' => Setting::first(),
                            'subject' => $subject,
                            'attendance_request' => $model
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
        })->where('user_type', 'division_hr')->get();

        if (isset($divisionHrs) && !empty($divisionHrs)) {
            foreach ($divisionHrs as $divisionHr) {
                // create notification for division hr
                $notificationData['creator_user_id'] = $authUser->id;
                $notificationData['notified_user_id'] = $divisionHr->id;
                $notificationData['message'] = $employeeModel->full_name . "'s " . $model->getType() . " request has been " . $statusTitle . " by " . $authorName;
                $notificationData['link'] = route('attendanceRequest.index');
                $notificationData['type'] = 'Attendance';
                $notificationData['type_id_value'] = $model->id;
                Notification::create($notificationData);

                // send notification in phone
                if ($divisionHr->device) {
                    $fields['include_player_ids'] = [optional($divisionHr->device)->os_player_id];
                    $fields['isIos'] = true;
                    $fields['isAndroid'] = true;
                    $message = $notificationData['message'];
                    OneSignal::sendPush($fields, $message);
                }

                // send email to all division hr
                if (emailSetting(2) == 11) {
                    $notified_user_email = User::getUserEmail($divisionHr->id);
                    if (isset($notified_user_email) && !empty($notified_user_email) && $model->enable_mail == 11) {
                        $notified_user_fullname = Employee::getName($divisionHr->id);
                        $details = array(
                            'email' => $notified_user_email,
                            'message' => $employeeModel->full_name . "'s " . $model->getType() . " request has been " . $statusTitle . " by " . $authorName,
                            'notified_user_fullname' => $notified_user_fullname,
                            'setting' => Setting::first(),
                            'subject' => $subject,
                            'attendance_request' => $model
                        );
                        $mailArray[] = $details;
                    }
                }
            }
        }

        //  Send all email at once
        if (count($mailArray) > 0) {
            $mail = new MailSender();
            foreach ($mailArray as $mailDetail) {
                try {
                    $mail->sendMail('admin::mail.attendance-request', $mailDetail);
                } catch (\Throwable $th) {
                    continue;
                }
            }
        }
        return true;
    }

    public function findTeamAttendance($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        // $empId = optional(User::where('id', auth()->user()->id)->first()->userEmployer)->id;
        // $firstApprovalEmps = EmployeeApprovalFlow::where('first_approval_user_id', $userId)->pluck('employee_id')->toArray();
        // $lastApprovalEmps = EmployeeApprovalFlow::where('last_approval_user_id', $userId)->pluck('employee_id')->toArray();
        // $mergeApprovalEmps = array_merge($firstApprovalEmps,$lastApprovalEmps);

        // $result = AttendanceRequest::when(true, function ($query) use ($mergeApprovalEmps) {
        //     if (auth()->user()->user_type == 'supervisor') {
        //         $query->orWhereIn('employee_id', $mergeApprovalEmps);
        //     }
        // })->orderBy($sort['by'], $sort['sort'])
        //     ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));;

        // return $result;

        $statusList = $this->getStatus();
        $user = auth()->user();
        $userId = $user->id;
        $usertype = $user->user_type;

        $firstApprovalEmps = EmployeeAttendanceApprovalFlow::where('first_approval_user_id', $userId)->pluck('last_approval_user_id', 'employee_id')->toArray();
        $firstApproval = AttendanceRequest::with('employee.employeeAttendanceApprovalFlow')->when(true, function ($query) use ($firstApprovalEmps, $user) {
            $query->whereHas('employee', function ($q) use ($user) {
                $q->where('organization_id', optional($user->userEmployer)->organization_id);
            });

            $query->whereIn('employee_id', array_keys($firstApprovalEmps));
            // $query->whereIn('status', [1, 3,4]);
            // $query->where('status', 1);
        })->get()->map(function ($approvals) use ($statusList, $usertype, $userId) {
            $approvalFlow = optional($approvals->employee)->employeeAttendanceApprovalFlow;
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
        // $lastApprovalEmps = EmployeeAttendanceApprovalFlow::where('last_approval_user_id', $userId)->pluck('first_approval_user_id', 'employee_id')->toArray();
        $lastApprovalEmps = EmployeeAttendanceApprovalFlow::where('last_approval_user_id', $userId)->select('first_approval_user_id', 'last_approval_user_id', 'employee_id')->get()->toArray();
        $lastApproval = [];
        if (count($lastApprovalEmps) > 0) {
            $lastApproval = AttendanceRequest::when(true, function ($query) use ($lastApprovalEmps, $user) {
                $query->whereHas('employee', function ($q) use ($user) {
                    $q->where('organization_id', optional($user->userEmployer)->organization_id);
                });
                $where = 'where';
                foreach ($lastApprovalEmps as $value) {

                    $query->$where(function ($query) use ($value, $where) {
                        $query->where('employee_id', $value['employee_id']);
                        if (is_null($value['first_approval_user_id'])) {
                            $query->whereIn('status', [1, 2, 3, 4, 5]);
                        } else {
                            $query->whereIn('status', [2, 3, 4, 5]);
                        }
                    });
                    $where = 'orWhere';
                }
            })->get()->map(function ($approvals) use ($statusList, $usertype, $userId) {
                $approvalFlow = optional($approvals->employee)->employeeAttendanceApprovalFlow;
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

        if (isset($filter['date_range'])) {
            $filterDates = explode(' - ', $filter['date_range']);
            $result = $result->where('date', '>=', $filterDates[0]);
            $result = $result->where('date', '<=', $filterDates[1]);
        }
        if (isset($filter['type']) && !empty($filter['type'])) {
            $result = $result->where('type', $filter['type']);
        }
        if (isset($filter['status']) && !empty($filter['status'])) {
            $result = $result->where('status', $filter['status']);
        }
        // $result = $result->where('parent_id', null);

        $result = paginate($result, 20, '', ['path' => request()->url()]);
        return $result;
    }

    public function checkRequestExists($data)
    {
        $status = [1, 2, 3]; //except rejected and cancelled
        return AttendanceRequest::where('date', $data['date'])->where('employee_id', $data['empId'])->where('type', $data['requestType'])->whereIn('status', $status)->exists();
    }

    public function returnBackDeductedLeave($data)
    {
        $model = Leave::where([
            'organization_id' => $data['org_id'],
            'employee_id' => $data['emp_id'],
            'date' => $data['date'],
            'generated_leave_type' => $data['generated_leave_type'],
            'generated_by' => 11
        ])->first();

        if ($model) {
            $model->status = 4;
            $model->save();

            $inputData['employee_id'] = $model->employee_id;
            $inputData['leave_type_id'] = $model->leave_type_id;
            $inputData['numberOfDays'] = $model->generated_no_of_days;
            EmployeeLeave::updateRemainingLeave($inputData, 'ADD');
        }
    }
}
