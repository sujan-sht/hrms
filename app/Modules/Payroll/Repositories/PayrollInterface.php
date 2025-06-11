<?php

namespace App\Modules\Payroll\Repositories;

interface PayrollInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function findOne($id);

    public function getEmployeePayrollList($id);

    public function create($data);

    public function findPayrollEmployee($id);

    public function update($id, $data);

    public function delete($id);

    public function findAllPayrollEmployee();

    public function calculatePayrollDataSum($start_fiscal_year, $payrollModel, $endMonth,$employeeId,$field);

    public function taxDetail($taxableSalary, $employeeModel);

    public function latestOne();

    public function findByOrganizationId($oraganizationId);

    public function getTaxCalculation($totalIncome, $totalDeduction, $festivalBonus = 0, $payrollEmployeeId = null);

}
