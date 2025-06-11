<?php

namespace App\Modules\Employee\Repositories;

interface TrainingDetailInterface
{
    public function findAll($empId);

    public function findOne($id);

    public function update($id, $data);
}
