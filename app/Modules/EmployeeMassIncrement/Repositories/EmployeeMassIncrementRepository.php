<?php
namespace App\Modules\EmployeeMassIncrement\Repositories;

use App\Modules\EmployeeMassIncrement\Entities\EmployeeMassIncrement;
use App\Modules\EmployeeMassIncrement\Entities\EmployeeMassIncrementDetail;

class EmployeeMassIncrementRepository implements EmployeeMassIncrementInterface
{

    public function all(){
        return EmployeeMassIncrement::pluck('employee_id');
    }
    public function save($data)
    {
        $employeeMassIncrement=EmployeeMassIncrement::create($data);
        if($data['income_setup_id'] && count($data['income_setup_id']) > 0){
            $temp=[];
            foreach($data['income_setup_id'] as $key=>$incomeId){
                $temp[]=[
                    'employee_mass_increment_id'=>$employeeMassIncrement->id,
                    'income_setup_id'=>$incomeId,
                    'exiting_amount'=>$data['exiting_amount'][$key],
                    'increased_amount'=>$data['increased_amount'][$key],
                    'effective_date'=>$data['effective_date'][$key]
                ];
            }
            EmployeeMassIncrementDetail::insert($temp);
        }

    }

    public function getList(){
        return EmployeeMassIncrement::paginate(10);
    }

    public function find($id){
        return EmployeeMassIncrement::find($id);
    }

    public function update($employeeMassIncrement,$data){

        $employeeMassIncrement->update($data);
        if($data['income_setup_id'] && count($data['income_setup_id']) > 0){
            $employeeMassIncrement->details()->where('status',false)->delete();
            $temp=[];
            foreach($data['income_setup_id'] as $key=>$incomeId){
                $temp[]=[
                    'employee_mass_increment_id'=>$employeeMassIncrement->id,
                    'income_setup_id'=>$incomeId,
                    'exiting_amount'=>$data['exiting_amount'][$key],
                    'increased_amount'=>$data['increased_amount'][$key],
                    'effective_date'=>$data['effective_date'][$key]
                ];
            }
            EmployeeMassIncrementDetail::insert($temp);
        }
    }




}
