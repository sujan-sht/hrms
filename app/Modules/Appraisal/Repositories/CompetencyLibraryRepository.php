<?php

namespace App\Modules\Appraisal\Repositories;

use App\Modules\Appraisal\Entities\CompetencyLibrary;

class CompetencyLibraryRepository implements CompetencyLibraryInterface
{
    protected $model;

    public function __construct(CompetencyLibrary $competencyLibrary)
    {
        $this->model = $competencyLibrary;
    }

    public function findAll($limit = null, $filter = [])
    {
        $result = $this->model->query();
        $result = $result->latest()->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }

    public function findOne($id)
    {
        return $this->model->where('id',$id)->first();
    }

    public function save($data)
    {
        return $this->model->create($data);
    }

    public function update($id, $data)
    {
        $competencyLibrary = $this->findOne($id);
        $competencyLibrary->fill($data);
        $competencyLibrary->update();

        return $competencyLibrary;
    }

    public function delete($id)
    {
        $this->findOne($id)->delete();
        return true;
    }
}
