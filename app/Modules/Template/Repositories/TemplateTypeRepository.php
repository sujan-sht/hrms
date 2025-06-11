<?php

namespace App\Modules\Template\Repositories;

use App\Modules\Template\Entities\TemplateType;

class TemplateTypeRepository implements TemplateTypeInterface
{
    public function getList()
    {
        return TemplateType::pluck('name', 'id');
    }

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = TemplateType::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['title']) && !empty($filter['title'])) {
                $query->where('title', 'like', '%' . $filter['title'] . '%');
            }
            if (isset($filter['template_type']) && !empty($filter['template_type'])) {
                $query->whereHas('template', function ($q) use ($filter) {
                    $q->where('template_type_id', $filter['template_type']);
                });
            }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));

        return $result;
    }

    public function findOne($id)
    {
        return TemplateType::find($id);
    }

    public function create($data)
    {
        return TemplateType::create($data);
    }

    public function update($id, $data)
    {
        $result = $this->findOne($id);

        return $result->update($data);
    }

    public function delete($id)
    {
        return TemplateType::destroy($id);
    }

    public function findBySlug($slug)
    {
        return TemplateType::where('slug', $slug)->first();
    }
}
