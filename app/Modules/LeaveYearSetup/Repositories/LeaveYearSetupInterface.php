<?php

namespace App\Modules\LeaveYearSetup\Repositories;

interface LeaveYearSetupInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function findOne($id);

    public function find();

    public function findEnglishLeaveYear();

    public function create($data);

    public function update($id, $data);

    public function delete($id);

    public function getCurrentLeaveYear();

    public function getLeaveYear();

    public function getLeaveYearList();
    public function getActiveLeaveYearList();

}
