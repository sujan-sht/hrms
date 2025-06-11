<?php

namespace App\Modules\Appraisal\Repositories;

interface CompetencyInterface
{
    public function findAll();

    public function findOne($id);

    public function save($data);

    public function update($id, $data);

    public function delete($id);
}
