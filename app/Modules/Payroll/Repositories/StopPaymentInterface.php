<?php

namespace App\Modules\Payroll\Repositories;

interface StopPaymentInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1]);

    public function find($id);

    public function getList($params=[]);

    public function save($data);

    public function update($id, $data);

    public function delete($id);

    public function getStopPayment($calendar_type,$employee_id, $start_date, $end_date);

}
