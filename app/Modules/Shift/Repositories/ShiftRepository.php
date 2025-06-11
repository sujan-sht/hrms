<?php

namespace App\Modules\Shift\Repositories;

use App\Modules\Shift\Entities\Shift;
use App\Modules\Shift\Entities\ShiftGroup;

/**
 * ShiftTypeRepository
 */
class ShiftRepository implements ShiftInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = Shift::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['title']) && !is_null($filter['title'])) {
                $query->where('title', 'like', '%' . $filter['title'] . '%');
            }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));

        return $result;
    }

    public function save($data)
    {
        return Shift::create($data);
    }

    public function getIdByTitle($title)
    {
        return Shift::whereTitle($title)->first()->id;
    }

    public function find($id)
    {
        return Shift::find($id);
    }

    public function update($id, $data)
    {
        return Shift::find($id)->update($data);
    }

    public function delete($id)
    {
        return Shift::find($id)->delete();
    }


    public function getShiftByOrganization($org_id)
    {
        return Shift::where('org_id', $org_id)->get();
    }

    public function getList()
    {
        // $shifts = Shift::pluck('title', 'id');
        // return $shifts;

        // ranjan
        $shifts = Shift::all();
        $returnArray = [];
        foreach ($shifts as $key => $value) {
            $returnArray[$value->id] = $value->title;

            if ($value->title == 'Custom') {
                $returnArray[$value->id] = $value->title . ': ' . $value->custom_title;
            }
        }
        return $returnArray;
    }

    public function getListOrganizationWise($org_id)
    {
        $shifts = [];
        $shiftGroups = ShiftGroup::where('org_id',$org_id)->get();
        foreach($shiftGroups as $shiftGroup){
            $shift = $shiftGroup->shift;
            if($shift){
                $shifts[$shift->id] = $shift->title ?? $shift->custom_title;
            }
        }
        return $shifts;
    }
}
