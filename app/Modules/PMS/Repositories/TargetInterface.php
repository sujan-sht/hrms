<?php

namespace App\Modules\PMS\Repositories;

interface TargetInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function findOne($id);

    public function create($data);

    public function update($id, $data);

    public function delete($id);

    public function KpiData($kpi_id);

    public function getTargetDetails($id);
    public function findTargetDetails($target_id, $quarter);
    public function findTargetDetailsByEmployee($employee_id, $quarter);

    //set target value
    public function storeAchievedValue($data);

    //set achieved value
    public function updateAchievedValue($id, $data);

    //Report
    public function findReport($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function targetDetailKpiwise($kpi_id);

    public function findTargetAchievement($id);

    public function updateTargetValues($id, $data);

    // public function deleteTarget($kpi_id);

    public function deleteTargetAchievement($kpi_id, $employee_id);

    public function getEmployeePMSList($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function findPMSEmployee($id);

    public function updateStatusPMSEmployee($id, $data);

    public function employeeTargetReportQuarterwise($employee_id);
}
