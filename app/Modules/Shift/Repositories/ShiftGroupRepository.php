<?php

namespace App\Modules\Shift\Repositories;

use App\Modules\Shift\Entities\ShiftGroup;
use App\Modules\Shift\Entities\GroupMember;
use App\Modules\Shift\Entities\Shift;
use App\Modules\Shift\Entities\ShiftGroupMember;

class ShiftGroupRepository implements ShiftGroupInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1])
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            $filter['org_id'] = optional($authUser->userEmployer)->organization_id;
        }
        $result = ShiftGroup::when(array_keys($filter, true), function ($query) use ($filter) {
            $authUser = auth()->user();
            if (isset($filter['shift_id'])) {
                $query->where('shift_id', '=', $filter['shift_id']);
            }
            if (isset($filter['org_id'])) {
                $query->where('org_id', '=', $filter['org_id']);
            }
        })->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }

    public function count($filter = [])
    {
        $result = ShiftGroup::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['shift_id'])) {
                $query->where('shift_id', '=', $filter['shift_id']);
            }
            if (isset($filter['org_id'])) {
                $query->where('org_id', '=', $filter['org_id']);
            }
        })->count();
        return $result;
    }

    public function find($id)
    {
        return ShiftGroup::find($id);
    }

   public function getList($filter = [])
{
    $query = ShiftGroup::with('shiftSeason_info');

    // Apply organization filter if org_id is set
    if (!empty($filter['organization_id'])) {
        $query->where('org_id', $filter['organization_id']);
    }

    $groups = $query->get()
            ->mapWithKeys(function ($group) {
                $groupName = $group->group_name;
                if ($group->shiftSeason_info) {
                    $groupName .= ' (' . $group->shiftSeason_info->date_from . ' TO ' . $group->shiftSeason_info->date_to . ')';
                }
                return [$group->id => $groupName];
            })->toArray();

    return $groups;
}

    public function save($data)
    {
        return ShiftGroup::create($data);
    }

    public function update($id, $data)
    {
        $Group = ShiftGroup::find($id);
        return $Group->update($data);
    }

    public function delete($id)
    {
        $team = ShiftGroup::find($id);
        $team->getGroupMember()->delete();
        return  $team->delete();
    }
    public function saveGroupMember($data)
    {
        return ShiftGroupMember::create($data);
    }

    public function updateGroupMember($id, $data)
    {
        $group = ShiftGroup::find($id);
        $group->getGroupMember()->delete();
        for ($i = 0; $i < sizeof($data); $i++) {
            $group_member['group_member'] = $data[$i];
            $group_member['group_id'] = $id;
            $this->saveGroupMember($group_member);
        }
        return true;
    }

    public function deleteGroupMember($id)
    {
        $group = ShiftGroup::find($id);
        $group->getGroupMember()->delete();
        return true;
    }

    public function deleteByShift($shift_id)
    {
        $group = ShiftGroup::whereShiftId($shift_id)->delete();
        return true;
    }

    public function findOneByGroup($org_id, $group_name)
    {
        $group = ShiftGroup::where('org_id', $org_id)->where('group_name', $group_name)->first();
        return $group;
    }

    public function findOneByOrg($org_id)
    {
        $group = ShiftGroup::where('org_id', $org_id)->first();
        return $group;
    }

    public function checkShiftExists($emp_id)
    {
        $isExists = false;
        $shiftGrpMember = ShiftGroupMember::where('group_member', $emp_id)->first();
        if (isset($shiftGrpMember)) {
            $shiftGroup = ShiftGroup::where('id', $shiftGrpMember->group_id)->first();
            if (isset($shiftGroup)) {
                $shift = Shift::where('id', $shiftGroup->shift_id)->first();
                if (isset($shift)) {
                    $isExists = true;
                }
            }
        }
        return $isExists;
    }
}
