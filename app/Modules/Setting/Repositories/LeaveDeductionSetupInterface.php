<?php

namespace App\Modules\Setting\Repositories;

interface LeaveDeductionSetupInterface
{
    public function findAll();

    public function findOne($id);

    public function create($data);

    public function update($id,$data);

    public function delete($id);
}
