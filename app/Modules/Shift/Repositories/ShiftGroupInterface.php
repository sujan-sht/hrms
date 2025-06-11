<?php
/**
 * Created by PhpStorm.
 * User: bidhee
 * Date: 9/9/19
 * Time: 11:59 AM
 */

namespace App\Modules\Shift\Repositories;


interface ShiftGroupInterface
{
    public function findAll($limit=null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1]);

    public function count($filter = []);

    public function find($id);

    public function getList($filter = []);

    public function save($data);

    public function update($id,$data);

    public function delete($id);

    public function saveGroupMember($data);

    public function updateGroupMember($id,$data);

    public function deleteGroupMember($id);

    public function deleteByShift($shift_id);

    public function findOneByGroup($org_id, $group_name);

    public function findOneByOrg($org_id);

    public function checkShiftExists($emp_id);

}
