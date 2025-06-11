<?php

namespace App\Modules\Setting\Repositories;

use App\Modules\Setting\Entities\Functional;
use App\Modules\Setting\Entities\FunctionalOrganization;

class FunctionRepository implements FunctionInterface
{

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $departmentModel  = Functional::query();
        $departmentModel->when(true, function ($query) use ($filter) {});
        $result = $departmentModel->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : config('attendance.export-length'));
        return $result;
    }
    public function find($id)
    {
        return Functional::find($id);
    }

    public function save($data)
    {
        $department =  Functional::create($data);
        return $department;
    }

    public function update($id, $data)
    {
        $result = $this->find($id);
        $flag = $result->update($data);
        return $flag;
    }

    public function delete($id)
    {
        $flag = Functional::destroy($id);
        return $flag;
    }

    public function getList()
    {
        return Functional::pluck('title', 'id');
    }
}
