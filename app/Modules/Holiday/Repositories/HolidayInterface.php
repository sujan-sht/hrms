<?php

namespace App\Modules\Holiday\Repositories;

interface HolidayInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1]);

    public function find($id);

    public function getList();

    public function save($data);

    public function update($id, $data);

    public function delete($id);

    public function deleteHolidayDetails($holidayId);

    public function getHolidayDetails($holidayId);

    public function getGenderType();

    public function getReligionType();
    public function getHolidayList();

    public function getLatestId();

    public function findAllGroupData($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1]);

    public function getIdByGroupId($groupId);

    public function getHolidayBranch($organisationId, $provinceId, $districtId, $branchId);

    public function getHolidayInfoBYOrgIdProvinceIdDistrictId($organisationId, $provinceId, $districtId);

    public function getHolidayBranchAll($organisationId, $provinceId, $districtId);

    public function updateOrCreateHolidayAccordingToBranch($holidayDetails, $branch);

    public function createHolidayDetailsAccordingToBranch($holidayId, $updateHoliday);

}
