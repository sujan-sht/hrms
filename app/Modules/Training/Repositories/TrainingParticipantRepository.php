<?php

namespace App\Modules\Training\Repositories;

use App\Modules\Training\Entities\TrainingParticipant;

class TrainingParticipantRepository implements TrainingParticipantInterface
{
    public function getList($training_id)
    {
        // return TrainingParticipant::where('training_id', $training_id)->pluck('employee_id', 'id');
        $trainingParticipantList =  TrainingParticipant::where('training_id', $training_id)->get();
        $participantDetails = [];
        if ($trainingParticipantList) {
            foreach ($trainingParticipantList as $trainingParticipant) {
                if ($trainingParticipant->employee_id != null) {
                    $participantDetails[] = [
                        'training_participant_id' => $trainingParticipant->id,
                        'employee_id' => $trainingParticipant->employee_id,
                        'employee_name' => optional($trainingParticipant->employeeModel)->full_name,
                    ];
                }
            }
        }
        return $participantDetails;
    }

    public function findAll($training_id, $limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = TrainingParticipant::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['employee_id']) && !empty($filter['employee_id'])) {
                $query->whereHas('employeeModel', function ($q) use ($filter) {
                    $q->where('id', $filter['employee_id']);
                });
            }
            if (isset($filter['contact_no']) && !empty($filter['contact_no'])) {
                $query->where('contact_no', $filter['contact_no']);
            }
            if (isset($filter['email']) && !empty($filter['email'])) {
                $query->where('email', $filter['email']);
            }
        })
            ->where('training_id', $training_id)
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));
        return $result;
        // $participants = $result->getCollection()->transform(function ($participant, $key) {
        //     //your code here
        //     $participant['asdw']='asdw';
        //     // $part= $participant->groupBy('training_id');
        //     return $participant;
        //     // dd($participant);
        // });

        // return $participants;
    }

    public function findOne($id)
    {
        return TrainingParticipant::find($id);
    }

    public function findByEmpId($emp_id)
    {
        return TrainingParticipant::where('employee_id', $emp_id)->first();
    }

    public function create($data)
    {
        return TrainingParticipant::create($data);
    }

    public function update($id, $data)
    {
        $result = $this->findOne($id);
        return $result->update($data);
    }

    public function delete($id)
    {
        return TrainingParticipant::destroy($id);
    }

    public function deleteParticipant($training_id)
    {
        return TrainingParticipant::where('training_id', $training_id)->delete();
    }


}
