<?php

namespace App\Modules\BusinessTrip\Repositories;

interface BusinessTripInterface
{
    public function findAll($limit, $filter, $sort);

    public function find($id);

    public function save($data);

    public function update($id, $data);

    public function delete($id);

    public function findTeamBusinessTripRequests($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function sendMailNotification($model);

    public function getEmployeeBusinessTrips($employeeId = null, $limit = null);

    public function empAllowanceSetup($data, $filter, $limit = null);

    public function getSetWiseAllowaceSetup($data,$setUpData,$filter,$limit);

    public function arrangeData($allowance_type);

    public function getAllowanceData($limit,$filter);
}
