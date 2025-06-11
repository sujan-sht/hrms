<?php 

namespace App\Modules\Tada\Repositories;

use App\Modules\Tada\Entities\TadaBillType;

/**
 * 
 */
class BillTypeRepository implements BillTypeInterface
{
	 public function findAll($limit = null, $filter, $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1])
    {
        $result = TadaBillType::orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }

	public function find($id)
	{
		return TadaBillType::find($id);
	}

	public function update($id, $data)
	{
		return TadaBillType::find($id)
			->update($data);
	}

	public function save($data)
	{
		return TadaBillType::create($data);
	}

	public function delete($id)
	{
		return TadaBillType::find($id)->delete();
	}

	public function getList()
	{
		return TadaBillType::where('status', 1)->pluck('title', 'id');
	}
}