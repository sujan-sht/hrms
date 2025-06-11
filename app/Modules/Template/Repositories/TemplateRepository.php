<?php

namespace App\Modules\Template\Repositories;

use App\Modules\Template\Entities\Template;

class TemplateRepository implements TemplateInterface
{
    public function getList()
    {
        return Template::pluck('name', 'id');
    }

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = Template::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['title']) && !empty($filter['title'])) {
                $query->where('title', 'like', '%' . $filter['title'] . '%');
            }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));

        return $result;
    }

    public function findOne($id)
    {
        return Template::find($id);
    }

    public function findByTemplateType($id)
    {
        return Template::where('template_type_id', $id)->first();
    }

    public function create($data)
    {
        return Template::create($data);
    }

    public function update($id, $data)
    {
        $result = $this->findOne($id);

        return $result->update($data);
    }

    public function delete($id)
    {
        return Template::destroy($id);
    }
}
