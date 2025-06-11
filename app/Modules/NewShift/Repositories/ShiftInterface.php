<?php

namespace App\Modules\NewShift\Repositories;

interface ShiftInterface
{
    public function findAll($limit, $filter, $sort);

    public function save($data);

    public function getIdByTitle($title);

    public function find($id);

    public function update($id, $data);

    public function delete($id);

    public function getNewShiftByOrganization($org_id);

    public function getList();

    //Requests
    public function findAllRequests($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function findRequest($id);

    public function saveRequest($data);

    public function updateRequest($id, $data);

    public function deleteRequest($id);
}
