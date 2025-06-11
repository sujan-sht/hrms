<?php

namespace App\Modules\Training\Repositories;

interface TrainingAttendanceInterface
{
    public function getList($training_id);

    public function findAll($training_id, $limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function findOne($id);

    public function create($data);

    public function update($id, $data);

    public function delete($id);

    public function attendeesAllDetails($training_id, $limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function deleteAttendance($training_id);

    public function trainingAttendeesDetails($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function getAttendeeByFilter($filter);

}
