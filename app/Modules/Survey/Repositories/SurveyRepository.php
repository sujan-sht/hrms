<?php

namespace App\Modules\Survey\Repositories;

use App\Modules\Survey\Entities\Survey;
use App\Modules\Survey\Entities\SurveyAnswer;
use App\Modules\Survey\Entities\SurveyParticipant;
use App\Modules\Survey\Entities\SurveyQuestion;
use App\Modules\User\Entities\User;

class SurveyRepository implements SurveyInterface
{
    public function getList()
    {
        return Survey::pluck('title', 'id');
    }

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = Survey::when(true, function ($query) use ($filter) {

            if (isset($filter['title']) && !empty($filter['title'])) {
                $query->where('title', 'like',  '%'.$filter['title'].'%');
            }
            if (auth()->user()->user_type == 'division_hr') {
                $divisionHrList = employee_helper()->getParentUserList(['division_hr']);
                $divisionHrList = $divisionHrList + employee_helper()->getParentUserList(['hr']);
                $query->whereIn('created_by', array_keys($divisionHrList));
            }

            if (isset($filter['checkSurveyParticipant']) && $filter['checkSurveyParticipant'] == true) {
                $query->whereHas('surveyParticipants', function($q) use ($filter){
                    if(isset($filter['organization_id']) && !empty($filter['organization_id'])){
                        $q->where('organization_id', $filter['organization_id']);
                    }

                    if(isset($filter['department_id']) && !empty($filter['department_id'])){
                        $q->where(function($q) use($filter){
                            $q->where('department_id', $filter['department_id']);
                            $q->orWhere('department_id', null);
                        });
                    }

                    if(isset($filter['level_id']) && !empty($filter['level_id'])){
                        $q->where(function($q) use($filter){
                            $q->where('level_id', $filter['level_id']);
                            $q->orWhere('level_id', null);
                        });
                    }
                });
            }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));

        return $result;
    }

    public function find($id)
    {
        return Survey::find($id);
    }

    public function save($data)
    {
        return Survey::create($data);
    }

    public function update($id, $data)
    {
        return Survey::find($id)->update($data);
    }

    public function delete($id)
    {
        return Survey::find($id)->delete();
    }

    public function deleteSurveyQuestion($survey_id)
    {
        return SurveyQuestion::where('survey_id', $survey_id)->delete();
    }

    public function deleteSurveyParticipants($survey_id)
    {
        return SurveyParticipant::where('survey_id', $survey_id)->delete();
    }

    public function deleteSurveyAnswers($survey_id)
    {
        return SurveyAnswer::where('survey_id', $survey_id)->delete();
    }

    public function surveyAllocation($data)
    {
        return SurveyParticipant::create($data);
    }

    public function findSurveyParticipant($survey_id, $organization_id)
    {
        return SurveyParticipant::where('survey_id', $survey_id)->where('organization_id', $organization_id)->get();
    }

    public function deleteSurveyParticipant($survey_id, $organization_id)
    {
        return SurveyParticipant::where('survey_id', $survey_id)->where('organization_id', $organization_id)->delete();
    }
  
    public function updateOrCreateSurveyParticipant($inputData)
    {
        if(isset($inputData['organization_ids']) && !empty($inputData['organization_ids'])){
            foreach ($inputData['organization_ids'] as $organization_id) {
                $checkParticipant = $this->findSurveyParticipant($inputData['survey_id'], $organization_id);
                if(isset($checkParticipant) && !empty($checkParticipant)){
                    $this->deleteSurveyParticipant($inputData['survey_id'], $organization_id);
                    $this->createSurveyParticipant($inputData, $organization_id);
                }else{
                    $this->createSurveyParticipant($inputData, $organization_id);
                }
            }
        }
    }

    public function createSurveyParticipant($inputData, $organization_id)
    {
        if ($inputData['type'] == 1) {
            if(isset($inputData['department_ids']) && !empty($inputData['department_ids'])){
                foreach ($inputData['department_ids'] as $department_id) {
                    $data['survey_id'] = $inputData['survey_id'];
                    $data['organization_id'] = $organization_id;
                    $data['department_id'] = $department_id;
                    $this->surveyAllocation($data);
                }
            }
        } elseif ($inputData['type'] == 2) {
            if(isset($inputData['level_ids']) && !empty($inputData['level_ids'])){
                foreach ($inputData['level_ids'] as $level_id) {
                    $data['survey_id'] = $inputData['survey_id'];
                    $data['organization_id'] = $organization_id;
                    $data['level_id'] = $level_id;
                    $this->surveyAllocation($data);
                }
            }
        }
    }

    public function saveSurveyAnswer($data)
    {
        return SurveyAnswer::create($data);
    }

    public function getEmployeeSurveyResponse($data)
    {
        return SurveyAnswer::select('survey_question_id', 'answer')->where('survey_id', $data['survey_id'])->where('employee_id', $data['employee_id'])->get();
    }

    public function isResponseSubmitted($survey_id)
    {
        return SurveyAnswer::where('survey_id', $survey_id)->where('employee_id', optional(auth()->user()->userEmployer)->id)->first();
    }
   
    public function responseGivenEmployeeList($survey_id){
        // return SurveyAnswer::where('survey_id', $survey_id)->get()->groupBy('employee_id');
        return SurveyAnswer::where('survey_id', $survey_id)->groupBy('employee_id')->pluck('employee_id');
    }

    public function checkResponseSubmitted($data)
    {
        return SurveyAnswer::where('survey_id', $data['surveyId'])->where('employee_id', $data['employeeId'])->where('survey_question_id', $data['surveyQnId'])->first();
    }

}
