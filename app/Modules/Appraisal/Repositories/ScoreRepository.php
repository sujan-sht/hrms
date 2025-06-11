<?php

namespace App\Modules\Appraisal\Repositories;

use App\Modules\Appraisal\Entities\Score;
use App\Modules\Appraisal\Repositories\ScoreInterface;

class ScoreRepository implements ScoreInterface
{
    protected $model;

    public function __construct(Score $score)
    {
        $this->model = $score;
    }

    public function findAll()
    {
        return $this->model->latest()->paginate(10);
    }

    public function findOne($id)
    {
        return $this->model->where('id',$id)->first();
    }

    public function findByScore($score)
    {
        return $this->model->where('score',$score)->first();
    }

    public function save($data)
    {
        return $this->model->create($data);
    }

    public function update($id, $data)
    {
        $score = $this->findByScore($id);
        $newdate = [
            $data['fieldtype'] => $data['field_value']
        ];

        $score->fill($newdate);
        $score->update();

        return $score;
    }

    public function delete($id)
    {
        $this->findOne($id)->delete();
        return true;
    }
}
