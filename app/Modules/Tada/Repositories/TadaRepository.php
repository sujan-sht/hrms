<?php

namespace App\Modules\Tada\Repositories;

use App\Modules\Admin\Entities\MailSender;
use File;
use App\Modules\Tada\Entities\Tada;
use App\Modules\User\Entities\User;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Notification\Entities\Notification;
use App\Modules\Employee\Entities\EmployeeApprovalFlow;
use App\Modules\Employee\Entities\EmployeeClaimRequestApprovalFlow;
use App\Modules\Setting\Entities\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * BillRepository
 */
class TadaRepository implements TadaInterface
{
    public function findAll($limit = null, $filter, $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1])
    {
        $pending_status = 1;
        $forwarded_status = 2;
        $accepted_status = 3;
        $rejected_status = 4;
        $fully_settled = 5;
        $partially_settled = 6;

        $userId = auth()->user()->id;
        $empId = optional(User::where('id', auth()->user()->id)->first()->userEmployer)->id;
        // $result = Tada::when(array_keys($filter, true), function ($query) use ($filter) {
        $result = Tada::when(true, function ($query) use ($filter, $empId, $userId, $pending_status, $forwarded_status, $accepted_status, $rejected_status, $fully_settled, $partially_settled) {
            if (isset($filter['organization_id']) && !empty($filter['organization_id'])) {
                $query->whereHas('employee', function ($qry) use ($filter) {
                    $qry->whereHas('organizationModel', function ($q) use ($filter) {
                        $q->where('id', $filter['organization_id']);
                    });
                });
            }

            if (isset($filter['branch_id']) && !empty($filter['branch_id'])) {
                $query->whereHas('employee', function ($qry) use ($filter) {
                    $qry->whereHas('branchModel', function ($q) use ($filter) {
                        $q->where('id', $filter['branch_id']);
                    });
                });
            }

            if (isset($filter['emp_id']) && !empty($filter['emp_id'])) {
                $query->where('employee_id', $filter['emp_id']);
            }
            if (setting('calendar_type') == 'BS') {
                if (isset($filter['from_date']) && !empty($filter['from_date'])) {
                    $query->where('nep_from_date', '>=', $filter['from_date']);
                }
                if (isset($filter['to_date']) && !empty($filter['to_date'])) {
                    $query->where('nep_to_date', '<=', $filter['to_date']);
                }
            } else {
                if (isset($filter['from_date']) && !empty($filter['from_date'])) {
                    $query->where('eng_from_date', '>=', $filter['from_date']);
                }
                if (isset($filter['to_date']) && !empty($filter['to_date'])) {
                    $query->where('eng_to_date', '<=', $filter['to_date']);
                }
            }

            if (isset($filter['status']) && !empty($filter['status'])) {
                $query->where('status', $filter['status']);
            }
            if (isset($filter['title']) && !empty($filter['title'])) {
                $query->where('title', 'like', '%' . $filter['title'] . '%');
            }

            if (auth()->user()->user_type == 'employee' || auth()->user()->user_type == 'supervisor') {
                $query->where('employee_id', $empId);
            } elseif (auth()->user()->user_type == 'division_hr') {
                $query->whereHas('employee', function ($q) use ($filter) {
                    $q->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
                });
            } elseif (auth()->user()->user_type == 'hr' || auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'super_admin') {
                $query->where('employee_id', '!=', null);
            }
            //ranjan
            // elseif (auth()->user()->user_type == 'supervisor') {
            //     $firstApprovalEmps = EmployeeClaimRequestApprovalFlow::where('first_claim_approval_user_id', $userId)->pluck('employee_id')->toArray();
            //     $lastApprovalEmps = EmployeeClaimRequestApprovalFlow::where('last_claim_approval_user_id', $userId)->pluck('employee_id')->toArray();
            //     $query->orWhere('employee_id', $empId);
            //     $query->orWhereIn('employee_id', $firstApprovalEmps)->whereIn('status', [$pending_status, $forwarded_status]);
            //     $query->orWhereIn('employee_id', $lastApprovalEmps)->whereNotIn('status', [$accepted_status, $rejected_status, $fully_settled]);
            // }
            //
            else {
                $query->where('employee_id', $empId);
            }
        })->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999)); //dd(DB::getQueryLog());
        return $result;
    }

    public function find($id)
    {
        return Tada::find($id);
    }

    public function update($id, $data)
    {
        $model = Tada::find($id);
        return $model->update($data);
    }

    public function save($data)
    {
        return Tada::create($data);
    }

    public function delete($id)
    {
        return Tada::find($id)->delete();
    }

    public function getList()
    {
        return Tada::pluck('title', 'id');
    }

    public function getEmployeeClaim($limit = null)
    {
        $activeUserModel = Auth::user();

        $query = Tada::query();
        $query->select('employee_id', 'id', 'title', 'eng_from_date', "eng_to_date", "eng_from_date as date", 'status', 'created_at')
            ->addSelect(DB::raw("'claim' as type"))
            ->where('status', 1);

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

        $result = $query->orderBy('created_at', 'DESC')->take($limit ? $limit : env('DEF_PAGE_LIMIT', 9999))->get();

        return $result;
    }


    public function getStatusList()
    {
        return Tada::statusList();
    }

    public function uploadExcel($file)
    {
        $path = public_path() . '/uploads/tada/excels';
        if (!file_exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        $imageName = $file->getClientOriginalName();
        $fileName = time() . '_' . preg_replace('[ ]', '-', $imageName);
        $file->move($path, $fileName);

        return $fileName;
    }

    /**
     * Send notification
     */
    public function sendMailNotification($model, $type)
    {
        if ($type == 'Tada') {
            $link = route('tada.index');
            $teamLink = route('tada.showTeamClaim');
            $message = 'Claim';
            $module = 3;
        } else if ($type == 'TadaRequest') {
            $link = route('tadaRequest.index');
            $teamLink = route('tadaRequest.showTeamRequest');
            $message = 'Request';
            $module = 4;
        } else {
            $link = '';
            $message = '';
        }

        $authUser = auth()->user();
        $employeeModel = Employee::find($model->employee_id);

        //check if there is first approval or not
        if (isset(optional($employeeModel->employeeClaimRequestApprovalDetailModel)->first_claim_approval_user_id) && !empty(optional($employeeModel->employeeClaimRequestApprovalDetailModel)->first_claim_approval_user_id)) {
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
        $mailArray = [];
        if (optional($employeeModel->getUser)->id) {
            if ($authUser->id != optional($employeeModel->getUser)->id && ($model->status == '1' || $model->status == '3' || $model->status == '4')) {
                // send notification to employee
                $notificationData['creator_user_id'] = $authUser->id;
                $notificationData['notified_user_id'] = optional($employeeModel->getUser)->id;
                $notificationData['message'] = "Your $message titled '" . $model->title . "' has been " . $statusTitle . " by " . $authorName;
                $notificationData['link'] = $link;
                $notificationData['type'] = $type;
                $notificationData['type_id_value'] = $model->id;
                Notification::create($notificationData);

                // send email to employee
                if (emailSetting($module) == 11) {
                    $notified_user_email = User::getUserEmail(optional($employeeModel->getUser)->id);
                    if (isset($notified_user_email) && !empty($notified_user_email) && $model->enable_mail == 11) {
                        $notified_user_fullname = Employee::getName(optional($employeeModel->getUser)->id);
                        $details = array(
                            'email' => $notified_user_email,
                            'message' => "Your $message titled '" . $model->title . "' has been " . $statusTitle . " by " . $authorName,
                            'notified_user_fullname' => $notified_user_fullname,
                            'setting' => Setting::first(),
                            'subject' => $message . ' Notification'
                        );
                        $mailArray[] = $details;
                    }
                }
            }
        }

        // To first approval
        if (optional($employeeModel->employeeClaimRequestApprovalDetailModel)->first_claim_approval_user_id && $model->status == '1') {
            //send notification
            $notificationData['creator_user_id'] = $authUser->id;
            $notificationData['notified_user_id'] = optional($employeeModel->employeeClaimRequestApprovalDetailModel)->first_claim_approval_user_id;
            $notificationData['message'] = $employeeModel->full_name . "'s $message titled '" . $model->title . "' has been " . $statusTitle . " by " . $authorName;
            $notificationData['link'] = $teamLink;
            $notificationData['type'] = $type;
            $notificationData['type_id_value'] = $model->id;
            Notification::create($notificationData);

            //send mail
            if (emailSetting($module) == 11) {

                $notified_user_email = User::getUserEmail(optional($employeeModel->employeeClaimRequestApprovalDetailModel)->first_claim_approval_user_id);
                if (isset($notified_user_email) && !empty($notified_user_email) && $model->enable_mail == 11) {
                    $notified_user_fullname = Employee::getName(optional($employeeModel->employeeClaimRequestApprovalDetailModel)->first_claim_approval_user_id);
                    $details = array(
                        'email' => $notified_user_email,
                        'message' => $employeeModel->full_name . "'s $message titled '" . $model->title . "' has been " . $statusTitle . " by " . $authorName,
                        'notified_user_fullname' => $notified_user_fullname,
                        'setting' => Setting::first(),
                        'subject' => $message . ' Notification'
                    );
                    $mailArray[] = $details;
                }
            }
        }

        // if (optional($employeeModel->employeeClaimRequestApprovalDetailModel)->first_claim_approval_user_id && ($model->status == '3' || $model->status == '4')) {
        //     //send notification
        //     $notificationData['creator_user_id'] = $authUser->id;
        //     $notificationData['notified_user_id'] = optional($employeeModel->employeeClaimRequestApprovalDetailModel)->first_claim_approval_user_id;
        //     $notificationData['message'] = $employeeModel->full_name . "'s $message titled '" . $model->title . "' has been " . $statusTitle . " by " . $authorName;
        //     $notificationData['link'] = $teamLink;
        //     $notificationData['type'] = $type;
        //     $notificationData['type_id_value'] = $model->id;
        //     Notification::create($notificationData);

        //     //send mail
        //     if(emailSetting($module) == 11){

        //         $notified_user_email = User::getUserEmail(optional($employeeModel->employeeClaimRequestApprovalDetailModel)->first_claim_approval_user_id);
        //         if (isset($notified_user_email) && !empty($notified_user_email) && $model->enable_mail == 11) {
        //             $notified_user_fullname = Employee::getName(optional($employeeModel->employeeClaimRequestApprovalDetailModel)->first_claim_approval_user_id);
        //             $details = array(
        //                 'email' => $notified_user_email,
        //                 'message' => $employeeModel->full_name . "'s $message titled '" . $model->title . "' has been " . $statusTitle . " by " . $authorName,
        //                 'notified_user_fullname' => $notified_user_fullname,
        //                 'setting' => Setting::first(),
        //                 'subject' => $message.' Notification'
        //             );
        //             $mailArray[] = $details;
        //         }
        //     }
        // }

        // send notification to last approval
        if (optional($employeeModel->employeeClaimRequestApprovalDetailModel)->last_claim_approval_user_id && ($model->status == '2' || ($singleApproval == true && $model->status == '1'))) {
            $notificationData['creator_user_id'] = $authUser->id;
            $notificationData['notified_user_id'] = optional($employeeModel->employeeClaimRequestApprovalDetailModel)->last_claim_approval_user_id;
            $notificationData['message'] = $employeeModel->full_name . "'s $message titled '" . $model->title . "' has been " . $statusTitle . " by " . $authorName;
            $notificationData['link'] = $teamLink;
            $notificationData['type'] = $type;
            $notificationData['type_id_value'] = $model->id;
            Notification::create($notificationData);

            //send mail
            if (emailSetting($module) == 11) {

                $notified_user_email = User::getUserEmail(optional($employeeModel->employeeClaimRequestApprovalDetailModel)->last_claim_approval_user_id);
                if (isset($notified_user_email) && !empty($notified_user_email) && $model->enable_mail == 11) {
                    $notified_user_fullname = Employee::getName(optional($employeeModel->employeeClaimRequestApprovalDetailModel)->last_claim_approval_user_id);
                    $details = array(
                        'email' => $notified_user_email,
                        'message' => $employeeModel->full_name . "'s $message titled '" . $model->title . "' has been " . $statusTitle . " by " . $authorName,
                        'notified_user_fullname' => $notified_user_fullname,
                        'setting' => Setting::first(),
                        'subject' => $message . ' Notification'
                    );
                    $mailArray[] = $details;
                }
            }
        }

        // send notification to all hr
        $hrs = User::where('user_type', 'hr')->pluck('id');
        if (isset($hrs) && !empty($hrs)) {
            foreach ($hrs as $hr) {
                $notificationData['creator_user_id'] = $authUser->id;
                $notificationData['notified_user_id'] = $hr;
                $notificationData['message'] = $employeeModel->full_name . "'s $message titled '" . $model->title . "' has been " . $statusTitle . " by " . $authorName;
                $notificationData['link'] = $link;
                $notificationData['type'] = $type;
                $notificationData['type_id_value'] = $model->id;
                Notification::create($notificationData);

                // send email to all hr
                if (emailSetting($module) == 11) {
                    $notified_user_email = User::getUserEmail($hr);
                    if (isset($notified_user_email) && !empty($notified_user_email) && $model->enable_mail == 11) {
                        $notified_user_fullname = Employee::getName($hr);
                        $details = array(
                            'email' => $notified_user_email,
                            'message' => $employeeModel->full_name . "'s $message titled '" . $model->title . "' has been " . $statusTitle . " by " . $authorName,
                            'notified_user_fullname' => $notified_user_fullname,
                            'setting' => Setting::first(),
                            'subject' => $message . ' Notification'
                        );
                        $mailArray[] = $details;
                    }
                }
            }
        }

        // send notification to division hr
        $divisionHrs = User::when(true, function ($query) use ($employeeModel) {
            $query->whereHas('userEmployer', function ($q) use ($employeeModel) {
                $q->where('organization_id', $employeeModel->organization_id)->where('status', 1);
            });
        })->where('user_type', 'division_hr')->pluck('id');

        if (isset($divisionHrs) && !empty($divisionHrs)) {
            foreach ($divisionHrs as $divisionHr) {
                $notificationData['creator_user_id'] = $authUser->id;
                $notificationData['notified_user_id'] = $divisionHr;
                $notificationData['message'] = $employeeModel->full_name . "'s $message titled '" . $model->title . "' has been " . $statusTitle . " by " . $authorName;
                $notificationData['link'] = $link;
                $notificationData['type'] = $type;
                $notificationData['type_id_value'] = $model->id;
                Notification::create($notificationData);

                // send email to all division Hr
                if (emailSetting($module) == 11) {
                    $notified_user_email = User::getUserEmail($divisionHr);
                    if (isset($notified_user_email) && !empty($notified_user_email) && $model->enable_mail == 11) {
                        $notified_user_fullname = Employee::getName($divisionHr);
                        $details = array(
                            'email' => $notified_user_email,
                            'message' => $employeeModel->full_name . "'s $message titled '" . $model->title . "' has been " . $statusTitle . " by " . $authorName,
                            'notified_user_fullname' => $notified_user_fullname,
                            'setting' => Setting::first(),
                            'subject' => $message . ' Notification'
                        );
                        $mailArray[] = $details;
                    }
                }
            }
        }

        // Send all email at once
        if (count($mailArray) > 0) {
            foreach ($mailArray as $mailDetail) {
                $mail = new MailSender();
                $mail->sendMail('admin::mail.tada', $mailDetail);
            }
        }
        return true;
    }

    public function findTeamClaim($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $userId = auth()->user()->id;

        $statusList = $this->getStatusList();
        $user = auth()->user();
        $usertype = $user->user_type;

        $firstApprovalEmps = EmployeeClaimRequestApprovalFlow::where('first_claim_approval_user_id', $userId)->pluck('last_claim_approval_user_id', 'employee_id')->toArray();
        $firstApproval = Tada::with('employee')->select('*')->when(true, function ($query) use ($firstApprovalEmps, $user) {
            $query->whereHas('employee', function ($q) use ($user) {
                $q->where('organization_id', optional($user->userEmployer)->organization_id);
            });
            $query->whereIn('employee_id', array_keys($firstApprovalEmps));
            $query->where('status', 1);
            // $query->where('organization_id', optional($user->userEmployer)->organization_id);

        })->get()->map(function ($approvals) use ($statusList, $usertype) {
            $approvalFlow = optional($approvals->employee)->employeeClaimRequestApprovalDetailModel;
            if ($usertype == 'supervisor') {
                if (!$approvalFlow->last_claim_approval_user_id || $approvals->status == 1) {
                    unset($statusList[3]);
                }
                // if ($approvals->status == 1) {
                //     unset($statusList[3]);
                // }
            }
            $approvals->status_list = json_encode($statusList);
            return $approvals;
        });

        $lastApprovalEmps = EmployeeClaimRequestApprovalFlow::where('last_claim_approval_user_id', $userId)->select('first_claim_approval_user_id', 'last_claim_approval_user_id', 'employee_id')->get()->toArray();

        $lastApproval = Tada::when(true, function ($query) use ($lastApprovalEmps, $user) {
            // $query->where('organization_id', optional($user->userEmployer)->organization_id);
            $query->whereHas('employee', function ($q) use ($user) {
                $q->where('organization_id', optional($user->userEmployer)->organization_id);
            });
            $where = 'where';
            foreach ($lastApprovalEmps as $value) {

                $query->$where(function ($query) use ($value, $where) {
                    $query->where('employee_id', $value['employee_id']);
                    if (is_null($value['first_claim_approval_user_id'])) {
                        // $query->whereIn('status', [1, 2]);
                        $query->whereIn('status', [1, 2, 3, 4]);
                    } else {
                        // $query->where('status', 2);
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

        $result = $firstApproval->merge($lastApproval);
        return $result;
    }
}
