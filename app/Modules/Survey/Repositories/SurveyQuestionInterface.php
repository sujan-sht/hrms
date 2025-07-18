<?php

namespace App\Modules\Survey\Repositories;

interface SurveyQuestionInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function find($id);

    public function save($data);

    public function update($id, $data);

    public function delete($id);

    public function questionLists($survey_id);

}
