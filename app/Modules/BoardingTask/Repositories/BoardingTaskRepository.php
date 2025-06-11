<?php

namespace App\Modules\BoardingTask\Repositories;

use App\Modules\BoardingTask\Entities\BoardingTask;
use App\Modules\Onboarding\Entities\Onboard;

class BoardingTaskRepository implements BoardingTaskInterface
{
    public function getList($params = null)
    {
        $list = [];

        if($params == 'Onboarding') {
            return BoardingTask::whereIn('category', [1,2,3])->get()->groupBy('category');
        } elseif($params == 'Offboarding') {
            return BoardingTask::whereIn('category', [4,5,6])->get()->groupBy('category');
        } else {
            return BoardingTask::pluck('title', 'id');
        }
    }

    public function getListWithData($params, $mrfId, $applicantId)
    {
        $list = [];

        $data = [
            'mrfId' => $mrfId,
            'applicantId' => $applicantId
        ];

        if($params == 'Onboarding') {
            return BoardingTask::whereIn('category', [1,2,3])->get()->map(function ($model) use ($data) {
                $realModel = Onboard::where([
                    'manpower_requisition_form_id' => $data['mrfId'],
                    'applicant_id' => $data['applicantId'],
                    'boarding_task_id' => $model->id
                ])->first();
                if($realModel) {
                    $model->onboard_date = $realModel->onboard_date;
                    $model->status = $realModel->status;
                }
                return $model;
            })->groupBy('category');
        } else {
            return BoardingTask::whereIn('category', [4,5,6])->get()->groupBy('category');
        } 
    }

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = BoardingTask::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['category']) && !empty($filter['category'])) {
                $query->where('category', $filter['category']);
            }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));

        return $result;
    }

    public function findOne($id)
    {
        return BoardingTask::find($id);
    }

    public function create($data)
    {
        return BoardingTask::create($data);
    }

    public function update($id, $data)
    {
        $result = $this->findOne($id);

        return $result->update($data);
    }

    public function delete($id)
    {
        return BoardingTask::destroy($id);
    }
}
