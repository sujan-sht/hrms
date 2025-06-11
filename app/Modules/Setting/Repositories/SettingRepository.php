<?php
namespace App\Modules\Setting\Repositories;

use App\Modules\Setting\Entities\PayrollCalenderTypeSetting;
use App\Modules\Setting\Entities\Setting;

class SettingRepository implements SettingInterface
{

    public function getdata(){
        return Setting::first();

    }
    public function find($id){
        return Setting::find($id);
    }

    public function save($data){
        return Setting::create($data);
    }

    public function update($id,$data){
        $result = $this->find($id);
        return $result->update($data);
    }
  
    public function upload($file){
        $imageName = $file->getClientOriginalName();
        $fileName = date('Y-m-d-h-i-s') . '-' . preg_replace('[ ]', '-', $imageName);

        $file->move(public_path() . Setting::FILE_PATH, $fileName);

        return $fileName;
    }
    public function savePayrollCalenderType($data){
        return PayrollCalenderTypeSetting::create($data);
    }
    public function updatePayrollCalenderType($data){
        $result = PayrollCalenderTypeSetting::where('organization_id',$data['organization_id']);
        return $result->update($data);
    }
    public function findPayrollCalenderTypeList(){
        return PayrollCalenderTypeSetting::pluck('calendar_type', 'organization_id');
    }
    public function findOne($data){
        return PayrollCalenderTypeSetting::where('organization_id',$data['organization_id'])->first();
    }
}
