<?php

namespace App\Modules\Training\Repositories;

interface TrainingInterface
{
    public function getList();

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function findOne($id);

    public function create($data);

    public function update($id, $data);

    public function delete($id);

    //Training Report
    public function countFacilitation();
    public function countLocation();
    public function countType();
    public function no_of_mandays_month_and_division_wise();
}
