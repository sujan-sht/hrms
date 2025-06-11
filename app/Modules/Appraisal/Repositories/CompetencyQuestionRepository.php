<?php

namespace App\Modules\Appraisal\Repositories;

use App\Modules\Appraisal\Entities\CompetencyQuestion;

class CompetencyQuestionRepository implements CompetencyQuestionInterface
{
    protected $model;

    public function __construct(CompetencyQuestion $competencyQuestion)
    {
        $this->model = $competencyQuestion;
    }

    public function findAll()
    {
        return $this->model->latest()->get();
    }

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
        $competencyQuestion = $this->findOne($id);
        $competencyQuestion->fill($data);
        $competencyQuestion->update();

        return $competencyQuestion;
    }

    public function delete($id)
    {
        $this->findOne($id)->delete();
        return true;
    }
}
