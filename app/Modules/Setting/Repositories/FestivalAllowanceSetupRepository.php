<?php
namespace App\Modules\Setting\Repositories;

use App\Modules\FestivalAllowance\Entities\Setting;
use App\Modules\Setting\Entities\FestivalAllowance;
use App\Modules\Setting\Repositories\FestivalAllowanceSetupInterface;

class FestivalAllowanceSetupRepository implements FestivalAllowanceSetupInterface
{
    public function getdata(){
        return FestivalAllowance::first();

    }
    public function find($id){
        return FestivalAllowance::find($id);
    }

    public function save($data){
        return FestivalAllowance::create($data);
    }

    public function update($id,$data){
        $result = $this->find($id);
        return $result->update($data);
    }
  
}
