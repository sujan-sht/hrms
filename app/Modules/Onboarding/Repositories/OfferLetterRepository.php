<?php

namespace App\Modules\Onboarding\Repositories;

use App\Modules\Onboarding\Entities\OfferLetter;

class OfferLetterRepository implements OfferLetterInterface
{
    public function getList()
    {
        // return OfferLetter::pluck('title', 'id');
    }

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = OfferLetter::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['organization']) && !empty($filter['organization'])) {
                $query->whereHas('evaluationModel.applicantModel.mrfModel',function($query){
                $query->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
            });
            }
            if (isset($filter['join_date']) && !empty($filter['join_date'])) {
                $query->where('join_date', 'like', '%'.$filter['join_date'].'%');
            }
        })
        ->orderBy($sort['by'], $sort['sort'])
        ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));

        return $result;
    }

    public function findOne($id)
    {
        return OfferLetter::find($id);
    }

    public function getStatusList()
    {
        return OfferLetter::statusList();
    }


    public function create($data)
    {
        $model = OfferLetter::create($data);

        return $model;
    }

    public function update($id, $data)
    {
        $result = $this->findOne($id);

        $flag = $result->update($data);

        return $flag;
    }

    public function delete($id)
    {
        $oldModelId = $id;

        $flag = OfferLetter::destroy($id);

        return $flag;
    }
}
