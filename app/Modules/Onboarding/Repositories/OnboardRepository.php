<?php

namespace App\Modules\Onboarding\Repositories;

use App\Modules\Onboarding\Entities\Onboard;

class OnboardRepository implements OnboardInterface
{
    public function getList()
    {
        //
    }

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = Onboard::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['mrf']) && !empty($filter['mrf'])) {
                $query->where('manpower_requisition_form_id', $filter['mrf']);
            }
            if (isset($filter['applicant']) && !empty($filter['applicant'])) {
                $query->where('applicant_id', $filter['applicant']);
            }
        })
        ->orderBy($sort['by'], $sort['sort'])
        ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));

        return $result;
    }

    public function findOne($id)
    {
        return Onboard::find($id);
    }

    public function create($data)
    {
        if(isset($data['boardingTasks'])) {
            foreach ($data['boardingTasks'] as $key => $boardingTaskId) {
                $data['boarding_task_id'] = $boardingTaskId;
                $data['onboard_date'] = setting('calendar_type') == 'BS' ? date_converter()->nep_to_eng_convert($data['dates'][$key]) : $data['dates'][$key];
                $data['status'] = $data['statuses'][$key];
                Onboard::create($data);
            }
        }

        return true;
    }

    public function update($id, $data)
    {
        $result = $this->findOne($id);

        return $result->update($data);
    }

    public function delete($id)
    {
        return Onboard::destroy($id);
    }

}
