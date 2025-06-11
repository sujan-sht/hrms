<?php

namespace App\Modules\Payroll\Repositories;

use App\Modules\Payroll\Entities\ThresholdBenefitSetup;

class ThresholdBenefitSetupRepository implements ThresholdBenefitSetupInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'ASC'], $status = [0, 1])
    {
        $result = ThresholdBenefitSetup::query();
        if (auth()->user()->user_type != 'admin' && auth()->user()->user_type != 'super_admin') {
            $result->where('created_by', auth()->user()->id);
        }

        $result = $result->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }

    public function find($id)
    {
        return ThresholdBenefitSetup::where('deduction_setup_id',$id)->first();
    }

    public function getList()
    {
        $result = ThresholdBenefitSetup::pluck('title', 'id');
        return $result;
    }
    public function getIds()
    {
        $result = ThresholdBenefitSetup::pluck('deduction_setup_id')->toArray();
        return $result;
    }

    public function getThresholdList()
    {
        $result = ThresholdBenefitSetup::pluck('amount', 'deduction_setup_id');
        return $result;
    }

    public function save($data)
    {
        return ThresholdBenefitSetup::create($data);
    }

    public function update($id, $data)
    {
        $result = ThresholdBenefitSetup::find($id);
        return $result->update($data);
    }

    public function delete($id)
    {
        return ThresholdBenefitSetup::destroy($id);
    }
    public function updateOrCreate($data){
        $deduction = ThresholdBenefitSetup::where('deduction_setup_id',$data['deduction_setup_id'])->first();
        // dd($deduction);
        if(isset($deduction)){
            $deduction->update(
                [
                    'deduction_setup_id' =>  $data['deduction_setup_id'],
                    'amount' => $data['amount'],
                ],
                $data
            );
        }
        else{

            ThresholdBenefitSetup::create(
                [
                    'deduction_setup_id' =>  $data['deduction_setup_id'],
                    'amount' => $data['amount'],
                ],
                $data
            );
        }
    }
}
