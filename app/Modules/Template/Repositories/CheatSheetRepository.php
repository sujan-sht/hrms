<?php

namespace App\Modules\Template\Repositories;

use App\Modules\Template\Entities\CheatSheet;

class CheatSheetRepository implements CheatSheetInterface
{
    public function getList()
    {
        return CheatSheet::pluck('name', 'id');
    }

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = CheatSheet::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['title']) && !empty($filter['title'])) {
                $query->where('title', 'like', '%' . $filter['title'] . '%');
            }
            if (isset($filter['short_code']) && !empty($filter['short_code'])) {
                $query->where('short_code', 'like', '%' . $filter['short_code'] . '%');
            }
        })
        ->orderBy($sort['by'], $sort['sort'])
        ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));

        return $result;
    }

    public function findOne($id)
    {
        return CheatSheet::find($id);
    }

    public function create($data)
    {
        return CheatSheet::create($data);
    }

    public function update($id, $data)
    {
        $result = $this->findOne($id);

        return $result->update($data);
    }

    public function delete($id)
    {
        return CheatSheet::destroy($id);
    }

}
