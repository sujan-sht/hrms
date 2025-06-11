<?php

namespace App\Modules\Training\Repositories;

use App\Modules\Training\Entities\TrainingAttendance;

class TrainingAttendanceRepository implements TrainingAttendanceInterface
{
    public function getList($training_id)
    {
        return TrainingAttendance::where('training_id', $training_id)->pluck('participant_name', 'id');
    }

    public function findAll($training_id, $limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = TrainingAttendance::when(array_keys($filter, true), function ($query) use ($filter) {
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
    }

    public function findOne($id)
    {
        return TrainingAttendance::find($id);
    }

    public function create($data)
    {
        return TrainingAttendance::create($data);
    }

    public function update($id, $data)
    {
        $result = $this->findOne($id);
        return $result->update($data);
    }

    public function delete($id)
    {
        return TrainingAttendance::destroy($id);
    }

    public function attendeesAllDetails($training_id, $limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = TrainingAttendance::when(array_keys($filter, true), function ($query) use ($filter) {
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
    }

    public function deleteAttendance($training_id)
    {
        return TrainingAttendance::where('training_id', $training_id)->delete();
    }

    public function trainingAttendeesDetails($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = TrainingAttendance::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['organization'])) {
                $query->whereHas('trainingInfo', function ($q) use ($filter) {
                    $q->where('division_id', $filter['organization']);
                });
            }
            if (isset($filter['training_id']) && !empty($filter['training_id'])) {
                $query->where('training_id', $filter['training_id']);
            }
            if (setting('calendar_type') == 'BS') {
                if (isset($filter['from_nep_date']) && !empty($filter['from_nep_date'])) {
                    $query->whereHas('trainingInfo', function ($q) use ($filter){
                        $q->where('from_date', '>=', date_converter()->nep_to_eng_convert($filter['from_nep_date']));
                    });
                }
                if (isset($filter['to_nep_date']) && !empty($filter['to_nep_date'])) {
                    $query->whereHas('trainingInfo', function ($q) use ($filter){
                        $q->where('to_date', '>=', date_converter()->nep_to_eng_convert($filter['to_nep_date']));
                    });
                }
            } else {
                if (isset($filter['date_range'])) {
                    $query->whereHas('trainingInfo', function ($q) use ($filter){
                        $filterDates = explode(' - ', $filter['date_range']);
                        $q->where('from_date', '>=', $filterDates[0]);
                        $q->where('to_date', '<=', $filterDates[1]);
                    });
                }
            }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));
        return $result;
    }

    public function getAttendeeByFilter($filter)
    {
        return TrainingAttendance::where($filter);
    }
}
