<?php

namespace App\Modules\EmployeeRequest\Repositories;

interface EmployeeRequestTypeInterface
{
	public function findAll($limit=null, $filter, $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1]);

	public function find($id);

	public function update($id, $data);

	public function save($data);

	public function delete($id);

	public function getList();


    public function getRequestTypes();

	public function getalllist();

	public function getRequestTypesList();
}
