<?php

namespace App\Modules\Offboarding\Repositories;

use App\Modules\Admin\Entities\MailSender;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Notification\Entities\Notification;
use App\Modules\Offboarding\Entities\OffboardResignation;
use App\Modules\Employee\Entities\EmployeeOffboardApprovalFlow;
use App\Modules\Offboarding\Entities\OffboardEmployeeClearance;
use App\Modules\Setting\Entities\Setting;
use App\Modules\User\Entities\User;

class OffboardResignationRepository implements OffboardResignationInterface
{
    public function getList()
    {
        $list = [];

        $models = OffboardResignation::where('status', 2)->get();
        foreach ($models as $model) {
            $list[$model->id] = $model->getFullName() . ' :: ' . optional($model->mrfModel)->title;
        }

        return $list;
    }

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = OffboardResignation::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['organization']) && !empty($filter['organization'])){
                $query->whereHas('employeeModel',function($q)use($filter){
                    $q->where('organization_id',$filter['organization']);
                });
            }
            if (isset($filter['employee_id']) && !empty($filter['employee_id'])) {
                $query->where('employee_id', $filter['employee_id']);
            }
            if (isset($filter['status']) && !empty($filter['status'])) {
                $query->where('status', $filter['status']);
            }
        })
        ->orderBy($sort['by'], $sort['sort'])
        ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));

        return $result;
    }

    public function findTeamRequest($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $authUser = auth()->user();
        $userId = $authUser->id;
        $usertype = $authUser->user_type;
        $statusList = OffboardResignation::getStatusList();

        $firstApprovals = EmployeeOffboardApprovalFlow::where('first_approval', $userId)->pluck('first_approval', 'employee_id')->toArray();
        $firstApproval = OffboardResignation::when(true, function ($query) use ($firstApprovals) {
            $query->whereIn('employee_id', array_keys($firstApprovals));
        })->get()->map(function ($approvals) use ($statusList, $usertype) {
            $approvalFlow = optional($approvals->employeeModel)->employeeApprovalFlowRelatedDetailModel;
            if ($usertype == 'supervisor') {
                if (!$approvalFlow->last_approval || $approvals->status == 1) {
                    unset($statusList[3]);
                }
            }
            $approvals->status_list = json_encode($statusList);
            return $approvals;
        });

        $lastApprovals = EmployeeOffboardApprovalFlow::where('last_approval', $userId)->select('first_approval', 'last_approval', 'employee_id')->get()->toArray();
        $lastApproval = [];
        if (count($lastApprovals) > 0) {
            $lastApproval = OffboardResignation::when(true, function ($query) use ($lastApprovals) {
                $where = 'where';
                foreach ($lastApprovals as $value) {
                    $query->$where(function ($query) use ($value) {
                        $query->where('employee_id', $value['employee_id']);
                        if (is_null($value['first_approval'])) {
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
        if (isset($filter['status']) && !empty($filter['status'])) {
            $result = $result->where('status', $filter['status']);
        }

        $result = paginate($result, $limit, '', ['path' => request()->url()]);
        return $result;
    }

    public function findOne($id)
    {
        return OffboardResignation::find($id);
    }

    public function create($data)
    {
        return OffboardResignation::create($data);
    }

    public function update($id, $data)
    {
        $result = $this->findOne($id);

        return $result->update($data);
    }

    public function delete($id)
    {
        return OffboardResignation::destroy($id);
    }

    public function upload($file)
    {
        $imageName = $file->getClientOriginalName();
        $fileName = time() . '-' . preg_replace('[ ]', '-', $imageName);
        $file->move(public_path() . '/' . OffboardResignation::FILE_PATH, $fileName);

        return $fileName;
    }

    public function sendMailNotification($data)
    {
        $authUser = auth()->user();

        if ($authUser->user_type == 'super_admin') {
            $authorName = $authUser->first_name;
        } else {
            $authorName = optional($authUser->userEmployer)->full_name;
        }

        $statusTitle = $data->status_detail['status'];
        if($statusTitle == 'Pending') {
            $statusTitle = 'Requested';
        }

        $model = EmployeeOffboardApprovalFlow::where('employee_id', $data->employee_id)->first();
        $mailArray = [];
        if($model) {
            $employeeModel = Employee::find($model->employee_id);

            if($statusTitle == 'Requested') {
                // create notification for first approval
                if(isset($model->first_approval)) {
                    $notificationData['creator_user_id'] = $authUser->id;
                    $notificationData['notified_user_id'] = $model->first_approval;
                    $notificationData['message'] = $employeeModel->full_name . "'s resignation has been " . $statusTitle . " by " . $authorName;
                    $notificationData['link'] = route('resignation.teamRequest');
                    $notificationData['type'] = 'resignation';
                    $notificationData['type_id_value'] = $data->id;
                    Notification::create($notificationData);

                    // send email
                    $notified_user_email = User::getUserEmail($model->first_approval);
                    if (isset($notified_user_email) && !empty($notified_user_email) && $data->enable_mail == 11) {
                        $notified_user_fullname = Employee::getName($model->first_approval);
                        $details = array(
                            'email' => $notified_user_email,
                            'message' => $employeeModel->full_name . "'s resignation has been " . $statusTitle . " by " . $authorName,
                            'notified_user_fullname' => $notified_user_fullname,
                            'setting' => Setting::first(),
                            'subject' => 'Resignation Notification'
                        );
                        $mailArray[] = $details;
                    }
                }
            } else {
                // create notification for last approval
                if(isset($model->last_approval)) {
                    $notificationData['creator_user_id'] = $authUser->id;
                    $notificationData['notified_user_id'] = $model->last_approval;
                    $notificationData['message'] = $employeeModel->full_name . "'s resignation has been " . $statusTitle . " by " . $authorName;
                    $notificationData['link'] =  route('resignation.teamRequest');
                    $notificationData['type'] = 'resignation';
                    $notificationData['type_id_value'] = $data->id;
                    Notification::create($notificationData);

                    // send email
                    $notified_user_email = User::getUserEmail($model->last_approval);
                    if (isset($notified_user_email) && !empty($notified_user_email) && $data->enable_mail == 11) {
                        $notified_user_fullname = Employee::getName($model->last_approval);
                        $details = array(
                            'email' => $notified_user_email,
                            'message' => $employeeModel->full_name . "'s resignation has been " . $statusTitle . " by " . $authorName,
                            'notified_user_fullname' => $notified_user_fullname,
                            'setting' => Setting::first(),
                            'subject' => 'Resignation Notification'
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
                $mail->sendMail('admin::mail.resignation', $mailDetail);
            }
        }
    }


}
