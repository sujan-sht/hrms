<?php

namespace App\Modules\Appraisal\Repositories;

use App\Modules\Appraisal\Entities\Appraisal;
use App\Modules\Appraisal\Entities\Competency;
use App\Modules\Appraisal\Entities\CompetencyQuestion;
use App\Modules\Appraisal\Entities\Respondent;
use App\Modules\Employee\Entities\EmployeeAppraisalApprovalFlow;
use App\Modules\Employee\Entities\EmployeeApprovalFlow;

class AppraisalRepository implements AppraisalInterface
{
    protected $model;

    public function __construct(Appraisal $appraisal)
    {
        $this->model = $appraisal;
    }

    public function getList($appraisee_id)
    {
        $query = $this->model->query();
        $query->where('appraisee', $appraisee_id);

        $query->whereHas('questionnaire', function ($q) {
            $q->where('form', '!=', 1);
        });
        return $query->get();
    }
    public function getAppraisal($appraisee_id){
        return Appraisal::where('appraisee',$appraisee_id)->first();
    }

    public function findAll($limit = null, $filter = [])
    {
        $query = $this->model->query();

        if(auth()->user()->user_type == 'division_hr'){
            $query->whereHas('employee', function($q){
                $q->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
            });
        }

        if (isset($filter['type']) && $filter['type'] != '') {
            $query = $query->where('type', $filter['type']);
        }

        if (isset($filter['emp_id']) && $filter['emp_id'] != '') {
            $query = $query->where('appraisee', $filter['emp_id']);
        }

        if (isset($filter['questionnaire_id']) && $filter['questionnaire_id'] != '') {
            $query = $query->where('questionnaire_id', $filter['questionnaire_id']);
        }

        if (isset($filter['from_date']) && $filter['from_date'] != '') {
            $query = $query->where('valid_date', '>=', $filter['from_date']);
        }

        if (isset($filter['to_date']) && $filter['to_date'] != '') {
            $query = $query->where('valid_date', '<=', $filter['to_date']);
        }
        $result = $query->with('appraisalResponses')->latest()->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }

    public function findOne($id)
    {
        return $this->model->where('id', $id)->with('respondents')->first();
    }

    public function invitationCodeExist($code)
    {
        return Respondent::where('invitation_code', $code)->whereHas('appraisal', function ($query) {
            $query->where('valid_date', '>=', date('Y-m-d'));
        })->where('already_responded', 0)->first();
    }

    public function findByInvitationCode($code)
    {
        $respondent = Respondent::where('invitation_code', $code)->first();
        // dd($respondent);

        if (!$respondent) {
            abort(404);
        }

        $appraisal = $this->model->where('id', $respondent->appraisal_id)->first();
        $questions = [];

        $questionIds = $appraisal->questionnaire->competency_ids;
        $competancies = Competency::whereIn('id',json_decode($questionIds))->get();
        return $competancies;
        // $questions = CompetencyQuestion::whereIn('competency_id', json_decode($questionIds))->get();
        // return $questions;
    }
    public function findAppraisalByInvitationCode($code)
    {
        $respondent = Respondent::where('invitation_code', $code)->first();
        return $respondent;
    }
    public function findEmployeeApproval($id){
        $employeeApproval = EmployeeAppraisalApprovalFlow::where('employee_id',$id)->first();
        return $employeeApproval;
    }

    public function findResponseById($id)
    {
        $respondent = Respondent::where('id', $id)->with('responses')->first();

        if (!$respondent) {
            abort(404);
        }
        return $respondent;
    }

    public function save($data)
    {
        return $this->model->create($data);
    }

    public function saveRespondents($data)
    {
        return Respondent::create($data);
    }

    public function update($id, $data)
    {
        $appraisal = $this->findOne($id);
        $appraisal->fill($data);
        $appraisal->update();

        return $appraisal;
    }

    public function delete($id)
    {
        $appraisal = $this->findOne($id)->delete();
        return true;
    }
}
