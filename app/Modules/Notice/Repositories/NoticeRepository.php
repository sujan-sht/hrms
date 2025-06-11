<?php

namespace App\Modules\Notice\Repositories;

use App\Modules\Admin\Entities\MailSender;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Notice\Entities\Notice;
use App\Modules\Notice\Entities\NoticeDepartment;
use App\Modules\Notification\Entities\Notification;
use App\Modules\Setting\Entities\Setting;
use App\Modules\User\Entities\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Ladumor\OneSignal\OneSignal;

class NoticeRepository implements NoticeInterface
{

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1])
    {
        if (auth()->user()->user_type == 'super_admin' || auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'hr') {
            $query = Notice::query();
        } else {
            $emp_id = auth()->user()->emp_id;
            $org_id = auth()->user()->userEmployer->organization_id;
            $dep_id = auth()->user()->userEmployer->department_id;

            $query = Notice::query();


            $query->whereJsonContains('employee_id', $emp_id)->orWhereNull('employee_id');

            $query->whereJsonContains('department_id', (string)$dep_id)->orWhereNull('department_id');
            $query->whereJsonContains('organization_id', (string)$org_id);
            $query->orWhere('created_by', auth()->user()->id);

            // Optionally filter notices by current date
            $query->whereDate('notice_date', '<=', now()->toDateString());


            // If today's date, also filter notices where notice_time has already passed
            if (now()->isToday()) {
                $query->whereTime('notice_time', '<=', now()->toTimeString());
            }
        }

        $result = $query->orderBy('notice_date', 'desc')->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));

        return $result;
    }

    public function find($id)
    {
        return Notice::find($id);
    }

    public function getLatestNotices($limit = null)
    {
        $query = Notice::query();
        $query->where('notice_date', '>=', Carbon::now()->toDateString());
        $query->when(true, function ($query) {
            if (in_array(auth()->user()->user_type, ['employee', 'division_hr', 'supervisor'])) {
                $divisionHrList = [1 => 'Admin'];
                $divisionHrList = $divisionHrList + (employee_helper()->getParentUserList(['division_hr', 'supervisor']));
                $divisionHrList = $divisionHrList + (employee_helper()->getParentUserList(['hr'], false));
                $query->whereIn('created_by', array_keys($divisionHrList));
            }

            if (in_array(auth()->user()->user_type, ['employee', 'supervisor'])) {
                $query->doesnthave('departments');

                $query->orWhereHas('departments', function ($q) {
                    $q->where('department_id', optional(auth()->user())->userEmployer->department_id);
                });
            }
        });
        $result = $query->orderBy('notice_date', 'desc')->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
        // return Notice::where('notice_date', '<', now()->addDays(3)->format('Y-m-d'))->paginate($limit ? $limit : 10);
    }

    public function getNotices($limit = null)
    {
        if (auth()->user()->user_type == 'super_admin' || auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'hr') {
            $query = Notice::query();
        } else {
            $emp_id = auth()->user()->emp_id;
            $org_id = auth()->user()->userEmployer->organization_id;
            $dep_id = auth()->user()->userEmployer->department_id;

            $query = Notice::query();


            $query->whereJsonContains('employee_id', $emp_id)->orWhereNull('employee_id');

            $query->whereJsonContains('department_id', (string)$dep_id)->orWhereNull('department_id');
            $query->whereJsonContains('organization_id', (string)$org_id);

            // Optionally filter notices by current date
            $query->whereDate('notice_date', '<=', now()->toDateString());

            // If today's date, also filter notices where notice_time has already passed
            if (now()->isToday()) {
                $query->whereTime('notice_time', '<=', now()->toTimeString());
            }
        }



        $result = $query->orderBy('notice_date', 'desc')->paginate($limit ?? env('DEF_PAGE_LIMIT', 9999));

        return $result;
    }



    public function getTodayLatestNotices()
    {
        return Notice::orderBy('notice_date', 'desc')->take(10)->get();
    }

    public function getTodayNotices()
    {
        return Notice::where('notice_date', now()->format('Y-m-d'))->get();
    }


    public function getList()
    {
        $result = Notice::pluck('title', 'id');
        return $result;
    }

    public function save($data)
    {
        $model = Notice::create($data);
        // if ($model) {
        //     $this->saveNoticeDepartments($model->id, $data);
        // }
        return $model;
    }

    public function update($id, $data)
    {
        $result = Notice::find($id);
        $flag = $result->update($data);

        // if ($flag) {
        //     $this->deleteNoticeDepartments($id);
        //     $this->saveNoticeDepartments($id, $data);
        // }
        return $flag;
    }

    public function delete($id)
    {
        $notice = Notice::find($id);
        $notice->departments()->delete();
        $notice->delete();
    }

    public function getLatestNotice()
    {
        $yesterday = date('Y-m-d', strtotime(' -1 day'));
        return Notice::where('notice_date', '>=', $yesterday)->orderBy('notice_date', 'ASC')->get();
    }

    public function getAllNoticeData($filter = [])
    {
        $result = Notice::get();
        return $result;
    }

    public function getAllNoticesForEmployee($limit = '')
    {
        $yesterday = date('Y-m-d', strtotime(' -1 day'));

        $result = Notice::where('notice_date', '>=', $yesterday)->whereHas('creator', function ($q) {
            $q->where('user_type', 'super_admin');
            $q->orWhere('user_type', 'admin');
            $q->orWhere('user_type', 'hr');
            $q->orWhereHas('userEmployer', function ($qry) {
                $qry->where('department_id', auth()->user()->userEmployer->department_id);
            });
        })
            ->orderBy('notice_date', 'ASC')
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));

        return $result;
    }

    public function getAllNoticesForManager($limit = '')
    {
        $yesterday = date('Y-m-d', strtotime(' -1 day'));
        $result = Notice::where('notice_date', '>=', $yesterday)->where(function ($q) {
            $q->orWhereHas('creator', function ($qry) {
                $qry->where('id', auth()->user()->id);
                $qry->orWhere('user_type', 'super_admin');
                $qry->orWhere('user_type', 'admin');
                $qry->orWhere('user_type', 'hr');
            });
        })

            ->orderBy('notice_date', 'ASC')
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));

        return $result;
    }

    public function getNoticeForEmployee($department_id)
    {
        $days_ago_date = Carbon::now()->subDays(2)->toDateString();
        $new_date = Carbon::now()->addDays(7)->toDateString();

        $result = Notice::whereBetween('notice_date', [$days_ago_date, $new_date])
            ->whereHas('creator', function ($q) use ($department_id) {
                $q->where('user_type', 'super_admin');
                $q->orWhere('user_type', 'admin');
                $q->orWhereHas('userEmployer', function ($qry) use ($department_id) {
                    $qry->where('department_id', $department_id);
                });
            })
            ->orderBy('notice_date', 'DESC')
            ->get();
        return $result;
    }

    public function getNoticeForManager()
    {
        $days_ago_date = Carbon::now()->toDateString();
        $new_date = Carbon::now()->addDays(90)->toDateString();

        $result = Notice::whereBetween('notice_date', [$days_ago_date, $new_date])
            ->where(function ($q) {
                $q->orWhereHas('creator', function ($qry) {
                    $qry->where('id', auth()->user()->id);
                    $qry->orWhere('user_type', 'super_admin');
                    $qry->orWhere('user_type', 'admin');
                });
            })

            ->orderBy('notice_date', 'DESC')
            ->get();

        return $result;
    }


    public function upload($file)
    {
        $imageName = $file->getClientOriginalName();
        $fileName = $imageName;

        $file->move(public_path() . Notice::FILE_PATH, $fileName);

        return $fileName;
    }

    // public function sendMailNotification($model)
    // {
    //     $authUser = auth()->user();
    //     $noticeDepartments = $model->departments->pluck('department_id')->toArray();

    //     if ($authUser->user_type == 'super_admin') {
    //         $authorName = $authUser->first_name;
    //     } else {
    //         $authorName = optional($authUser->userEmployer)->full_name;
    //     }
    //     // with('getUser.device')->
    //     $employeeLists = Employee::where(function ($query) use ($noticeDepartments) {
    //         $query->where('status', '1');
    //         if ($noticeDepartments) {
    //             $query->whereIn('department_id', $noticeDepartments);
    //         }
    //     })->get();
    //     dd($employeeLists);
    //     if ($authUser->user_type == 'division_hr') {
    //         $employeeLists = Employee::where(function ($query) use ($noticeDepartments) {
    //             $query->where('status', '1')->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
    //             if ($noticeDepartments) {
    //                 $query->whereIn('department_id', $noticeDepartments);
    //             }
    //         })->get();
    //     }
    //     $playerArrayId = [];
    //     foreach ($employeeLists as $employee) {
    //         if (is_null($employee->getUser)) continue;
    //         $userModel = optional($employee->getUser);

    //         $notificationData['creator_user_id'] = $authUser->id;
    //         $notificationData['notified_user_id'] = optional($employee->getUser)->id;
    //         $notificationData['message'] = "The notice titled " . $model->title . " has been created" . " by " . $authUser->user_type . ' ' . "Department";
    //         $notificationData['link'] = route('notice.view', $model->id);
    //         $notificationData['type'] = 'notice';
    //         $notificationData['type_id_value'] = $model->id;
    //         Notification::create($notificationData);

    //         if ($userModel->device) {
    //             $playerArrayId[] = optional($userModel->device)->os_player_id;
    //         }
    //     }

    //     if (is_array($playerArrayId) && !empty($playerArrayId)) {
    //         // dd($playerArrayId,['735e9529-d185-476a-9544-3155fa100a58']);
    //         $fields['include_player_ids'] = $playerArrayId;
    //         $message = "The notice titled " . $model->title . " has been created" . " by " . $authorName;
    //         OneSignal::sendPush($fields, $message);
    //     }

    //     return true;
    // }

    public function sendMailNotification($model)
    {
        if ($model->type == 1) {
            $authUser = auth()->user();
        } else {
            $authUser = User::find($model->created_by);
        }


        if ($authUser->user_type == 'super_admin') {
            $authorName = $authUser->first_name;
        } else {
            $authorName = optional($authUser->userEmployer)->full_name;
        }

        if (!empty($model->employee_id)) {
            $employeeLists = Employee::whereIn('id', json_decode($model->employee_id))->get();
        } elseif (!empty($model->department_id)) {
            $noticeDepartments = json_decode($model->department_id);
            $employeeLists = Employee::where(function ($query) use ($noticeDepartments) {
                $query->where('status', '1');
                if ($noticeDepartments) {
                    $query->whereIn('department_id', $noticeDepartments);
                }
            })->get();
        } elseif (!empty($model->organization_id)) {
            $noticeOrganization = json_decode($model->organization_id);
            $employeeLists = Employee::where(function ($query) use ($noticeOrganization) {
                $query->where('status', '1');
                if ($noticeOrganization) {
                    $query->whereIn('organization_id', $noticeOrganization);
                }
            })->get();
        }


        $playerArrayId = [];
        $mailArray = [];
        foreach ($employeeLists as $employee) {
            if (is_null($employee->getUser)) continue;
            $userModel = optional($employee->getUser);

            $notificationData['creator_user_id'] = $authUser->id;
            $notificationData['notified_user_id'] = optional($employee->getUser)->id;
            $notificationData['message'] = "The notice titled " . $model->title . " has been created" . " by " . $authUser->user_type . ' ' . "Department";
            $notificationData['link'] = route('notice.view', $model->id);
            $notificationData['type'] = 'notice';
            $notificationData['type_id_value'] = $model->id;
            Notification::create($notificationData);

            if ($userModel->device) {
                $playerArrayId[] = optional($userModel->device)->os_player_id;
            }

            
            if(emailSetting(7) == 11){
                $notified_user_email = $employee->official_email;
                if (isset($notified_user_email) && !empty($notified_user_email)) {
                    $notified_user_fullname = $employee->full_name;
                    $details = array(
                        'email' => $notified_user_email,
                        'message' => "The notice titled " . $model->title . " has been created" . " by " . $authUser->user_type . ' ' . "Department",
                        'notified_user_fullname' => $notified_user_fullname,
                        'setting' => Setting::first(),
                        'subject' => 'Notice'
                    );
                    $mailArray[] = $details;
                }
            }

        }

        if (is_array($playerArrayId) && !empty($playerArrayId)) {
            // dd($playerArrayId,['735e9529-d185-476a-9544-3155fa100a58']);
            $fields['include_player_ids'] = $playerArrayId;
            $fields['isIos'] = true;
            $fields['isAndroid'] = true;
            $message = "The notice titled " . $model->title . " has been created" . " by " . $authorName;
            OneSignal::sendPush($fields, $message);
        }
        //  Send all email at once
        if (count($mailArray) > 0) {
            foreach ($mailArray as $mailDetail) {
                $mail = new MailSender();
                $mail->sendMail('admin::mail.notice', $mailDetail);
            }
        }

        return true;
    }

    public function saveNoticeDepartments($noticeId, $data)
    {
        if (isset($data['departmentArray'])) {
            foreach ($data['departmentArray'] as $departmentId) {
                $model = NoticeDepartment::where('notice_id', $noticeId)->where('department_id', $departmentId)->first();
                if (!$model) {
                    $model = new NoticeDepartment();
                }

                $model->notice_id = $noticeId;
                $model->department_id = $departmentId;
                $model->save();
            }
        }
        return true;
    }

    public function deleteNoticeDepartments($noticeId)
    {
        return NoticeDepartment::where('notice_id', $noticeId)->delete();
    }
}
