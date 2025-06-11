<?php
namespace App\Modules\Setting\Repositories;

use App\Modules\Setting\Entities\OtRateIncomeHeading;
use App\Modules\Setting\Entities\OtRateSetup;

class OTRateSetupRepository implements OTRateSetupInterface
{

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = OtRateSetup::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['organization_id']) && !empty($filter['organization_id'])) {
                $query->where('organization_id', $filter['organization_id']);
            }
        })
        ->orderBy($sort['by'], $sort['sort'])
        ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));

        return $result;
    }

    public function findOne($data) {
        return OtRateSetup::where('organization_id',$data['organization_id'])->where('ot_type',$data['ot_type'])->first();
    }
    public function findOtRateByOrganization($data) {
        return OtRateSetup::where('organization_id',$data['organization_id'])->get();
    }
    public function findOtRatef($data){

    }

    public function create($data) {
        return OtRateSetup::create($data);
    }
    public function createOtDetail($data) {
        return OtRateIncomeHeading::create($data);
    }


    public function update($id,$data) {
        $result = $this->findOne($id);
        return $result->update($data);
    }
  
    public function delete($id) {
        $result = $this->findOne($id);
        return $result->delete();
    }
    public function deleteChild($id) {
        $result = OtRateIncomeHeading::where('ot_rate_setup_id',$id)->delete();
        return $result;
    }
}
