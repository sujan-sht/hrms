<?php 

namespace App\Modules\Tada\Repositories;

use App\Modules\Tada\Entities\TadaAllowanceType;

/**
 * AllowanceTypeRepository
 */
class AllowanceTypeRepository implements AllowanceTypeInterface
{
	public function findAll($limit=null, $filter, $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1])
	{
		$result = TadaAllowanceType::orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
		return $result;
	}

	public function find($id)
	{
		return TadaAllowanceType::find($id);
	}

	public function update($id, $data)
	{
		return TadaAllowanceType::find($id)
		->update($data);
	}

	public function save($data)
	{
		return TadaAllowanceType::create($data);
	}

	public function delete($id)
	{
		return TadaAllowanceType::find($id)->delete();
	}

	public function getList()
	{
		return TadaAllowanceType::where('status', 1)->pluck('title', 'id');
	}
}