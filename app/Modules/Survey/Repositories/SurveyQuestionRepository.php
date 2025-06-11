<?php

namespace App\Modules\Survey\Repositories;

use App\Modules\Survey\Entities\SurveyQuestion;

class SurveyQuestionRepository implements SurveyQuestionInterface
{

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = SurveyQuestion::when(array_keys($filter, true), function ($query) use ($filter) {

            if (isset($filter['survey_id']) && !empty($filter['survey_id'])) {
                $query->where('survey_id', $filter['survey_id']);
            }

            if (isset($filter['question_type']) && !empty($filter['question_type'])) {
                $query->where('question_type', $filter['question_type']);
            }

            if (isset($filter['question']) && !empty($filter['question'])) {
                $query->where('question', 'like', '%'.$filter['question'].'%');
            }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));

        return $result;
    }

    public function find($id)
    {
        return SurveyQuestion::find($id);
    }

    public function save($data)
    {
        return SurveyQuestion::create($data);
    }

    public function update($id, $data)
    {
        return SurveyQuestion::find($id)->update($data);
    }
    public function delete($id)
    {
        return SurveyQuestion::find($id)->delete();
    }
    
    public function questionLists($survey_id){
        return SurveyQuestion::where('survey_id', $survey_id)->pluck('question', 'id');
    }
   
}
