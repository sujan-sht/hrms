<?php

namespace App\Modules\Training\Repositories;

interface TrainingParticipantInterface
{
    public function getList($training_id);

    public function findAll($training_id, $limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function findOne($id);

    public function findByEmpId($emp_id);

    public function create($data);

    public function update($id, $data);

    public function delete($id);

    public function deleteParticipant($training_id);
}
