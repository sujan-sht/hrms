<?php

namespace App\Modules\Payroll\Repositories;

use Illuminate\Http\Request;

interface HoldPaymentInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1]);

    public function find($id);

    public function getList($params=[]);

    public function save($data);

    public function update($id, $data);

    public function updateStatus($data);

    public function getStatus();

    public function delete($id);

    public function getHoldPayment($year, $month,$organizationId);

    public function getHoldPaymentEmployee($year, $month,$organizationId);

    public function getHoldPaymentEmployeeWithStatus($year, $month, $organizationId);

    public function getHoldPaymentEmployeeNameList($year, $month,$organizationId);

    public function getAllHoldPaymentByEmployee($id);

    public function getFinalizedPayrollMonth(Request $request);
}
