<?php
namespace App\Modules\Setting\Repositories;

interface SettingInterface
{
    public function getdata();

    public function find($id);

    public function save($data);

    public function update($id,$data);

    public function upload($file);

    public function savePayrollCalenderType($data);

    public function updatePayrollCalenderType($data);

    public function findPayrollCalenderTypeList();
    
    public function findOne($data);

}
