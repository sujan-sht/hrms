<?php

namespace App\Modules\Onboarding\Repositories;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Setting\Entities\MrfApprovalFlow;
use App\Modules\Notification\Entities\Notification;
use App\Modules\Onboarding\Entities\ManpowerRequisitionForm;

class MrfRepository implements MrfInterface
{
    public function getList()
    {
        return ManpowerRequisitionForm::pluck('reference_number', 'id');
    }

    public function getListWithTitle()
    {
        if (auth()->user()->user_type == 'division_hr') {
            $models = ManpowerRequisitionForm::where('organization_id',optional(auth()->user()->userEmployer)->organization_id)->get();
        }
        else{
            $models = ManpowerRequisitionForm::get();
        }
        $list = [];
        foreach ($models as $model) {
            $list[$model->id] = $model->reference_number . ' :: ' . $model->title;
        }

        return $list;
    }

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = ManpowerRequisitionForm::when(array_keys($filter, true), function ($query) use ($filter) {
            // if (auth()->user()->user_type == 'division_hr') {
            //     $query->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
            // }
            if (isset($filter['organization']) && !empty($filter['organization'])) {
                $query->where('organization_id', $filter['organization']);
            }
            if (isset($filter['division']) && !empty($filter['division'])) {
                $query->where('division', $filter['division']);
            }
            if (isset($filter['department']) && !empty($filter['department'])) {
                $query->where('department', $filter['department']);
            }
            if (isset($filter['designation']) && !empty($filter['designation'])) {
                $query->where('designation', $filter['designation']);
            }
            if (isset($filter['status']) && !empty($filter['status'])) {
                $query->where('status', $filter['status']);
            }
            if (isset($filter['statuses']) && !empty($filter['statuses'])) {
                $query->whereIn('status', $filter['statuses']);
            }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));

        return $result;
    }

    public function findOne($id)
    {
        return ManpowerRequisitionForm::find($id);
    }

    public function create($data)
    {
        return ManpowerRequisitionForm::create($data);
    }

    public function update($id, $data)
    {
        $result = $this->findOne($id);

        return $result->update($data);
    }

    public function delete($id)
    {
        return ManpowerRequisitionForm::destroy($id);
    }

    public function upload($file)
    {
        // $imageName = $file->getClientOriginalName();
        // $fileName = time() . '-' . preg_replace('[ ]', '-', $imageName);
        // $file->move(public_path() . '/' . ManpowerRequisitionForm::IMAGE_PATH, $fileName);

        // return $fileName;
    }

    public function checkData($params)
    {
        return ManpowerRequisitionForm::where([
            'employee_id' => $params['employee_id'],
            'leave_type_id' => $params['leave_type_id'],
            'date' => $params['date']
        ])->first();
    }

    /**
     *
     */
    public function sendMailNotification($model)
    {
        $authUser = auth()->user();

        if ($authUser->user_type != 'super_admin') {
            $employeeId = $authUser->emp_id;
            $employeeModel = Employee::find($employeeId);

            $mrfApprovalModel = MrfApprovalFlow::where('organization_id', $employeeModel->organization_id)->first();
            if ($mrfApprovalModel) {
                $authorName = optional($authUser->userEmployer)->full_name;
                if ($model->status == '1') {
                    $statusTitle = 'Created';
                } else {
                    $statusTitle = $model->getStatus();
                }

                // create notification for first approval
                $notificationData['creator_user_id'] = $authUser->id;
                if ($model->status == '1') {
                    $notificationData['notified_user_id'] = optional(optional($mrfApprovalModel->firstApprovalEmployeeModel)->getUser)->id;
                } elseif ($model->status == '5') {
                    $notificationData['notified_user_id'] = optional(optional($mrfApprovalModel->secondApprovalEmployeeModel)->getUser)->id;
                } elseif ($model->status == '6') {
                    $notificationData['notified_user_id'] = optional(optional($mrfApprovalModel->thirdApprovalEmployeeModel)->getUser)->id;
                } elseif ($model->status == '7') {
                    $notificationData['notified_user_id'] = optional(optional($mrfApprovalModel->fourthApprovalEmployeeModel)->getUser)->id;
                }
                $notificationData['message'] = "MRF titled " . $model->title . " has been " . $statusTitle . " by " . $authorName;
                $notificationData['link'] = route('mrf.index');
                $notificationData['type'] = 'mrf';
                $notificationData['type_id_value'] = $model->id;
                Notification::create($notificationData);
            }
        }

        return true;
    }


}
