<?php

namespace App\Modules\Onboarding\Repositories;

use App\Modules\Onboarding\Entities\Interview;
use App\Modules\Onboarding\Entities\Evaluation;
use App\Modules\Onboarding\Entities\EvaluationDetail;

class EvaluationRepository implements EvaluationInterface
{
    public function getList()
    {
        return Evaluation::pluck('reference_number', 'id');
    }

    public function getListWithTitle()
    {
        $models = Evaluation::get();
        foreach ($models as $model) {
            $list[$model->id] = $model->reference_number . ' :: ' . $model->title;
        }

        return $list;
    }

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = Evaluation::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['organization']) && !empty($filter['organization'])) {
                $query->whereHas('applicantModel.mrfModel',function($query){
                $query->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
            });
            }

            if (isset($filter['mrf']) && !empty($filter['mrf'])) {
                $query->whereHas('applicantModel', function ($query) use ($filter) {
                    $query->where('manpower_requisition_form_id', $filter['mrf']);
                });
            }
            if (isset($filter['organization']) && !empty($filter['organization'])) {
                $query->whereHas('applicantModel.mrfModel', function ($query) {
                    $query->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
                });
            }
            if(setting('calendar_type') == 'BS'){
                if (isset($filter['date']) && !empty($filter['date'])) {
                   $query->where('created_at','like', date_converter()->nep_to_eng_convert($filter['date']). '%');
                }
            }else{
                if (isset($filter['date']) && !empty($filter['date'])) {
                    $query->where('created_at', 'like', $filter['date'] . '%');
                }
            }
            if (isset($filter['parent']) && !empty($filter['parent'])) {
                $query->where('parent_id', NULL);
            }
            if (isset($filter['parent_id']) && !empty($filter['parent_id'])) {
                $query->where('parent_id', $filter['parent_id']);
            }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));

        return $result;
    }

    public function findOne($id)
    {
        return Evaluation::find($id);
    }

    public function create($data)
    {
        $maxScore = 0;
        $totalScore = 0;
        $employeeId = $data['employee_id'];

        // check for parent
        $parentModel = Evaluation::where([
            'applicant_id' => $data['applicant_id'],
            'interview_id' => $data['interview_id'],
            'interview_level_id' => $data['interview_level_id'],
        ])->orderBy('id', 'ASC')->first();
        if (!$parentModel) {
            $data['employee_id'] = null;
            $parentModel = Evaluation::create($data);
            $data['employee_id'] = $employeeId;
        }

        $data['parent_id'] = $parentModel->id;
        $model = Evaluation::create($data);
        if ($model) {
            foreach ($data['questions'] as $key => $question) {
                $maxScore += 5;
                $totalScore += $data['scores'][$key];
                $evaluationDetailModel = new EvaluationDetail();
                $evaluationDetailModel->evaluation_id = $model->id;
                $evaluationDetailModel->question = $question;
                $evaluationDetailModel->score = $data['scores'][$key];

                $evaluationDetailModel->save();
            }
            $model->total_score = $totalScore;
            $model->percentage = ($totalScore / $maxScore) * 100;
            if ($model->save()) {
                $parentModel->total_score += $totalScore;
                $parentModel->save();
                // $interviewModel = Interview::where('id', $data['interview_id'])->first();
                // $interviewModel->status = "2";
                // $interviewModel->save();
            }
        }

        return $model;
    }

    public function update($id, $data)
    {
        $result = $this->findOne($id);

        return $result->update($data);
    }

    public function delete($id)
    {
        $result = Evaluation::destroy($id);
        if ($result) {
            EvaluationDetail::where('evaluation_id', $id)->delete();
        }

        return $result;
    }

    public function upload($file)
    {
        // $imageName = $file->getClientOriginalName();
        // $fileName = time() . '-' . preg_replace('[ ]', '-', $imageName);
        // $file->move(public_path() . '/' . Evaluation::IMAGE_PATH, $fileName);

        // return $fileName;
    }

    public function checkData($params)
    {
        return Evaluation::where([
            'employee_id' => $params['employee_id'],
            'leave_type_id' => $params['leave_type_id'],
            'date' => $params['date']
        ])->first();
    }
}
