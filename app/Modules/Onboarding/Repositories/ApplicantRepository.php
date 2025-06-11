<?php

namespace App\Modules\Onboarding\Repositories;

use App\Modules\Onboarding\Entities\Applicant;

class ApplicantRepository implements ApplicantInterface
{
    public function getList()
    {
        $list = [];
        if (auth()->user()->user_type == 'division_hr') {
            $models = Applicant::where('status', 2)->whereHas('mrfModel', function ($query) {
                $query->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
            })->get();
        }
        else{
            $models = Applicant::where('status', 2)->get();
        }
      
        foreach ($models as $model) {
            $list[$model->id] = $model->getFullName() . ' :: ' . optional($model->mrfModel)->title;
        }

        return $list;
    }

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = Applicant::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['organization']) && !empty($filter['organization'])) {
                $query->whereHas('mrfModel', function ($query) {
                    $query->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
                });
            }
            if(setting('calendar_type') == 'BS'){
                if (isset($filter['date']) && !empty($filter['date'])) {
                   $query->where('created_at', 'like', date_converter()->nep_to_eng_convert($filter['date']) . '%');
                }
            }else{
                if (isset($filter['date']) && !empty($filter['date'])) {
                    $query->where('created_at', 'like', $filter['date'] . '%');
                }
            }
            if (isset($filter['mrf']) && !empty($filter['mrf'])) {
                $query->where('manpower_requisition_form_id', $filter['mrf']);
            }
            if (isset($filter['status']) && !empty($filter['status'])) {
                $query->where('status', $filter['status']);
            }
            if (isset($filter['applicant']) && !empty($filter['applicant'])) {
                $query->where('first_name', 'like', '%' . $filter['applicant'] . '%');
                $query->orWhere('middle_name', 'like', '%' . $filter['applicant'] . '%');
                $query->orWhere('last_name', 'like', '%' . $filter['applicant'] . '%');
            }
            if (isset($filter['mobile']) && !empty($filter['mobile'])) {
                $query->where('mobile', 'like', '%' . $filter['mobile'] . '%');
            }

            if (isset($filter['source']) && !empty($filter['source'])) {
                $query->where('source', $filter['source']);
            }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));

        return $result;
    }

    public function findOne($id)
    {
        return Applicant::find($id);
    }

    public function create($data)
    {
        return Applicant::create($data);
    }

    public function update($id, $data)
    {
        $result = $this->findOne($id);

        return $result->update($data);
    }

    public function delete($id)
    {
        return Applicant::destroy($id);
    }

    public function uploadResume($file)
    {
        $imageName = $file->getClientOriginalName();
        $fileName = time() . '-' . preg_replace('[ ]', '-', $imageName);
        $file->move(public_path() . '/' . Applicant::RESUME_PATH, $fileName);

        return $fileName;
    }

    public function uploadCoverLetter($file)
    {
        $imageName = $file->getClientOriginalName();
        $fileName = time() . '-' . preg_replace('[ ]', '-', $imageName);
        $file->move(public_path() . '/' . Applicant::COVER_LETTER_PATH, $fileName);

        return $fileName;
    }
}
