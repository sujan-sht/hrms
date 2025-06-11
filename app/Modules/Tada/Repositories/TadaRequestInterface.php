<?php

namespace App\Modules\Tada\Repositories;

interface TadaRequestInterface
{
    public function findAll($limit = null, $filter, $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1]);

    public function find($id);

    public function update($id, $data);

    public function save($data);

    public function delete($id);

    public function getList();

    public function getStatusList();

    public function getListById($id);

    public function uploadExcel($file);

    public function findTeamRequest($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function getEmployeeTadaRequest($limit='');


}
