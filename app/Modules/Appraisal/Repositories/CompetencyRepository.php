<?php

namespace App\Modules\Appraisal\Repositories;

use App\Modules\Appraisal\Entities\Competency;

class CompetencyRepository implements CompetencyInterface
{
    protected $model;

    public function __construct(Competency $competancy)
    {
        $this->model = $competancy;
    }

    public function findAll($limit = null, $filter = [])
    {
        $result = $this->model->query();
        $result = $result->latest()->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }

    public function findCompetancyQuestion(){
        
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
        $competancy = $this->findOne($id);
        $competancy->fill($data);
        $competancy->update();

        return $competancy;
    }

    public function delete($id)
    {
        $this->findOne($id)->delete();
        return true;
    }
}
