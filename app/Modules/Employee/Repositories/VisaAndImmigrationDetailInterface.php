<?php

namespace App\Modules\Employee\Repositories;

interface VisaAndImmigrationDetailInterface
{
    public function findAll($empId);

    public function findOne($id);

    public function save($data);

    public function update($id, $data);

    public function delete($id);
}
