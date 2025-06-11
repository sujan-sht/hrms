<?php

namespace App\Modules\EmployeeRequest\Repositories;

interface EmployeeRequestInterface
{
	public function findAll($limit=null, $filter, $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1]);

	public function findUserRequests($limit = null, $user_id, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $page_number = 1);

	public function find($id);

	public function update($id, $data);

	public function save($data);

	public function delete($id);

	public function benefit();

	public function upload($file);

	public function getTotal($status);

	public function getTotalRequest();

    public function getTotalRequestlist();

    public function getTotalRequestlistbytype($type_id);

    public function getByRequestType($type,$empid);

    public function findAllTodayList($empid);

	public function getEmployeerequest();

    public function findRequestByType($empid,$requesttype);

	public function advanceSearch($limit = 10, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);
}
