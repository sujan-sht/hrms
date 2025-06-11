<?php

namespace App\Modules\Onboarding\Repositories;

use App\Modules\Onboarding\Entities\InterviewLevel;
use App\Modules\Onboarding\Entities\InterviewLevelQuestion;

class InterviewLevelRepository implements InterviewLevelInterface
{
    public function getList()
    {
        return InterviewLevel::pluck('title', 'id');
    }

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = InterviewLevel::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['title']) && !empty($filter['title'])) {
                $query->where('title', 'like', '%'.$filter['title'].'%');
            }
        })
        ->orderBy($sort['by'], $sort['sort'])
        ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));

        return $result;
    }

    public function findOne($id)
    {
        return InterviewLevel::find($id);
    }

    public function create($data)
    {
        $model = InterviewLevel::create($data);
        if($model) {
            // <i class="icon-plus2"></i> Add data
            foreach ($data['questions'] as $question) {
                $newModel = new InterviewLevelQuestion();
                $newModel->interview_level_id = $model->id;
                $newModel->question = $question;
                $newModel->save();
            }
        }

        return $model;
    }

    public function update($id, $data)
    {
        $result = $this->findOne($id);

        $flag = $result->update($data);
        if($flag) {
            // delete old records
            InterviewLevelQuestion::where('interview_level_id', $result->id)->delete();
            // <i class="icon-plus2"></i> Add data
            foreach ($data['questions'] as $question) {
                $newModel = new InterviewLevelQuestion();
                $newModel->interview_level_id = $result->id;
                $newModel->question = $question;
                $newModel->save();
            }
        }
    }

    public function delete($id)
    {
        $oldModelId = $id;

        $flag = InterviewLevel::destroy($id);
        if($flag) {
            // delete old records
            InterviewLevelQuestion::where('interview_level_id', $oldModelId)->delete();
        }

        return $flag;
    }
}
