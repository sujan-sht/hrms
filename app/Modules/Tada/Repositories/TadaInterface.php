<?php

namespace App\Modules\Tada\Repositories;

interface TadaInterface
{
    public function findAll($limit = null, $filter, $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1]);

    public function find($id);

    public function update($id, $data);

    public function save($data);

    public function delete($id);

    public function getList();

    public function getStatusList();

    public function uploadExcel($file);

    public function sendMailNotification($model, $type);

    public function findTeamClaim($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function getEmployeeClaim($limit = '');
}
