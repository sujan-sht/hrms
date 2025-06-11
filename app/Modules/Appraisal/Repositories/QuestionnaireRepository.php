<?php

namespace App\Modules\Appraisal\Repositories;

use App\Modules\Appraisal\Entities\Questionnaire;

class QuestionnaireRepository implements QuestionnaireInterface
{
    protected $model;

    public function __construct(Questionnaire $questionnaire)
    {
        $this->model = $questionnaire;
    }

    public function findAll($limit = null, $filter = [])
    {
        $result = $this->model->query();
        $result = $result->latest()->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;    }

    public function findOne($id)
    {
        return $this->model->where('id',$id)->first();
    }

    public function save($data)
    {
        return $this->model->create($data);
    }

    public function update($id, $data)
    {
        $questionnaire = $this->findOne($id);
        $questionnaire->fill($data);
        $questionnaire->update();

        return $questionnaire;
    }

    public function delete($id)
    {
        $this->findOne($id)->delete();
        return true;
    }
}
