<?php

namespace App\Modules\EmployeeMassIncrement\Repositories;

interface EmployeeMassIncrementInterface
{

    public function save($data);

    public function getList();
    public function find($id);
    public function all();
    public function update($employeeMassIncrement,$data);

}
