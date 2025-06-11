<?php 

namespace App\Modules\Tada\Repositories;

interface BillInterface
{
	public function findAll($limit=null, $filter, $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1]);

	public function find($id);

	public function update($id, $data);

	public function save($data);

	public function delete($id);

	public function getList();

	public function upload($file, $folder_name);

	public function getPath($folder_name);

	public function uploadBills($bills);

    public function saveBills($images, $tada_id);
}