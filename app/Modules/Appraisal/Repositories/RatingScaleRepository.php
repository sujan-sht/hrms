<?php

namespace App\Modules\Appraisal\Repositories;

use App\Modules\Appraisal\Entities\AppraisalRatingScale;
use App\Modules\Notification\Entities\Notification;

class RatingScaleRepository implements RatingScaleInterface
{
    public function getList()
    {
        $list = [];

        // $models = RatingScale::where('status', 2)->get();
        // foreach ($models as $model) {
        //     $list[$model->id] = $model->getFullName() . ' :: ' . optional($model->mrfModel)->title;
        // }

        return $list;
    }

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'ASC'])
    {
        $result = AppraisalRatingScale::when(array_keys($filter, true), function ($query) use ($filter) {
            // if (isset($filter['title']) && !empty($filter['title'])) {
            //     $query->where('title', 'like', '%' . $filter['title'] . '%');
            // }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));
        return $result;
    }

    public function findOne($id)
    {
        return AppraisalRatingScale::find($id);
    }

    public function create($data)
    {
        return AppraisalRatingScale::create($data);
    }

    public function update($id, $data)
    {
        $result = $this->findOne($id);

        return $result->update($data);
    }

    public function delete($id)
    {
        return AppraisalRatingScale::destroy($id);
    }
}
