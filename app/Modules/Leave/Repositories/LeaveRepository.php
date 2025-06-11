<?php

namespace App\Modules\Leave\Repositories;

use App\Helpers\DateTimeHelper;
use App\Modules\Admin\Entities\MailSender;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\EmployeeApprovalFlow;
use App\Modules\Employee\Entities\EmployeeDayOff;
use App\Modules\Employee\Entities\EmployeeLeave;
use App\Modules\Employee\Entities\EmployeeSubstituteLeave;
use App\Modules\LeaveYearSetup\Entities\LeaveYearSetup;
use App\Modules\Holiday\Entities\HolidayDetail;
use App\Modules\Leave\Entities\EmployeeLeaveOpening;
use App\Modules\Leave\Entities\Leave;
use App\Modules\Leave\Entities\LeaveAttachment;
use App\Modules\Leave\Entities\LeaveEncashmentLog;
use App\Modules\Leave\Entities\LeaveEncashmentLogActivity;
use App\Modules\Leave\Entities\LeaveOverview;
use App\Modules\Leave\Entities\LeaveType;
use App\Modules\Notification\Entities\Notification;
use App\Modules\Setting\Entities\Setting;
use App\Modules\User\Entities\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ladumor\OneSignal\OneSignal;

class LeaveRepository implements LeaveInterface
{
    public function getList()
    {
        return Leave::pluck('name', 'id');
    }

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $userId = auth()->user()->id;
        $empId = optional(User::where('id', auth()->user()->id)->first()->userEmployer)->id;
        $forwarded_status = 2;
        $accepted_status = 3;
        $rejected_status = 4;

        $authUser = auth()->user();
        // if ($authUser->user_type == 'division_hr') {
        //     $filter['organization_id'] = optional($authUser->userEmployer)->organization_id;
        // }
        $leaveModel = Leave::query();
        $leaveModel->when(true, function ($query) use ($filter, $empId, $forwarded_status, $accepted_status, $rejected_status, $userId) {
            if (isset($filter['isParent']) && !empty($filter['isParent'])) {
                $query->where('parent_id', null);
            }

            if (isset($filter['date_range'])) {
                $filterDates = explode(' - ', $filter['date_range']);
                $query->where('date', '>=', $filterDates[0]);
                $query->where('date', '<=', $filterDates[1]);
            }

            if (isset($filter['branch_id']) && !empty($filter['branch_id'])) {
                $query->whereHas('employeeModel', function ($qry) use ($filter) {
                    $qry->whereHas('branchModel', function ($q) use ($filter) {
                        $q->where('id', $filter['branch_id']);
                    });
                });
            }

            if (isset($filter['from_nep_date']) && !empty($filter['from_nep_date'])) {
                $query->where('nepali_date', '>=', $filter['from_nep_date']);
            }

            if (isset($filter['to_nep_date']) && !empty($filter['to_nep_date'])) {
                $query->where('nepali_date', '<=', $filter['to_nep_date']);
            }

            if (isset($filter['organization_id']) && !empty($filter['organization_id'])) {
                $query->where('organization_id', $filter['organization_id']);
            }

            if (isset($filter['employee_id']) && !empty($filter['employee_id'])) {
                $query->where('employee_id', $filter['employee_id']);
            }

            if (isset($filter['leave_type_id']) && !empty($filter['leave_type_id'])) {
                $query->where('leave_type_id', $filter['leave_type_id']);
            }

            if (isset($filter['leave_kind']) && !empty($filter['leave_kind'])) {
                $query->where('leave_kind', $filter['leave_kind']);
            }

            if (isset($filter['status']) && !empty($filter['status'])) {
                $query->where('status', $filter['status']);
            }

            if (isset($filter['show_on_employee']) && !empty($filter['show_on_employee'])) {
                $query->whereHas('leaveTypeModel', function ($qry) use ($filter) {
                    $qry->where('show_on_employee', $filter['show_on_employee']);
                });
            }

            if (isset($filter['leave_year_id']) && !empty($filter['leave_year_id'])) {
                $query->whereHas('leaveTypeModel', function ($qry) use ($filter) {
                    $qry->where('leave_year_id', $filter['leave_year_id']);
                });
            }
            if (auth()->user()->user_type == 'employee' || auth()->user()->user_type == 'supervisor') {
                //supervisor logic changes
                $query->where('employee_id', $empId);
            } elseif (auth()->user()->user_type == 'division_hr') {
                // $divisionHrList = (employee_helper()->getParentEmployeeList('employee'));
                // $divArrayList = array_keys($divisionHrList);
                // (array_push($divArrayList, (int) auth()->user()->emp_id));
                // $query->whereIn('employee_id', $divArrayList);
                $query->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
            } elseif (auth()->user()->user_type == 'hr' || auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'super_admin') {
                $query->where('employee_id', '!=', null);
            }
            // elseif (auth()->user()->user_type == 'supervisor') {
            //     $firstApprovalEmps = EmployeeApprovalFlow::where('first_approval_user_id', $userId)->pluck('employee_id')->toArray();
            //     $lastApprovalEmps = EmployeeApprovalFlow::where('last_approval_user_id', $userId)->pluck('employee_id')->toArray();
            //     $query->orWhere('employee_id', $empId);
            //     $query->orWhereIn('employee_id', $firstApprovalEmps)->whereNotIn('status', [$accepted_status, $rejected_status]);
            //     $query->orWhereIn('employee_id', $lastApprovalEmps)->where('status', $forwarded_status);
            // }
            else {
                $query->where('employee_id', $empId);
            }
        })
            // ->where('parent_id', null)
            ->orderBy($sort['by'], $sort['sort']);
        if (isset($filter['is_export']) && !empty($filter['is_export'])) {
            $result = $leaveModel->get();
        } else {
            $result = $leaveModel->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        }
        return $result;
    }

    public function findOne($id)
    {
        return Leave::find($id);
    }

    public function create($data)
    {
        $model = Leave::create($data);
        if ($model) {
            $leaveTypeModel = $model->leaveTypeModel->where('code', 'SUBLV')->first();
            if ($leaveTypeModel) {
                EmployeeSubstituteLeave::where([
                    'date' => $model->substitute_date,
                    'employee_id' => $model->employee_id
                ])->update(['is_expired' => 11]);
            }
        }
        return $model;

        // $check_rejected_leave =$this->checkData($data);
        // // dd($check_rejected_leave);
        // if ($check_rejected_leave && in_array($check_rejected_leave->status, [1, 2, 3])) {
        //     toastr()->warning('Some days are rejected due to duplicate entry.');
        // } else {
        //     // dd('asd');
        //     $leave_data= Leave::create($data);
        //     $this->sendMailNotification($leave_data);
        //     toastr()->success('Leave Created Successfully');
        //     // return true;
        // }
    }

    public function update($id, $data)
    {
        $result = $this->findOne($id);

        return $result->update($data);
    }

    public function delete($id)
    {
        $leaveModel = Leave::find($id);
        $leaveModel->childs()->delete();
        $leaveModel->delete();
        return true;
        // return Leave::destroy($id);
    }

    public function upload($file)
    {
        // $imageName = $file->getClientOriginalName();
        // $fileName = time() . '-' . preg_replace('[ ]', '-', $imageName);
        // $file->move(public_path() . '/' . Leave::IMAGE_PATH, $fileName);

        // return $fileName;
    }

    public function checkData($params)
    {
        return Leave::where([
            'employee_id' => $params['employee_id'],
            // 'leave_type_id' => $params['leave_type_id'],
            'date' => $params['date']
        ])->whereNotIn('status', [3,4, 5])->latest()->first();
    }

    public function getEmployeeLeaves($employeeId = null, $limit = null)
    {
        $activeUserModel = Auth::user();
        $query = Leave::query();
        $query->select('*')
            ->addSelect(DB::raw("'leave' as type"));

        // if (isset($filter['leave_year_id']) && !empty($filter['leave_year_id'])) {
        $query->whereHas('leaveTypeModel', function ($qry) {
            $qry->where('leave_year_id', getCurrentLeaveYearId());
        });
        // }

        if ($employeeId) {
            $employeeModel = Employee::find($employeeId);
            if ($employeeModel) {
                $query->where('organization_id', $employeeModel->organization_id);
                $query->where('employee_id', $employeeModel->id);
            }
        }

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
            $query->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
        }

        $query->where('parent_id', null);

        $result = $query->orderBy('id', 'DESC')->take($limit ? $limit : env('DEF_PAGE_LIMIT', 9999))->get()->map(function ($leave) {
            $leave->title = optional($leave->leaveTypeModel)->name;
            return $leave;
        });
        return $result;
    }

    public function getEmployeeApprovalFlow($employee_id)
    {
        $resp = EmployeeApprovalFlow::where('employee_id', $employee_id)->first();
        return $resp;
    }

    public function getStatus()
    {
        return Leave::statusList();
    }

    public function findTeamleaves($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {

        // Only for supervisor roles
        $userId = auth()->user()->id;
        $statusList = $this->getStatus();
        $user = auth()->user();
        $usertype = $user->user_type;

        $firstApprovalEmps = EmployeeApprovalFlow::where('first_approval_user_id', $userId)->pluck('last_approval_user_id', 'employee_id')->toArray();
        $firstApproval = Leave::with('employeeModel.employeeApprovalFlowRelatedDetailModel')->when(true, function ($query) use ($firstApprovalEmps, $user) {
            $query->whereIn('employee_id', array_keys($firstApprovalEmps));
            // $query->where('organization_id', optional($user->userEmployer)->organization_id);
        })->get()->map(function ($approvals) use ($statusList, $usertype) {
            $approvalFlow = optional($approvals->employeeModel)->employeeApprovalFlowRelatedDetailModel;
            if ($usertype == 'supervisor') {
                if (!$approvalFlow->last_approval_user_id || $approvals->status == 1) {
                    unset($statusList[3]);
                }
            }
            $approvals->status_list = json_encode($statusList);
            return $approvals;
        });

        $lastApprovalEmps = EmployeeApprovalFlow::where('last_approval_user_id', $userId)->select('first_approval_user_id', 'last_approval_user_id', 'employee_id')->get()->toArray();
        $lastApproval = [];
        if (count($lastApprovalEmps) > 0) {
            $lastApproval = Leave::when(true, function ($query) use ($lastApprovalEmps, $user) {
                // $query->where('organization_id', optional($user->userEmployer)->organization_id);

                $where = 'where';
                foreach ($lastApprovalEmps as $value) {

                    $query->$where(function ($query) use ($value, $where) {
                        $query->where('employee_id', $value['employee_id']);
                        if (is_null($value['first_approval_user_id'])) {
                            $query->whereIn('status', [1, 2, 3, 4]);
                        } else {
                            $query->whereIn('status', [2, 3, 4]);
                        }
                    });
                    $where = 'orWhere';
                }
            })->get()->map(function ($approvals) use ($statusList, $usertype) {
                if ($usertype == 'supervisor') {
                    if ($approvals->status == 1) {
                        unset($statusList[2]);
                    } elseif ($approvals->status == 2) {
                        unset($statusList[1]);
                    }
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
        if (isset($filter['employee_id']) && !empty($filter['employee_id'])) {
            $result = $result->where('employee_id', $filter['employee_id']);
        }
        if (isset($filter['leave_type_id']) && !empty($filter['leave_type_id'])) {
            $result = $result->where('leave_type_id', $filter['leave_type_id']);
        }
        if (isset($filter['leave_kind']) && !empty($filter['leave_kind'])) {
            $result = $result->where('leave_kind', $filter['leave_kind']);
        }
        if (isset($filter['status']) && !empty($filter['status'])) {
            $result = $result->where('status', $filter['status']);
        }
        $result = $result->where('parent_id', null);

        $result = paginate($result, 20, '', ['path' => request()->url()]);
        return $result;
    }

    public function getApprovedTodayLeave()
    {
        $user =    auth()->user();
        $result = Leave::when(true, function ($query) use ($user) {
            $query->where('status', 3);
            $query->where('date', date('Y-m-d'));

            if ($user->user_type == 'employee' || $user->user_type == 'supervisor' || $user->user_type == 'division_hr') {
                $query->where('organization_id', optional($user->userEmployer)->organization_id);
            }

            // if (auth()->user()->user_type == 'employee') { //employee logic changes
            //     $employee = Employee::findOrFail($user->emp_id);

            //     $query->whereHas('employeeModel', function ($q) use ($employee) {
            //         $q->where('organization_id', $employee->organization_id);
            //     });
            // }

            // if (auth()->user()->user_type == 'supervisor') {
            //     $getSubordinates = Employee::getSubordinates($user->id);
            //     $query->whereIn('employee_id', $getSubordinates);
            // }
        })->get()->map(function ($leave) {
            $leave->emp_name =  optional($leave->employeeModel)->getFullName();
            return $leave;
        });
        return $result;
    }

    public function sendMailNotification($model)
    {
        $authUser = auth()->user();
        $leaveLink = route('leave.index');
        $teamLeaveLink = route('leave.showTeamleaves');

        $leaveTypeModel = LeaveType::find($model['leave_type_id']);
        $employeeModel = Employee::find($model->employee_id);
        $userModel = optional($employeeModel->getUser);
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
            $statusTitle = 'Created';
        } else {
            $statusTitle = $model->getStatus();
        }
        $subject = 'Leave ' . $statusTitle . ' for ' . $employeeModel->full_name . ' (' . $employeeModel->employee_code . ')';


        $mailArray = [];
        if (optional($employeeModel->getUser)->id) {
            if ($authUser->id != optional($employeeModel->getUser)->id && ($model->status == '1' || $model->status == '3' || $model->status == '4')) {
                // create notification for employee user
                $notificationData['creator_user_id'] = $authUser->id;
                $notificationData['notified_user_id'] = $userModel->id;
                $notificationData['message'] = "Your " . $leaveTypeModel->name . " has been " . $statusTitle . " by " . $authorName;
                $notificationData['link'] = $leaveLink;
                $notificationData['type'] = 'Leave';
                $notificationData['type_id_value'] = $model->id;
                Notification::create($notificationData);

                // send notification in phone
                if ($userModel->device) {
                    $fields['include_player_ids'] = [optional($userModel->device)->os_player_id];
                    $fields['isIos'] = true;
                    $fields['isAndroid'] = true;
                    $message = $notificationData['message'];
                    OneSignal::sendPush($fields, $message);
                }

                if (emailSetting(1) == 11) {
                    // send email to employee who needs leave
                    $notified_user_email = User::getUserEmail(optional($employeeModel->getUser)->id);
                    if (isset($notified_user_email) && !empty($notified_user_email) && $model->enable_mail == 11) {
                        $notified_user_fullname = Employee::getName(optional($employeeModel->getUser)->id);

                        $leaveTypeWithLink = "<a href='$leaveLink'>$leaveTypeModel->name</a>";

                        $details = array(
                            'email' => $notified_user_email,
                            'message' => "Your " . $leaveTypeWithLink . " has been " . $statusTitle . " by " . $authorName,
                            'notified_user_fullname' => $notified_user_fullname,
                            'setting' => Setting::first(),
                            'leave' => $model,
                            'subject' => $subject
                        );
                        $mailArray[] = $details;
                    }
                }
            }
        }

        // check for first approval
        if (optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->first_approval_user_id && $model->status == '1') {
            // create notification for first approval
            $notificationData['creator_user_id'] = $authUser->id;
            $notificationData['notified_user_id'] = optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->first_approval_user_id;
            $notificationData['message'] = $employeeModel->full_name . "'s " . $leaveTypeModel->name . " has been " . $statusTitle . " by " . $authorName;
            $notificationData['link'] = $teamLeaveLink;
            $notificationData['type'] = 'Leave';
            $notificationData['type_id_value'] = $model->id;
            Notification::create($notificationData);

            // send notification in phone
            if (optional(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->userFirstApproval)->device) {
                $fields['include_player_ids'] = [optional(optional(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->userFirstApproval)->device)->os_player_id];
                $fields['isIos'] = true;
                $fields['isAndroid'] = true;
                $message = $notificationData['message'];
                OneSignal::sendPush($fields, $message);
            }

            // send email to supervisor
            if (emailSetting(1) == 11) {
                $notified_user_email = User::getUserEmail(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->first_approval_user_id);
                if (isset($notified_user_email) && !empty($notified_user_email) && $model->enable_mail == 11) {
                    $notified_user_fullname = Employee::getName(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->first_approval_user_id);

                    $leaveTypeWithLink = "<a href='$teamLeaveLink'>$leaveTypeModel->name</a>";

                    $details = array(
                        'email' => $notified_user_email,
                        'message' => $employeeModel->full_name . "'s " . $leaveTypeWithLink . " has been " . $statusTitle . " by " . $authorName,
                        'notified_user_fullname' => $notified_user_fullname,
                        'setting' => Setting::first(),
                        'leave' => $model,
                        'subject' => $subject
                    );
                    $mailArray[] = $details;
                }
            }
        }

        // check for last approval
        if (optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->last_approval_user_id && ($model->status == '2' || ($singleApproval == true && $model->status == '1'))) {
            // create notification for last approval
            $notificationData['creator_user_id'] = $authUser->id;
            $notificationData['notified_user_id'] = optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->last_approval_user_id;
            $notificationData['message'] = $employeeModel->full_name . "'s " . $leaveTypeModel->name . " has been " . $statusTitle . " by " . $authorName;
            $notificationData['link'] = $teamLeaveLink;
            $notificationData['type'] = 'Leave';
            $notificationData['type_id_value'] = $model->id;
            Notification::create($notificationData);

            // send notification in phone
            if (optional(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->userLastApproval)->device) {
                $fields['include_player_ids'] = [optional(optional(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->userLastApproval)->device)->os_player_id];
                $fields['isIos'] = true;
                $fields['isAndroid'] = true;
                $message = $notificationData['message'];
                OneSignal::sendPush($fields, $message);
            }

            // send email to last approval
            if (emailSetting(1) == 11) {
                $notified_user_email = User::getUserEmail(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->last_approval_user_id);
                if (isset($notified_user_email) && !empty($notified_user_email) && $model->enable_mail == 11) {
                    $notified_user_fullname = Employee::getName(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->last_approval_user_id);

                    $leaveTypeWithLink = "<a href='$teamLeaveLink'>$leaveTypeModel->name</a>";

                    $details = array(
                        'email' => $notified_user_email,
                        'message' => $employeeModel->full_name . "'s " . $leaveTypeWithLink . " has been " . $statusTitle . " by " . $authorName,
                        'notified_user_fullname' => $notified_user_fullname,
                        'setting' => Setting::first(),
                        'leave' => $model,
                        'subject' => $subject
                    );
                    $mailArray[] = $details;
                }
            }
        }

        // check for all hr roles
        $hrs = User::where('user_type', 'hr')->get();
        if (isset($hrs) && !empty($hrs)) {
            foreach ($hrs as $hr) {
                // create notification for all hr
                $notificationData['creator_user_id'] = $authUser->id;
                $notificationData['notified_user_id'] = $hr->id;
                $notificationData['message'] = $employeeModel->full_name . "'s " . $leaveTypeModel->name . " has been " . $statusTitle . " by " . $authorName;
                $notificationData['link'] = $leaveLink;
                $notificationData['type'] = 'Leave';
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
                if (emailSetting(1) == 11) {
                    $notified_user_email = User::getUserEmail($hr->id);
                    if (isset($notified_user_email) && !empty($notified_user_email) && $model->enable_mail == 11) {
                        $notified_user_fullname = Employee::getName($hr->id);

                        $leaveTypeWithLink = "<a href='$leaveLink'>$leaveTypeModel->name</a>";

                        $details = array(
                            'email' => $notified_user_email,
                            'message' => $employeeModel->full_name . "'s " . $leaveTypeWithLink . " has been " . $statusTitle . " by " . $authorName,
                            'notified_user_fullname' => $notified_user_fullname,
                            'setting' => Setting::first(),
                            'leave' => $model,
                            'subject' => $subject
                        );
                        $mailArray[] = $details;
                    }
                }
            }
        }

        // check for division hr roles
        $divisionHrs = User::when(true, function ($query) use ($employeeModel) {
            $query->whereHas('userEmployer', function ($q) use ($employeeModel) {
                $q->where('organization_id', $employeeModel->organization_id)->where('status', 1);
            });
        })->where('user_type', 'division_hr')->get();

        if (isset($divisionHrs) && !empty($divisionHrs)) {
            foreach ($divisionHrs as $divisionHr) {
                // create notification for all division Hr
                $notificationData['creator_user_id'] = $authUser->id;
                $notificationData['notified_user_id'] = $divisionHr->id;
                $notificationData['message'] = $employeeModel->full_name . "'s " . $leaveTypeModel->name . " has been " . $statusTitle . " by " . $authorName;
                $notificationData['link'] = $leaveLink;
                $notificationData['type'] = 'Leave';
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

                // send email to all division Hr
                if (emailSetting(1) == 11) {
                    $notified_user_email = User::getUserEmail($divisionHr->id);
                    if (isset($notified_user_email) && !empty($notified_user_email) && $model->enable_mail == 11) {
                        $notified_user_fullname = Employee::getName($divisionHr->id);

                        $leaveTypeWithLink = "<a href='$leaveLink'>$leaveTypeModel->name</a>";

                        $details = array(
                            'email' => $notified_user_email,
                            'message' => $employeeModel->full_name . "'s " . $leaveTypeWithLink . " has been " . $statusTitle . " by " . $authorName,
                            'notified_user_fullname' => $notified_user_fullname,
                            'setting' => Setting::first(),
                            'leave' => $model,
                            'subject' => $subject
                        );
                        $mailArray[] = $details;
                    }
                }
            }
        }

        if (isset($model->alt_employee_id) && $model->status == '3') {
            $alternateEmployeeModel = Employee::find($model->alt_employee_id);

            // create notification for alternative employee
            $notificationData['creator_user_id'] = $authUser->id;
            $notificationData['notified_user_id'] = optional($alternateEmployeeModel->getUser)->id;
            $notificationData['message'] = "You have been assigned as alternative on behalf of " . $employeeModel->full_name;
            $notificationData['link'] = $leaveLink;
            $notificationData['type'] = 'Leave';
            $notificationData['type_id_value'] = $model->id;
            Notification::create($notificationData);

            // send notification in phone
            if ($alternateEmployeeModel->getUser) {
                $fields['include_player_ids'] = [optional(optional($alternateEmployeeModel->getUser)->device)->os_player_id];
                $fields['isIos'] = true;
                $fields['isAndroid'] = true;
                $message = $notificationData['message'];
                OneSignal::sendPush($fields, $message);
            }

            // send email to alternate employee
            if (emailSetting(1) == 11) {
                $notified_user_email = User::getUserEmail(optional($alternateEmployeeModel->getUser)->id);
                if (isset($notified_user_email) && !empty($notified_user_email) && $model->enable_mail == 11) {
                    $notified_user_fullname = Employee::getName(optional($alternateEmployeeModel->getUser)->id);
                    $details = array(
                        'email' => $notified_user_email,
                        'message' => "You have been assigned as alternative on behalf of " . $employeeModel->full_name,
                        'notified_user_fullname' => $notified_user_fullname,
                        'setting' => Setting::first(),
                        'leave' => $model,
                        'subject' => $subject
                    );
                    $mailArray[] = $details;
                }
            }
        }

        // Send all email at once
        if (count($mailArray) > 0) {
            $mail = new MailSender();
            foreach ($mailArray as $mailDetail) {
                try {
                    $mail->sendMail('admin::mail.leave', $mailDetail);
                } catch (\Throwable $th) {
                    continue;
                }
            }
        }

        return true;
    }

    public function countEmployeeLeaveStatus()
    {
        $returnLeave = [];
        $query = Leave::query();
        $query->select('status');
        if (auth()->user()->user_type == 'employee') {
            $employeeID = auth()->user()->emp_id;
            $query->where('employee_id', $employeeID);
        }
        $result = $query->get()->groupBy('status');

        $statusList = ($this->getStatus());
        foreach ($result as $key => $value) {
            $returnLeave[$statusList[$key]] = $value->count();
        }
        $returnLeave['Total'] = array_sum($returnLeave);
        return $returnLeave;
    }

    public function pluckEmployeeApproval()
    {
        $employeeApproval = $this->getEmployeeApprovalFlow(auth()->user()->emp_id);

        return [
            'firstApproval' => $employeeApproval->userFirstApproval,
            'lastApproval' => $employeeApproval->userLastApproval,
        ];
    }

    // public function preProcessData($request)
    // {
    //     $inputData = $request->all();
    //     $leaveTypeId = $inputData['params']['leaveType'];
    //     $startDate = $inputData['params']['startDate'];
    //     $employeeId = $inputData['params']['employeeId'];
    //     $maxDays = $inputData['params']['maxDays'];

    //     $leaveTypeModel = (new LeaveTypeRepository())->findOne($leaveTypeId);
    //     if ($maxDays <= 0) {
    //         $endDate = null;
    //     } else {

    //         if (isset($leaveTypeModel->max_per_day_leave)) {
    //             $newMaxDays = min($maxDays, $leaveTypeModel->max_per_day_leave);
    //             $adjustDays = (int)$newMaxDays - 1;
    //             $endDate = date('Y-m-d', strtotime('+ ' . $adjustDays . ' days', strtotime($startDate)));
    //         } else {
    //             $adjustDays = (int)$maxDays - 1;
    //             $endDate = date('Y-m-d', strtotime('+ ' . $adjustDays . ' days', strtotime($startDate)));
    //         }
    //         // dd($adjustDays,$endDate);

    //         $countHoliday = 0;
    //         if (($leaveTypeModel->sandwitch_rule_status == "10")) {
    //             $holidayModels = HolidayDetail::where('eng_date', '>=', $startDate)->where('eng_date', '<=', $endDate)->orderBy('eng_date', 'ASC')->get();
    //             if (count($holidayModels) > 0) {
    //                 foreach ($holidayModels as $holidayModel) {
    //                     $countHoliday++;
    //                 }
    //             }

    //             $countDayOff = 0;
    //             $employeeDayOffModels = EmployeeDayOff::where('employee_id', $employeeId)->get();
    //             if (count($employeeDayOffModels) > 0) {
    //                 foreach ($employeeDayOffModels as $employeeDayOffModel) {
    //                     $excludeDays[] = $employeeDayOffModel->day_off;
    //                 }
    //                 $j = 1;
    //                 $tempDate = Carbon::parse($startDate);
    //                 $numberOfDays = 0;
    //                 $numberOfDays = DateTimeHelper::DateDiffInDay($startDate, $endDate);
    //                 $numberOfDays += 1; // adjust data from proper calculation
    //                 $response['numberOfDays'] = $numberOfDays;
    //                 for ($i = 0; $i < $numberOfDays; $i++) {
    //                     if (in_array($tempDate->format('l'), $excludeDays)) {
    //                         $countDayOff++;
    //                     }
    //                     $tempDate = $tempDate->addDay($j);
    //                 }
    //             }
    //             $totalSandwichDays = $countHoliday + $countDayOff;
    //             $endDate = date('Y-m-d', strtotime('+ ' . $totalSandwichDays . ' days', strtotime($endDate)));
    //         }
    //     }

    //     $response['endDate'] = $endDate;

    //     return  json_encode($response);
    // }

    // public function postProcessData($request)
    // {
    //     $inputData = $request->all();
    //     $employeeId = $inputData['params']['employeeId'];
    //     $leaveTypeId = $inputData['params']['leaveType'];
    //     $startDate = $inputData['params']['startDate'];
    //     $endDate = $inputData['params']['endDate'];
    //     // dd($inputData);
    //     $numberOfDays = 0;
    //     $numberOfDays = DateTimeHelper::DateDiffInDay($startDate, $endDate);
    //     $numberOfDays += 1; // adjust data from proper calculation
    //     $response['numberOfDays'] = $numberOfDays;
    //     // dd($numberOfDays);

    //     $leaveTypeModel = (new LeaveTypeRepository())->findOne($leaveTypeId);
    //     $response['restrictSave'] = 'false';

    //     $countDayOff = 0;
    //     $offDates = [];
    //     $excludeDays = [];
    //     $calStartDate = Carbon::parse($startDate);
    //     $employeeDayOffModels = EmployeeDayOff::where('employee_id', $employeeId)->get();
    //     if (count($employeeDayOffModels) > 0) {
    //         foreach ($employeeDayOffModels as $employeeDayOffModel) {
    //             $excludeDays[] = $employeeDayOffModel->day_off;
    //         }
    //         $j = 1;
    //         $tempDate = $calStartDate;
    //         for ($i = 0; $i < $numberOfDays; $i++) {
    //             if (in_array($tempDate->format('l'), $excludeDays)) {
    //                 $countDayOff++;
    //                 $offDates[] = $tempDate->toDateString();
    //             }
    //             $tempDate = $calStartDate->addDay($j);
    //         }
    //         $data['dayOffs'] = implode(', ', $offDates);
    //     }

    //     $countHoliday = 0;
    //     $holidayDays = [];
    //     $holidayModels = HolidayDetail::where('eng_date', '>=', $startDate)->where('eng_date', '<=', $endDate)->orderBy('eng_date', 'ASC')->get();
    //     if (count($holidayModels) > 0) {
    //         foreach ($holidayModels as $holidayModel) {
    //             $countHoliday++;
    //             $holidayDays[] = $holidayModel->eng_date;
    //         }
    //         $numberOfDays -= $countHoliday;
    //         $data['holidays'] = implode(', ', $holidayDays);
    //     }

    //     $leaveDays = [];
    //     $LeaveModels = Leave::where('employee_id', $employeeId)->where('date', '>=', $startDate)->where('date', '<=', $endDate)->where('status', '!=', '4')->orderBy('date', 'ASC')->get();
    //     if (count($LeaveModels) > 0) {
    //         foreach ($LeaveModels as $LeaveModel) {
    //             $leaveDays[] = $LeaveModel->date;
    //         }
    //         $data['previousLeaves'] = implode(', ', $leaveDays);
    //         $response['restrictSave'] = "true";
    //     }

    //     if (isset($leaveTypeModel->pre_inform_days)) {
    //         $today = date('Y-m-d');
    //         $requiredRequestDate = date('Y-m-d', strtotime('+' . $leaveTypeModel->pre_inform_days . ' Days', strtotime($today)));
    //         if ($startDate >= $requiredRequestDate) {
    //             // do nothing
    //         } else {
    //             $data['preInformMessage'] = "You have to request before " . $leaveTypeModel->pre_inform_days . " days for this leave type";
    //             $response['restrictSave'] = "true";
    //         }
    //     }

    //     if (isset($leaveTypeModel->max_per_day_leave)) {
    //         if ($numberOfDays > $leaveTypeModel->max_per_day_leave) {
    //             $data['maxLeaveMessage'] = "Maximum number of days per request for this leave type is " . $leaveTypeModel->max_per_day_leave . " Days";
    //             $response['restrictSave'] = "true";
    //         }
    //     }

    //     if ($leaveTypeModel->sandwitch_rule_status == '11' && !empty($data['dayOffs'])) {
    //         $data['sandwitchMessage'] = 'Since this leave type has a sandwich rule, your leave will also be created on ' . $data['dayOffs'];
    //     }


    //     if ($leaveTypeModel->sandwitch_rule_status != '11') {
    //         $numberOfDays -= $countDayOff;
    //     }

    //     if ($response['restrictSave'] == 'false') {
    //         $data['finalMessage'] = "The total number of days you are applying is " . $numberOfDays;
    //     }

    //     $response['msg'] = $data;
    //     $response['noticeList'] = view('leave::leave.partial.notice-list', $data)->render();

    //     return  json_encode($response);
    // }

    public function preProcessData($request)
    {
        $inputData = $request->all();
        $leaveTypeId = $inputData['params']['leaveType'];
        $startDate =  $inputData['params']['startDate'];
        $employeeId = $inputData['params']['employeeId'];
        $maxDays = $inputData['params']['maxDays'];
        $leaveTypeModel = (new LeaveTypeRepository())->findOne($leaveTypeId);
        if ($maxDays <= 0) {
            $endDate = null;
        } else {

            if (isset($leaveTypeModel->max_per_day_leave)) {
                $newMaxDays = min($maxDays, $leaveTypeModel->max_per_day_leave);
                $adjustDays = (int)$newMaxDays - 1;
                $endDate = date('Y-m-d', strtotime('+ ' . $adjustDays . ' days', strtotime($startDate)));
            } else {
                $adjustDays = ((int)$maxDays < 1 && (int)$maxDays >= 0.5) ? 1 : (int)$maxDays - 1;
                // $adjustDays = (int)$maxDays - 1;
                $endDate = date('Y-m-d', strtotime('+ ' . $adjustDays . ' days', strtotime($startDate)));
            }

            $countHoliday = 0;
            if (($leaveTypeModel->sandwitch_rule_status == "10")) {
                $holidayModels = HolidayDetail::where('eng_date', '>=', $startDate)->where('eng_date', '<=', $endDate)->orderBy('eng_date', 'ASC')->get();
                if (count($holidayModels) > 0) {
                    foreach ($holidayModels as $holidayModel) {
                        $countHoliday++;
                    }
                }

                $countDayOff = 0;
                $employeeDayOffModels = EmployeeDayOff::where('employee_id', $employeeId)->get();
                if (count($employeeDayOffModels) > 0) {
                    foreach ($employeeDayOffModels as $employeeDayOffModel) {
                        $excludeDays[] = $employeeDayOffModel->day_off;
                    }
                    $j = 1;
                    $tempDate = Carbon::parse($startDate);
                    $numberOfDays = 0;
                    $numberOfDays = DateTimeHelper::DateDiffInDay($startDate, $endDate);
                    $numberOfDays += 1; // adjust data from proper calculation
                    $response['numberOfDays'] = $numberOfDays;
                    for ($i = 0; $i < $numberOfDays; $i++) {
                        if (in_array($tempDate->format('l'), $excludeDays)) {
                            $countDayOff++;
                        }
                        $tempDate = $tempDate->addDay($j);
                    }
                }
                $totalSandwichDays = $countHoliday + $countDayOff;
                $endDate = date('Y-m-d', strtotime('+ ' . $totalSandwichDays . ' days', strtotime($endDate)));
            }

            if ($leaveTypeModel->code == "SUBLV") {
                $response['numberOfDays'] = 1;
                $response['endDate'] =  $startDate;
                return  json_encode($response);
            }
        }
        // $response['endDate'] = $endDate;
        $response['endDate'] =  $endDate;

        return  json_encode($response);
    }

    /**
     * Ajax function
     * Post Process data
     */
    public function postProcessData($request)
    {
        $inputData = $request->all();
        $employeeId = $inputData['params']['employeeId'];
        $leaveTypeId = $inputData['params']['leaveType'];
        $leaveKind = $inputData['params']['leaveKind'];
        $maxDays = $inputData['params']['maxDays'];
        $endDate = $inputData['params']['endDate'];
        $startDate =  $inputData['params']['startDate'];

        $numberOfDays = 0;

        if ($leaveKind == 1 && $maxDays >= 0.5) {
            $numberOfDays = 0.5;
        } elseif ($leaveKind == 2 && $maxDays >= 1) {
            $numberOfDays = DateTimeHelper::DateDiffInDay($startDate, $endDate);
            $numberOfDays += 1; // adjust data from proper calculation
        }
        $response['numberOfDays'] = $numberOfDays;

        $leaveTypeModel = (new LeaveTypeRepository())->findOne($leaveTypeId);
        $response['restrictSave'] = 'false';

        $countDayOff = 0;
        $offDates = [];
        $excludeDays = [];
        $calStartDate = Carbon::parse($startDate);
        $employeeDayOffModels = EmployeeDayOff::where('employee_id', $employeeId)->get();
        if (count($employeeDayOffModels) > 0) {
            foreach ($employeeDayOffModels as $employeeDayOffModel) {
                $excludeDays[] = $employeeDayOffModel->day_off;
            }
            $j = 1;
            $tempDate = $calStartDate;
            for ($i = 0; $i < $numberOfDays; $i++) {
                if (in_array($tempDate->format('l'), $excludeDays)) {
                    $countDayOff++;
                    $tempDateString = $tempDate->toDateString();
                    $offDates[] =  $tempDateString;
                }
                $tempDate = $calStartDate->addDay($j);
            }
            $numberOfDays -= $countDayOff;
            $data['dayOffs'] = implode(', ', $offDates);
        }

        $countHoliday = 0;
        $holidayDays = [];
        $holidayModels = HolidayDetail::where('eng_date', '>=', $startDate)->where('eng_date', '<=', $endDate)->orderBy('eng_date', 'ASC')->get();
        if (count($holidayModels) > 0) {
            foreach ($holidayModels as $holidayModel) {
                $countHoliday++;
                $holidayDays[] =  $holidayModel->eng_date;
            }
            $numberOfDays -= $countHoliday;
            $data['holidays'] = implode(', ', $holidayDays);
        }

        $leaveDays = [];

        $LeaveModels = Leave::where('employee_id', $employeeId)->where('date', '>=', $startDate)->where('date', '<=', $endDate)->where('status', '!=', '4')->orderBy('date', 'ASC')->get();
        if (count($LeaveModels) > 0) {
            foreach ($LeaveModels as $LeaveModel) {
                $leaveDays[] =  $LeaveModel->date;
            }
            $data['previousLeaves'] = implode(', ', $leaveDays);
            $response['restrictSave'] = "true";
        }

        if (isset($leaveTypeModel->pre_inform_days)) {
            $today = date('Y-m-d');
            $requiredRequestDate = date('Y-m-d', strtotime('+' . $leaveTypeModel->pre_inform_days . ' Days', strtotime($today)));
            if ($startDate >= $requiredRequestDate) {
                // do nothing
            } else {
                $data['preInformMessage'] = "You have to request before " . $leaveTypeModel->pre_inform_days . " days for this leave type";
                $response['restrictSave'] = "true";
            }
        }

        if (isset($leaveTypeModel->max_per_day_leave)) {
            if ($numberOfDays > $leaveTypeModel->max_per_day_leave) {
                $data['maxLeaveMessage'] = "Maximum number of days per request for this leave type is " . $leaveTypeModel->max_per_day_leave . " Days";
                $response['restrictSave'] = "true";
            }
        }


        if ($leaveTypeModel->sandwitch_rule_status == '11') {
            $numberOfDays += ($countHoliday + $countDayOff);
            if ($data['dayOffs'] || count($holidayDays) > 0) {
                $data['sandwitchMessage'] = 'Since this leave type has a sandwich rule, your leave will also be created on ' . $data['dayOffs'] . ',' . implode(', ', $holidayDays);
            }
        }


        if ($numberOfDays > 0) {
            if ($response['restrictSave'] == 'false') {
                $data['finalMessage'] = "The total number of days you are applying is " . $numberOfDays;
            }
        } else {
            $response['restrictSave'] = "true";
        }
        $response['msg'] = $data;

        $response['noticeList'] = view('leave::leave.partial.notice-list', $data)->render();

        return  json_encode($response);
    }

    public function uploadAttachment($id, $file)
    {
        $fileDetail = LeaveAttachment::saveFile($file);
        $modelData['leave_id'] = $id;
        $modelData['title'] = $fileDetail['filename'];
        $modelData['extension'] = $fileDetail['extension'];
        $modelData['size'] = $fileDetail['size'];

        LeaveAttachment::create($modelData);
    }

    public function checkLeave($params)
    {
        return Leave::where($params);
    }

    public function employeeRemainingLeaveDetails($filter, $limit = '')
    {
        $currentLeaveYear = LeaveYearSetup::currentLeaveYear();
        if (empty($currentLeaveYear)) {
            toastr()->error('Please set Active Leave Year first !!!');
            return redirect()->route('leaveYearSetup.index');
        }

        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            $filter['organization_id'] = optional($authUser->userEmployer)->organization_id;
        }

        $query = Employee::query();
        if (isset($filter['organization_id']) && $filter['organization_id'] != '') {
            $query = $query->where('organization_id', $filter['organization_id']);
        }

        if (auth()->user()->user_type == 'employee') {
            $query = $query->where('id', auth()->user()->emp_id);
        } else if (auth()->user()->user_type == 'supervisor') {
            $authEmpId = array(intval(auth()->user()->emp_id));
            $employeeIds = Employee::getSubordinates(auth()->user()->id);
            $allEmployeeIds = array_merge($authEmpId, $employeeIds);
            $query = $query->whereIn('id', $allEmployeeIds);
        }



        $employees = $query->where('status', 1)->paginate($limit);
        $collection = $employees->getCollection();

        $filteredCollection = $collection->transform(function ($emp) use ($filter, $currentLeaveYear) {
            $leaveTypeId = isset($filter['leave_type_id']) ? $filter['leave_type_id'] : null;
            $data = [
                'employee_id' => $emp->id,
                'leave_year_id' => $currentLeaveYear->id,
                'leave_type_id' => $leaveTypeId
            ];

            $emp->previousLeaveRemaining = $this->totalAccumulatedLeave($data);

            $emp->currentLeaveYearLeaveOpening = $this->currentLeaveYearOpeningLeave($data);
            $emp->currentLeaveYearLeaveTaken = $this->currentLeaveYearLeaveTaken($data);
            $emp->currentLeaveYearLeaveBalance = $emp->previousLeaveRemaining + $emp->currentLeaveYearLeaveOpening - $emp->currentLeaveYearLeaveTaken;
            return $emp;
        });
        return $employees->setCollection($filteredCollection);
    }

    public function empRemainingLeaveDetailsLeaveTypewise($emp_id)
    {
        $filter = [];
        $employeeModel = Employee::find($emp_id);
        $leave_year = LeaveYearSetup::currentLeaveYear();
        $empLeave = [];

        if (auth()->user()->user_type == 'employee') {
            $filter['showStatus'] = 11;
        }

        $leaveTypeModels = LeaveType::when(true, function ($query) use ($filter, $employeeModel) {
            $query->where('status', 11);
            $query->where('organization_id', $employeeModel->organization_id);
            if (isset($filter['showStatus']) && !empty($filter['showStatus'])) {
                $query->where('show_on_employee', $filter['showStatus']);
            }

            $query->where(function ($qry) use ($employeeModel) {
                $qry->where('gender', $employeeModel->gender);
                $qry->orWhere('gender', null);
            });

            $query->where(function ($qry) use ($employeeModel) {
                $qry->where('marital_status', $employeeModel->marital_status);
                $qry->orWhere('marital_status', null);
            });
        })->get();

        if (count($leaveTypeModels) > 0) {
            foreach ($leaveTypeModels as $leaveTypeModel) {
                $data = [
                    'employee_id' => $employeeModel->id,
                    'leave_year_id' => $leave_year->id,
                    'leave_type_id' => $leaveTypeModel->id
                ];
                $empLeave[$leaveTypeModel->id]['leaveTypeName'] = $leaveTypeModel->name;

                $empLeave[$leaveTypeModel->id]['previousLeaveRemaining'] = $this->totalAccumulatedLeave($data);
                $empLeave[$leaveTypeModel->id]['currentLeaveYearLeaveOpening'] = $this->currentLeaveYearOpeningLeave($data);
                $empLeave[$leaveTypeModel->id]['currentLeaveYearLeaveTaken'] = $this->currentLeaveYearLeaveTaken($data);
                $empLeave[$leaveTypeModel->id]['currentLeaveYearLeaveBalance'] = $empLeave[$leaveTypeModel->id]['previousLeaveRemaining'] + $empLeave[$leaveTypeModel->id]['currentLeaveYearLeaveOpening'] - $empLeave[$leaveTypeModel->id]['currentLeaveYearLeaveTaken'];
            }
        }
        return $empLeave;
    }

    public function currentLeaveYearOpeningLeave($data)
    {
        $empOpeningLeave = EmployeeLeaveOpening::where('leave_year_id', $data['leave_year_id'])->where('employee_id', $data['employee_id'])->where('leave_type_id', $data['leave_type_id'])->first();
        $openingLeaveCount = 0;
        if ($empOpeningLeave) {
            $openingLeaveCount = $empOpeningLeave->opening_leave;
        }
        return $openingLeaveCount;
    }

    public function currentLeaveYearLeaveTaken($data)
    {
        $query = Leave::query();
        $query->where('employee_id', $data['employee_id'])->where('leave_type_id', $data['leave_type_id'])->whereNotIn('status', [4, 5]);

        $leaveTaken =  $query->whereHas('leaveTypeModel', function ($q) use ($data) {
            $q->where('leave_year_id', $data['leave_year_id']);
        })->selectRaw('SUM(CASE WHEN leave_kind = 1 THEN 0.5 ELSE 1 END) as total_leaves')
            ->first()
            ->total_leaves;
        return $leaveTaken;
    }
    public function totalAccumulatedLeave($data)
    {
        $leaveOverview = LeaveOverview::where('employee_id', $data['employee_id'])->where('leave_type_id', $data['leave_type_id'])->first();
        $totalRemainingLeave = 0;
        if ($leaveOverview) {
            $totalRemainingLeave = $leaveOverview->previous_remaining_leave;
        }
        return $totalRemainingLeave;
    }


    public function leaveEncashmentLogs($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $leaveEncashmentLogs = LeaveEncashmentLog::query();
        $leaveEncashmentLogs->when(true, function ($query) use ($filter) {
            if (isset($filter['organization_id']) && !empty($filter['organization_id'])) {
                $query->whereHas('employee', function ($q) use ($filter) {
                    $q->where('organization_id', $filter['organization_id']);
                });
            }
        })
            ->orderBy($sort['by'], $sort['sort']);
        if (isset($filter['is_export']) && !empty($filter['is_export'])) {
            $result = $leaveEncashmentLogs->get();
        } else {
            $result = $leaveEncashmentLogs->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        }
        return $result;
    }

    public function leaveEncashmentLogsActivity($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $leaveEncashmentLogsActivity = LeaveEncashmentLogActivity::query();
        $leaveEncashmentLogsActivity->when(true, function ($query) use ($filter) {
            $query->whereHas('leaveEncashmentLog',function($q){
                $q->whereHas('employee',function($item){
                    $item->where('archived_date','<=',Carbon::now()->format('Y-m-d'));
                });
            });
            // if (isset($filter['organization_id']) && !empty($filter['organization_id'])) {
            //     $query->whereHas('employee', function($q) use($filter){
            //         $q->where('organization_id', $filter['organization_id']);
            //     });
            // }
        })
            ->orderBy($sort['by'], $sort['sort']);
        if (isset($filter['is_export']) && !empty($filter['is_export'])) {
            $result = $leaveEncashmentLogsActivity->get();
        } else {
            $result = $leaveEncashmentLogsActivity->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        }
        return $result;
    }
}
