<?php

namespace App\Modules\Asset\Repositories;

use App\Modules\Asset\Entities\AssetAllocate;
use App\Modules\Notification\Entities\Notification;

class AssetAllocateRepository implements AssetAllocateInterface
{

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = AssetAllocate::when(array_keys($filter, true), function ($query) use ($filter) {

            if (isset($filter['employee_id']) && !empty($filter['employee_id'])) {
                $query->where('employee_id', $filter['employee_id']);
            }

            if (isset($filter['asset_id']) && !empty($filter['asset_id'])) {
                $query->where('asset_id', $filter['asset_id']);
            }
            if (setting('calendar_type') == 'BS') {
                if (isset($filter['from_nep_date']) && !empty($filter['from_nep_date'])) {
                    $query->where('allocated_date', '>=',  $filter['from_nep_date']);
                }

                if (isset($filter['to_nep_date']) && !empty($filter['to_nep_date'])) {
                    $query->where('allocated_date', '<=',  $filter['to_nep_date']);
                }
            } else {
                if (isset($filter['allocated_date']) && !empty($filter['allocated_date'])) {
                    $fullDate = explode(' - ', $filter['allocated_date']);
                    $query->where('allocated_date', '>=',  $fullDate[0]);
                    $query->where('allocated_date', '<=',  $fullDate[1]);
                }
            }
            
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));

        return $result;
    }

    public function find($id)
    {
        return AssetAllocate::find($id);
    }

    public function save($data)
    {
        return AssetAllocate::create($data);
    }

    public function update($id, $data)
    {
        return AssetAllocate::find($id)->update($data);
    }

    public function delete($id)
    {
        return AssetAllocate::find($id)->delete();
    }

    public function sendMailNotification($model)
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'super_admin') {
            $authorName = $authUser->first_name;
        } else {
            $authorName = optional($authUser->userEmployer)->full_name;
        }

        $employee = $model->employee;
        if ($employee->getUser) {
            $notificationData['creator_user_id'] = $authUser->id;
            $notificationData['notified_user_id'] = optional($employee->getUser)->id;
            $notificationData['message'] = "Asset titled " . optional($model->asset)->title . " has been Allocated" . " by " . $authorName;
            $notificationData['link'] = route('assetAllocate.index', $model->id);
            $notificationData['type'] = 'asset';
            $notificationData['type_id_value'] = $model->id;
            Notification::create($notificationData);
        }
    }
}
