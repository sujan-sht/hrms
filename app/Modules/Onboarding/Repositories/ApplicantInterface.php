<?php

namespace App\Modules\Onboarding\Repositories;

interface ApplicantInterface
{
    public function getList();

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function findOne($id);

    public function create($data);

    public function update($id, $data);

    public function delete($id);

    public function uploadResume($file);

    public function uploadCoverLetter($file);
}
