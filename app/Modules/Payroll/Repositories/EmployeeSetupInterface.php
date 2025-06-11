<?php

namespace App\Modules\Payroll\Repositories;

interface EmployeeSetupInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1]);

    public function find($id);

    public function findOne($filter = []);

    public function getList();

    public function save($data);

    public function update($id, $data);

    public function findEmployeeBonus($filter = []);

    public function findEmployeeTaxExclude($filter = []);

    public function delete($id);

    public function updateOrCreate($data);

    public function updateOrCreateBonus($data);

    public function updateOrCreateTaxExclude($data);

    public function findAllGrosssalary($limit = null, $filter = [], $sort = ['by' => 'employee_id', 'sort' => 'asc']);
    public function updateOrCreateGrossSalary($data);
    public function updateGrosssalary($id, $data);


    // public function getActiveDeductionList();

}
