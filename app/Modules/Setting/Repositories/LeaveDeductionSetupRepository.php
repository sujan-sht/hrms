<?php
namespace App\Modules\Setting\Repositories;

use App\Modules\Setting\Entities\LeaveDeductionSetup;
use App\Modules\Setting\Repositories\LeaveDeductionSetupInterface;

class LeaveDeductionSetupRepository implements LeaveDeductionSetupInterface
{
    public function findAll(){
        return LeaveDeductionSetup::all();

    }
    public function findOne($id){
        return LeaveDeductionSetup::find($id);
    }

    public function create($data){
        return LeaveDeductionSetup::create($data);
    }

    public function update($id,$data){
        $result = $this->findOne($id);
        return $result->update($data);
    }

    public function delete($id){
        $result = $this->findOne($id);
        return $result->delete();
    }
}
