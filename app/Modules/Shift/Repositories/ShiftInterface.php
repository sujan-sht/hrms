<?php

namespace App\Modules\Shift\Repositories;

interface ShiftInterface
{
	public function findAll($limit, $filter, $sort);

	public function save($data);

	public function getIdByTitle($title);

	public function find($id);

	public function update($id, $data);

	public function delete($id);

	public function getShiftByOrganization($org_id);

	public function getList();
    public function getListOrganizationWise($org_id);

}
