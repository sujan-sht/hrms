<?php

namespace App\Modules\Employee\Repositories;

interface EmployeeSubstituteLeaveInterface
{
    public function findAll($limit=null, $filter=[], $sort = ['by' => 'id', 'sort' => 'desc']);

    public function findOne($id);

    public function create($data);

    public function update($id, $data);

    public function delete($id);
    
    public function findTeamleaves($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function findTeamClaimedleaves($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function getMinDate($organizationId);
}
