<?php

namespace App\Modules\Training\Repositories;
use App\Modules\Training\Entities\TrainingTrainer;

class TrainingTrainerRepository implements TrainingTrainerInterface
{
    public function getList()
    {
        return TrainingTrainer::pluck('title', 'id');
    }

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = TrainingTrainer::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['fiscal_year_id']) && !empty($filter['fiscal_year_id'])) {
                $query->where('fiscal_year_id', $filter['fiscal_year_id']);
            }
            if (isset($filter['division_id']) && !empty($filter['division_id'])) {
                $query->where('division_id', $filter['division_id']);
            }
            if (isset($filter['type']) && !empty($filter['type'])) {
                $query->where('type', $filter['type']);
            }
            if (isset($filter['location']) && !empty($filter['location'])) {
                $query->where('location', $filter['location']);
            }
            if (isset($filter['facilitator']) && !empty($filter['facilitator'])) {
                $query->where('facilitator', $filter['facilitator']);
            }
            if (isset($filter['month']) && !empty($filter['month'])) {
                $query->where('month', $filter['month']);
            }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));

        return $result;
    }

    public function findOne($id)
    {
        return TrainingTrainer::find($id);
    }

    public function create($data)
    {
        return TrainingTrainer::create($data);
    }

    public function updateOrCreate($data)
    {
        TrainingTrainer::updateOrCreate(
            [
                // 'employee_id' =>  $data['employee_id'],
                'training_id' => $data['training_id'],
                // 'reference_id' => $data['reference_id'],
            ],
            $data
        );
    }

    public function update($id, $data)
    {
        $result = $this->findOne($id);
        return $result->update($data);
    }

    public function delete($id)
    {
        return TrainingTrainer::destroy($id);
    }

}
