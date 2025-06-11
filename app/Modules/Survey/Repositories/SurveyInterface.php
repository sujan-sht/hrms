<?php

namespace App\Modules\Survey\Repositories;

interface SurveyInterface
{
    public function getList();
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function find($id);

    public function save($data);

    public function update($id, $data);

    public function delete($id);

    public function deleteSurveyQuestion($survey_id);

    public function deleteSurveyParticipants($survey_id);

    public function deleteSurveyAnswers($survey_id);
    public function surveyAllocation($data);
    public function updateOrCreateSurveyParticipant($inputData);

    public function deleteSurveyParticipant($survey_id, $organization_id);

    public function saveSurveyAnswer($data);

    public function getEmployeeSurveyResponse($data);

    public function isResponseSubmitted($survey_id);
    public function responseGivenEmployeeList($survey_id);
    public function checkResponseSubmitted($data);


}
