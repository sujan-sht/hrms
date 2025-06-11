<?php

namespace App\Modules\Tada\Repositories;

use App\Modules\Tada\Entities\TadaSubType;
use App\Modules\Tada\Entities\TadaType;

/**
 * TadaTypeRepository
 */
class TadaTypeRepository implements TadaTypeInterface
{
    public function findAll($limit = null, $filter, $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1])
    {
        $result = TadaType::orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }

    public function find($id)
    {
        return TadaType::find($id);
    }

    public function update($id, $data)
    {
        return TadaType::find($id)
            ->update($data);
    }

    public function save($data)
    {
        return TadaType::create($data);
    }

    public function delete($id)
    {
        return TadaType::find($id)->delete();
    }

    public function getList($type = null)
    {
        if ($type == 'claim') {
            return TadaType::where('status', 1)->where('type', 1)->pluck('title', 'id');
        } elseif ($type == 'request') {
            return TadaType::where('status', 1)->where('type', 0)->pluck('title', 'id');
        } else {
            return TadaType::where('status', 1)->pluck('title', 'id');
        }
    }

    public function saveSubType($data)
    {
        return TadaSubType::create($data);
    }

    public function deleteSubType($id)
    {
        return TadaSubType::where('tada_type_id', $id)->delete();
    }

    public function subTypeLists()
    {
        return TadaSubType::pluck('sub_type_title', 'id');
    }
}
