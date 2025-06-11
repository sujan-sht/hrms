<?php

namespace App\Modules\Offboarding\Repositories;

use App\Modules\Notification\Entities\Notification;
use App\Modules\Offboarding\Entities\OffboardClearance;
use App\Modules\Offboarding\Entities\OffboardClearanceResponsible;
use App\Modules\Offboarding\Entities\OffboardEmployeeClearance;

class OffboardClearanceRepository implements OffboardClearanceInterface
{
    public function getList()
    {
        $list = [];

        // $models = OffboardClearance::where('status', 2)->get();
        // foreach ($models as $model) {
        //     $list[$model->id] = $model->getFullName() . ' :: ' . optional($model->mrfModel)->title;
        // }

        return $list;
    }

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = OffboardClearance::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['title']) && !empty($filter['title'])) {
                $query->where('title', 'like', '%' . $filter['title'] . '%');
            }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));

        return $result;
    }

    public function findOne($id)
    {
        return OffboardClearance::find($id);
    }

    public function create($data)
    {
        return OffboardClearance::create($data);
    }

    public function update($id, $data)
    {
        $result = $this->findOne($id);

        return $result->update($data);
    }

    public function delete($id)
    {
        OffboardClearanceResponsible::where('offboard_clearance_id', $id)->delete();
        return OffboardClearance::destroy($id);
    }

    public function sendMailNotification($models,$resignationModel)
    {
        // dd($model);
        $authUser = auth()->user();

        if ($authUser->user_type == 'super_admin') {
            $authorName = $authUser->first_name;
        } else {
            $authorName = optional($authUser->userEmployer)->full_name;
        }

        foreach($models as $model) {
            foreach ($model->clearanceResponsible as $key => $value) {
                $notificationData['creator_user_id'] = $authUser->id;
                $notificationData['notified_user_id'] = $value->employee->getUser->id;
                $notificationData['message'] = optional($resignationModel->employeeModel)->getFullName() . "'s clearance has been " . "send" . " by " . $authorName;
                $notificationData['link'] = route('clearance.employee.show',$model->id).'?reg_id='.$resignationModel->id.'&responsible_id='.$value->id;
                $notificationData['type'] = 'clearance';
                $notificationData['type_id_value'] = $model->id;
                Notification::create($notificationData);
            }
        }
    }
    public function createEmployeeClearance($data){
        return OffboardEmployeeClearance::create($data);
    }
}
